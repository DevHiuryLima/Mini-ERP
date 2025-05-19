<div class="row mb-3 estoque-item">
    <div class="col-md-5">
        <label class="form-label">Variação (opcional)</label>
        <input type="text"
            name="estoques[{{ $index }}][variacao]"
            class="form-control"
            value="{{ old("estoques.$index.variacao", $item['variacao'] ?? '') }}">
    </div>
    <div class="col-md-5">
        <label class="form-label">Quantidade</label>
        <input type="number"
            name="estoques[{{ $index }}][quantidade]"
            class="form-control"
            min="0"
            value="{{ old("estoques.$index.quantidade", $item['quantidade'] ?? 1) }}"
            required>
        </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-outline-danger remove-estoque w-100">×</button>
    </div>
</div>
