@extends('layouts.app')
@section('title', 'Todos Cupons')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Cupons</h1>
    <a href="{{ route('cupons.create') }}" class="btn btn-success d-flex align-items-center">Novo Cupom</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Código</th>
        <th>Tipo</th>
        <th>Valor</th>
        <th>Valor mínimo para ser usado</th>
        <th>Validade</th>
        <th>Ativo</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cupons as $cupom)
        <tr>
            <td>{{ $cupom->codigo }}</td>
            <td>{{ ucfirst($cupom->tipo_desconto) }}</td>
            <td>
                @if($cupom->tipo_desconto === 'percentual')
                    {{ $cupom->valor }}%
                @else
                    R$ {{ number_format($cupom->valor,2,',','.') }}
                @endif
            </td>
            <td>R$ {{ number_format($cupom->minimo_subtotal, 2, ',', '.') }}</td>
            <td>{{ $cupom->validade->format('d/m/Y') }}</td>
            <td>
            @if($cupom->ativo)
                <span class="badge bg-success">Ativo</span>
            @else
                <span class="badge bg-secondary">Inativo</span>
            @endif
            </td>

            <td>
                <a href="{{ route('cupons.edit', $cupom) }}" class="btn btn-sm btn-primary">Editar</a>
                <form action="{{ route('cupons.destroy', $cupom) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center my-4">
    {{ $cupons->links('pagination::bootstrap-5') }}
</div>
@endsection
