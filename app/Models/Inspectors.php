<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspectors extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'action',
        'seccion',
        'created_at',
    ];
}
