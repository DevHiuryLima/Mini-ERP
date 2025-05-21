<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'subtotal',
        'frete',
        'total',
        'cupom_id',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'email_cliente',
        'status',
    ];

    public function itens(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function cupom(): BelongsTo
    {
        return $this->belongsTo(Cupom::class);
    }
}
