<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ $title }}
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Overzicht van alle behandelingen.
                </p>
            </div>

            <a
                href="{{ route('behandelingen.create') }}"
                class="inline-flex items-center rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                Nieuwe behandeling
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

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Naam</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Prijs</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Duur</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Opmerking</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:text-white">
                                Acties
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($behandelingen as $behandeling)
                            <tr>
                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $behandeling->Naam }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    EUR {{ number_format($behandeling->Prijs, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $behandeling->DuurMinuten }} minuten
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    @if ($behandeling->IsActief)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                            Actief
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-medium text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                            Inactief
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $behandeling->Opmerking ?: '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('behandelingen.edit', $behandeling->BehandelingId) }}"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-600 text-white transition hover:bg-emerald-500"
                                            title="Wijzig"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Z"/>
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('behandelingen.destroy', $behandeling->BehandelingId) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-rose-600 text-white transition hover:bg-rose-500"
                                                title="Verwijder"
                                                onclick="return confirm('Weet je zeker dat je deze behandeling wilt verwijderen?')"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-2.327L4.772 5.79m14.456 0A48.108 48.108 0 0 0 15.75 5.5m-6.75 0a48.11 48.11 0 0 1 3.75-.145m-3.75.145L8.09 4.28A2.25 2.25 0 0 1 10.337 3h3.326a2.25 2.25 0 0 1 2.247 1.28l.91 1.22"/>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                    Geen behandelingen gevonden
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
