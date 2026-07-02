<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /** @var Product */
    protected Product $productModel;

    /** Create the product model instance used for stored procedure calls. */
    public function __construct()
    {
        $this->productModel = new Product();
    }

    /** Show the overview page with all products, including inactive ones. */
    public function index()
    {
        // Load every product for the index so inactive products remain visible.
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

    /** Show the form for creating a new product. */
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

    /** Persist a new product and its selected treatments. */
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

    /** Show the form for editing an existing product. */
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

    /** Update a product and synchronize its linked treatments. */
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

    /** Disable a product instead of removing it from the database. */
    public function destroy($id)
    {
        $updated = $this->productModel->spDeleteProduct($id);

        if ($updated) {
            return redirect()->route('producten.index')
                ->with('error', 'Product is uitgeschakeld.');
        }

        return redirect()->route('producten.index')
            ->with('error', 'Product kon niet worden verwijderd.');
    }
}
