<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sabores;

class Insumo extends Model
{
    use HasFactory;
      protected $fillable = [
        'idSabor',
        'CodigoBarras',
        'Cantidad_Articulos',
        'Fecha_Vencimiento',
        'User',
        
    ];
    public function sabor()
    {
        return $this->belongsTo(Sabores::class, 'idSabor');
    }
      public static function getLotesBySabor($idSabor)
    {
        return self::where('idSabor', $idSabor)->get();
    }
}
