<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historial extends Model
{
    use HasFactory;
    protected $fillable = [
        'operacion',
        'mensaje',
        'valor',
        'cartera_id',
    ];
    public $table = "history";
}
