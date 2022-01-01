<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController
{
    protected string $classe;

    public function index(Request $request) 
    {
        //valor pdarão do per_page é definido na model do banco
        return $this->classe::paginate($request->per_page);
    }

    public function store(Request $request) 
    {  
        //retorna uma resposta em json
        //passando os dados do post
        //e o status http 201 que indica sucesso no post
        return response()
            ->json(
                $this->classe::create($request->all()), 
                201
            );
    }

    //busca série pelo id
    public function show(int $id)
    {   
        //busca serie pelo id recebido na url
        $recurso = $this->classe::find($id);
        if(is_null($recurso)) {
            //se o recurso não for encontrado reorna um valor vazio
            //e o tipo de resposta 204, para valor não encontrado
            return response()->json('', 204);
        }
        //retorna serie e status de resposta 200
        return response()->json($recurso);
    }

    public function update(int $id, Request $request) 
    {
        $recurso = $this->classe::find($id);
        if(is_null($recurso)) {
            //caso o recurso não exista aqui, nesse caso vamos usar o 404
            //porque partimos do principio que esse é um erro do cliente ao tentar trabalhar 
            //como uma serie que não existe
            return response()->json(['erro' => 'Recurso não encontrado'], 404);
        }
        //podemo usar o request->all() porque nesse caso o request apenas passa o nome do recurso
        //além disso na nossa model o único valor definido como fillable é o 'nome'
        //mas poderíamos passar o valor de fill(['nome' => $request->nome])
        $recurso->fill($request->all());
        $recurso->save();

        return $recurso;
    }

    public function destroy(int $id)
    {
        //destroy retorna um inteiro com o total de ids removidos
        $qtdRecursosRemovidos = $this->classe::destroy($id);
        if($qtdRecursosRemovidos === 0 ) {
            //se nenhum recurso(serie) foi removido, siginifica que o id informado não existe no banco
            return response()->json(['erro' => 'Recurso não encontrado'], 404);
        }
        //caso contrário significa que um recurso foi removido
        //daí retornamos 204, que indica sucesso, e que não tem mais nenhum conteúdo a ser exibido nessa url
        return response()->json('', 204);
    }
}