<div {{ $attributes->except('class') }} class="{{ $attributes->get('class') }} p-5 rounded-md border-2 border-slate-200 bg-white shadow-md">
    {{ $slot }}
</div>
