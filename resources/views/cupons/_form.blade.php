<div class="mb-3">
    <label class="form-label">Código</label>
    <input name="codigo" class="form-control" value="{{ old('codigo', $cupom->codigo ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Tipo de Desconto</label>
    <select name="tipo_desconto" class="form-select" required>
        <option value="">-- selecione --</option>
        @foreach(['percentual'=>'Percentual','fixo'=>'Fixo'] as $key=>$label)
            <option value="{{ $key }}" {{ old('tipo_desconto', $cupom->tipo_desconto ?? '') === $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Valor</label>
    <input type="number" step="0.01" name="valor" class="form-control" value="{{ old('valor', $cupom->valor ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Mínimo Subtotal</label>
    <input type="number" step="0.01" name="minimo_subtotal" class="form-control" value="{{ old('minimo_subtotal', $cupom->minimo_subtotal ?? '0') }}">
</div>
<div class="mb-3">
    <label class="form-label">Validade</label>
    <input type="date" name="validade" class="form-control" value="{{ old('validade', isset($cupom) ? $cupom->validade->format('Y-m-d') : '') }}" required>
</div>
<div class="form-check mb-3">
    <label class="form-check-label" for="ativo">Está Ativo?</label>
    <input type="checkbox" name="ativo" id="ativo" value="1" class="form-check-input" {{ old('ativo', $cupom->ativo ?? true) ? 'checked' : '' }}>
</div>
