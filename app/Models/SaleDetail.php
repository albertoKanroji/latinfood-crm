<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = ['price','quantity','product_id','sale_id','lot_id','scanned'];
   public function sales(){
        return $this->hasMany(Sale::class ,'id');
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
 public function lot()
    {
        return $this->belongsTo(Lotes::class, 'lot_id');
    }

}
