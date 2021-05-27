<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\NoticiaEnviadaRepository;
use App\Repositories\DestinatarioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Exception;
use Feeds;
use View;
use Log;

class NoticiaController extends Controller{

    protected $NoticiaEnviadaRepository;
    protected $DestinatarioRepository;

    public function __construct(NoticiaEnviadaRepository $NoticiaEnviadaRepository, DestinatarioRepository $DestinatarioRepository){
        $this->NoticiaEnviadaRepository = $NoticiaEnviadaRepository;
        $this->DestinatarioRepository = $DestinatarioRepository;
    }

    public function dispararNoticias() {
        date_default_timezone_set('America/Sao_Paulo');
        $destinatarios = $this->DestinatarioRepository->listarAtivos();
        $noticias_enviadas = $this->NoticiaEnviadaRepository->listar();

        $feed = Feeds::make('https://g1.globo.com/rss/g1/');
        $noticias = $feed->get_items();

        foreach($noticias as $noticia){

            //checa se o a noticia ja esta na lista de noticias enviadas
            $res = $this->NoticiaEnviadaRepository->porTitulo($noticia->get_title());

            //Se retornou a noticia sai do loop pois todas noticias antes dela tambem ja terao sido enviadas
            if($res){

                return redirect('/admin/destinatario');

            } else {
                
                foreach($destinatarios as $destinatario){

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer <'. env('TOKEN_ZAPITO'). '>',
                    ])->post('https://zapito.com.br/api/messages', [
                        "test_mode" => true,
                        "data" => [
                            "phone" => $destinatario->telefone,
                            "message" => $noticia->get_title(),
                            "test_mode" => true
                        ]
                    ]);
                
                    DB::beginTransaction();
                    try {

                        //Adiciona a noticia a lista de noticias enviadas
                        $input['titulo'] = $noticia->get_title();
                        $this->NoticiaEnviadaRepository->criar($input);

                        DB::commit();

                    }catch (\Exception $e) {
                        DB::rollback();
                        throw new Exception($e->getMessage());
                    }

                }
            }

            return redirect('/admin/destinatario');
        // return View::make('feed', $noticias);
        }
    }


}
