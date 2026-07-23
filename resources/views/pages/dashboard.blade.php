@php
    $breadcrumps = [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps">
    <p>Ini Dashboard</p>
</x-layout.workspace>

