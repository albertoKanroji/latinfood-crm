<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\envios;

class Operario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'apellido2',
        'edad', 'compañia', 'de_Planta'
    ];

public function envios()
    {
        return $this->hasMany(Envio::class, 'id_transport');
    }

  

  
  
}
