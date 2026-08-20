@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'col' => 'col-lg-4 col-md-6 col-sm-12',
])

<div class="{{ $col }}">
    <div class="qform-group">
        @if ($label)
            <label for="{{ $name }}">
                {{ $label }}
                @if ($attributes->has('required'))
                    <span class="qtext-danger">*</span>
                @endif
            </label>
        @endif

        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            class="qform {{ $errors->has($name) ? 'is-invalid' : '' }}" value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}" {{ $attributes }}>

        @error($name)
            <div class="qtext-danger qcaption">{{ $message }}</div>
        @enderror
    </div>
</div>
