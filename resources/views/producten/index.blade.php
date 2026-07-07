<x-layouts::app :title="$title">

<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8"
    x-data="{ openDelete: false, deleteForm: null }">

    {{-- DELETE MODAL --}}
    <div x-show="openDelete"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl border border-rose-600 dark:bg-zinc-900">

            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                Product uitschakelen
            </h2>

            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                Weet je zeker dat je dit product wilt verwijderen?
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
                    Uitschakelen
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
                Overzicht van producten, voorraad en gekoppelde behandelingen.
            </p>
        </div>


        <a href="{{ route('producten.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">

            Nieuw product

        </a>

    </div>


    {{-- ALERTS --}}

    @if(session('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ session('error') }}
        </div>

    @endif



    <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 overflow-hidden">


        <div class="hidden lg:block">

            <table class="w-full table-fixed">

                <thead class="bg-zinc-50 dark:bg-zinc-950/40">

                    <tr>

                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Categorie</th>
                        <th class="px-4 py-3 text-left">EAN</th>
                        <th class="px-4 py-3 text-left">Voorraad</th>
                        <th class="px-4 py-3 text-left">Leverancier</th>
                        <th class="px-4 py-3 text-left">Behandelingen</th>
                        <th class="px-4 py-3 text-center">Acties</th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">


                @forelse($products as $product)

                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">


                        <td class="px-4 py-3">
                            {{ $product->ProductNaam }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $product->categorie_naam ?? '-' }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $product->EANCode }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $product->Voorraad }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $product->leverancier_naam ?? '-' }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $product->treatments->join(', ') ?: '-' }}
                        </td>



                        <td class="px-4 py-3">

                            <div class="flex justify-center gap-2">


                                <a href="{{ route('producten.edit',$product->Id) }}"
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-600">

                                    <img src="https://img.icons8.com/ios-filled/50/ffffff/edit.png"
                                        class="h-5 w-5">

                                </a>



                                <form method="POST"
                                    action="{{ route('producten.destroy',$product->Id) }}"
                                    x-ref="deleteForm{{ $product->Id }}">

                                    @csrf
                                    @method('DELETE')


                                    <button type="button"
                                        @click="deleteForm = $refs.deleteForm{{ $product->Id }}; openDelete = true"
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-600">


                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/delete.png"
                                            class="h-5 w-5">


                                    </button>


                                </form>


                            </div>


                        </td>


                    </tr>


                @empty

                    <tr>
                        <td colspan="7" class="py-8 text-center">
                            Geen producten gevonden
                        </td>
                    </tr>

                @endforelse


                </tbody>


            </table>


        </div>



        {{-- MOBILE --}}

        <div class="space-y-3 p-3 lg:hidden">


            @foreach($products as $product)


            <div class="rounded-xl border p-4">


                <div class="flex justify-between">

                    <span>
                        {{ $product->ProductNaam }}
                    </span>

                    <span>
                        {{ $product->Voorraad }}
                    </span>

                </div>



                <div class="mt-3 flex gap-2">


                    <a href="{{ route('producten.edit',$product->Id) }}"
                        class="flex-1 rounded bg-emerald-600 py-1 text-center text-xs text-white">
                        Edit
                    </a>



                    <form method="POST"
                        action="{{ route('producten.destroy',$product->Id) }}"
                        x-ref="deleteFormMobile{{ $product->Id }}">


                        @csrf
                        @method('DELETE')


                        <button type="button"
                            @click="deleteForm=$refs.deleteFormMobile{{ $product->Id }}; openDelete=true"
                            class="rounded bg-rose-600 px-4 py-1 text-xs text-white">

                            Del

                        </button>


                    </form>


                </div>


            </div>


            @endforeach


        </div>



    </div>


</div>


</x-layouts::app>