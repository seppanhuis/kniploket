<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht van producten, voorraad en gekoppelde behandelingen.</p>
            </div>

            <a href="{{ route('producten.create') }}" class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                Nieuw product
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        @if ($lowStockProducts->isNotEmpty())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200">
                <p class="font-semibold">Lage voorraad waarschuwingen</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($lowStockProducts as $product)
                        <li>{{ $product->ProductNaam }} heeft nog {{ $product->Voorraad }} stuks op voorraad (minimum {{ $product->MinimumVoorraad }}).</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="hidden overflow-hidden lg:block">
                <table class="w-full table-fixed border-collapse">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300" w-48">Productnaam</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Categorie</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">EAN-code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Voorraad</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Leverancier</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Behandelingen</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-zinc-600 dark:text-zinc-300">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100"><div class="truncate" title="{{ $product->ProductNaam }}">{{ $product->ProductNaam }}</div></td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300"><div class="truncate" title="{{ $product->categorie_naam ?? '-' }}">{{ $product->categorie_naam ?? '-' }}</div></td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300 whitespace-nowrap">{{ $product->EANCode }}</td>
                                <td class="px-4 py-3 text-sm font-medium break-words {{ $product->low_stock ? 'text-amber-600 dark:text-amber-400' : 'text-zinc-900 dark:text-zinc-100' }}">
                                    {{ $product->Voorraad }}
                                    @if ($product->low_stock)
                                        <span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Lage voorraad</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300"><div class="truncate" title="{{ $product->leverancier_naam ?? '-' }}">{{ $product->leverancier_naam ?? '-' }}</div></td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300 break-words">
                                    @if ($product->treatments->isNotEmpty())
                                        {{ $product->treatments->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <a href="{{ route('producten.edit', $product->Id) }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-emerald-500">
                                            Wijzig
                                        </a>
                                        <form action="{{ route('producten.destroy', $product->Id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit product wilt uitschakelen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-rose-500">
                                                Verwijder
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen producten gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-3 lg:hidden">
                @forelse ($products as $product)
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-950/60">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $product->ProductNaam }}</h3>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $product->categorie_naam ?? '-' }}</p>
                            </div>
                            <span class="rounded-full {{ $product->low_stock ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' }} px-2.5 py-1 text-xs font-medium">
                                {{ $product->Voorraad }} in voorraad
                            </span>
                        </div>

                        <dl class="mt-3 space-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <div class="flex justify-between gap-3">
                                <dt class="font-medium">EAN</dt>
                                <dd class="text-right break-all">{{ $product->EANCode }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="font-medium">Leverancier</dt>
                                <dd class="text-right break-all">{{ $product->leverancier_naam ?? '-' }}</dd>
                            </div>
                            <div class="flex flex-col gap-1">
                                <dt class="font-medium">Behandelingen</dt>
                                <dd class="text-zinc-600 dark:text-zinc-400">{{ $product->treatments->isNotEmpty() ? $product->treatments->join(', ') : '-' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('producten.edit', $product->Id) }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-emerald-500">
                                Wijzig
                            </a>
                            <form action="{{ route('producten.destroy', $product->Id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit product wilt uitschakelen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-rose-500">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen producten gevonden</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts::app>
