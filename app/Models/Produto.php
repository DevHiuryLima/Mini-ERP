<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'preco',
    ];

    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class);
    }

    public function pedidoItens(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }
}
