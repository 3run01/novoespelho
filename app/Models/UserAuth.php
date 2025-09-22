<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserAuth extends Model
{
    use HasFactory;

    protected $connection = 'ediario';
    protected $table = 'usuario';

    protected $fillable = [
        'id',
        'nome',
        'matricula',
        'id_urano',
        'login_intranet',
        'senha_intranet',
        'email',
        'cpf',
        'rg',
        'status',
        'ultimo_acesso',
        'ultimo_acesso_sistema',
        'token_api',
        'foto_intranet',
        'google_auth_token',
    ];
}
