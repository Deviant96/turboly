<div class="form-group">
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" id="{{ $id }}"
        name="{{ $name }}" value="{{ old($name, $value) }}" placeholder="" {{ $attributes }}>
    <label for="{{ $id }}" class="placeholder">{{ $label }}</label>
    @if ($icon)
        <span class="icon">
            <i class="{{ $icon }}"></i>
        </span>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
