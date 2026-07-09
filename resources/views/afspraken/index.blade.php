<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8"
         x-data="{ openDelete: false, deleteForm: null }">

        <div x-show="openDelete"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

            <div class="w-full max-w-md rounded-2xl border border-rose-600 bg-white p-6 shadow-xl dark:bg-zinc-900">

                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                    Afspraak verwijderen
                </h2>

                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Weet je zeker dat je deze afspraak wilt verwijderen?
                </p>

                <div class="mt-6 flex justify-end gap-3">

                    <button type="button"
                            @click="openDelete = false"
                            class="rounded-lg bg-zinc-200 px-4 py-2 text-sm hover:bg-zinc-300 dark:bg-zinc-800 dark:text-white">
                        Annuleren
                    </button>

                    <button type="button"
                            @click="deleteForm.submit()"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm text-white hover:bg-rose-500">
                        Verwijderen
                    </button>

                </div>

            </div>

        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ $title }}
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Overzicht van alle afspraken.
                </p>
            </div>

            <a href="{{ route('afspraken.create') }}"
               title="Nieuwe afspraak aanmaken"
               class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                Nieuwe afspraak
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
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Klant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Medewerker</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Behandeling</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Datum</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Tijd</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Actief</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-zinc-500">Opmerking</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-zinc-500">Acties</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">

                        @forelse ($afspraken as $afspraak)
                            <tr>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Klant ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Medewerker ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Behandeling ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Datum ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->StartTijd ?? '-' }} - {{ $afspraak->EindTijd ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Status ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    @if ((int) ($afspraak->IsActief ?? 0) === 1)
                                        Actief
                                    @else
                                        Inactief
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                    {{ $afspraak->Opmerking ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-right text-sm">
                                    <div class="flex justify-end gap-2">

                                        <a href="{{ route('afspraken.edit', $afspraak->Id) }}"
                                           title="Afspraak wijzigen"
                                           class="rounded-lg border border-zinc-300 px-3 py-1.5 text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                            Wijzigen
                                        </a>

                                                                                <form method="POST"
                                                                                            action="{{ route('afspraken.destroy', $afspraak->Id) }}"
                                                                                            x-ref="deleteForm{{ $afspraak->Id }}">

                                            @csrf
                                            @method('DELETE')

                                                                                        <button type="button"
                                                                                                        @click="deleteForm = $refs.deleteForm{{ $afspraak->Id }}; openDelete = true"
                                                    title="Afspraak verwijderen"
                                                    class="rounded-lg border border-rose-300 px-3 py-1.5 text-rose-700 hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/40">
                                                Verwijderen
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-sm text-zinc-500">
                                    Geen afspraken gevonden
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</x-layouts::app>