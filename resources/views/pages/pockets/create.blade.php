@php
    $breadcrumps = [
        [
            'label' => 'Kantong',
            'url' => route('pockets.index')
        ],
        [
            'label' => 'Buat Kantong Baru',
            'url' => route('pockets.create')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps">
    <h3 class="font-semibold text-xl">Buat Kantong Baru</h3>

    <form action="{{ route('pockets.store') }}" method="POST" class="w-full">
        @csrf
        <section class="w-full grid grid-cols-12 gap-3 my-5">
            <div class="mb-3 col-span-12 md:col-span-6">
                <x-ui.label for="name" required>Nama</x-ui.label>
                <x-ui.input
                    type="text"
                    id="name"
                    name="name"
                    required
                    autocomplete="name"
                    placeholder="Tabungan Dana Darurat"
                    class="w-full"
                    value="{{ old('name') }}"
                />
            </div>
            <div class="mb-3 col-span-12 md:col-span-6"></div>
            <div class="mb-3 col-span-12 md:col-span-6">
                <x-ui.label for="amount" required>Saldo Awal (Rp)</x-ui.label>
                <x-ui.input
                    type="number"
                    id="amount"
                    name="amount"
                    required
                    placeholder="0"
                    class="w-full"
                    value="{{ old('amount') }}"
                />
            </div>
        </section>

        <section class="flex justify-between">
            <x-ui.button type="reset" variant="secondary">Reset</x-ui.button>
            <div class="flex items-center gap-3">
                <x-ui.button onclick="window.history.back()" type="button" variant="outline">Kembali</x-ui.button>
                <x-ui.button type="submit" variant="primary">Buat Kantong</x-ui.button>
            </div>
        </section>
    </form>

</x-layout.workspace>

