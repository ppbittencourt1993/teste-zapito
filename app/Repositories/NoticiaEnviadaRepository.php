<?php

namespace App\Repositories;

use App\Models\NoticiaEnviada;
use DB;

class NoticiaEnviadaRepository{

    public function listar(){
        $res = NoticiaEnviada::all();
        return $res;
    }
    
    public function porId($id){
        $res = NoticiaEnviada::find($id);
        return $res;
    }

    public function porTitulo($titulo){
        $res = NoticiaEnviada::where('titulo', $titulo)->first();
        return $res;
    }
    
    public function criar($input){
        $res = NoticiaEnviada::create($input);
        return $res;
    }

}