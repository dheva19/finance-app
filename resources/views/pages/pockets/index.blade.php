@php
    $breadcrumps = [
        [
            'label' => 'Kantong',
            'url' => route('pockets.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps">
    <h3 class="font-semibold text-xl">List Kantong Uang</h3>


    <div class="flex justify-between items-center my-7">
        <div>
            <x-ui.input
                type="search"
                placeholder="Cari Kantong..."
            />
            <x-ui.button>Cari</x-ui.button>
        </div>
        <x-ui.button onclick="window.location.href='{{ route('pockets.create') }}'" >+ Kantong Baru</x-ui.button>
    </div>

    @foreach ($pockets as $item)

    <section class="grid grid-cols-12 gap-3">
        <x-ui.card class="col-span-12 md:col-span-3">
            <div class="flex justify-between items-center">
                <p class="text-md">{{ $item->name }} </p>
                @if ($item->is_primary)
                    <span class="text-xs text-white bg-blue-500 py-0.5 px-2 rounded-xl" >Utama</span>
                @endif
            </div>
            <p class="font-semibold text-lg my-1">Rp{{ number_format($item->amount, 2, ',', '.') }}</p>
            <p class="text-xs text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</p>

            <div class="w-full flex justify-end">
            </div>
        </x-ui.card>

    </section>
    @endforeach
</x-layout.workspace>

