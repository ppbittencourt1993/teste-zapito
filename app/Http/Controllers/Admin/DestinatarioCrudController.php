<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DestinatarioRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class DestinatarioCrudController extends CrudController{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup(){
        CRUD::setModel(\App\Models\Destinatario::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/destinatario');
        CRUD::setEntityNameStrings('destinatario', 'destinatarios');
    }


    protected function setupListOperation(){
        CRUD::column('nome');
        CRUD::column('telefone');
        CRUD::column('email');
        $this->crud->addColumn([
            'name'  => 'ativo',
            'label' => 'Ativo',
            'type'  => 'boolean',
            'options' => [0 => 'Não', 1 => 'Sim']
        ]);
    }


    protected function setupCreateOperation(){
        CRUD::setValidation(DestinatarioRequest::class);

        CRUD::field('nome')->size(6);
        CRUD::field('telefone')->size(6);
        CRUD::field('email')->size(6);
        CRUD::field('ativo')->size(6);
    }


    protected function setupUpdateOperation(){
        $this->setupCreateOperation();
    }


    protected function setupShowOperation(){
        CRUD::column('nome');
        CRUD::column('telefone');
        CRUD::column('email');
        $this->crud->addColumn([
            'name'  => 'ativo',
            'label' => 'Ativo',
            'type'  => 'boolean',
            'options' => [0 => 'Não', 1 => 'Sim']
        ]);
    }
    
}
