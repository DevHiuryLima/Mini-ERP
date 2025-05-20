@extends('layouts.app')
@section('title', 'Criar Cupom')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>Criar Cupom</h1>
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

<form action="{{ route('cupons.store') }}" method="POST">
    @csrf
    @include('cupons._form')
    <div class="m-0 p-0 d-flex align-items-center">
        <button type="submit" class="btn btn-success m-1 mb-4">Salvar Cupom</button>
    </div>
</form>
@endsection
