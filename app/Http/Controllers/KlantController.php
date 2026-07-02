<?php

namespace App\Http\Controllers;

use App\Models\Klant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KlantController extends Controller
{
    public function __construct(protected Klant $klantModel) {}

    public function index(): View
    {
        $klanten = $this->klantModel->spGetAllKlanten();

        return view('klanten.index', [
            'title' => 'Klanten',
            'klanten' => $klanten,
        ]);
    }

    public function create(): View
    {
        return view('klanten.create', [
            'title' => 'Nieuwe klant toevoegen',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'voornaam' => ['required', 'string', 'max:50'],
            'achternaam' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:Gebruiker,Email'],
            'telefoonnummer' => ['required', 'regex:/^[0-9]{1,10}$/'],
            'wensen' => ['nullable', 'string', 'max:255'],
            'opmerking' => ['nullable', 'string', 'max:255'],
            'straat' => ['required', 'string', 'max:100'],
            'huisnummer' => ['required', 'numeric', 'max:999999'],
            'toevoeging' => ['nullable', 'string', 'max:5'],
            'postcode' => ['required', 'string', 'max:6'],
            'woonplaats' => ['required', 'string', 'max:50'],
            'is_actief' => ['nullable', 'boolean'],
        ], [
            'email.unique' => 'email al in gebruik',
            'telefoonnummer.regex' => 'telefoonnummer mag alleen cijfers bevatten en maximaal 10 cijfers lang zijn',
        ]);

        $data['wachtwoord'] = bcrypt('Welkom123!');
        $data['is_actief'] = $request->boolean('is_actief');

        $result = $this->klantModel->spCreateKlant($data);

        if ($result && $result->new_id) {
            return redirect()->route('klanten.index')->with('success', 'klant succesvol toegevoegd');
        }

        return back()->withInput()->with('error', 'klant kon niet worden toegevoegd');
    }

    public function edit(int $id): View
    {
        $klant = $this->klantModel->spGetKlantById($id);
        abort_if(! $klant, 404);

        return view('klanten.edit', [
            'title' => 'Klant wijzigen',
            'klant' => $klant,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
{
    $klant = $this->klantModel->spGetKlantById($id);

    abort_if(! $klant, 404);

    $data = $request->validate([
        'voornaam' => ['required', 'string', 'max:50'],
        'achternaam' => ['required', 'string', 'max:50'],
        'email' => [
            'required',
            'email',
            'max:100',
            'unique:Gebruiker,Email,' . $klant->GebruikerId . ',Id',
        ],
        'telefoonnummer' => ['required', 'regex:/^[0-9]{1,10}$/'],
        'wensen' => ['nullable', 'string', 'max:255'],
        'opmerking' => ['nullable', 'string', 'max:255'],
        'straat' => ['required', 'string', 'max:100'],
        'huisnummer' => ['required', 'numeric', 'max:999999'],
        'toevoeging' => ['nullable', 'string', 'max:5'],
        'postcode' => ['required', 'string', 'max:6'],
        'woonplaats' => ['required', 'string', 'max:50'],
        'is_actief' => ['nullable', 'boolean'],
    ], [
        'email.unique' => 'email al in gebruik',
        'telefoonnummer.regex' => 'telefoonnummer mag alleen cijfers bevatten en maximaal 10 cijfers lang zijn',
    ]);

    $data['is_actief'] = $request->boolean('is_actief');

    $result = $this->klantModel->spUpdateKlant($id, $data);

    if ($result > 0) {
        return redirect()->route('klanten.index')
            ->with('success', 'klant succesvol gewijzigd');
    }

    return back()
        ->withInput()
        ->with('error', 'klant kon niet worden gewijzigd');
}

    public function destroy(int $id): RedirectResponse
    {
        $klant = $this->klantModel->spGetKlantById($id);

        if ($klant && isset($klant->Email) && auth()->check() && auth()->user()->email === $klant->Email) {
            return redirect()->route('klanten.index')->with('error', 'je kan jezelf niet verwijderen');
        }

        $result = $this->klantModel->spDeleteKlant($id);

        if ($result > 0) {
            return redirect()->route('klanten.index')->with('success', 'klant succesvol verwijderd');
        }

        return redirect()->route('klanten.index')->with('error', 'klant kon niet worden verwijderd');
    }
}
