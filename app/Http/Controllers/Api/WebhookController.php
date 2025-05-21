<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PedidoStatusAtualizado;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            $mensagem = "Pedido {$pedidoId} cancelado com sucesso.";
        } else {
            $pedido->status = $status;
            $pedido->save();
            $mensagem = "Status do pedido {$pedidoId} atualizado para {$status}.";
            Mail::to($pedido->email_cliente)->send(new PedidoStatusAtualizado($pedido, $status));
            $enviouEmail = true;
        }

        return response()->json(['message' => $mensagem, 'email_enviado' => $enviouEmail], 200);
    }
}
