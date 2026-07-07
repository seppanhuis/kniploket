<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">

        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                {{ $title }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Wijzig de gegevens van een afspraak.
            </p>
        </div>

        {{-- SUCCES / ERROR MELDINGEN --}}
        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @php($errorsBag = $errors ?? null)
        @if ($errorsBag && $errorsBag->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc pl-5">
                    @foreach ($errorsBag->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('afspraken.update', $afspraak->Id) }}"
              class="max-w-3xl rounded-2xl border bg-white p-6">

            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label>Klant</label>
                    <select name="klant_id" class="w-full border p-2">
                        @foreach($klanten as $klant)
                            <option value="{{ $klant->Id }}"
                                @selected(old('klant_id', $afspraak->KlantId) == $klant->Id)>
                                {{ $klant->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Medewerker</label>
                    <select name="medewerker_id" class="w-full border p-2">
                        @foreach($medewerkers as $medewerker)
                            <option value="{{ $medewerker->Id }}"
                                @selected(old('medewerker_id', $afspraak->MedewerkerId) == $medewerker->Id)>
                                {{ $medewerker->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Behandeling</label>
                    <select name="behandeling_id" class="w-full border p-2">
                        @foreach($behandelingen as $behandeling)
                            <option value="{{ $behandeling->BehandelingId }}"
                                @selected(old('behandeling_id', $afspraak->BehandelingId) == $behandeling->BehandelingId)>
                                {{ $behandeling->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Status</label>
                    <select name="afspraak_status_id" class="w-full border p-2">
                        @foreach($statussen as $status)
                            <option value="{{ $status->Id }}"
                                @selected(old('afspraak_status_id', $afspraak->AfspraakStatusId) == $status->Id)>
                                {{ $status->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Datum</label>
                    <input type="date" name="datum"
                        value="{{ old('datum', \Carbon\Carbon::parse($afspraak->Datum)->format('Y-m-d')) }}"
                        class="w-full border p-2">
                </div>

                {{-- 🔥 FIX HIER --}}
                <div>
                    <label>Starttijd</label>
                    <input type="time" name="start_tijd"
                        value="{{ old('start_tijd', \Carbon\Carbon::parse($afspraak->StartTijd)->format('H:i')) }}"
                        class="w-full border p-2">
                </div>

                <div>
                    <label>Eindtijd</label>
                    <input type="time" name="eind_tijd"
                        value="{{ old('eind_tijd', \Carbon\Carbon::parse($afspraak->EindTijd)->format('H:i')) }}"
                        class="w-full border p-2">
                </div>

                <div>
                    <label>Opmerking</label>
                    <input type="text" name="opmerking"
                        value="{{ old('opmerking', $afspraak->Opmerking) }}"
                        class="w-full border p-2">
                </div>

                <div class="md:col-span-2">
                    <label>
                        <input type="checkbox" name="is_actief" value="1"
                            @checked(old('is_actief', (int) $afspraak->IsActief) === 1)>
                        Actief
                    </label>
                </div>

            </div>

            <div class="mt-6">
                <button class="bg-black text-white px-4 py-2 rounded-xl">
                    Opslaan
                </button>
            </div>

        </form>
    </div>
</x-layouts::app>
