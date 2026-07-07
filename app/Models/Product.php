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

    /** Retrieve all products for the overview page via the stored procedure. */
    public function spGetAllProducten()
    {
        try {
            $products = collect(DB::select('CALL sp_GetAllProducten()'));

            return $products->sortByDesc(function ($product) {
                $modifiedAt = data_get($product, 'DatumGewijzigd');
                $createdAt = data_get($product, 'DatumAangemaakt');

                $dates = array_filter([
                    $modifiedAt ? strtotime($modifiedAt) : null,
                    $createdAt ? strtotime($createdAt) : null,
                ]);

                return $dates ? max($dates) : 0;
            })->values();
        } catch (\Throwable $e) {
            Log::warning('sp_GetAllProducten failed', ['reason' => $e->getMessage()]);

            return collect();
        }
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
            return (int) static::query()->insertGetId([
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
            return static::query()->where('Id', $id)->first();
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
            return (int) static::query()->where('Id', $id)->update([
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

    /** Permanently delete a product through the stored procedure. */
    public function spDeleteProduct($id)
    {
        try {
            $row = DB::selectOne('CALL sp_DeleteProduct(:id)', ['id' => $id]);
            $affected = (int) ($row->affected ?? 0);

            Log::info('Product delete procedure executed', ['product_id' => $id, 'affected' => $affected]);

            return $affected;
        } catch (\Throwable $e) {
            Log::warning('Product delete procedure failed', ['product_id' => $id, 'reason' => $e->getMessage()]);

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
            Log::warning('sp_GetTreatmentsForProduct failed', ['product_id' => $productId, 'reason' => $e->getMessage()]);

            return collect();
        }
    }

    /** Return the active categories for product forms. */
    public function spGetActiveCategories()
    {
        try {
            return collect(DB::select('CALL sp_GetActiveProductCategories()'));
        } catch (\Throwable $e) {
            if ($this->isMissingProcedureException($e)) {
                return DB::table('ProductCategorie')
                    ->select(['Id', 'Naam'])
                    ->where('IsActief', 1)
                    ->orderBy('Naam')
                    ->get();
            }

            Log::warning('sp_GetActiveProductCategories failed', ['reason' => $e->getMessage()]);

            return collect();
        }
    }

    /** Return the active suppliers for product forms. */
    public function spGetActiveSuppliers()
    {
        try {
            return collect(DB::select('CALL sp_GetActiveSuppliers()'));
        } catch (\Throwable $e) {
            if ($this->isMissingProcedureException($e)) {
                return DB::table('Leverancier')
                    ->select(['Id', 'Naam'])
                    ->where('IsActief', 1)
                    ->orderBy('Naam')
                    ->get();
            }

            Log::warning('sp_GetActiveSuppliers failed', ['reason' => $e->getMessage()]);

            return collect();
        }
    }

    /** Return the active treatments for product forms. */
    public function spGetActiveTreatments()
    {
        try {
            return collect(DB::select('CALL sp_GetActiveTreatments()'));
        } catch (\Throwable $e) {
            if ($this->isMissingProcedureException($e)) {
                return DB::table('Behandeling')
                    ->select(['BehandelingId', 'Naam'])
                    ->where('IsActief', 1)
                    ->orderBy('Naam')
                    ->get();
            }

            Log::warning('sp_GetActiveTreatments failed', ['reason' => $e->getMessage()]);

            return collect();
        }
    }

    /** Return the active treatment ids currently linked to a product. */
    public function spGetTreatmentIdsForProduct($productId)
    {
        try {
            $rows = DB::select('CALL sp_GetTreatmentIdsForProduct(:id)', ['id' => $productId]);

            return collect($rows)->pluck('BehandelingId');
        } catch (\Throwable $e) {
            if ($this->isMissingProcedureException($e)) {
                return DB::table('BehandelingProduct')
                    ->where('ProductId', $productId)
                    ->where('IsActief', 1)
                    ->orderBy('BehandelingId')
                    ->pluck('BehandelingId');
            }

            Log::warning('sp_GetTreatmentIdsForProduct failed', ['product_id' => $productId, 'reason' => $e->getMessage()]);

            return collect();
        }
    }

    /** Synchronize the treatments linked to a product by activating the selected ones. */
    public function syncTreatmentsForProduct($productId, array $treatmentIds = [])
    {
        try {
            $payload = json_encode(array_values(array_unique(array_filter($treatmentIds))));

            DB::select('CALL sp_SyncTreatmentsForProduct(:product_id, :treatment_ids)', [
                'product_id' => $productId,
                'treatment_ids' => $payload,
            ]);

            return true;
        } catch (\Throwable $e) {
            if (! $this->isMissingProcedureException($e)) {
                Log::warning('sp_SyncTreatmentsForProduct failed', ['product_id' => $productId, 'reason' => $e->getMessage()]);

                return false;
            }

            $selectedIds = collect($treatmentIds)
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->unique()
                ->values();

            try {
                DB::transaction(function () use ($productId, $selectedIds) {
                    $now = now();

                    if ($selectedIds->isEmpty()) {
                        DB::table('BehandelingProduct')
                            ->where('ProductId', $productId)
                            ->update([
                                'IsActief' => 0,
                                'DatumGewijzigd' => $now,
                            ]);

                        return;
                    }

                    DB::table('BehandelingProduct')
                        ->where('ProductId', $productId)
                        ->whereIn('BehandelingId', $selectedIds->all())
                        ->update([
                            'IsActief' => 1,
                            'DatumGewijzigd' => $now,
                        ]);

                    $existingIds = DB::table('BehandelingProduct')
                        ->where('ProductId', $productId)
                        ->pluck('BehandelingId')
                        ->map(fn ($id) => (int) $id)
                        ->all();

                    $missingIds = array_values(array_diff($selectedIds->all(), $existingIds));

                    foreach ($missingIds as $behandelingId) {
                        DB::table('BehandelingProduct')->insert([
                            'BehandelingId' => $behandelingId,
                            'ProductId' => $productId,
                            'Aantal' => 1,
                            'IsActief' => 1,
                            'Opmerking' => null,
                            'DatumAangemaakt' => $now,
                            'DatumGewijzigd' => $now,
                        ]);
                    }

                    DB::table('BehandelingProduct')
                        ->where('ProductId', $productId)
                        ->whereNotIn('BehandelingId', $selectedIds->all())
                        ->update([
                            'IsActief' => 0,
                            'DatumGewijzigd' => $now,
                        ]);
                });

                return true;
            } catch (\Throwable $fallbackException) {
                Log::warning('sp_SyncTreatmentsForProduct fallback failed', [
                    'product_id' => $productId,
                    'reason' => $fallbackException->getMessage(),
                ]);

                return false;
            }
        }
    }

    private function isMissingProcedureException(\Throwable $e): bool
    {
        $message = $e->getMessage();

        return str_contains($message, 'does not exist') && str_contains($message, 'PROCEDURE');
    }
}
