<?php

namespace App\Repositories;

use App\Models\Destinatario;

class DestinatarioRepository{

    public function listarAtivos(){
        $res = Destinatario::where('ativo', 1)->get();
        return $res;
    }

}