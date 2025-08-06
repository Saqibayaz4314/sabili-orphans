@props(['name', 'label', 'options' => [], 'selected' => null, 'required' => false])

    <label class="mb-2 fw-bold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="d-flex align-items-center gap-5">
        @foreach($options as $display => $value)
            <div class="d-flex gap-1">
                <input
                    class="radio-input p-0"
                    name="{{ $name }}"
                    type="radio"
                    id="{{ $name }}-{{ $loop->index }}"
                    value="{{ $value }}"
                    @checked(old($name, $selected) == $value)
                />
                <label class="form-check-label"
                       for="{{ $name }}-{{ $loop->index }}"
                       style="color: rgba(36, 36, 36, 0.6)">
                    {{ $display }}
                </label>
            </div>
        @endforeach

        @error($name)
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
