<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCupomRequest;
use App\Http\Requests\UpdateCupomRequest;
use App\Models\Cupom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CupomController extends Controller
{
    private $cupom;

    public function __construct(Cupom $cupom)
    {
        $this->cupom = $cupom;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cupons = $this->cupom->orderBy('validade', 'desc')->paginate(10);
        return view('cupons.index', compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCupomRequest $request)
    {
        $this->cupom->create($request->validated());

        return redirect()->route('cupons.index')->with('success','Cupom criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupom $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCupomRequest $request, Cupom $cupom)
    {
        $cupom->update($request->validated());

        return redirect()->route('cupons.index')->with('success','Cupom atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return redirect()->route('cupons.index') ->with('success','Cupom removido com sucesso!');
    }

    public function aplicarCupom(Request $request)
    {
        $request->validate([
            'codigo' => ['required','string','exists:cupons,codigo'],
        ]);

        $cupom = Cupom::where('codigo', $request->codigo)->where('ativo', true)->whereDate('validade', '>=', now())->firstOrFail();

        $carrinho = Session::get('carrinho', []);

        $subtotal = array_reduce($carrinho['produtos'], function($soma, $item) {
            return $soma + ($item['preco'] * $item['quantidade']);
        }, 0);

        if ($subtotal < $cupom->minimo_subtotal) {
            return response()->json([
                'errors' => 'Requer subtotal mínimo de R$ ' . number_format($cupom->minimo_subtotal,2,',','.'),
            ], 400);
        }

        // Calcula desconto
        if ($cupom->tipo_desconto === 'percentual') {
            $desconto = $subtotal * ($cupom->valor / 100);
        } else {
            $desconto = $cupom->valor;
        }

        $rawTotal = session('carrinho.total', $subtotal);

        if (is_string($rawTotal)) {
            $numericTotal = floatval(str_replace(',', '.', $rawTotal));
        } else {
            $numericTotal = (float) $rawTotal;
        }

        $total = max(0, $numericTotal - $desconto);

        $rawFrete = session('carrinho.frete', 0);
        if ($rawFrete) {
            $freteNumeric = is_string($rawFrete)
                ? floatval(str_replace(',', '.', $rawFrete))
                : (float) $rawFrete;

            $total += $freteNumeric;
        }

        // Atualiza sessão
        session([
            'carrinho.cupom.id' => $cupom->id,
            'carrinho.cupom.codigo' => $cupom->codigo,
            'carrinho.cupom.desconto' => round($desconto,2),
            'carrinho.total' => number_format($total, 2, ',', '.'),
        ]);

        return response()->json([
            'desconto' => number_format($desconto, 2, ',', '.'),
            'total' => number_format($total, 2, ',', '.'),
            'mensagem' => 'Cupom aplicado'. $cupom->codigo,
        ]);
    }
}
