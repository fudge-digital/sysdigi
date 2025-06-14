@props([
    'name',
    'type' => 'text', // text, select, number, etc
    'value' => '',
    'label' => null,
    'options' => [], // jika select
    'canEdit' => false,
    'readonly' => false,
])

@php
    $fieldLabel = $label ?? ucfirst(str_replace('_', ' ', $name));
@endphp

<div class="form-control col-span-2 md:col-span-1 w-full">
    <label class="label text-black text-bold mb-2 w-full">{{ $fieldLabel }}</label>

    @if ($canEdit)
        @if ($type === 'select')
            <select name="{{ $name }}" class="select select-neutral w-full" {{ $readonly ? 'disabled' : '' }}>
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected($optionValue == old($name, $value))>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
            @if ($readonly)
                <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}">
            @endif
        @else
            <input type="{{ $type }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                class="input input-bordered w-full"
                {{ $readonly ? 'readonly' : '' }}>
        @endif
    @else
        {{-- Tidak bisa diedit: tetap dikirim dengan input hidden --}}
        <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}">
        <div class="py-2 px-4 bg-gray-100 rounded">{{ old($name, $value) }}</div>
    @endif
</div>
@if ($errors->has($name))
    <span class="text-red-500 text-sm mt-1">{{ $errors->first($name) }}</span>