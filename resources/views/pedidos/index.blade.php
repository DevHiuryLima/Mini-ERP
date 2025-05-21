@extends('layouts.app')
@section('title', 'Todos Pedidos')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Pedidos</h1>
</div>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Subtotal</th>
        <th>Cupom de desconto</th>
        <th>Frete</th>
        <th>Total</th>
        <th>Endereço</th>
        <th>Itens</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pedidos as $pedido)
        <tr>
            <td></td>
            <td>{{ $pedido->subtotal }}</td>
            <td>
                @if($pedido->cupom)
                    <span><b>Código: </b>{{ $pedido->cupom->codigo }}</span><br>
                    <span><b>Valor de desconto: </b>{{ $pedido->cupom->valor }}</span><br>
                @endif
            </td>
            <td>{{ $pedido->frete }}</td>
            <td>{{ $pedido->total }}</td>
            <td>
                <span><b>Estado: </b>{{ $pedido->uf }}</span><br>
                <span><b>Cidade: </b>{{ $pedido->cidade }}</span><br>
                <span><b>Logradouro: </b>{{ $pedido->logradouro }}</span><br>
                <span><b>Bairro: </b>{{ $pedido->bairro }}</span><br>
                <span><b>CEP: </b>{{ $pedido->cep }}</span>
            </td>
            <td>
                @if($pedido->itens->isNotEmpty())
                    @foreach($pedido->itens as $item)
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">{{ $item->produto->nome }} ({{ $item->variacao ?? 'Padrão' }})</h6>
                                <p class="card-text m-0 p-0">Quantidade: {{ $item->quantidade }}</p>
                                <p class="card-text m-0 p-0">Preço Unitário: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</p>
                                <p class="card-text m-0 p-0">Subtotal: R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    Nenhum item neste pedido.
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center my-4">
    {{ $pedidos->links('pagination::bootstrap-5') }}
</div>
@endsection
