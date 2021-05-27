<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;

class NoticiaEnviada extends Model
{
    use HasFactory, CrudTrait;

    protected $table = "noticias_enviadas";

    protected $fillable = [
        'titulo',
    ];

}
