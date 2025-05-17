<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Estoque;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::with('estoques')->paginate(10);
        return view('produtos.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdutoRequest $request)
    {
        DB::transaction(function() use ($request) {
            $produto = Produto::create($request->only(['nome','preco']));
            $produto->estoques()->createMany($request->input('estoques', []));
        });

        return redirect()
            ->route('produtos.index')
            ->with('success','Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto->load('estoques');
        return view('produtos.show', compact('produto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produto->load('estoques');
        return view('produtos.edit', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutoRequest $request, string $id)
    {
        DB::transaction(function() use ($request, $produto) {
            $produto->update($request->only(['nome', 'preco']));

            if ($request->has('estoques')) {
                $estoques = collect($request->input('estoques'));

                $estoques->each(function ($item) use ($produto) {
                    if (!empty($item['id'])) {
                        Estoque::where('id', $item['id'])
                            ->update([
                                'variacao'   => $item['variacao'] ?? null,
                                'quantidade' => $item['quantidade'],
                            ]);
                    } else {
                        $produto->estoques()->create($item);
                    }
                });

                $idsEnviados = $estoques->pluck('id')->filter();
                $produto->estoques()
                       ->whereNotIn('id', $idsEnviados)
                       ->delete();
            }
        });

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto->delete();

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto removido com sucesso!');
    }
}
