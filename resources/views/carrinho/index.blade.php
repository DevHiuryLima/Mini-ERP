@extends('layouts.app')
@section('title', 'Carrinho de Compras')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Carrinho de Compras</h1>
</div>

@if(empty($carrinho))
<p>Seu carrinho está vazio.</p>
<a href="{{ route('produtos.index') }}" class="btn btn-primary">Continuar Comprando</a>
@else
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Produto</th>
        <th>Variação</th>
        <th>Preço Unitário</th>
        <th>Quantidade</th>
        <th>Subtotal</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @php $totalCarrinho = 0; @endphp
    @foreach($carrinho as $item)
    <tr>
        <td>{{ $item['nome'] }}</td>
        <td>{{ $item['variacao'] ?? 'Padrão' }}</td>
        <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
        <td>{{ $item['quantidade'] }}</td>
        <td>R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
        <td>
            <form action="{{ route('carrinho.remover') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <input type="hidden" name="produto_id" value="{{ $item['produto_id'] }}">
                <input type="hidden" name="variacao" value="{{ $item['variacao'] }}">
                <button type="submit" class="btn btn-sm btn-danger">Remover</button>
            </form>
        </td>
    </tr>
    @php $totalCarrinho += $item['subtotal']; @endphp
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4"></td>
        <th>Total: R$ {{ number_format($totalCarrinho, 2, ',', '.') }}</th>
        <td></td>
    </tr>
    </tfoot>
</table>
<a href="{{ route('produtos.index') }}" class="btn btn-primary">Continuar Comprando</a>
<a href="#" class="btn btn-success">Finalizar Pedido</a>
@endif
@endsection
