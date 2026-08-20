@props(['name', 'label', 'options' => [], 'value' => null, 'col' => 'col-lg-4 col-md-6 col-sm-12'])

<div class="{{ $col }}">
    <div class="qform-group">
        <label for="{{ $name }}">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="qtext-danger">*</span>
            @endif
        </label>

        <select name="{{ $name }}" id="{{ $name }}"
            class="qform expended {{ $errors->has($name) ? 'is-invalid' : '' }}" {{ $attributes }}>
            <option value=""></option>

            @foreach ($options as $key => $option)
                @php
                    $valueKey = is_object($option) ? $option->id : $key;
                    $labelValue = is_object($option) ? $option->name : $option;
                @endphp

                <option value="{{ $valueKey }}" {{ old($name, $value) == $valueKey ? 'selected' : '' }}>
                    {{ $labelValue }}
                </option>
            @endforeach
        </select>

        @error($name)
            <div class="qtext-danger qcaption">{{ $message }}</div>
        @enderror
    </div>
</div>
