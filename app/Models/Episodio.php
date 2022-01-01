<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class Episodio extends Model
{
    public $timestamps = false;
    protected $fillable = ['temporada', 'numero', 'assistido', 'serie_id'];
    protected $appends = ['links'];

    public function serie() 
    {
        return $this->belongsTo(Serie::class);    
    }

    //atualmente se buscarmos o valor de assistido, ele será 0 ou 1
    //vamos usar um método do eloquent para definir que o valor retornado seja booleano
    //método chamado de Accessor, get{AttributeName}Attribute
    public function getAssistidoAttribute($assistido): bool
    {
        return $assistido;
    } 

    //usando Accessor para alterar um valor já existente no banco
    //no caso vamos adiconar '#' antes de cada número de episódio
    public function getNumeroAttribute(int $numero): string
    {
        return '#' . $numero;
    }

    //accessor para exibir um item com o link para a serie na busca por episodios
    public function getLinksAttribute(): array
    {
        return [
            'self' => '/api/episodios/' . $this->id,
            'serie' => '/api/series/' . $this->serie_id
        ];
    }
}