<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function destroy(Estoque $estoque)
    {
        $estoque->delete();
        return response()->json(['success' => true]);
    }
}
