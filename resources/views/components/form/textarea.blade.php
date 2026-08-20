@props(['name', 'label', 'value' => null, 'placeholder' => '', 'rows' => 3, 'col' => 'col-lg-4 col-md-6 col-sm-12'])

<div class="{{ $col }}">
    <div class="qform-group">
        <label for="{{ $name }}">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="qtext-danger">*</span>
            @endif
        </label>

        <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
            class="qform {{ $errors->has($name) ? 'is-invalid' : '' }}" placeholder="{{ $placeholder }}" {{ $attributes }}>{{ old($name, $value) }}</textarea>

        @error($name)
            <div class="qtext-danger qcaption">{{ $message }}</div>
        @enderror
    </div>
</div>
