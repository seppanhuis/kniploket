<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'Product';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'ProductNaam',
        'EANCode',
        'Voorraad',
        'MinimumVoorraad',
        'LeverancierId',
        'CategorieId',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public function spGetAllProducten()
    {
        try {
            return collect(DB::select('CALL sp_GetAllProducten()'));
        } catch (\Throwable $e) {
            return DB::table('Product as p')
                ->select(
                    'p.Id',
                    'p.ProductNaam',
                    'p.EANCode',
                    'p.Voorraad',
                    'p.MinimumVoorraad',
                    'p.IsActief',
                    'p.Opmerking',
                    'p.LeverancierId',
                    'p.CategorieId',
                    'l.Naam as leverancier_naam',
                    'c.Naam as categorie_naam'
                )
                ->leftJoin('Leverancier as l', 'l.Id', '=', 'p.LeverancierId')
                ->leftJoin('ProductCategorie as c', 'c.Id', '=', 'p.CategorieId')
                ->where('p.IsActief', true)
                ->orderBy('p.ProductNaam')
                ->get();
        }
    }

    public function spCreateProduct(array $data)
    {
        try {
            $row = DB::selectOne(
                'CALL sp_CreateProduct(:product_naam, :ean_code, :voorraad, :minimum_voorraad, :leverancier_id, :categorie_id, :is_actief, :opmerking)',
                [
                    'product_naam' => $data['product_naam'],
                    'ean_code' => $data['ean_code'],
                    'voorraad' => $data['voorraad'],
                    'minimum_voorraad' => $data['minimum_voorraad'],
                    'leverancier_id' => $data['leverancier_id'],
                    'categorie_id' => $data['categorie_id'],
                    'is_actief' => (int) $data['is_actief'],
                    'opmerking' => $data['opmerking'] ?? null,
                ]
            );

            return (int) ($row->new_id ?? 0);
        } catch (\Throwable $e) {
            return (int) DB::table('Product')->insertGetId([
                'ProductNaam' => $data['product_naam'],
                'EANCode' => $data['ean_code'],
                'Voorraad' => $data['voorraad'],
                'MinimumVoorraad' => $data['minimum_voorraad'],
                'LeverancierId' => $data['leverancier_id'],
                'CategorieId' => $data['categorie_id'],
                'IsActief' => (bool) $data['is_actief'],
                'Opmerking' => $data['opmerking'] ?? null,
                'DatumAangemaakt' => now(),
                'DatumGewijzigd' => now(),
            ]);
        }
    }

    public function spGetProductById($id)
    {
        try {
            return DB::selectOne('CALL sp_GetProductById(:id)', ['id' => $id]);
        } catch (\Throwable $e) {
            return DB::table('Product')->where('Id', $id)->first();
        }
    }

    public function spUpdateProduct($id, array $data)
    {
        try {
            $row = DB::selectOne(
                'CALL sp_UpdateProduct(:id, :product_naam, :ean_code, :voorraad, :minimum_voorraad, :leverancier_id, :categorie_id, :is_actief, :opmerking)',
                [
                    'id' => $id,
                    'product_naam' => $data['product_naam'],
                    'ean_code' => $data['ean_code'],
                    'voorraad' => $data['voorraad'],
                    'minimum_voorraad' => $data['minimum_voorraad'],
                    'leverancier_id' => $data['leverancier_id'],
                    'categorie_id' => $data['categorie_id'],
                    'is_actief' => (int) $data['is_actief'],
                    'opmerking' => $data['opmerking'] ?? null,
                ]
            );

            return (int) ($row->affected ?? 0);
        } catch (\Throwable $e) {
            return (int) DB::table('Product')->where('Id', $id)->update([
                'ProductNaam' => $data['product_naam'],
                'EANCode' => $data['ean_code'],
                'Voorraad' => $data['voorraad'],
                'MinimumVoorraad' => $data['minimum_voorraad'],
                'LeverancierId' => $data['leverancier_id'],
                'CategorieId' => $data['categorie_id'],
                'IsActief' => (bool) $data['is_actief'],
                'Opmerking' => $data['opmerking'] ?? null,
                'DatumGewijzigd' => now(),
            ]);
        }
    }

    public function spDeleteProduct($id)
    {
        try {
            $row = DB::selectOne('CALL sp_DeleteProduct(:id)', ['id' => $id]);

            return (int) ($row->affected ?? 0);
        } catch (\Throwable $e) {
            return (int) DB::table('Product')->where('Id', $id)->update([
                'IsActief' => false,
                'DatumGewijzigd' => now(),
            ]);
        }
    }

    public function spGetTreatmentsForProduct($productId)
    {
        try {
            $rows = DB::select('CALL sp_GetTreatmentsForProduct(:id)', ['id' => $productId]);

            return collect($rows)->pluck('Naam');
        } catch (\Throwable $e) {
            return DB::table('BehandelingProduct as bp')
                ->join('Behandeling as b', 'b.BehandelingId', '=', 'bp.BehandelingId')
                ->where('bp.ProductId', $productId)
                ->where('bp.IsActief', true)
                ->pluck('b.Naam');
        }
    }

    public function spGetTreatmentIdsForProduct($productId)
    {
        return collect(DB::table('BehandelingProduct')
            ->where('ProductId', $productId)
            ->where('IsActief', true)
            ->pluck('BehandelingId'));
    }

    public function syncTreatmentsForProduct($productId, array $treatmentIds = [])
    {
        DB::table('BehandelingProduct')->where('ProductId', $productId)->update([
            'IsActief' => false,
            'DatumGewijzigd' => now(),
        ]);

        foreach (array_unique(array_filter($treatmentIds)) as $treatmentId) {
            DB::table('BehandelingProduct')->updateOrInsert(
                [
                    'ProductId' => $productId,
                    'BehandelingId' => $treatmentId,
                ],
                [
                    'Aantal' => 1,
                    'IsActief' => true,
                    'DatumAangemaakt' => now(),
                    'DatumGewijzigd' => now(),
                ]
            );
        }

        return true;
    }
}
