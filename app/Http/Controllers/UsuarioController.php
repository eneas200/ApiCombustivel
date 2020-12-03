<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\Hash;



class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        echo "App Combustivel";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // retorna um array na variavel $us
        $us = $request->all();
        $usuario = new Usuario();

        // atribuindo valores nos atributos da class Usuario
        $usuario->nome = $us['nome'];
        $usuario->email = $us['email'];
        $usuario->senha = $us['senha'];

        // verificando se o usuario ja tem um email cadastrado no banco
        $usuarioExiste = Usuario::where('email', $us['email'])->first();
        if($usuarioExiste)
        {
          return response("Já existe um usuario com este email registrado", 400);
        }

        $confirmarSenha = $us['confirmarSenha'];
        if($usuario->senha != $confirmarSenha)
        {
          return response("As senha não conhecidem. ", 400);
        } else 
        {
          $usuario->senha = Hash::make($usuario->senha);
          $usuario->save();
          return $usuario;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(int $usuario_id)
    {
        //
        
        return Usuario::with('carros')->find($usuario_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
    // metodo que faz o login e retorna uma uma response
    public function login(Request $request){
      $email = $request->input('email');
      $senha = $request->input('senha');
      if(!$email || !$senha){
        return response("Credenciais invalida!", 400);
      }

      $usuario = Usuario::where("email", $email)->first();

      if(!$usuario) {
        return response("Credenciais invalida!", 400);
      }

      if(!Hash::check($senha, $usuario->senha)){
        return response("Credenciais inválida!", 400);
      }

      return $usuario;
    }

}
