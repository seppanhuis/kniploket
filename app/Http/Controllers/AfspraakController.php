<?php

namespace App\Http\Controllers;

use App\Models\Afspraak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AfspraakController extends Controller
{
    public function __construct(protected Afspraak $afspraakModel)
    {
    }

    public function index(): View
    {
        return view('afspraken.index', [
            'title' => 'Afspraken',
            'afspraken' => $this->afspraakModel->spGetAllAfspraken(),
        ]);
    }

    public function create(): View
    {
        return view('afspraken.create', [
            'title' => 'Nieuwe afspraak toevoegen',
            'klanten' => $this->getKlanten(),
            'medewerkers' => $this->getMedewerkers(),
            'behandelingen' => $this->getBehandelingen(),
            'statussen' => $this->getStatussen(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'klant_id' => ['required', 'integer'],
            'medewerker_id' => ['required', 'integer'],
            'behandeling_id' => ['required', 'integer'],
            'afspraak_status_id' => ['required', 'integer'],
            'datum' => ['required', 'date', 'after_or_equal:today'],
            'start_tijd' => ['required'],
            'eind_tijd' => ['required'],
            'opmerking' => ['nullable', 'string', 'max:255'],
            'is_actief' => ['nullable', 'boolean'],
        ]);

        $data['is_actief'] = $request->boolean('is_actief');

        // 🔥 FIX: tijd check (belangrijk)
        if (strtotime($data['eind_tijd']) <= strtotime($data['start_tijd'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'eind_tijd' => 'De eindtijd mag niet eerder zijn dan de starttijd.'
                ]);
        }

        if ($this->hasOverlap(
            $data['medewerker_id'],
            $data['datum'],
            $data['start_tijd'],
            $data['eind_tijd']
        )) {
            return back()
                ->withInput()
                ->with('error', 'Dit tijdstip is al in gebruik');
        }

        $result = $this->afspraakModel->spCreateAfspraak($data);

        return ($result && $result->new_id)
            ? redirect()->route('afspraken.index')->with('success', 'Afspraak succesvol toegevoegd')
            : back()->withInput()->with('error', 'Afspraak kon niet worden toegevoegd');
    }

    public function edit(int $id): View
    {
        $afspraak = $this->afspraakModel->spGetAfspraakById($id);
        abort_if(! $afspraak, 404);

        return view('afspraken.edit', [
            'title' => 'Afspraak wijzigen',
            'afspraak' => $afspraak,
            'klanten' => $this->getKlanten(),
            'medewerkers' => $this->getMedewerkers(),
            'behandelingen' => $this->getBehandelingen(),
            'statussen' => $this->getStatussen(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'klant_id' => ['required', 'integer'],
            'medewerker_id' => ['required', 'integer'],
            'behandeling_id' => ['required', 'integer'],
            'afspraak_status_id' => ['required', 'integer'],
            'datum' => ['required', 'date', 'after_or_equal:today'],
            'start_tijd' => ['required'],
            'eind_tijd' => ['required'],
            'opmerking' => ['nullable', 'string', 'max:255'],
            'is_actief' => ['nullable', 'boolean'],
        ]);

        $data['is_actief'] = $request->boolean('is_actief');

        if (strtotime($data['eind_tijd']) <= strtotime($data['start_tijd'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'eind_tijd' => 'De eindtijd mag niet eerder zijn dan de starttijd.'
                ]);
        }

        if ($this->hasOverlap(
            $data['medewerker_id'],
            $data['datum'],
            $data['start_tijd'],
            $data['eind_tijd'],
            $id
        )) {
            return back()
                ->withInput()
                ->with('error', 'Dit tijdstip is al in gebruik');
        }

        $result = $this->afspraakModel->spUpdateAfspraak($id, $data);

        return ($result > 0)
            ? redirect()->route('afspraken.index')->with('success', 'Afspraak succesvol gewijzigd')
            : back()->withInput()->with('error', 'Afspraak kon niet worden gewijzigd');
    }

    public function destroy(int $id): RedirectResponse
    {
        $afspraak = $this->afspraakModel->spGetAfspraakById($id);

        if (! $afspraak) {
            return back()->with('error', 'Afspraak niet gevonden');
        }

        if ((int) $afspraak->IsActief === 0) {
            return back()->with('error', 'Inactieve afspraken kunnen niet verwijderd worden');
        }

        $result = $this->afspraakModel->spDeleteAfspraak($id);

        return ($result > 0)
            ? redirect()->route('afspraken.index')->with('success', 'Afspraak verwijderd')
            : back()->with('error', 'Kon afspraak niet verwijderen');
    }

    private function hasOverlap($medewerkerId, $datum, $start, $eind, $ignoreId = null): bool
    {
        $query = DB::table('Afspraak')
            ->where('MedewerkerId', $medewerkerId)
            ->whereDate('Datum', $datum)
            ->where('StartTijd', '<', $eind)
            ->where('EindTijd', '>', $start);

        if ($ignoreId) {
            $query->where('Id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    private function getKlanten()
    {
        return DB::table('Klant')
            ->join('Gebruiker', 'Gebruiker.Id', '=', 'Klant.GebruikerId')
            ->select('Klant.Id', DB::raw("CONCAT(Gebruiker.Voornaam, ' ', Gebruiker.Achternaam) AS Naam"))
            ->orderBy('Gebruiker.Voornaam')
            ->get();
    }

    private function getMedewerkers()
    {
        return DB::table('Medewerker')
            ->join('Gebruiker', 'Gebruiker.Id', '=', 'Medewerker.GebruikerId')
            ->select('Medewerker.Id', DB::raw("CONCAT(Gebruiker.Voornaam, ' ', Gebruiker.Achternaam) AS Naam"))
            ->orderBy('Gebruiker.Voornaam')
            ->get();
    }

    private function getBehandelingen()
    {
        return DB::table('Behandeling')
            ->select('BehandelingId', 'Naam')
            ->orderBy('Naam')
            ->get();
    }

    private function getStatussen()
    {
        return DB::table('AfspraakStatus')
            ->select('Id', 'Naam')
            ->orderBy('Naam')
            ->get();
    }
}