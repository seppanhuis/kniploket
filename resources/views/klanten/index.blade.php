<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht van alle klanten.</p>
            </div>

            <a href="{{ route('klanten.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                Nieuwe klant
            </a>
        </div>

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

        <div
            class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Naam</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Telefoon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Status</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Acties</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($klanten as $klant)
                            <tr>
                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $klant->Voornaam ?? ($klant->voornaam ?? '') }}
                                    {{ $klant->Achternaam ?? ($klant->achternaam ?? '') }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $klant->Email ?? ($klant->email ?? '') }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $klant->Telefoonnummer ?? ($klant->telefoonnummer ?? '') }}</td>
                                <td class="px-4 py-3 text-sm">
    @php
        $isActief = (int) ($klant->IsActief ?? ($klant->is_actief ?? 1));
    @endphp

    <span class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-semibold
        {{ $isActief
            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
        <span class="h-2 w-2 rounded-full {{ $isActief ? 'bg-green-500' : 'bg-red-500' }}"></span>
        {{ $isActief ? 'Actief' : 'Inactief' }}
    </span>
</td>
                                <td class="px-4 py-3 text-right text-sm">
                                    <div class="flex justify-end items-center gap-2">

                                        <!-- Bewerken -->
                                        <a href="{{ route('klanten.edit', $klant->Id ?? $klant->id) }}"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-600 transition-all duration-200 hover:scale-105 hover:bg-blue-100 hover:shadow-md dark:border-blue-800 dark:bg-blue-950/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                                            title="Klant bewerken">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.1 2.1 0 113 2.97L8.25 18.07l-4.25 1.03 1.03-4.25L16.862 3.487z" />
                                            </svg>
                                        </a>

                                        <!-- Verwijderen -->
                                        <form method="POST"
                                            action="{{ route('klanten.destroy', $klant->Id ?? $klant->id) }}"
                                            onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-600 transition-all duration-200 hover:scale-105 hover:bg-red-100 hover:shadow-md dark:border-red-800 dark:bg-red-950/30 dark:text-red-400 dark:hover:bg-red-900/50"
                                                title="Klant verwijderen">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 7h12M9 7V4h6v3m-8 0l1 13h6l1-13" />
                                                </svg>

                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen klanten
                                    gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
