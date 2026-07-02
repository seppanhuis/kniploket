<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Voeg een nieuw product toe met voorraad, leverancier en categorie.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('producten.store') }}" class="max-w-3xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="product_naam" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Productnaam</label>
                    <input type="text" id="product_naam" name="product_naam" value="{{ old('product_naam') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="ean_code" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">EAN-code</label>
                    <input type="text" id="ean_code" name="ean_code" value="{{ old('ean_code') }}" required maxlength="13" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="voorraad" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Voorraad</label>
                    <input type="number" id="voorraad" name="voorraad" value="{{ old('voorraad') }}" required min="0" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="minimum_voorraad" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Minimum voorraad</label>
                    <input type="number" id="minimum_voorraad" name="minimum_voorraad" value="{{ old('minimum_voorraad') }}" required min="0" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="leverancier_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Leverancier</label>
                    <select id="leverancier_id" name="leverancier_id" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        <option value="">Kies een leverancier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->Id }}" {{ old('leverancier_id') == $supplier->Id ? 'selected' : '' }}>{{ $supplier->Naam }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="categorie_id" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Categorie</label>
                    <select id="categorie_id" name="categorie_id" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        <option value="">Kies een categorie</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->Id }}" {{ old('categorie_id') == $category->Id ? 'selected' : '' }}>{{ $category->Naam }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="is_actief" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Actief</label>
                    <select id="is_actief" name="is_actief" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        <option value="1" {{ old('is_actief', true) ? 'selected' : '' }}>Ja</option>
                        <option value="0" {{ old('is_actief') === '0' ? 'selected' : '' }}>Nee</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Behandelingen</label>
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-800 dark:bg-zinc-950/60">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($treatments as $treatment)
                                <label class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
                                    <input type="checkbox" name="treatment_ids[]" value="{{ $treatment->BehandelingId }}" {{ in_array($treatment->BehandelingId, old('treatment_ids', $selectedTreatmentIds ?? []), true) ? 'checked' : '' }}>
                                    <span>{{ $treatment->Naam }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Selecteer één of meerdere behandelingen door op de vakjes te klikken.</p>
                </div>

                <div>
                    <label for="opmerking" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Opmerking</label>
                    <input type="text" id="opmerking" name="opmerking" value="{{ old('opmerking') }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Opslaan
                </button>
                <a href="{{ route('producten.index') }}" class="inline-flex items-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>
