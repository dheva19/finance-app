@props([
    'variant' => 'primary',
])

@php
    $variantClass = [
        'primary' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'secondary' => 'bg-gray-200 hover:bg-gray-300',
        'outline' => 'bg-none border border-gray-400 hover:bg-gray-100',
        'ghost' => 'bg-none',
        'destructive' => 'bg-red-500 hover:bg-red-600 text-white'
    ];
@endphp

<button {{ $attributes->except('class') }} class="{{ $attributes->get('class') }} px-3 py-2 rounded-md {{ $variantClass[$variant] }} cursor-pointer active:scale-105">
    {{ $slot }}
</button>
