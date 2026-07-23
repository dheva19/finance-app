<input
    {{ $attributes->except(['class']) }}
    class="{{ $attributes->get('class') }} px-3 py-2 border border-slate-300 outline-blue-500 rounded-md"
    @error($attributes->get('name'))
        style="border-color: #ef4444!important;"
    @enderror
/>
@error($attributes->get('name'))
    <p class="text-sm text-red-500">{{ $message }}</p>
@enderror
