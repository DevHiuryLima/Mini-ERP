<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use App\Services\ViaCepService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = Session::get('carrinho', [
            'produtos' => [],
            'frete'    => null,
            'total'    => null,
            'endereco' => [],
        ]);
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

        $carrinho = Session::get('carrinho.produtos', []);
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

        Session::put('carrinho.produtos', $carrinho);

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

        $carrinho = session()->get('carrinho.produtos', []);

        if (isset($carrinho[$key])) {
            unset($carrinho[$key]);
            session()->put('carrinho.produtos', $carrinho);
        }

        return redirect()->route('carrinho.index')->with('success', 'Produto removido do carrinho!');
    }

    public function calcularFrete(Request $request, ViaCepService $viacep)
    {
        $request->validate([
            'cep' => ['required', 'regex:/^\d{5}-?\d{3}$/'],
        ], [
            'cep.regex' => 'O CEP deve ter 8 dígitos (ex: 12345-678).'
        ]);

        try {
            $endereco = $viacep->consultaViaCep($request->cep);
        } catch (RequestException $e) {
            return response()->json(['errors' => 'Não foi possível contatar o serviço de CEP.'], 503);
        }

        if (! $endereco) {
            return response()->json(['errors' => 'CEP não encontrado.'], 404);
        }

        $cep = preg_replace('/[^0-9]/', '', $request->cep); // limpar o CEP

        $carrinho = Session::get('carrinho', []);

        $subtotal = array_reduce($carrinho['produtos'], function($soma, $item) {
            return $soma + ($item['preco'] * $item['quantidade']);
        }, 0);

        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
            $mensagem = 'Frete de R$ 15,00 para subtotal entre R$ 52,00 e R$ 166,59.';
        } elseif ($subtotal > 200) {
            $frete = 0.00;
            $mensagem = 'Frete grátis para subtotal acima de R$ 200,00!';
        } else {
            $frete = 20.00;
            $mensagem = 'Frete de R$ 20,00 para outros valores.';
        }

        session([
            'carrinho.frete'    => number_format($frete, 2, ',', '.'),
            'carrinho.total'    => number_format($subtotal + $frete, 2, ',', '.'),
            'carrinho.endereco' => [
                'cep'        => $endereco['cep'],
                'uf'        => $endereco['uf'],
                'localidade'=> $endereco['localidade'],
                'bairro'    => $endereco['bairro'],
                'logradouro'=> $endereco['logradouro'],
            ],
        ]);

        return response()->json([
            'frete'    => number_format($frete, 2, ',', '.'),
            'total'    => number_format($subtotal + $frete, 2, ',', '.'),
            'endereco' => $endereco,
            'mensagem' => $mensagem,
        ]);
    }
}
