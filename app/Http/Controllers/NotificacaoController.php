<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class NotificacaoController extends Controller{

    public function __construct(){
    }

    public function notificacaoZapito(Request $request) {
        $dados = $request->all();
        Log::info(json_decode($dados->getBody(), true));
        return true;
    }

}
