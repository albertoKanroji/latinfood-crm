<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;
    protected $fillable = ['items', 'id_producto', 'id_cliente'];

    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'id_cliente');
    }
    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto');
    }
}
