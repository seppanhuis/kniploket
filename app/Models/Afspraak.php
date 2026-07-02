<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Afspraak extends Model
{
    protected $table = 'Afspraak';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    public function spGetAllAfspraken(): array
    {
        try {
            return DB::select('CALL sp_GetAllAfspraken()');
        } catch (\Throwable) {
            return DB::select("
                SELECT
                    A.Id,
                    CONCAT(GK.Voornaam, ' ', GK.Achternaam) AS Klant,
                    CONCAT(GM.Voornaam, ' ', GM.Achternaam) AS Medewerker,
                    B.Naam AS Behandeling,
                    S.Naam AS Status,
                    A.Datum,
                    A.StartTijd,
                    A.EindTijd,
                    A.IsActief,
                    A.Opmerking,
                    A.DatumAangemaakt,
                    A.DatumGewijzigd
                FROM Afspraak A
                INNER JOIN Klant K ON K.Id = A.KlantId
                INNER JOIN Gebruiker GK ON GK.Id = K.GebruikerId
                INNER JOIN Medewerker M ON M.Id = A.MedewerkerId
                INNER JOIN Gebruiker GM ON GM.Id = M.GebruikerId
                INNER JOIN Behandeling B ON B.BehandelingId = A.BehandelingId
                INNER JOIN AfspraakStatus S ON S.Id = A.AfspraakStatusId
                ORDER BY A.Datum, A.StartTijd
            ");
        }
    }

    public function spCreateAfspraak(array $data): object
    {
        try {
            return DB::selectOne(
                'CALL sp_CreateAfspraak(:klant_id, :medewerker_id, :behandeling_id, :afspraak_status_id, :datum, :start_tijd, :eind_tijd, :is_actief, :opmerking)',
                [
                    'klant_id' => $data['klant_id'],
                    'medewerker_id' => $data['medewerker_id'],
                    'behandeling_id' => $data['behandeling_id'],
                    'afspraak_status_id' => $data['afspraak_status_id'],
                    'datum' => $data['datum'],
                    'start_tijd' => $data['start_tijd'],
                    'eind_tijd' => $data['eind_tijd'],
                    'is_actief' => $data['is_actief'] ?? true,
                    'opmerking' => $data['opmerking'],
                ]
            );
        } catch (\Throwable $e) {
            // Business-rule violation (bv. overlap) -> NIET afvangen, doorgooien
            if ($this->isBusinessRuleViolation($e)) {
                throw $e;
            }

            // Alleen hier belanden als sp_CreateAfspraak niet bestaat
            $id = DB::table('Afspraak')->insertGetId([
                'KlantId' => $data['klant_id'],
                'MedewerkerId' => $data['medewerker_id'],
                'BehandelingId' => $data['behandeling_id'],
                'AfspraakStatusId' => $data['afspraak_status_id'],
                'Datum' => $data['datum'],
                'StartTijd' => $data['start_tijd'],
                'EindTijd' => $data['eind_tijd'],
                'IsActief' => $data['is_actief'] ?? true,
                'Opmerking' => $data['opmerking'],
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ]);

            return (object) ['new_id' => $id];
        }
    }

    public function spGetAfspraakById(int $id): ?object
    {
        try {
            return DB::selectOne(
                'CALL sp_GetAfspraakById(:id)',
                ['id' => $id]
            );
        } catch (\Throwable) {

            return DB::selectOne(
                'SELECT * FROM Afspraak WHERE Id = :id',
                ['id' => $id]
            );
        }
    }

    public function spUpdateAfspraak(int $id, array $data): int
    {
        try {
            $row = DB::selectOne(
                'CALL sp_UpdateAfspraak(:id, :klant_id, :medewerker_id, :behandeling_id, :afspraak_status_id, :datum, :start_tijd, :eind_tijd, :is_actief, :opmerking)',
                [
                    'id' => $id,
                    'klant_id' => $data['klant_id'],
                    'medewerker_id' => $data['medewerker_id'],
                    'behandeling_id' => $data['behandeling_id'],
                    'afspraak_status_id' => $data['afspraak_status_id'],
                    'datum' => $data['datum'],
                    'start_tijd' => $data['start_tijd'],
                    'eind_tijd' => $data['eind_tijd'],
                    'is_actief' => $data['is_actief'] ?? true,
                    'opmerking' => $data['opmerking'],
                ]
            );

            return (int) ($row->affected ?? 0);
        } catch (\Throwable $e) {
            if ($this->isBusinessRuleViolation($e)) {
                throw $e;
            }

            return (int) DB::table('Afspraak')
                ->where('Id', $id)
                ->update([
                    'KlantId' => $data['klant_id'],
                    'MedewerkerId' => $data['medewerker_id'],
                    'BehandelingId' => $data['behandeling_id'],
                    'AfspraakStatusId' => $data['afspraak_status_id'],
                    'Datum' => $data['datum'],
                    'StartTijd' => $data['start_tijd'],
                    'EindTijd' => $data['eind_tijd'],
                    'IsActief' => $data['is_actief'] ?? true,
                    'Opmerking' => $data['opmerking'],
                    'DatumGewijzigd' => now(),
                ]);
        }
    }

    private function isBusinessRuleViolation(\Throwable $e): bool
    {
        return $e->getCode() === '45000';
    }

    public function spDeleteAfspraak(int $id): int
    {
        try {

            $row = DB::selectOne(
                'CALL sp_DeleteAfspraak(:id)',
                ['id' => $id]
            );

            return (int) ($row->affected ?? 0);

        } catch (\Throwable) {

            return (int) DB::table('Afspraak')
                ->where('Id', $id)
                ->delete();
        }
    }
}
