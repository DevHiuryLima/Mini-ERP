<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('produtos.index');
});

Route::resource('produtos', ProdutoController::class);
Route::delete('estoques/{estoque}', [EstoqueController::class, 'destroy'])->name('estoques.destroy');
Route::resource('cupons', CupomController::class)->parameters(['cupons' => 'cupom']);
Route::post('cupons/aplicar', [CupomController::class, 'aplicarCupom'])->name('cupom.aplicar');

Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::delete('/carrinho/remover', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::post('carrinho/frete/calcular', [CarrinhoController::class, 'calcularFrete'])->name('carrinho.frete');


Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::post('/pedidos/finalizar', [PedidoController::class, 'finalizar'])->name('pedidos.finalizar');
