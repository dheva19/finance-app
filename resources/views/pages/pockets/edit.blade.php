@php
    $breadcrumps = [
        [
            'label' => 'Kantong',
            'url' => route('pockets.index')
        ],
        [
            'label' => 'Edit Kantong',
            'url' => route('pockets.edit', $pocket->id)
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Edit Kantong">
    <x-ui.card class="w-full h-fit">
        <h3 class="font-semibold text-xl">Edit Kantong "{{ $pocket->name }}"</h3>

        <form action="{{ route('pockets.update', $pocket->id) }}" method="POST" class="w-full">
            @method('PATCH')
            @csrf
            <section class="w-full grid grid-cols-12 gap-3 my-5">
                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="name" required>Nama</x-ui.label>
                    <x-ui.input
                        type="text"
                        id="name"
                        name="name"
                        required
                        autocomplete="name"
                        placeholder="Tabungan Dana Darurat"
                        class="w-full"
                        value="{{ $pocket->name }}"
                    />
                </div>
                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="amount">Saldo Awal (Rp)</x-ui.label>
                    <x-ui.input
                        type="number"
                        id="amount"
                        name="amount"
                        required
                        placeholder="0"
                        class="w-full"
                        disabled
                        value="{{ $pocket->amount }}"
                    />
                </div>
            </section>

            <section class="flex justify-between">
                <x-ui.button type="reset" variant="secondary">Reset</x-ui.button>
                <div class="flex items-center gap-3">
                    <x-ui.button onclick="window.location.href='{{ route('pockets.index') }}'" type="button" variant="outline">Kembali</x-ui.button>
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                </div>
            </section>
        </form>
    </x-ui.card>
</x-layout.workspace>

