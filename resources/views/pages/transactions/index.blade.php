@php
    $breadcrumps = [
        [
            'label' => 'Transaksi',
            'url' => route('transactions.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Transaksi">
    <x-ui.card class="w-full h-fit">
        <div class="flex flex-col md:flex-row justify-between gap-5">
            <h3 class="font-semibold text-xl">List Transaksi</h3>
            <x-ui.button onclick="window.location.href='{{ route('transactions.create') }}'" >+ Tambah</x-ui.button>
        </div>
        <form class="flex flex-wrap gap-2 my-7 items-center">
            <x-ui.input
                name="search"
                type="search"
                placeholder="Cari Nomor Transaksi..."
                value="{{ request()->search }}"
            />
            <x-ui.select name="type">
                <x-ui.option value="" selectedValue="{{ request()->type }}">Semua Tipe</x-ui.option>
                <x-ui.option value="income" selectedValue="{{ request()->type }}">Income</x-ui.option>
                <x-ui.option value="expense" selectedValue="{{ request()->type }}">Expense</x-ui.option>
                <x-ui.option value="transfer" selectedValue="{{ request()->type }}">Transfer</x-ui.option>
            </x-ui.select>
            <x-ui.input
                name="date"
                type="date"
                value="{{ request()->date }}"
            />
            <x-ui.select name="from_pocket">
                <x-ui.option value="">Dari Kantong</x-ui.option>
                @foreach ($pockets as $item)
                    <x-ui.option value="{{ $item->id }}" selectedValue="{{ request()->from_pocket }}">{{ $item->name }}</x-ui.option>
                @endforeach
            </x-ui.select>
            <x-ui.select name="to_pocket">
                <x-ui.option value="">Untuk Kantong</x-ui.option>
                @foreach ($pockets as $item)
                    <x-ui.option value="{{ $item->id }}" selectedValue="{{ request()->to_pocket }}">{{ $item->name }}</x-ui.option>
                @endforeach
            </x-ui.select>
            <x-ui.button type="submit">Terapkan</x-ui.button>
            <x-ui.button type="button" variant="secondary" onclick="window.location.href='{{ route('transactions.index') }}'">Reset</x-ui.button>
            <p>Paginate:</p>
            <x-ui.select name="paginate">
                <x-ui.option value="10" selectedValue="{{ request()->paginate }}">10</x-ui.option>
                <x-ui.option value="15" selectedValue="{{ request()->paginate }}">15</x-ui.option>
                <x-ui.option value="50" selectedValue="{{ request()->paginate }}">50</x-ui.option>
                <x-ui.option value="100" selectedValue="{{ request()->paginate }}">100</x-ui.option>
            </x-ui.select>
        </form>

        <section class="max-w-screen overflow-auto">
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr class="text-white bg-blue-500">
                        <th class="px-3 py-2 border border-slate-200">Nomor</th>
                        <th class="px-3 py-2 border border-slate-200">Tipe</th>
                        <th class="px-3 py-2 border border-slate-200">Tanggal</th>
                        <th class="px-3 py-2 border border-slate-200">Jumlah</th>
                        <th class="px-3 py-2 border border-slate-200">Dari Kantong</th>
                        <th class="px-3 py-2 border border-slate-200">Untuk Kantong</th>
                        <th class="px-3 py-2 border border-slate-200">Catatan</th>
                        <th class="px-3 py-2 border border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $item)
                        <tr class="hover:bg-slate-100">
                            <td class="px-3 py-2 border border-slate-200">{{ $item->transaction_number }}</td>
                            <td class="px-3 py-2 border border-slate-200 text-center"><span class="{{ $item->getTypeStyle() }}">{{ $item->type }}</span></td>
                            <td class="px-3 py-2 border border-slate-200 text-center">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="px-3 py-2 border border-slate-200 font-semibold">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border border-slate-200 text-red-500">{{ $item->fromPocket->name ?? '-' }}</td>
                            <td class="px-3 py-2 border border-slate-200 text-green-500">{{ $item->toPocket->name ?? '-' }}</td>
                            <td class="px-3 py-2 border border-slate-200"><button class="text-blue-500 cursor-pointer hover:underline" onclick="openNoteModal('{{ $item->note ?? '-' }}', '{{ $item->id }}')">Lihat Catatan</button></td>
                            <td class="px-3 py-2 border border-slate-200 text-center">
                                <x-ui.button onclick="handleDelete('{{ $item->id }}', '{{ $item->transaction_number }}')" class="text-xs px-2 py-1" variant="destructive">Hapus</x-ui.button>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($transactions) < 1)
                        <tr>
                            <td colspan="8" class="px-3 py-10 border border-slate-200 text-center"><p>Tidak ada data yang ditemukan!</p></td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="my-3">
                {{ $transactions->links() }}
            </div>

        </section>
    </x-ui.card>
</x-layout.workspace>
<x-note-modal/>

<form id="delete-form" method="POST">
    @method('DELETE')
    @csrf
</form>

<script>
    function handleDelete(id, nomor){
        Swal.fire({
            title: "Konfirmasi Aksi!",
            text: `Apakah anda yakin ingin menghapus transaksi dengan nomor "${nomor}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
            }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#delete-form').setAttribute('action', `/transactions/${id}`);
                document.querySelector('#delete-form').submit();
            }
        });
    }
</script>

