<?php

namespace App\Http\Controllers;

use App\Models\Behandeling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BehandelingController extends Controller
{
    private Behandeling $behandelingModel;

    public function __construct()
    {
        $this->behandelingModel = new Behandeling();
    }

    public function index()
    {
        try {
            $behandelingen = $this->behandelingModel->sp_GetAllBehandelingen();

            return view('behandelingen.index', [
                'title' => 'Behandelingen',
                'behandelingen' => $behandelingen,
            ]);
        } catch (\Throwable $e) {
            Log::error('Fout bij ophalen behandelingen', [
                'message' => $e->getMessage(),
            ]);

            return view('behandelingen.index', [
                'title' => 'Behandelingen',
                'behandelingen' => [],
            ])->with('error', 'Er is een fout opgetreden bij het laden van de behandelingen.');
        }
    }

    public function create()
    {
        return view('behandelingen.create', [
            'title' => 'Nieuwe behandeling toevoegen',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:100',
            'prijs' => 'required|numeric|min:0',
            'duur_minuten' => 'required|integer|min:1',
            'opmerking' => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->behandelingModel->sp_CreateBehandeling(
                $data['naam'],
                (float) $data['prijs'],
                (int) $data['duur_minuten'],
                $data['opmerking'] ?? null
            );

            if (($result->status ?? '') === 'exists') {
                return back()
                    ->withInput()
                    ->with('error', 'Behandeling bestaat al');
            }

            return redirect()
                ->route('behandelingen.index')
                ->with('success', 'Behandeling succesvol toegevoegd');
        } catch (\Throwable $e) {
            Log::error('Fout bij toevoegen behandeling', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het toevoegen van de behandeling.');
        }
    }

    public function edit($id)
    {
        try {
            $behandeling = $this->behandelingModel->sp_GetBehandelingById((int) $id);

            abort_if(!$behandeling, 404);

            return view('behandelingen.edit', [
                'title' => 'Behandeling wijzigen',
                'behandeling' => $behandeling,
            ]);
        } catch (\Throwable $e) {
            Log::error('Fout bij ophalen behandeling voor wijzigen', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('behandelingen.index')
                ->with('error', 'De behandeling kon niet worden geladen.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:100',
            'prijs' => 'required|numeric|min:0',
            'duur_minuten' => 'required|integer|min:1',
            'is_actief' => 'required|boolean',
            'opmerking' => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->behandelingModel->sp_UpdateBehandeling(
                (int) $id,
                $data['naam'],
                (float) $data['prijs'],
                (int) $data['duur_minuten'],
                (int) $data['is_actief'],
                $data['opmerking'] ?? null
            );

            if (($result->status ?? '') === 'exists') {
                return back()
                    ->withInput()
                    ->with('error', 'Behandeling bestaat al');
            }

            if (($result->affected ?? 0) > 0) {
                return redirect()
                    ->route('behandelingen.index')
                    ->with('success', 'Behandeling succesvol gewijzigd');
            }

            return back()
                ->withInput()
                ->with('error', 'Behandeling is niet gewijzigd');
        } catch (\Throwable $e) {
            Log::error('Fout bij wijzigen behandeling', [
                'id' => $id,
                'message' => $e->getMessage(),
                'data' => $data,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het wijzigen van de behandeling.');
        }
    }

    public function destroy($id)
{
    try {
        $result = $this->behandelingModel->sp_DeleteBehandeling((int) $id);

        if (($result->affected ?? 0) > 0) {
            return redirect()
                ->route('behandelingen.index')
                ->with('success', 'Behandeling succesvol verwijderd');
        }

        return redirect()
            ->route('behandelingen.index')
            ->with('error', 'Behandeling bestaat niet of is al verwijderd');
    } catch (\Throwable $e) {
        Log::error('Fout bij verwijderen behandeling', [
            'id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()
            ->route('behandelingen.index')
            ->with('error', 'Er is een fout opgetreden bij het verwijderen van de behandeling.');
    }
}

}
