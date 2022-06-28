<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Acao
{

    public static function listar(): array
    {
        return DB::select('
            select
                *
            from
                acao aca
        ');
    }

    public static function obterPorRotinaEAcao($rotinaId, $acao)
    {
        return collect(DB::select('
            select
                *
            from
                acao aca
            where
                1 = 1
                and aca.rotina_id = ?
                and aca.nome = ?

        ', [$rotinaId, $acao]))->first();
    }

    public static function criar($registro): int
    {
        return DB::table('acao')->insertGetId($registro);
    }


}
