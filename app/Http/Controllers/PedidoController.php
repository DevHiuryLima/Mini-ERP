<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\ItemPedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('itens')->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    public function finalizar(Request $request)
    {
        $carrinho = Session::get('carrinho');

        if (!$carrinho || empty($carrinho['produtos'])) {
            return redirect()->route('carrinho.index')->with('warning', 'Seu carrinho está vazio.');
        }

        DB::beginTransaction();

        try {
            $pedido = Pedido::create([
                'subtotal' => collect($carrinho['produtos'])->sum('subtotal'),
                'frete' => str_replace(',', '.', $carrinho['frete'] ?? 0),
                'total' => str_replace(',', '.', $carrinho['total'] ?? 0),
                'cupom_id' => $carrinho['cupom']['id'] ?? null,
                'cep' => $carrinho['endereco']['cep'] ?? null,
                'endereco' => $carrinho['endereco']['logradouro'] ?? null,
                'bairro' => $carrinho['endereco']['bairro'] ?? null,
                'cidade' => $carrinho['endereco']['localidade'] ?? null,
                'uf' => $carrinho['endereco']['uf'] ?? null,
                'email_cliente' => $request->input('email'),
            ]);

            foreach ($carrinho['produtos'] as $item) {
                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'variacao' => $item['variacao'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                ]);

                $estoque = Estoque::where('produto_id', $item['produto_id'])->where('variacao', $item['variacao'])->first();

                if ($estoque) {
                    $estoque->decrement('quantidade', $item['quantidade']);
                }
            }

            Session::forget('carrinho');
            DB::commit();

            return response()->json(['message' => 'Pedido realizado com sucesso!'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['Houve um erro ao finalizar o pedido.' => $e->getMessage()]], 500);
        }
    }
}
