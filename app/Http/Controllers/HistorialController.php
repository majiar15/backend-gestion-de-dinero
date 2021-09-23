<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\historial;

class HistorialController extends Controller
{
    //crear historial 
    public function getHistorial($id){
        $historial = historial::where('cartera_id','=', $id)->get();
        if(is_null($historial) || isset($historial)){
            return response()->json(
                [
                    'message' => 'no se puedo encontrar la cartera con id '.$id,
                ],
                400
            );
        }else{
            return response()->json(
                [
                    'message' => 'historial encontrado correctamente',
                    'historial' => $historial
                ],
                400
            );
        }
    }
}
