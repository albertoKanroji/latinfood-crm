<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;


class Customer extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;
   
      protected $fillable = [
        'id',
        'name',
        'last_name',
        'last_name2',
        'email',
        'phone',
        'address',
        'password',
        'saldo',
        'image',
        'woocommerce_cliente_id',
        'notification_token'
    ];

         public function sale(){
        return $this->hasMany(Sale::class ,'CustomerID');
    }
}
