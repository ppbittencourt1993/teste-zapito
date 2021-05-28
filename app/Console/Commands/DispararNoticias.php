<?php

namespace App\Console\Commands;

use App\Repositories\DestinatarioRepository;
use App\Repositories\NoticiaEnviadaRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Exception;
use Feeds;

class DispararNoticias extends Command {
    
    protected $DestinatarioRepository;
    protected $NoticiaEnviadaRepository;

    protected $signature = 'disparar-noticias';

    protected $description = 'Disparar uma noticia retirada do feed rss do G1 para os destinatarios ativos a cada 5 minutos.
                              Existe um controle interno para que noticias já enviadas não sejam enviadas novamente.';


    public function __construct(DestinatarioRepository $DestinatarioRepository, NoticiaEnviadaRepository $NoticiaEnviadaRepository){
        parent::__construct();
        $this->DestinatarioRepository = $DestinatarioRepository;
        $this->NoticiaEnviadaRepository = $NoticiaEnviadaRepository;
    }


    public function handle(){
        date_default_timezone_set('America/Sao_Paulo');
        $destinatarios = $this->DestinatarioRepository->listarAtivos();
        $noticias_enviadas = $this->NoticiaEnviadaRepository->listar();

        $feed = Feeds::make('https://g1.globo.com/rss/g1/');
        $noticias = $feed->get_items();

        foreach($noticias as $noticia){

            //checa se o a noticia ja esta na lista de noticias enviadas
            $res = $this->NoticiaEnviadaRepository->porTitulo($noticia->get_title());

            //Se ainda nao foi enviada
            if(!$res){
                
                foreach($destinatarios as $destinatario){

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer '. env('TOKEN_ZAPITO'),
                        'Content-Type' => 'application/json'
                    ])
                    ->post('https://zapito.com.br/api/messages', [
                        'data' => [
                            [
                                'phone' => $destinatario->telefone,
                                'message' => $noticia->get_title(). "\n\n" .$destinatario->nome. ", se quiser ver a noticia completa acesse: ". $noticia->get_link(), 
                                'test_mode' => true
                            ]
                        ]
                    ]);

                }

                //Salva a noticia na tabela de noticias enviadas
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
                
                return redirect('/admin/destinatario');
            }

        }
        
        return redirect('/admin/destinatario');
    }
}
