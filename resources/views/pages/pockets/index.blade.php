@php
    $breadcrumps = [
        [
            'label' => 'Kantong',
            'url' => route('pockets.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Kantong">
    <x-ui.card class="w-full h-fit">
        <h3 class="font-semibold text-xl">List Kantong Uang</h3>

        <div class="flex flex-col md:flex-row justify-between gap-3 my-7">
            <form>
                <x-ui.input
                    name="search"
                    type="search"
                    placeholder="Cari Kantong..."
                    value="{{ request()->search }}"
                />
                <x-ui.button type="submit">Terapkan</x-ui.button>
                <x-ui.button type="button" variant="secondary" onclick="window.location.href='{{ route('pockets.index') }}'">Reset</x-ui.button>
            </form>
            <x-ui.button onclick="window.location.href='{{ route('pockets.create') }}'" >+ Kantong Baru</x-ui.button>
        </div>

        <section class="grid grid-cols-12 gap-3 py-5">
            @foreach ($pockets as $item)
            <x-ui.card class="col-span-12 md:col-span-3 hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <p class="text-md">{{ $item->name }} </p>
                    @if ($item->is_primary)
                        <span class="text-xs text-white bg-blue-500 py-0.5 px-2 rounded-xl" >Utama</span>
                    @endif
                </div>
                <p class="font-semibold text-lg my-1">Rp{{ number_format($item->amount, 0, ',', '.') }}</p>
                <p class="text-xs text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</p>

                <div class="w-full flex justify-end gap-2">
                    <a href="{{ route('pockets.edit', $item->id) }}">Edit</a>
                    <button onclick="handleDelete('{{ $item->id }}', '{{ $item->name }}')" class="cursor-pointer">Delete</button>
                </div>
            </x-ui.card>
            @endforeach

            @if (count($pockets) < 1)
                <div class="col-span-12">
                    <p class="text-center text-muted">Tidak ada data yang ditemukan!</p>
                </div>
            @endif
        </section>
    </x-ui.card>
</x-layout.workspace>

<form id="delete-form" method="POST">
    @method('DELETE')
    @csrf
</form>

<script>
    function handleDelete(id, name){
        Swal.fire({
            title: "Konfirmasi Aksi!",
            text: `Apakah anda yakin ingin menghapus kantong "${name}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
            }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('#delete-form').setAttribute('action', `/pockets/${id}`);
                document.querySelector('#delete-form').submit();
            }
        });
    }
</script>

