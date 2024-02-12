<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Sale;
class Cliente extends Model
{
    use HasFactory;
protected $fillable = ['name','last_name','last_name2','email','phone','address','document','password','saldo'];

  public function sales(){
        return $this->belongsTo(Sale::class, 'phone', 'id');
    }
}
