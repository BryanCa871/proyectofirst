<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function registrar(Request $request)
    {
        $validacion= validator::make(
            $request->all(),
            [
                "name"=>"required|Max:255",
                "email"=>"required|Max:255",
                "password"=>"required|Max:255"
            ]
        );
        if($validacion->fails())
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Validacion no exitosa",
                    "Error"=>$validacion->errors(),
                    "Data"=>[]
                ], 400
                );
        }

        $User = new User();

        $User ->name = $request->name;
        $User ->email = $request->email;
        $User ->password =Hash::make($request->password);
        
        if($User->save())
        {

            return response()->json(
                [
                    "status"=>201,
                    "mensaje"=>"Usuario registrado",
                    "error"=>null,
                    "data"=>$User,
                   'user'=>$User,
                ],201
                );
        }
        else 
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Error, maldito estupido",
                    "error"=>null,
                    "data"=>[]
                ],400
                );
        }

    }

    public function createLogin(Request $request)
{

    $validacion= validator::make(
        $request->all(),
        [
            "email"=>"required|Max:255",
            "password"=>"required|Max:255",
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $User = User::whereEmail($request->email)->first();
    
    if(!is_null($User) && Hash::check($request->password, $User->password))
    {
        if($User->status == false)
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Cuenta desactivada, favor de activarlo mediante el correo que recibio",
                ],400
                );
        }

        if($User->save())
       {
          $token=$User->CreateToken("Token")->plainTextToken;
         return response()->json(
            [
             "status"=>201,
             "mensaje"=>"Bienvenido al sistema",
             "error"=>null,
             "data"=>$User,
             "token"=>$token
            ],201
         );
         
        }
    }
    else{

        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Los datos no son correctos",
                "error"=>null,
                "data"=>$User,
            ],400
            );
    }
}

public function logout(Request $request)
    {
        $user=$request->user();
        
    return response()->json(
        [
            "status"=>201,
            "mensaje"=>"Se ha cerrado exitosamente",
            "error"=>null,
            "token"=>$request->user()->tokens()->delete(),
        ],201
        );    
    }

    
   


















}
