<?php

namespace App\Models\Base;

use Illuminate\Support\Facades\DB;

class UsuarioPerfil
{

    public static function criar($registro): int
    {
        return DB::table('usuario_perfis')->insertGetId($registro);
    }

    public static function deletar($userId)
    {
        DB::table('usuario_perfis')
            ->where('usuario_id', $userId)
            ->delete();
    }

}
