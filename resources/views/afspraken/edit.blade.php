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

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('afspraken.update', $afspraak->Id) }}"
              class="max-w-3xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">

            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">

                {{-- KLANT --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Klant</label>
                    <select name="klant_id" required class="w-full rounded-xl border p-2">
                        @foreach($klanten as $klant)
                            <option value="{{ $klant->Id }}"
                                {{ old('klant_id', $afspraak->KlantId) == $klant->Id ? 'selected' : '' }}>
                                {{ $klant->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- MEDEWERKER --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Medewerker</label>
                    <select name="medewerker_id" required class="w-full rounded-xl border p-2">
                        @foreach($medewerkers as $medewerker)
                            <option value="{{ $medewerker->Id }}"
                                {{ old('medewerker_id', $afspraak->MedewerkerId) == $medewerker->Id ? 'selected' : '' }}>
                                {{ $medewerker->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BEHANDELING --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Behandeling</label>
                    <select name="behandeling_id" required class="w-full rounded-xl border p-2">
                        @foreach($behandelingen as $behandeling)
                            <option value="{{ $behandeling->BehandelingId }}"
                                {{ old('behandeling_id', $afspraak->BehandelingId) == $behandeling->BehandelingId ? 'selected' : '' }}>
                                {{ $behandeling->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Status</label>
                    <select name="afspraak_status_id" required class="w-full rounded-xl border p-2">
                        @foreach($statussen as $status)
                            <option value="{{ $status->Id }}"
                                {{ old('afspraak_status_id', $afspraak->AfspraakStatusId) == $status->Id ? 'selected' : '' }}>
                                {{ $status->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DATUM --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Datum</label>
                    <input type="date"
                           name="datum"
                           value="{{ old('datum', \Carbon\Carbon::parse($afspraak->Datum)->format('Y-m-d')) }}"
                           class="w-full rounded-xl border p-2">
                </div>

                {{-- START --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Starttijd</label>
                    <input type="time"
                           name="start_tijd"
                           value="{{ old('start_tijd', $afspraak->StartTijd) }}"
                           class="w-full rounded-xl border p-2">
                </div>

                {{-- EINDE --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Eindtijd</label>
                    <input type="time"
                           name="eind_tijd"
                           value="{{ old('eind_tijd', $afspraak->EindTijd) }}"
                           class="w-full rounded-xl border p-2">
                </div>

                {{-- OPMERKING --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">Opmerking</label>
                    <input type="text"
                           name="opmerking"
                           value="{{ old('opmerking', $afspraak->Opmerking) }}"
                           class="w-full rounded-xl border p-2">
                </div>

                {{-- ACTIEF (FIXED) --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-sm font-medium">
                        <input type="checkbox"
                               name="is_actief"
                               value="1"
                               @checked(old('is_actief', (int) $afspraak->IsActief) === 1)>
                        Actief
                    </label>
                </div>

            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="rounded-xl bg-black px-4 py-2 text-white">
                    Opslaan
                </button>

                <a href="{{ route('afspraken.index') }}"
                   class="rounded-xl border px-4 py-2">
                    Annuleren
                </a>
            </div>

        </form>
    </div>
</x-layouts::app>