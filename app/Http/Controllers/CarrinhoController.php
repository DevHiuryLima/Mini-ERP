<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = Session::get('carrinho', []);
        return view('carrinho.index', compact('carrinho'));
    }

    public function adicionar(Request $request)
    {
        $request->validate([
            'produto_id'  => ['required', 'integer', 'exists:produtos,id'],
            'quantidade'  => ['required', 'integer', 'min:1'],
            'variacao'    => ['nullable', 'string'],
        ]);

        $produtoId = $request->input('produto_id');
        $quantidade = $request->input('quantidade', 1);
        $variacao = $request->input('variacao');

        $produto = Produto::findOrFail($produtoId);
        $estoque = Estoque::where('produto_id', $produtoId)->where('variacao', $variacao)->first();

        if (!$estoque || $estoque->quantidade < $quantidade) {
            return response()->json(['errors' => ['estoque' => 'Estoque insuficiente para a quantidade selecionada.']], 422);
        }

        $carrinho = Session::get('carrinho', []);
        $itemKey = $produtoId . '-' . $variacao;

        if (isset($carrinho[$itemKey])) {
            $carrinho[$itemKey]['quantidade'] += $quantidade;
        } else {
            $carrinho[$itemKey] = [
                'produto_id' => $produtoId,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'variacao' => $variacao,
                'quantidade' => $quantidade,
                'subtotal'   => $produto->preco * $quantidade,
            ];
        }

        Session::put('carrinho', $carrinho);

        return response()->json(['message' => 'Produto adicionado ao carrinho!']);
    }

    public function remover(Request $request)
    {
        $request->validate([
            'produto_id' => ['required', 'integer', 'exists:produtos,id'],
            'variacao' => ['nullable', 'string'],
        ]);

        $produtoId = $request->input('produto_id');
        $variacao = $request->input('variacao');
        $key = $produtoId . '-' . ($variacao ?? '');

        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$key])) {
            unset($carrinho[$key]);
            session()->put('carrinho', $carrinho);
        }

        return redirect()->route('carrinho.index')->with('success', 'Produto removido do carrinho!');
    }
}
