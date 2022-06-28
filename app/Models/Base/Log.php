<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    public static function create($usuarioId, $action)
    {
        DB::table('log')->insert([
            'usuario_id'  => $usuarioId,
            'url_acesso'  => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'ip_origem'   => getIp(),
            'rota_acesso' => $action,
            'data_acesso' => DB::raw('NOW()'),
        ]);
    }
}
