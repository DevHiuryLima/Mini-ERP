<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCupomRequest;
use App\Http\Requests\UpdateCupomRequest;
use App\Models\Cupom;
use Illuminate\Http\Request;

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

        $cupom = Cupom::where('codigo', $request->codigo)
            ->where('ativo', true)
            ->whereDate('validade', '>=', now())
            ->firstOrFail();

        $subtotal = session('carrinho.subtotal', 0);

        if ($subtotal < $cupom->minimo_subtotal) {
            return back()->withErrors([
                'cupom' => "Requer subtotal mínimo de R$ ".number_format($cupom->minimo_subtotal,2,',','.'),
            ]);
        }

        // Calcula desconto
        if ($cupom->tipo_desconto === 'percentual') {
            $desconto = $subtotal * ($cupom->valor / 100);
        } else {
            $desconto = $cupom->valor;
        }

        // Atualiza sessão
        session([
            'carrinho.cupom'       => $cupom->codigo,
            'carrinho.desconto'    => round($desconto,2),
            'carrinho.total'       => max(0, session('carrinho.total', $subtotal) - $desconto),
        ]);

        return back()->with('success', 'Cupom aplicado: '. $cupom->codigo);
    }

}
