<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Response\Response;

class UsuarioController extends Controller
{

    public function getUsuario($pageSize)
    {
        $user = Usuario::orderBy('id', 'desc')->paginate($pageSize);
        return response()->json($user);
    }

    public function createUsuario(Request $request)
    {
        $usuario = Usuario::create($request->all());
        return response()->json($usuario);
    }

    public function editUsuario($usuarioId, Request $request)
    {
        $usuario = Usuario::find($usuarioId);
        foreach ($request->all() as $key => $value) {
            $usuario[$key] = $value;
        }
        $usuario->save();
        return response()->json($usuario);
    }

    public function deleteUsuario($usuarioId)
    {
        /* $salida = Salida::where('usuario_id', $usuarioId)->limit(1)->get();
        if(count($salida) > 0) {
            $response = new Response();
            $response->data = [];
            $response->message = 'No se puede borrar porque esta siendo usado en ventas';
            $response->status = 400;
            return response()->json($response);
        } */
        $usuario = Usuario::find($usuarioId);
        $usuario->delete();
        $response = new Response();
        $response->data = [];
        $response->message = 'deleted';
        $response->status = 200;
        return response()->json($response);
    }
}
