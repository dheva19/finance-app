@props(['selectedValue' => null])
<option @if ($attributes->get('value') == $selectedValue)
    selected
@endif class="{{ $attributes->get('class') }} disabled:bg-slate-200" {{ $attributes->except('class') }}>{{ $slot }}</option>
