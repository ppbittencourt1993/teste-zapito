<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class Destinatario extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'ativo'
    ];

}
