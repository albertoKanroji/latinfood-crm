<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Despacho extends Model
{
    use HasFactory;


     protected $fillable = [
        'CustomerID',
        'sku',
        'observa',
        'num_pedido',
        'created_at',
        
    ];

      public function sale(){
        return $this->hasMany(Sale::class ,'CustomerID');
    }
      public function cliente(){
        return $this->belongsTo(Product::class, 'SKU', 'id');
    }
}
