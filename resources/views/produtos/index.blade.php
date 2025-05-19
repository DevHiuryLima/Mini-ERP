@extends('layouts.app')
@section('title', 'Todos Produtos')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Produtos</h1>
    <a href="{{ route('produtos.create') }}" class="btn btn-success d-flex align-items-center">Novo Produto</a>
</div>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Nome</th>
        <th>Preço</th>
        <th>Variações / Estoque</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach($produtos as $produto)
        <tr>
            <td>{{ $produto->nome }}</td>
            <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
            <td>
                @foreach($produto->estoques as $e)
                    <span class="badge bg-secondary">{{ $e->variacao ?? '' }}: {{ $e->quantidade }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</button>
                </form>

                <button type="button" class="btn btn-sm btn-success btn-buy" data-bs-toggle="modal" data-bs-target="#buyModal" data-id="{{ $produto->id }}"
                            data-variacoes='@json($produto->estoques->pluck("variacao")->filter()->unique()->values())'
                >
                    Comprar
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center my-4">
    {{ $produtos->links('pagination::bootstrap-5') }}
</div>


<div class="modal fade" id="buyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="buyForm" action="{{ route('carrinho.adicionar') }}" method="POST">
            @csrf
            <input type="hidden" name="produto_id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comprar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalVariacao" class="form-label">Variação</label>
                        <select name="variacao" id="modalVariacao" class="form-select form-select-sm">
                            <option value="">-- Selecione --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modalQuantidade" class="form-label">Quantidade</label>
                        <input
                            type="number"
                            name="quantidade"
                            id="modalQuantidade"
                            min="1"
                            value="1"
                            class="form-control form-control-sm"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Adicionar ao carrinho</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#buyModal').on('show.bs.modal', function(event) {
        const button                 = $(event.relatedTarget);
        const $modalVariacaoSelect   = $('#modalVariacao');
        const $variacaoContainer     = $modalVariacaoSelect.closest('.mb-3');
        const $quantidade            = $('#modalQuantidade');
        const $form                  = $('#buyForm');

        let variacoes = button.data('variacoes') ?? [];
        console.log(variacoes);
        let produtoId    = button.data('id');

        $form.find('input[name="produto_id"]').val(produtoId);

        if (typeof variacoes === 'string') {
            try {
                variacoes = JSON.parse(variacoes);
            } catch (e) {
                variacoes = variacoes ? variacoes.split(',') : [];
            }
        }

        $modalVariacaoSelect.empty().append('<option value="">-- Selecione --</option>');

        if (variacoes.length > 0) {
            variacoes.forEach(variacao => {
                $modalVariacaoSelect.append(`<option value="${variacao}">${variacao}</option>`);
            });
            $variacaoContainer.show();
        } else { $variacaoContainer.hide(); }

        $quantidade.val(1);
    });

    $('#buyForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const url = $form.attr('action');
        let data = $form.serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                $('#buyModal').modal('hide');
                window.alert('Produto adicionado com sucesso!', response);
            },
            error: function(xhr) {
                window.alert('Não foi possível adicionar o produto. Verifique os dados e tente novamente.');
            }
        });
    });
});
</script>
@endpush
@endsection
