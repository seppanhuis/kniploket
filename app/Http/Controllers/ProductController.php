<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

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

    public function create()
    {
        return view('producten.create', [
            'title' => 'Nieuw product toevoegen',
            'categories' => DB::table('ProductCategorie')->where('IsActief', true)->orderBy('Naam')->get(),
            'suppliers' => DB::table('Leverancier')->where('IsActief', true)->orderBy('Naam')->get(),
            'treatments' => DB::table('Behandeling')->where('IsActief', true)->orderBy('Naam')->get(),
            'selectedTreatmentIds' => old('treatment_ids', []),
        ]);
    }

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

        $productId = $this->productModel->spCreateProduct($data);
        $this->productModel->syncTreatmentsForProduct($productId, $request->input('treatment_ids', []));

        return redirect()->route('producten.index')
            ->with('success', 'Product succesvol toegevoegd.');
    }

    public function edit($id)
    {
        $product = $this->productModel->spGetProductById($id);

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

        $result = $this->productModel->spUpdateProduct($id, $data);
        $this->productModel->syncTreatmentsForProduct($id, $request->input('treatment_ids', []));

        if ($result > 0) {
            return redirect()->route('producten.index')
                ->with('success', 'Product succesvol gewijzigd.');
        }

        return back()->withInput()->with('error', 'Product kon niet worden gewijzigd.');
    }

    public function destroy($id)
    {
        $updated = $this->productModel->spDeleteProduct($id);

        if ($updated) {
            return redirect()->route('producten.index')
                ->with('success', 'Product is uitgeschakeld.');
        }

        return redirect()->route('producten.index')
            ->with('error', 'Product kon niet worden verwijderd.');
    }
}
