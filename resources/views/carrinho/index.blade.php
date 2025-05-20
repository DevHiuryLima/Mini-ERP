@extends('layouts.app')
@section('title', 'Carrinho de Compras')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Carrinho de Compras</h1>
</div>

@if(empty($carrinho['produtos']))
<p>Seu carrinho está vazio.</p>
<a href="{{ route('produtos.index') }}" class="btn btn-primary">Continuar Comprando</a>

@else
<div class="row mb-4">
    <div class="col-lg-8">
        <table class="table table-bordered table-striped">
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
            @foreach($carrinho['produtos'] as $item)
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

            @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h5>Resumo do Pedido</h5></div>
            <div class="card-body">
                <p>
                    <strong>Subtotal:</strong> R$
                    @php $totalCarrinho = 0; @endphp
                    @foreach($carrinho['produtos'] as $item)
                        @php $totalCarrinho += $item['subtotal']; @endphp
                    @endforeach
                    {{ number_format($totalCarrinho, 2, ',', '.') }}
                </p>
                <p><strong>Frete:</strong> <span id="valorFrete">R$ {{ $carrinho['frete'] }}</span></p>
                <p><strong>Cupom:</strong> <span id="descontoCupom" class="text-success">-</span></p>
                <hr>
                <p><strong>Total:</strong> <span id="valorTotal">R$ {{ $carrinho['total'] }}</span></p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Calcular Frete</h5></div>
            <div class="card-body">
                <form id="formFrete" class="d-flex g-2" action="{{ route('carrinho.frete') }}" method="POST">
                    @csrf
                    <input type="text" name="cep" id="cep" class="form-control cep me-2" placeholder="Digite seu CEP" value="{{ $carrinho['endereco']['cep'] }}" required>
                    <button class="btn btn-outline-primary">Calcular</button>
                </form>

                <div id="enderecoResultado" class="mt-3 {{ empty($carrinho['endereco'] ?? null) ? 'd-none' : '' }}">
                    <p class="mb-1"><strong>Estado:</strong> <span id="uf">{{ $carrinho['endereco']['uf'] }}</span></p>
                    <p class="mb-1"><strong>Ciade:</strong> <span id="localidade">{{ $carrinho['endereco']['localidade'] }}</span></p>
                    <p class="mb-1"><strong>Bairro:</strong> <span id="bairro">{{ $carrinho['endereco']['bairro'] }}</span></p>
                    <p class="mb-1"><strong>Logradouro:</strong> <span id="logradouro">{{ $carrinho['endereco']['logradouro'] }}</span></p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Aplicar cupom</h5></div>
            <div class="card-body">
                <form id="formCupom" class="d-flex g-2" action="" method="POST">
                    @csrf
                    <input type="text" name="cep" id="cep" class="form-control cep me-2" placeholder="" required>
                    <button class="btn btn-outline-primary">Aplicar</button>
                </form>
            </div>
        </div>

        <a href="#" class="btn btn-success w-100">Finalizar Pedido</a>
    </div>
</div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    $('.cep').mask('00000-000');

    $('#formFrete').on('submit', function(e) {
        e.preventDefault();
        let json = {
            'cep': $(this).find('#cep').val(),
            '_token': $(this).find('input[name="_token"]').val(),
        }

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: json,
            success: function(response) {
                $('#valorFrete').text(`R$ ${response.frete}`);
                $('#valorTotal').text(`R$ ${response.total}`);
                $('#valorTotal').text(`R$ ${response.total}`);
                $('#logradouro').text(response.endereco.logradouro);
                $('#bairro').text(response.endereco.bairro);
                $('#localidade').text(response.endereco.localidade);
                $('#uf').text(response.endereco.uf);
                $('#enderecoResultado').removeClass('d-none');
            },
            error: function(xhr) {
                window.alert(xhr.responseJSON.errors);
            }
        });
    });
});
</script>
@endpush
@endsection
