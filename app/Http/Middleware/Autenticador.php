<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class Autenticador
{
    public function handle(Request $request, Closure $next)
    {
        try {
            //buscar se existe o cabeçalho infomado os dados de autorização
            if (!$request->hasHeader('Authorization')) {
                throw new Exception();
            }
            //pega o cabaeçalho de Authorization 
            $authorizationHeader = $request->header('Authorization');
            //com o cabeçalho podemos pegar o token removendo o identificador Bearer e o espaço até o token
            $token = str_replace('Bearer ', '', $authorizationHeader);
            //usando biblioteca para decodificar o token
            $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']);
            
            //retorna o usuário encontardo no banco de dados
            $user = User::where('email', $dadosAutenticacao->email)->first();
            //senão encontrar usuário lança exceção
            if(is_null($user)) {
                throw new Exception();
            }
            //se estiver tudo certo faz a autenticação
            return $next($request);
        
        } catch (Exception $e) {
            //qualquer exception lançada vai cair aqui e retornar essa resposta
            return response()->json('Não autorizado!', 401);
        }
    }
}