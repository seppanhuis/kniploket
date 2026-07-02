<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /** @var Product */
    protected Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /** Overzicht van alle producten inclusief inactieve */
    public function index()
    {
        $products = $this->productModel->spGetAllProducten();

        foreach ($products as $product) {
            $product->treatments = $this->productModel->spGetTreatmentsForProduct($product->Id);
            $product->low_stock = $product->Voorraad <= $product->MinimumVoorraad;
        }

        $lowStockProducts = collect($products)->filter(fn ($product) => $product->low_stock);

        return view('producten.index', [
            'title' => 'Producten',
            'products' => $products,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }

    /** Formulier voor nieuw product */
    public function create()
    {
        Log::info('Product aanmaakformulier geopend');

        return view('producten.create', [
            'title' => 'Nieuw product toevoegen',
            'categories' => DB::table('ProductCategorie')->where('IsActief', true)->orderBy('Naam')->get(),
            'suppliers' => DB::table('Leverancier')->where('IsActief', true)->orderBy('Naam')->get(),
            'treatments' => DB::table('Behandeling')->where('IsActief', true)->orderBy('Naam')->get(),
            'selectedTreatmentIds' => old('treatment_ids', []),
        ]);
    }

    /** Opslaan van nieuw product */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_naam' => ['required', 'string', 'max:100', 'unique:Product,ProductNaam'],
            'ean_code' => ['required', 'digits_between:1,13', 'unique:Product,EANCode'],
            'voorraad' => ['required', 'integer', 'min:0'],
            'minimum_voorraad' => ['required', 'integer', 'min:0'],
            'leverancier_id' => ['required', 'exists:Leverancier,Id'],
            'categorie_id' => ['required', 'exists:ProductCategorie,Id'],
            'is_actief' => ['required', 'boolean'],
            'opmerking' => ['nullable', 'string', 'max:255'],
            'treatment_ids' => ['nullable', 'array'],
            'treatment_ids.*' => ['integer', 'exists:Behandeling,BehandelingId'],
        ]);

        Log::info('Nieuw product aangemaakt', [
            'product_naam' => $data['product_naam']
        ]);

        $productId = $this->productModel->spCreateProduct($data);
        $this->productModel->syncTreatmentsForProduct($productId, $request->input('treatment_ids', []));

        Log::info('Product succesvol opgeslagen', [
            'product_id' => $productId
        ]);

        return redirect()->route('producten.index')
            ->with('success', 'Product is succesvol toegevoegd.');
    }

    /** Bewerken formulier */
    public function edit($id)
    {
        Log::info('Product bewerkverzoek ontvangen', [
            'product_id' => $id
        ]);

        $product = $this->productModel->spGetProductById($id);

        Log::info('Product opgehaald voor bewerken', [
            'product_id' => $id,
            'gevonden' => !empty($product)
        ]);

        abort_if(! $product, 404);

        return view('producten.edit', [
            'title' => 'Product wijzigen',
            'product' => $product,
            'categories' => DB::table('ProductCategorie')->where('IsActief', true)->orderBy('Naam')->get(),
            'suppliers' => DB::table('Leverancier')->where('IsActief', true)->orderBy('Naam')->get(),
            'treatments' => DB::table('Behandeling')->where('IsActief', true)->orderBy('Naam')->get(),
            'selectedTreatmentIds' => $this->productModel->spGetTreatmentIdsForProduct($id)->toArray(),
        ]);
    }

    /** Updaten van product */
    public function update(Request $request, $id)
    {
        $product = $this->productModel->spGetProductById($id);

        abort_if(! $product, 404);

        $data = $request->validate([
            'product_naam' => ['required', 'string', 'max:100', 'unique:Product,ProductNaam,'.$id.',Id'],
            'ean_code' => ['required', 'digits_between:1,13', 'unique:Product,EANCode,'.$id.',Id'],
            'voorraad' => ['required', 'integer', 'min:0'],
            'minimum_voorraad' => ['required', 'integer', 'min:0'],
            'leverancier_id' => ['required', 'exists:Leverancier,Id'],
            'categorie_id' => ['required', 'exists:ProductCategorie,Id'],
            'is_actief' => ['required', 'boolean'],
            'opmerking' => ['nullable', 'string', 'max:255'],
            'treatment_ids' => ['nullable', 'array'],
            'treatment_ids.*' => ['integer', 'exists:Behandeling,BehandelingId'],
        ]);

        Log::info('Product update gestart', [
            'product_id' => $id,
            'product_naam' => $data['product_naam']
        ]);

        $result = $this->productModel->spUpdateProduct($id, $data);
        $this->productModel->syncTreatmentsForProduct($id, $request->input('treatment_ids', []));

        Log::info('Product update voltooid', [
            'product_id' => $id,
            'resultaat' => $result
        ]);

        if ($result > 0) {
            return redirect()->route('producten.index')
                ->with('success', 'Product is succesvol gewijzigd.');
        }

        return back()->withInput()->with('error', 'Het product kon niet worden gewijzigd.');
    }

    /** Verwijderen van product */
    public function destroy($id)
    {
        Log::info('Product verwijderverzoek ontvangen', [
            'product_id' => $id
        ]);

        $deleted = $this->productModel->spDeleteProduct($id);

        Log::info('Resultaat van verwijderen product', [
            'product_id' => $id,
            'verwijderd' => $deleted
        ]);

        if ($deleted > 0) {
            return redirect()->route('producten.index')
                ->with('success', 'Product is verwijderd.');
        }

        Log::warning('Product niet verwijderd (mogelijk al inactief)', [
            'product_id' => $id
        ]);

        return redirect()->route('producten.index')
            ->with('error', 'Product is al inactief of kon niet worden verwijderd.');
    }
}