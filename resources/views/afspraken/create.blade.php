<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Voeg een nieuwe afspraak toe.</p>
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

        <form method="POST" action="{{ route('afspraken.store') }}"
              class="max-w-3xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label for="klant_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Klant
                    </label>

                    <select
                        id="klant_id"
                        name="klant_id"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">

                        <option value="">Kies klant</option>

                        @foreach($klanten as $klant)
                            <option
                                value="{{ $klant->Id }}"
                                @selected(old('klant_id') == $klant->Id)>
                                {{ $klant->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="medewerker_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Medewerker
                    </label>

                    <select
                        id="medewerker_id"
                        name="medewerker_id"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">

                        <option value="">Kies medewerker</option>

                        @foreach($medewerkers as $medewerker)
                            <option
                                value="{{ $medewerker->Id }}"
                                @selected(old('medewerker_id') == $medewerker->Id)>
                                {{ $medewerker->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="behandeling_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Behandeling
                    </label>

                    <select
                        id="behandeling_id"
                        name="behandeling_id"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">

                        <option value="">Kies behandeling</option>

                        @foreach($behandelingen as $behandeling)
                            <option
                                value="{{ $behandeling->BehandelingId }}"
                                @selected(old('behandeling_id') == $behandeling->BehandelingId)>
                                {{ $behandeling->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="afspraak_status_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Status
                    </label>

                    <select
                        id="afspraak_status_id"
                        name="afspraak_status_id"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">

                        <option value="">Kies status</option>

                        @foreach($statussen as $status)
                            <option
                                value="{{ $status->Id }}"
                                @selected(old('afspraak_status_id') == $status->Id)>
                                {{ $status->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="datum" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Datum
                    </label>

                    <input
                        type="date"
                        id="datum"
                        name="datum"
                        value="{{ old('datum') }}"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">
                </div>

                <div>
                    <label for="start_tijd" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Starttijd
                    </label>

                    <input
                        type="time"
                        id="start_tijd"
                        name="start_tijd"
                        value="{{ old('start_tijd') }}"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">
                </div>

                <div>
                    <label for="eind_tijd" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Eindtijd
                    </label>

                    <input
                        type="time"
                        id="eind_tijd"
                        name="eind_tijd"
                        value="{{ old('eind_tijd') }}"
                        required
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">
                </div>

                <div>
                    <label for="opmerking" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Opmerking
                    </label>

                    <input
                        type="text"
                        id="opmerking"
                        name="opmerking"
                        value="{{ old('opmerking') }}"
                        class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        <input
                            type="checkbox"
                            name="is_actief"
                            value="1"
                            checked
                            class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950">

                        Actief
                    </label>
                </div>

            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Afspraak toevoegen
                </button>

                <a
                    href="{{ route('afspraken.index') }}"
                    class="inline-flex items-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Annuleren
                </a>
            </div>

        </form>
    </div>
</x-layouts::app>