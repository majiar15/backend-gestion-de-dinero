<?php

namespace App\Http\Controllers;

use App\Models\Cartera;
use App\Models\historial;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;


class CarteraController extends Controller
{
    public function getAll(Request $request)
    {
        $id = $request["id"];
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|max:255',
        ]);
        if ($validator->fails()) {

            return response()->json(
                $validator->errors()->toJson(),
                400
            );
        }
        return Cartera::where('user_id', $id)->get();
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'nombre' => 'required|string',
            'cantidad' => 'required|int',

        ]);
        if ($validator->fails()) {

            return response()->json(
                $validator->errors()->toJson(),
                400
            );
        }
        $userId = $request["user_id"];
        $nombre = $request["nombre"];
        $cantidad = $request["cantidad"];

        $cartera = Cartera::create([
            'name' => $nombre,
            'cantidad' => $cantidad,
            'user_id' => $userId,
        ]);

        historial::create([
            'operacion' => 'creacion de cartera',
            'mensaje' => 'creacion de cartera',
            'cartera_id' => $cartera->id,
            'valor' => $cantidad,
        ]);
        return response()->json(
            [
                'message' => 'cartera creada correctamente',
            ],
            201
        );
    }
    public function getOne(Cartera $cartera)
    {
        if ($cartera) {
            return $cartera;
        }
        return response()->json(
            [
                'message' => 'cartera no encontrada',
            ],
            400
        );
    }
    public function delete($id)
    {
        $cartera = Cartera::find($id);
        if (!is_null($cartera)) {
            $cartera->delete();
            if ($cartera->trashed()) {
                //
                return response()->json(
                    [
                        'message' => 'cartera eliminada correctamente',
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'message' => 'no se pudo eliminar la cartera',
                    ],
                    401
                );
            }
        }else{
            return response()->json(
                [
                    'message' => 'no se encontro ningun registro con id '.$id,
                ],
                401
            );
        }
    }
    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'nombre' => 'required|string',
        ]);
        if ($validator->fails()) {

            return response()->json(
                $validator->errors()->toJson(),
                400
            );
        }
        $id = $request["id"];
        
        $cartera = Cartera::find($id);
        if (is_null($cartera)){
            return response()->json([
                'message' => 'no se puso enocntrar la cartera con id '.$id,
            ]);
        }else{
            $cartera->name = $request["nombre"];
            $cartera->save();
            return response()->json(
                [
                    'message' => 'cartera  '.$id.' editada correctamente',
                    'cartera' => $cartera,
                ],
                401
            );
        }
        

    }
    public function deposit(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'nombre' => 'required|string',
            'cantidad' => 'required|int',
            'mensaje' => 'string',
        ]);   
        if ($validator->fails()) {

            return response()->json(
                $validator->errors()->toJson(),
                400
            );
        }
        
        if(isset($request["mensaje"])){
            $mensaje = $request["mensaje"];
        }else{
            $mensaje = "Deposito de".$request["cantidad"];
        }
        $id = $request["id"];
        $cantidad = $request["cantidad"];

        $cartera = Cartera::find($id);
        
        if (is_null($cartera)){
            return response()->json([
                'message' => 'no se pudo enocntrar la cartera con id '.$id,
                'cartera' => $cartera,
            ]);
        }else{
            $cartera->cantidad += $cantidad;
            $historial = new historial;
            $historial->operacion = "deposito";
            $historial->mensaje = $mensaje;
            $historial->valor = $cartera->cantidad;
            $historial->cartera_id = $id;
            $historial->save();
            $cartera->save();
            
                return response()->json(
                    [
                        'message' => 'cartera  '.$id.' editada correctamente',
                        'historial' => $historial,
                        'cartera' => $cartera,
                    ],
                    200
                );
        
            
        }


    }
    public function withdrawal(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'cantidad' => 'required|int',
            'mensaje' => 'string',
        ]);   
        if ($validator->fails()) {

            return response()->json(
                $validator->errors()->toJson(),
                400
            );
        }
        
        if(isset($request["mensaje"])){
            $mensaje = $request["mensaje"];
        }else{
            $mensaje = "Retiro de".$request["cantidad"];
        }
        $id = $request["id"];
        $cantidad = $request["cantidad"];

        $cartera = Cartera::find($id);
        
        if (is_null($cartera)){
            return response()->json([
                'message' => 'no se pudo enocntrar la cartera con id '.$id,
                'cartera' => $cartera,
            ]);
        }else{
            if($cartera->cantidad > $cantidad){
                $cartera->cantidad -= $cantidad;
                $historial = new historial;
                $historial->operacion = "retiro";
                $historial->mensaje = $mensaje;
                $historial->valor = $cartera->cantidad;
                $historial->cartera_id = $id;
                $historial->save();
                $cartera->save();
                
                    return response()->json(
                        [
                            'message' => 'cartera  '.$id.' editada correctamente',
                            'historial' => $historial,
                            'cartera' => $cartera,
                        ],
                        200
                    );
            }else{
                return response()->json(
                    [
                        'message' => 'fondos insuficientes',
                        
                    ],
                    400
                ); 
            }
            
        
            
        }


    }
}
