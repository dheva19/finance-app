@php
    $breadcrumps = [
        [
            'label' => 'Kategori',
            'url' => route('categories.index')
        ],
        [
            'label' => 'Edit Kategori',
            'url' => route('categories.edit', $category->id)
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Edit Kategori">
    <x-ui.card class="w-full h-fit">
        <h3 class="font-semibold text-xl">Edit Kategori "{{ $category->name }}"</h3>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="w-full">
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
                        value="{{ $category->name }}"
                    />
                </div>

                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="type" required>Tipe</x-ui.label>
                    <x-ui.select id="type" name="type" class="w-full" disabled>
                        <x-ui.option value="income" selectedValue="{{ $category->type }}">Income (Pemasukan)</x-ui.option>
                        <x-ui.option value="expense" selectedValue="{{ $category->type }}">Expense (Pengeluaran)</x-ui.option>
                        <x-ui.option value="transfer" selectedValue="{{ $category->type }}">Transfer</x-ui.option>
                    </x-ui.select>
                </div>

            </section>

            <section class="flex justify-between">
                <x-ui.button type="reset" variant="secondary">Reset</x-ui.button>
                <div class="flex items-center gap-3">
                    <x-ui.button onclick="window.location.href='{{ route('categories.index') }}'" type="button" variant="outline">Kembali</x-ui.button>
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                </div>
            </section>
        </form>
    </x-ui.card>
</x-layout.workspace>

