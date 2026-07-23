@props(['required' => null])

<label {{ $attributes->except('class') }} class="{{ $attributes->get('class') }} text-md
    @error($attributes->get('for'))
        text-red-500
    @enderror">{{ $slot }}<span class="text-sm">{{ isset($required) ? '*' : '' }}</span></label><br>
