<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            return DB::select('CALL sp_GetAllKlanten()');
        } catch (\Throwable) {
            return DB::select('SELECT K.Id, G.Voornaam, G.Achternaam, G.Email, G.Telefoonnummer, K.Wensen, K.Opmerking, K.IsActief FROM Klant AS K INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId ORDER BY K.Id ASC');
        }
    }

    public function spCreateKlant(array $data): object
    {
        try {
            return DB::selectOne(
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
        } catch (\Throwable) {
            $baseUsername = strtolower(Str::slug($data['voornaam'].' '.$data['achternaam'], '.'));
            $username = $baseUsername;
            $counter = 1;

            while (DB::table('Gebruiker')->where('Gebruikersnaam', $username)->exists()) {
                $username = $baseUsername.$counter;
                $counter++;
            }

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

            return (object) ['new_id' => $gebruikerId];
        }
    }

    public function spGetKlantById(int $id): ?object
    {
        try {
            return DB::selectOne('CALL sp_GetKlantById(:id)', ['id' => $id]);
        } catch (\Throwable) {
            return DB::selectOne('SELECT K.Id, G.Voornaam, G.Achternaam, G.Email, G.Telefoonnummer, G.Straat, G.Huisnummer, G.Toevoeging, G.Postcode, G.Woonplaats, K.Wensen, K.Opmerking, K.IsActief FROM Klant AS K INNER JOIN Gebruiker AS G ON G.Id = K.GebruikerId WHERE K.Id = :id', ['id' => $id]);
        }
    }

    public function spUpdateKlant(int $id, array $data): int
    {
        try {
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

            return (int) ($row->affected ?? 0);
        } catch (\Throwable) {
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

            return (int) $updated;
        }
    }

    public function spDeleteKlant(int $id): int
    {
        try {
            $row = DB::selectOne('CALL sp_DeleteKlant(:id)', ['id' => $id]);

            return (int) ($row->affected ?? 0);
        } catch (\Throwable) {
            return (int) DB::table('Klant')->where('Id', $id)->delete();
        }
    }
}
