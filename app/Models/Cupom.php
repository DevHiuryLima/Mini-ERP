<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'tipo_desconto',
        'valor',
        'minimo_subtotal',
        'validade',
        'ativo',
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}
