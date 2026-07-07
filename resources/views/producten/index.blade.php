<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ $title }}
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Overzicht van producten, voorraad en gekoppelde behandelingen.
                </p>
            </div>

            <a href="{{ route('producten.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                Nieuw product
            </a>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- LOW STOCK --}}
        @if ($lowStockProducts->isNotEmpty())
            <div
                class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200">
                <p class="font-semibold">Lage voorraad waarschuwingen</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($lowStockProducts as $product)
                        <li>
                            {{ $product->ProductNaam }} heeft nog {{ $product->Voorraad }} stuks
                            (minimum {{ $product->MinimumVoorraad }}).
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TABLE WRAPPER --}}
        <div
            class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 overflow-hidden">

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block">
                <table class="w-full table-fixed border-collapse">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium">Product</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Categorie</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">EAN</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Voorraad</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Leverancier</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Behandelingen</th>
                            <th class="px-4 py-3 text-center text-sm font-medium">Acties</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                                <td class="px-4 py-3 text-sm font-medium truncate" title="{{ $product->ProductNaam }}">
                                    {{ $product->ProductNaam }}
                                </td>

                                <td class="px-4 py-3 text-sm">{{ $product->categorie_naam ?? '-' }}</td>

                                <td class="px-4 py-3 text-sm" title="EAN Code {{ $product->EANCode }}">
                                    {{ $product->EANCode }}
                                </td>

                                <td class="px-4 py-3 text-sm font-medium
                                        {{ $product->low_stock ? 'text-amber-600' : 'text-zinc-900 dark:text-zinc-100' }}"
                                    title="Voorraad: {{ $product->Voorraad }}">
                                    {{ $product->Voorraad }}

                                    @if ($product->low_stock)
                                        <span class="ml-2 text-xs text-amber-600">⚠</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm">{{ $product->leverancier_naam ?? '-' }}</td>

                                <td class="px-4 py-3 text-sm truncate" title="Behandelingen">
                                    {{ $product->treatments->isNotEmpty() ? $product->treatments->join(', ') : '-' }}
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-4 py-3">
                                    <div class="flex flex-row items-center justify-center gap-2">

                                        {{-- EDIT --}}
                                        <a href="{{ route('producten.edit', $product->Id) }}"
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-600 hover:bg-emerald-500 transition"
                                            title="Product bewerken">

                                            <img src="https://img.icons8.com/ios-filled/50/ffffff/edit.png" alt="edit"
                                                class="h-5 w-5">
                                        </a>

                                        {{-- DELETE --}}
                                        <form action="{{ route('producten.destroy', $product->Id) }}" method="POST"
                                            onsubmit="return confirm('Weet je zeker dat je dit product wilt uitschakelen?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-600 hover:bg-rose-500 transition"
                                                title="Product verwijderen">

                                                <img src="https://img.icons8.com/ios-filled/50/ffffff/delete.png" alt="delete"
                                                    class="h-5 w-5">
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-sm text-zinc-500">
                                    Geen producten gevonden
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE --}}
            <div class="space-y-3 p-3 lg:hidden">

                @forelse ($products as $product)
                    <div class="rounded-xl border p-4 dark:border-zinc-800">

                        <div class="flex justify-between">
                            <div class="text-sm font-semibold" title="{{ $product->ProductNaam }}">
                                {{ $product->ProductNaam }}
                            </div>

                            <span class="text-xs">{{ $product->Voorraad }}</span>
                        </div>

                        <div class="text-xs text-zinc-500 mt-1">
                            {{ $product->EANCode }}
                        </div>

                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('producten.edit', $product->Id) }}"
                                class="flex-1 text-xs bg-emerald-600 text-white rounded py-1 text-center">
                                Edit
                            </a>

                            <form action="{{ route('producten.destroy', $product->Id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="flex-1 text-xs bg-rose-600 text-white rounded py-1">
                                    Del
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="text-center text-sm text-zinc-500">
                        Geen producten gevonden
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</x-layouts::app>