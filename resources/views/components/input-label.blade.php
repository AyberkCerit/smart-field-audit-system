@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-text']) }}>
    {{ $value ?? $slot }}
</label>
