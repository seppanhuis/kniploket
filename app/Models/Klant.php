<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Klant extends Model
{
    protected $table = 'Klant';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    public function spGetAllKlanten(): array
    {
        try {
            Log::info('Klanten ophalen (sp_GetAllKlanten)');

            $result = DB::select('CALL sp_GetAllKlanten()');

            Log::info('Klanten succesvol opgehaald', [
                'count' => count($result)
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::warning('Fallback gebruikt voor sp_GetAllKlanten', [
                'error' => $e->getMessage()
            ]);

            return DB::select('SELECT K.Id, G.Voornaam, G.Achternaam, G.Email, G.Telefoonnummer, K.Wensen, K.Opmerking, K.IsActief FROM Klant AS K INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId ORDER BY K.Id ASC');
        }
    }

    public function spCreateKlant(array $data): object
    {
        try {
            Log::info('Klant aanmaken via stored procedure', [
                'email' => $data['email'] ?? null
            ]);

            $result = DB::selectOne(
                'CALL sp_CreateKlant(:voornaam, :achternaam, :email, :telefoonnummer, :wensen, :opmerking, :straat, :huisnummer, :toevoeging, :postcode, :woonplaats, :is_actief, :wachtwoord)',
                [
                    'voornaam' => $data['voornaam'],
                    'achternaam' => $data['achternaam'],
                    'email' => $data['email'],
                    'telefoonnummer' => $data['telefoonnummer'],
                    'wensen' => $data['wensen'],
                    'opmerking' => $data['opmerking'],
                    'straat' => $data['straat'],
                    'huisnummer' => $data['huisnummer'],
                    'toevoeging' => $data['toevoeging'],
                    'postcode' => $data['postcode'],
                    'woonplaats' => $data['woonplaats'],
                    'is_actief' => $data['is_actief'] ?? true,
                    'wachtwoord' => $data['wachtwoord'],
                ]
            );

            Log::info('Klant aangemaakt via procedure');

            return $result;
        } catch (\Throwable $e) {
            Log::warning('Stored procedure create mislukt, fallback gestart', [
                'error' => $e->getMessage(),
                'email' => $data['email'] ?? null
            ]);

            $baseUsername = strtolower(Str::slug($data['voornaam'].' '.$data['achternaam'], '.'));
            $username = $baseUsername;
            $counter = 1;

            while (DB::table('Gebruiker')->where('Gebruikersnaam', $username)->exists()) {
                $username = $baseUsername.$counter;
                $counter++;
            }

            Log::info('Unieke gebruikersnaam gegenereerd', [
                'username' => $username
            ]);

            $gebruikerData = [
                'RolId' => 4,
                'Gebruikersnaam' => $username,
                'Wachtwoord' => $data['wachtwoord'],
                'Voornaam' => $data['voornaam'],
                'Achternaam' => $data['achternaam'],
                'Straat' => $data['straat'],
                'Huisnummer' => (int) $data['huisnummer'],
                'Toevoeging' => $data['toevoeging'],
                'Postcode' => $data['postcode'],
                'Woonplaats' => $data['woonplaats'],
                'Telefoonnummer' => $data['telefoonnummer'],
                'Email' => $data['email'],
                'LaatsteLogin' => now(),
                'IsActief' => $data['is_actief'] ?? true,
                'Opmerking' => $data['opmerking'],
            ];

            if (Schema::hasColumn('Gebruiker', 'Tussenvoegsel')) {
                $gebruikerData['Tussenvoegsel'] = null;
            }

            if (Schema::hasColumn('Gebruiker', 'DatumAangemaakt')) {
                $gebruikerData['DatumAangemaakt'] = now();
            }

            if (Schema::hasColumn('Gebruiker', 'DatumGewijzigd')) {
                $gebruikerData['DatumGewijzigd'] = now();
            }

            $gebruikerId = DB::table('Gebruiker')->insertGetId($gebruikerData);

            Log::info('Gebruiker aangemaakt (fallback)', [
                'gebruiker_id' => $gebruikerId
            ]);

            $klantData = [
                'GebruikerId' => $gebruikerId,
                'Wensen' => $data['wensen'],
                'IsActief' => $data['is_actief'] ?? true,
                'Opmerking' => $data['opmerking'],
            ];

            if (Schema::hasColumn('Klant', 'DatumAangemaakt')) {
                $klantData['DatumAangemaakt'] = now();
            }

            if (Schema::hasColumn('Klant', 'DatumGewijzigd')) {
                $klantData['DatumGewijzigd'] = now();
            }

            DB::table('Klant')->insert($klantData);

            Log::info('Klant aangemaakt (fallback)', [
                'gebruiker_id' => $gebruikerId
            ]);

            return (object) ['new_id' => $gebruikerId];
        }
    }

    public function spGetKlantById(int $id): ?object
    {
        try {
            Log::info('Klant ophalen via ID (procedure)', ['id' => $id]);

            return DB::selectOne('CALL sp_GetKlantById(:id)', ['id' => $id]);
        } catch (\Throwable $e) {
            Log::warning('Fallback getKlantById gebruikt', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return DB::selectOne('SELECT K.Id, G.Voornaam, G.Achternaam, G.Email, G.Telefoonnummer, G.Straat, G.Huisnummer, G.Toevoeging, G.Postcode, G.Woonplaats, K.Wensen, K.Opmerking, K.IsActief FROM Klant AS K INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId WHERE K.Id = :id', ['id' => $id]);
        }
    }

    public function spUpdateKlant(int $id, array $data): int
    {
        try {
            Log::info('Klant updaten via procedure', [
                'id' => $id
            ]);

            $row = DB::selectOne(
                'CALL sp_UpdateKlant(:id, :voornaam, :achternaam, :email, :telefoonnummer, :wensen, :opmerking, :straat, :huisnummer, :toevoeging, :postcode, :woonplaats, :is_actief)',
                [
                    'id' => $id,
                    'voornaam' => $data['voornaam'],
                    'achternaam' => $data['achternaam'],
                    'email' => $data['email'],
                    'telefoonnummer' => $data['telefoonnummer'],
                    'wensen' => $data['wensen'],
                    'opmerking' => $data['opmerking'],
                    'straat' => $data['straat'],
                    'huisnummer' => $data['huisnummer'],
                    'toevoeging' => $data['toevoeging'],
                    'postcode' => $data['postcode'],
                    'woonplaats' => $data['woonplaats'],
                    'is_actief' => $data['is_actief'] ?? true,
                ]
            );

            Log::info('Klant geüpdatet via procedure', [
                'id' => $id,
                'affected' => $row->affected ?? 0
            ]);

            return (int) ($row->affected ?? 0);
        } catch (\Throwable $e) {
            Log::warning('Fallback update gebruikt', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            $updated = DB::table('Gebruiker')
                ->join('Klant', 'Klant.GebruikerId', '=', 'Gebruiker.Id')
                ->where('Klant.Id', $id)
                ->update([
                    'Gebruiker.Voornaam' => $data['voornaam'],
                    'Gebruiker.Achternaam' => $data['achternaam'],
                    'Gebruiker.Email' => $data['email'],
                    'Gebruiker.Telefoonnummer' => $data['telefoonnummer'],
                    'Gebruiker.Straat' => $data['straat'],
                    'Gebruiker.Huisnummer' => $data['huisnummer'],
                    'Gebruiker.Toevoeging' => $data['toevoeging'],
                    'Gebruiker.Postcode' => $data['postcode'],
                    'Gebruiker.Woonplaats' => $data['woonplaats'],
                    'Gebruiker.IsActief' => $data['is_actief'] ?? true,
                    'Gebruiker.Opmerking' => $data['opmerking'],
                    'Gebruiker.DatumGewijzigd' => now(),
                    'Klant.Wensen' => $data['wensen'],
                    'Klant.Opmerking' => $data['opmerking'],
                    'Klant.IsActief' => $data['is_actief'] ?? true,
                    'Klant.DatumGewijzigd' => now(),
                ]);

            Log::info('Klant geüpdatet via fallback', [
                'id' => $id,
                'affected' => $updated
            ]);

            return (int) $updated;
        }
    }

    public function spDeleteKlant(int $id): int
    {
        try {
            Log::info('Klant verwijderen via procedure', ['id' => $id]);

            $row = DB::selectOne('CALL sp_DeleteKlant(:id)', ['id' => $id]);

            Log::info('Klant verwijderd via procedure', [
                'id' => $id,
                'affected' => $row->affected ?? 0
            ]);

            return (int) ($row->affected ?? 0);
        } catch (\Throwable $e) {
            Log::warning('Fallback delete gebruikt', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            $deleted = DB::table('Klant')->where('Id', $id)->delete();

            Log::info('Klant verwijderd via fallback', [
                'id' => $id,
                'affected' => $deleted
            ]);

            return (int) $deleted;
        }
    }
}
