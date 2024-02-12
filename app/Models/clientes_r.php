<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
class clientes_r extends Model
{
    use HasFactory;

    protected $fillable = ['name','last_name','last_name2','email','phone','address','password','saldo'];


       public function sale(){
        return $this->hasMany(Sale::class ,'cliente_id');
    }
  
}
