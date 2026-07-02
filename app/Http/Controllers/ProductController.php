<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $lowStockProducts = collect($products)->filter(fn($product) => $product->low_stock);

        return view('producten.index', [
            'title' => 'Producten',
            'products' => $products,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }

    public function create()
    {
        Log::info('Product aanmaakformulier geopend');

        return view('producten.create', [
            'title' => 'Nieuw product toevoegen',
            'categories' => $this->productModel->spGetActiveCategories(),
            'suppliers' => $this->productModel->spGetActiveSuppliers(),
            'treatments' => $this->productModel->spGetActiveTreatments(),
            'selectedTreatmentIds' => old('treatment_ids', []),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
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
            ],
            [
                'product_naam.required' => 'Productnaam is verplicht.',
                'product_naam.unique' => 'Deze productnaam bestaat al.',

                'ean_code.required' => 'EAN-code is verplicht.',
                'ean_code.unique' => 'Deze EAN-code bestaat al.',
                'ean_code.digits_between' => 'EAN-code moet tussen 1 en 13 cijfers zijn.',

                'voorraad.required' => 'Voorraad is verplicht.',
                'voorraad.integer' => 'Voorraad moet een geheel getal zijn.',
                'voorraad.min' => 'Voorraad mag niet negatief zijn.',

                'minimum_voorraad.required' => 'Minimum voorraad is verplicht.',
                'minimum_voorraad.integer' => 'Minimum voorraad moet een geheel getal zijn.',
                'minimum_voorraad.min' => 'Minimum voorraad mag niet negatief zijn.',

                'leverancier_id.required' => 'Leverancier is verplicht.',
                'leverancier_id.exists' => 'Geselecteerde leverancier bestaat niet.',

                'categorie_id.required' => 'Categorie is verplicht.',
                'categorie_id.exists' => 'Geselecteerde categorie bestaat niet.',

                'is_actief.required' => 'Status is verplicht.',
                'is_actief.boolean' => 'Status moet waar of onwaar zijn.',

                'treatment_ids.*.exists' => 'Geselecteerde behandeling bestaat niet.',
            ]
        );

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

    public function edit($id)
    {
        Log::info('Product bewerkverzoek ontvangen', [
            'product_id' => $id
        ]);

        $product = $this->productModel->spGetProductById($id);

        abort_if(! $product, 404);

        return view('producten.edit', [
            'title' => 'Product wijzigen',
            'product' => $product,
            'categories' => $this->productModel->spGetActiveCategories(),
            'suppliers' => $this->productModel->spGetActiveSuppliers(),
            'treatments' => $this->productModel->spGetActiveTreatments(),
            'selectedTreatmentIds' => $this->productModel->spGetTreatmentIdsForProduct($id)->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productModel->spGetProductById($id);

        abort_if(! $product, 404);

        $data = $request->validate(
            [
                'product_naam' => ['required', 'string', 'max:100', 'unique:Product,ProductNaam,' . $id . ',Id']
                ,'ean_code' => ['required', 'digits_between:1,13', 'unique:Product,EANCode,' . $id . ',Id']
                ,'voorraad' => ['required', 'integer', 'min:0', 'max:65535']
                ,'minimum_voorraad' => ['required', 'integer', 'min:0', 'max:65535']
                ,'leverancier_id' => ['required', 'exists:Leverancier,Id']
                ,'categorie_id' => ['required', 'exists:ProductCategorie,Id']
                ,'is_actief' => ['required', 'boolean']
                ,'opmerking' => ['nullable', 'string', 'max:255']
                ,'treatment_ids' => ['nullable', 'array']
                ,'treatment_ids.*' => ['integer', 'exists:Behandeling,BehandelingId']
            ],
            [
                'product_naam.required' => 'Productnaam is verplicht.',
                'product_naam.unique' => 'Deze productnaam bestaat al.',

                'ean_code.required' => 'EAN-code is verplicht.',
                'ean_code.unique' => 'Deze EAN-code bestaat al.',

                'voorraad.required' => 'Voorraad is verplicht.',
                'voorraad.integer' => 'Voorraad moet een geheel getal zijn.',
                'voorraad.min' => 'Voorraad mag niet negatief zijn.',

                'minimum_voorraad.required' => 'Minimum voorraad is verplicht.',
                'minimum_voorraad.integer' => 'Minimum voorraad moet een geheel getal zijn.',
                'minimum_voorraad.min' => 'Minimum voorraad mag niet negatief zijn.',

                'leverancier_id.required' => 'Leverancier is verplicht.',
                'categorie_id.required' => 'Categorie is verplicht.',
            ]
        );

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
