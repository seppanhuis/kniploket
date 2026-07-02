<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    /** The database table used for products. */
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

    /** Retrieve all products for the overview page, including inactive products. */
    public function spGetAllProducten()
    {
        return collect(
            DB::table('Product as p')
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
                ->orderBy('p.ProductNaam')
                ->get()
        );
    }

    /** Create a new product through the stored procedure and fall back to Eloquent if needed. */
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

    /** Retrieve one product by id using the stored procedure when available. */
    public function spGetProductById($id)
    {
        try {
            return DB::selectOne('CALL sp_GetProductById(:id)', ['id' => $id]);
        } catch (\Throwable $e) {
            return DB::table('Product')->where('Id', $id)->first();
        }
    }

    /** Update an existing product and return the affected row count. */
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

    /** Permanently delete a product only when it is still active. */
    public function spDeleteProduct($id)
    {
        $product = DB::table('Product')->where('Id', $id)->first();

        if (! $product || (int) $product->IsActief !== 1) {
            Log::info('Product delete skipped because it is already inactive', ['product_id' => $id]);

            return 0;
        }

        try {
            return DB::transaction(function () use ($id) {
                $removedTreatmentLinks = DB::table('BehandelingProduct')->where('ProductId', $id)->delete();
                $removedOrderLines = DB::table('Bestelregel')->where('ProductId', $id)->delete();
                $deleted = DB::table('Product')->where('Id', $id)->delete();

                Log::info('Product delete executed', [
                    'product_id' => $id,
                    'deleted' => $deleted,
                    'removed_treatment_links' => $removedTreatmentLinks,
                    'removed_order_lines' => $removedOrderLines,
                ]);

                return (int) $deleted;
            });
        } catch (\Throwable $e) {
            Log::warning('Product delete failed', ['product_id' => $id, 'reason' => $e->getMessage()]);

            return 0;
        }
    }

    /** Return the treatment names linked to a product. */
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

    /** Return the active treatment ids currently linked to a product. */
    public function spGetTreatmentIdsForProduct($productId)
    {
        return collect(DB::table('BehandelingProduct')
            ->where('ProductId', $productId)
            ->where('IsActief', true)
            ->pluck('BehandelingId'));
    }

    /** Synchronize the treatments linked to a product by activating the selected ones. */
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
