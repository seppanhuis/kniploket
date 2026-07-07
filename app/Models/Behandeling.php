<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Behandeling extends Model
{
    protected $table = 'Behandeling';
    protected $primaryKey = 'BehandelingId';
    public $timestamps = false;

    /** Haal alle behandelingen op via de stored procedure. */
    public function sp_GetAllBehandelingen()
    {
        try {
            Log::info('Behandelingen ophalen via sp_GetAllBehandelingen');

            $result = DB::select('CALL sp_GetAllBehandelingen()');

            Log::info('Behandelingen succesvol opgehaald', [
                'count' => count($result),
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::warning('sp_GetAllBehandelingen mislukt', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /** Haal één behandeling op op basis van de id. */
    public function sp_GetBehandelingById(int $id)
    {
        try {
            Log::info('Behandeling ophalen via sp_GetBehandelingById', [
                'id' => $id,
            ]);

            $result = DB::select('CALL sp_GetBehandelingById(?)', [$id]);

            Log::info('Behandeling succesvol opgehaald', [
                'id' => $id,
                'found' => isset($result[0]),
            ]);

            return $result[0] ?? null;
        } catch (\Throwable $e) {
            Log::warning('sp_GetBehandelingById mislukt', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /** Maak een nieuwe behandeling aan via de stored procedure. */
    public function sp_CreateBehandeling(string $naam, float $prijs, int $duurMinuten, ?string $opmerking = null)
    {
        try {
            Log::info('Behandeling aanmaken via sp_CreateBehandeling', [
                'naam' => $naam,
                'prijs' => $prijs,
                'duur_minuten' => $duurMinuten,
                'heeft_opmerking' => $opmerking !== null,
            ]);

            $result = DB::select(
                'CALL sp_CreateBehandeling(?, ?, ?, ?)',
                [$naam, $prijs, $duurMinuten, $opmerking]
            );

            Log::info('Behandeling succesvol aangemaakt', [
                'naam' => $naam,
                'result_found' => isset($result[0]),
            ]);

            return $result[0] ?? null;
        } catch (\Throwable $e) {
            Log::warning('sp_CreateBehandeling mislukt', [
                'naam' => $naam,
                'prijs' => $prijs,
                'duur_minuten' => $duurMinuten,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /** Werk een bestaande behandeling bij via de stored procedure. */
    public function sp_UpdateBehandeling(
        int $id,
        string $naam,
        float $prijs,
        int $duurMinuten,
        int $isActief,
        ?string $opmerking = null
    ) {
        try {
            Log::info('Behandeling updaten via sp_UpdateBehandeling', [
                'id' => $id,
                'naam' => $naam,
                'prijs' => $prijs,
                'duur_minuten' => $duurMinuten,
                'is_actief' => $isActief,
                'heeft_opmerking' => $opmerking !== null,
            ]);

            $result = DB::select(
                'CALL sp_UpdateBehandeling(?, ?, ?, ?, ?, ?)',
                [$id, $naam, $prijs, $duurMinuten, $isActief, $opmerking]
            );

            Log::info('Behandeling succesvol geüpdatet', [
                'id' => $id,
                'result_found' => isset($result[0]),
            ]);

            return $result[0] ?? null;
        } catch (\Throwable $e) {
            Log::warning('sp_UpdateBehandeling mislukt', [
                'id' => $id,
                'naam' => $naam,
                'prijs' => $prijs,
                'duur_minuten' => $duurMinuten,
                'is_actief' => $isActief,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /** Verwijder een behandeling via de stored procedure. */
    public function sp_DeleteBehandeling(int $id): array
    {
        $behandeling = static::query()
            ->select(['BehandelingId', 'IsActief'])
            ->where('BehandelingId', $id)
            ->first();

        if (!$behandeling) {
            Log::warning('Behandeling verwijderen geweigerd: behandeling niet gevonden', [
                'id' => $id,
            ]);

            return [
                'status' => 'not_found',
                'message' => 'Behandeling bestaat niet of is al verwijderd.',
                'affected' => 0,
            ];
        }

        if ((int) $behandeling->IsActief === 0) {
            Log::warning('Behandeling verwijderen geweigerd: behandeling is inactief', [
                'id' => $id,
            ]);

            return [
                'status' => 'inactive',
                'message' => 'Behandeling is inactief en kan daarom niet worden verwijderd.',
                'affected' => 0,
            ];
        }

        try {
            Log::info('Behandeling verwijderen via sp_DeleteBehandeling', [
                'id' => $id,
            ]);

            $result = DB::selectOne('CALL sp_DeleteBehandeling(?)', [$id]);

            $status = (string) ($result->status ?? 'unknown');
            $affected = (int) ($result->affected ?? 0);

            if ($status === 'deleted' && $affected > 0) {
                Log::info('Behandeling succesvol verwijderd', [
                    'id' => $id,
                    'affected' => $affected,
                ]);

                return [
                    'status' => $status,
                    'message' => 'Behandeling succesvol verwijderd.',
                    'affected' => $affected,
                ];
            }

            if ($status === 'inactive') {
                Log::warning('Behandeling verwijderen geweigerd door procedure: behandeling is inactief', [
                    'id' => $id,
                ]);

                return [
                    'status' => $status,
                    'message' => 'Behandeling is inactief en kan daarom niet worden verwijderd.',
                    'affected' => 0,
                ];
            }

            Log::warning('Behandeling verwijderen gaf geen resultaat', [
                'id' => $id,
                'status' => $status,
                'affected' => $affected,
            ]);

            return [
                'status' => $status,
                'message' => 'Behandeling kon niet worden verwijderd.',
                'affected' => $affected,
            ];
        } catch (\Throwable $e) {
            $message = 'Behandeling kon niet worden verwijderd door een databasefout.';

            if (str_contains($e->getMessage(), '1451') || str_contains($e->getMessage(), '1452') || str_contains(strtolower($e->getMessage()), 'foreign key')) {
                $message = 'Behandeling kan niet worden verwijderd omdat er nog gekoppelde gegevens bestaan.';
            }

            Log::warning('sp_DeleteBehandeling mislukt', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => $message,
                'affected' => 0,
            ];
        }
    }

}
