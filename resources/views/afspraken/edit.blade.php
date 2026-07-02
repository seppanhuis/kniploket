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

        {{-- ✅ SUCCES / ERROR --}}
        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900/60 dark:bg-green-950/40 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

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

                <div>
                    <label>Klant</label>
                    <select name="klant_id" class="w-full border p-2">
                        @foreach($klanten as $klant)
                            <option value="{{ $klant->Id }}"
                                {{ old('klant_id', $afspraak->KlantId) == $klant->Id ? 'selected' : '' }}>
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
                                {{ old('medewerker_id', $afspraak->MedewerkerId) == $medewerker->Id ? 'selected' : '' }}>
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
                                {{ old('behandeling_id', $afspraak->BehandelingId) == $behandeling->BehandelingId ? 'selected' : '' }}>
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
                                {{ old('afspraak_status_id', $afspraak->AfspraakStatusId) == $status->Id ? 'selected' : '' }}>
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

                <div>
                    <label>Starttijd</label>
                    <input type="time" name="start_tijd"
                           value="{{ old('start_tijd', $afspraak->StartTijd) }}"
                           class="w-full border p-2">
                </div>

                <div>
                    <label>Eindtijd</label>
                    <input type="time" name="eind_tijd"
                           value="{{ old('eind_tijd', $afspraak->EindTijd) }}"
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