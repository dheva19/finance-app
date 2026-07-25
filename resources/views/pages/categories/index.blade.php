@php
    $breadcrumps = [
        [
            'label' => 'Kategori',
            'url' => route('categories.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Transaksi">
    <section class="grid grid-cols-12 gap-7">
        <x-ui.card class="col-span-12 md:col-span-4 h-fit">
            <h3 class="font-semibold text-xl">Tambah Kategori</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="w-full">
            @csrf
            <section class="w-full grid grid-cols-12 gap-3 my-5">
                <div class="mb-3 col-span-12">
                    <x-ui.label for="name" required>Nama</x-ui.label>
                    <x-ui.input
                        type="text"
                        id="name"
                        name="name"
                        required
                        autocomplete="name"
                        placeholder="Kebutuhan"
                        class="w-full"
                        value="{{ old('name') }}"
                    />
                </div>

                <div class="mb-3 col-span-12">
                    <x-ui.label for="type" required>Tipe</x-ui.label>
                    <x-ui.select id="type" name="type" class="w-full">
                        <x-ui.option disabled selected>--Pilih Tipe--</x-ui.option>
                        <x-ui.option value="income">Income (Pemasukan)</x-ui.option>
                        <x-ui.option value="expense">Expense (Pengeluaran)</x-ui.option>
                        <x-ui.option value="transfer">Transfer</x-ui.option>
                    </x-ui.select>
                </div>

            </section>

            <section class="flex flex-col gap-3">
                <x-ui.button type="submit" variant="primary" class="w-full">Kirim</x-ui.button>
                <x-ui.button type="reset" variant="secondary" class="w-full">Reset</x-ui.button>
            </section>
        </form>
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-8 h-fit">
            <h3 class="font-semibold text-xl">List Kategori</h3>
            <form class="flex flex-wrap gap-2 my-7 items-center">
                <x-ui.input
                    name="search"
                    type="search"
                    placeholder="Cari ..."
                    value="{{ request()->search }}"
                />
                <x-ui.select name="type">
                    <x-ui.option value="" selectedValue="{{ request()->type }}">Semua Tipe</x-ui.option>
                    <x-ui.option value="income" selectedValue="{{ request()->type }}">Income</x-ui.option>
                    <x-ui.option value="expense" selectedValue="{{ request()->type }}">Expense</x-ui.option>
                    <x-ui.option value="transfer" selectedValue="{{ request()->type }}">Transfer</x-ui.option>
                </x-ui.select>
                <x-ui.button type="submit">Terapkan</x-ui.button>
                <x-ui.button type="button" variant="secondary" onclick="window.location.href='{{ route('categories.index') }}'">Reset</x-ui.button>
            </form>

            <section class="max-w-screen overflow-auto">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="text-white bg-blue-500">
                            <th class="px-3 py-2 border border-slate-200">ID</th>
                            <th class="px-3 py-2 border border-slate-200">Nama</th>
                            <th class="px-3 py-2 border border-slate-200">Tipe</th>
                            <th class="px-3 py-2 border border-slate-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $item)
                            <tr class="hover:bg-slate-100">
                                <td class="px-3 py-2 border border-slate-200">{{ $item->id }}</td>
                                <td class="px-3 py-2 border border-slate-200">{{ $item->name }}</td>
                                <td class="px-3 py-2 border border-slate-200 text-center"><span class="{{ $item->getTypeStyle() }}">{{ $item->type }}</span></td>
                                <td class="px-3 py-2 border border-slate-200 text-center">
                                    <x-ui.button onclick="window.location.href='{{ route('categories.edit', $item->id) }}'"  class="text-xs px-2 py-1" variant="primary">Edit</x-ui.button>
                                    <x-ui.button onclick="handleDelete('{{ $item->id }}', '{{ $item->name }}')" class="text-xs px-2 py-1" variant="destructive">Hapus</x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                        @if (count($categories) < 1)
                            <tr>
                                <td colspan="4" class="px-3 py-10 border border-slate-200 text-center"><p>Tidak ada data yang ditemukan!</p></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="my-3">
                    {{ $categories->links() }}
                </div>

            </section>
        </x-ui.card>
    </section>
</x-layout.workspace>
<x-note-modal/>

<form id="delete-form" method="POST">
    @method('DELETE')
    @csrf
</form>

<script>
    function handleDelete(id, name){
        Swal.fire({
            title: "Konfirmasi Aksi!",
            text: `Apakah anda yakin ingin menghapus kategori "${name}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
            }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#delete-form').setAttribute('action', `/categories/${id}`);
                document.querySelector('#delete-form').submit();
            }
        });
    }
</script>

