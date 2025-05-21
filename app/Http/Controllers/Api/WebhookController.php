<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pedidos,id',
            'status' => 'required|string|in:pendente,processando,enviado,entregue,cancelado',
        ]);

        $pedidoId = $request->input('id');
        $status = $request->input('status');
        $pedido = Pedido::findOrFail($pedidoId);

        if ($status === 'cancelado') {
            $pedido->delete();
            return response()->json(['message' => "Pedido {$pedidoId} cancelado com sucesso."], 200);
        } else {
            $pedido->status = $status;
            $pedido->save();
            return response()->json(['message' => "Status do pedido {$pedidoId} atualizado para {$status}."], 200);
        }
    }
}
