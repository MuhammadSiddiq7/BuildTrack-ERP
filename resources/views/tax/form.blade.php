{{-- resources/views/tax/partials/_form.blade.php --}}
@php
    $s = $slab ?? null;
@endphp

<div class="col-md-4">
    <label class="form-label">Min Income (Inclusive)</label>
    <input type="number" step="0.01" name="min_income" class="form-control"
           value="{{ old('min_income', $s->min_income ?? '') }}" required>
    @error('min_income') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="col-md-4">
    <label class="form-label">Max Income (Inclusive) — leave blank for “Above”</label>
    <input type="number" step="0.01" name="max_income" class="form-control"
           value="{{ old('max_income', $s->max_income ?? '') }}">
    @error('max_income') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="col-md-2">
    <label class="form-label">Fixed Tax (Rs.)</label>
    <input type="number" step="0.01" name="fixed_tax" class="form-control"
           value="{{ old('fixed_tax', $s->fixed_tax ?? 0) }}" required>
    @error('fixed_tax') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="col-md-2">
    <label class="form-label">Percent (%)</label>
    <input type="number" step="0.01" name="percentage" class="form-control"
           value="{{ old('percentage', $s->percentage ?? 0) }}" required>
    @error('percentage') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

{{-- <div class="col-12">
    <div class="alert alert-info mb-0 p-2">
        Tax formula: <strong>Fixed Tax + (Percentage × (Income − Min Income))</strong>.
    </div>
</div> --}}
