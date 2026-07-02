<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Behandeling extends Model
{
    protected $table = 'Behandeling';
    protected $primaryKey = 'BehandelingId';
    public $timestamps = false;

    public function sp_GetAllBehandelingen()
    {
        return DB::select('CALL sp_GetAllBehandelingen()');
    }

    public function sp_GetBehandelingById(int $id)
    {
        $result = DB::select('CALL sp_GetBehandelingById(?)', [$id]);

        return $result[0] ?? null;
    }

    public function sp_CreateBehandeling(string $naam, float $prijs, int $duurMinuten, ?string $opmerking = null)
    {
        $result = DB::select(
            'CALL sp_CreateBehandeling(?, ?, ?, ?)',
            [$naam, $prijs, $duurMinuten, $opmerking]
        );

        return $result[0] ?? null;
    }

    public function sp_UpdateBehandeling(
        int $id,
        string $naam,
        float $prijs,
        int $duurMinuten,
        int $isActief,
        ?string $opmerking = null
    ) {
        $result = DB::select(
            'CALL sp_UpdateBehandeling(?, ?, ?, ?, ?, ?)',
            [$id, $naam, $prijs, $duurMinuten, $isActief, $opmerking]
        );

        return $result[0] ?? null;
    }

    public function sp_DeleteBehandeling(int $id)
{
    $result = DB::select('CALL sp_DeleteBehandeling(?)', [$id]);

    return $result[0] ?? null;
}

}
