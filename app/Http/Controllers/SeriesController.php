<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;

class SeriesController extends BaseController
{
    public function __construct()
    {
        //define a classe a ser usada nos métodos para CRUD do BaseController
        $this->classe = Serie::class;
    }
}