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
                <span class="badge bg-secondary">{{ $e->variacao ?? '—' }}: {{ $e->quantidade }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center my-4">
    {{ $produtos->links('pagination::bootstrap-5') }}
</div>
@endsection
