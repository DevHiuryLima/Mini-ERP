@extends('layouts.app')
@section('title', 'Editar Cupom')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Editar Cupom</h1>
    <a href="{{ route('cupons.index') }}" class="btn btn-primary d-flex align-items-center">Voltar</a>
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

<form action="{{ route('cupons.update', $cupom) }}" method="POST">
    @csrf
    @method('PUT')
    @include('cupons._form', ['cupom'=>$cupom])
    <div class="m-0 p-0 d-flex align-items-center">
        <button type="submit" class="btn btn-success m-1 mb-4">Atualizar Cupom</button>
    </div>
</form>
@endsection
