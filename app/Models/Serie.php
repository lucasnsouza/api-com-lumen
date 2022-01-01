<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    public $timestamps = false;
    protected $fillable = ['nome'];
    //definindo o valor padrão da paginação
    //va buscar apenas 3 elementos por página
    protected $perPage = 3;
    protected $appends = ['links'];

    public function episodios() {
        return $this->hasMany(Episodio::class);
    }

    //usando Mutator para definir como um valor será inserido no banco
    //vamos definir um mutator para sempre inserir as séries com primeira letra maiuscula
    //essa regra sempre será palicada quando um nome for definido
    public function setNomeAttribute(string $nome)
    {
        //estamos definindo que para o atributo nome da model Serie
        //vamos aplicar o mb_convert_case, onde
        //para o valor recebido $nome, a primeira letra sempre será maiúscula(MB_CASE_TITLE)
        $this->attributes['nome'] = mb_convert_case($nome, MB_CASE_TITLE);
    }

    //usando Accessor para definir um "novo" valor
    //vamos exibir todos os nomes de série como maiúculo
    //como isso nós poderíamos acessar esse novo método da seguinte maneira
    //$serie->nome_maiusculo
    public function getNomeMaisculoAttribute(): string
    {
        return mb_strtoupper($this->nome);
    }

    //criando um Accessor para retornar os links para acesso do episódios de cada série
    public function getLinksAttribute(): array
    {
        return [
            'self' => '/api/series/' . $this->id,
            'episodios' => '/api/series/' . $this->id . '/episodios'
        ];
    }
}