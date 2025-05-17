@extends('layouts.app')
@section('title', 'Editar Produto')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Cadastrar Novo Produto</h1>
    <a href="{{ route('produtos.index') }}" class="btn btn-primary d-flex align-items-center">Voltar</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
    @foreach($errors->all() as $msg)
        <li>{{ $msg }}</li>
    @endforeach
    </ul>
</div>
@endif

<form action="{{ route('produtos.update', $produto) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="{{ $produto->nome }}" required>
    </div>
    <div class="mb-3">
        <label for="preco" class="form-label">Preço</label>
        <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ $produto->preco }}" required>
    </div>
    
    <h2>Estoque</h2>
    <div id="estoques-container">
    @foreach($produto->estoques as $i => $item)
        @include('produtos._estoque_fields', [
        'index' => $i,
        'item'  => ['variacao' => $item->variacao, 'quantidade' => $item->quantidade]
        ])
    @endforeach
    </div>
    
    <div class="m-0 p-0 d-flex align-items-center">
        <button type="button" id="add-estoque" class="btn btn-outline-secondary m-1 mb-4">+ Adicionar variação</button>
        <button type="submit" class="btn btn-success m-1 mb-4">Salvar Produto</button>
    </div>
</form>

@push('scripts')
<script>
$(function() {
    const $container = $('#estoques-container');
    let index = $container.children().length;

    // pré-carrega o template já sem quebras de linha
    const template = `{!! str_replace(["\r", "\n"], '', 
        view('produtos._estoque_fields', ['index' => '__INDEX__', 'item' => []])->render()
    ) !!}`;

    // adicionar nova variação
    $('#add-estoque').on('click', function() {
        const html = template.replace(/__INDEX__/g, index);
        $container.append(html);
        index++;
    });

    // remover variação existente (delegation)
    $container.on('click', '.remove-estoque', function() {
        $(this).closest('.estoque-item').remove();
    });
});
</script>
@endpush
@endsection