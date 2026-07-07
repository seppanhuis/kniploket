<?php

namespace App\Http\Controllers;

use App\Models\Afspraak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

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
            'datum' => ['required', 'date'],
            'start_tijd' => ['required'],
            'eind_tijd' => ['required'],
            'opmerking' => ['nullable', 'string', 'max:255'],
        ]);
        $data['is_actief'] = $request->boolean('is_actief');

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['datum'].' '.$data['start_tijd']);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $data['datum'].' '.$data['eind_tijd']);
        $now   = Carbon::now();

        if ($start->lt($now)) {
            return back()->withInput()->withErrors([
                'start_tijd' => 'Geen afspraken in het verleden.'
            ]);
        }

        if ($end->lte($start)) {
            return back()->withInput()->withErrors([
                'eind_tijd' => 'Eindtijd moet na starttijd liggen.'
            ]);
        }

        if ($this->hasOverlap($start, $end)) {
            return back()->withInput()->with('error', 'Dit tijdslot is al bezet.');
        }

        $data['start_tijd'] = $start->format('H:i:s');
        $data['eind_tijd'] = $end->format('H:i:s');
        $data['datum'] = $start->format('Y-m-d H:i:s');

        $result = $this->afspraakModel->spCreateAfspraak($data);

        return ($result && $result->new_id)
            ? redirect()->route('afspraken.index')->with('success', 'Toegevoegd')
            : back()->with('error', 'Mislukt');
    }

    public function edit(int $id): View
    {
        $afspraak = DB::table('Afspraak')->where('Id', $id)->first();

        abort_if(! $afspraak, 404);

        return view('afspraken.edit', [
            'title' => 'Wijzigen',
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
            'klant_id' => ['required'],
            'medewerker_id' => ['required'],
            'behandeling_id' => ['required'],
            'afspraak_status_id' => ['required'],
            'datum' => ['required'],
            'start_tijd' => ['required'],
            'eind_tijd' => ['required'],
            'is_actief' => ['nullable'],
        ]);
        $data['is_actief'] = $request->boolean('is_actief');

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['datum'].' '.$data['start_tijd']);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $data['datum'].' '.$data['eind_tijd']);
        $now   = Carbon::now();

        if ($start->lt($now)) {
            return back()->withInput()->withErrors([
                'start_tijd' => 'Geen verleden afspraken.'
            ]);
        }

        if ($end->lte($start)) {
            return back()->withInput()->withErrors([
                'eind_tijd' => 'Ongeldige tijd.'
            ]);
        }

        if ($this->hasOverlap($start, $end, $id)) {
            return back()->withInput()->with('error', 'Dit tijdslot is al bezet.');
        }

        $data['start_tijd'] = $start->format('H:i:s');
        $data['eind_tijd'] = $end->format('H:i:s');
        $data['datum'] = $start->format('Y-m-d H:i:s');

        try {
            $result = $this->afspraakModel->spUpdateAfspraak($id, $data);
        } catch (\Throwable $e) {
            if ((string) $e->getCode() === '45000' || str_contains($e->getMessage(), 'Er bestaat al een afspraak op dit tijdslot')) {
                throw ValidationException::withMessages([
                    'start_tijd' => 'Dit tijdslot is al bezet.',
                ]);
            }

            throw $e;
        }

        return ($result > 0)
            ? redirect()->route('afspraken.index')->with('success', 'Gewijzigd')
            : back()->with('error', 'Mislukt');
    }

    public function destroy(int $id): RedirectResponse
    {
        $afspraak = DB::table('Afspraak')
            ->select('IsActief')
            ->where('Id', $id)
            ->first();

        if (! $afspraak) {
            return redirect()->route('afspraken.index')->with('error', 'Afspraak niet gevonden.');
        }

        if (! (bool) $afspraak->IsActief) {
            return redirect()->route('afspraken.index')->with('error', 'Inactieve afspraken kunnen niet worden verwijderd');
        }

        $deleted = $this->afspraakModel->spDeleteAfspraak($id);

        return ($deleted > 0)
            ? redirect()->route('afspraken.index')->with('success', 'Afspraak verwijderd.')
            : redirect()->route('afspraken.index')->with('error', 'Afspraak kon niet worden verwijderd.');
    }

    /**
     * 🔥 FIX: correcte overlap check (DIT lost je probleem op)
     */
    private function hasOverlap(Carbon $start, Carbon $end, $ignoreId = null): bool
    {
        return DB::table('Afspraak')
            ->whereRaw("
                TIMESTAMP(Datum, StartTijd) < ?
                AND TIMESTAMP(Datum, EindTijd) > ?
            ", [$end, $start])
            ->when($ignoreId, fn($q) => $q->where('Id', '!=', $ignoreId))
            ->exists();
    }

    private function getKlanten()
    {
        return DB::table('Klant')
            ->join('Gebruiker', 'Gebruiker.Id', '=', 'Klant.GebruikerId')
            ->select('Klant.Id', DB::raw("CONCAT(Gebruiker.Voornaam, ' ', Gebruiker.Achternaam) AS Naam"))
            ->get();
    }

    private function getMedewerkers()
    {
        return DB::table('Medewerker')
            ->join('Gebruiker', 'Gebruiker.Id', '=', 'Medewerker.GebruikerId')
            ->select('Medewerker.Id', DB::raw("CONCAT(Gebruiker.Voornaam, ' ', Gebruiker.Achternaam) AS Naam"))
            ->get();
    }

    private function getBehandelingen()
    {
        return DB::table('Behandeling')->select('BehandelingId', 'Naam')->get();
    }

    private function getStatussen()
    {
        return DB::table('AfspraakStatus')->select('Id', 'Naam')->get();
    }
}