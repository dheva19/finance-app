@php
    $breadcrumps = [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Dashboard">
    <x-ui.card class="w-full h-fit">
        <p>Ini Dashboard</p>
    </x-ui.card>
</x-layout.workspace>

