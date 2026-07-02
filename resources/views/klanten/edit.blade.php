<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Wijzig de gegevens van een klant.</p>
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

        <form method="POST" action="{{ route('klanten.update', $klant->Id ?? $klant->id) }}" class="max-w-3xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="voornaam" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Voornaam</label>
                    <input type="text" id="voornaam" name="voornaam" value="{{ old('voornaam', $klant->Voornaam ?? $klant->voornaam ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="achternaam" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Achternaam</label>
                    <input type="text" id="achternaam" name="achternaam" value="{{ old('achternaam', $klant->Achternaam ?? $klant->achternaam ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $klant->Email ?? $klant->email ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="telefoonnummer" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Telefoonnummer</label>
                    <input type="text" id="telefoonnummer" name="telefoonnummer" value="{{ old('telefoonnummer', $klant->Telefoonnummer ?? $klant->telefoonnummer ?? '') }}" required maxlength="10" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">

                </div>

                <div>
                    <label for="straat" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Straat</label>
                    <input type="text" id="straat" name="straat" value="{{ old('straat', $klant->Straat ?? $klant->straat ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="huisnummer" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Huisnummer</label>
                    <input type="text" id="huisnummer" name="huisnummer" value="{{ old('huisnummer', $klant->Huisnummer ?? $klant->huisnummer ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="toevoeging" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Toevoeging</label>
                    <input type="text" id="toevoeging" name="toevoeging" value="{{ old('toevoeging', $klant->Toevoeging ?? $klant->toevoeging ?? '') }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="postcode" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Postcode</label>
                    <input type="text" id="postcode" name="postcode" value="{{ old('postcode', $klant->Postcode ?? $klant->postcode ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="woonplaats" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Woonplaats</label>
                    <input type="text" id="woonplaats" name="woonplaats" value="{{ old('woonplaats', $klant->Woonplaats ?? $klant->woonplaats ?? '') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="wensen" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Wensen</label>
                    <input type="text" id="wensen" name="wensen" value="{{ old('wensen', $klant->Wensen ?? $klant->wensen ?? '') }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="opmerking" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Opmerking</label>
                    <input type="text" id="opmerking" name="opmerking" value="{{ old('opmerking', $klant->Opmerking ?? $klant->opmerking ?? '') }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        <input type="checkbox" name="is_actief" value="1" {{ old('is_actief', (int) ($klant->IsActief ?? $klant->is_actief ?? 1)) ? 'checked' : '' }} class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950">
                        Actief
                    </label>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Klant wijzigen
                </button>
                <a href="{{ route('klanten.index') }}" class="inline-flex items-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>
