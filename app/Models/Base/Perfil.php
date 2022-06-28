<?php

namespace App\Models\Base;

use Illuminate\Support\Facades\DB;

class Perfil
{

    public static function listar()
    {
        return DB::select("
            select
                perfil_id id,
                nome,
                pode_ser_excluido,
                DATE_FORMAT(data_criacao, '%d/%m/%Y %H:%i:%s') data_criacao,
                DATE_FORMAT(data_alteracao, '%d/%m/%Y %H:%i:%s') data_alteracao
            from
                perfil
            where
                excluido_por is null
        ");
    }

    public static function obterPorPerfilId($id)
    {
        return collect(DB::select('
            select
                *
            from
                perfil
            where
                1 = 1
                and excluido_por is null
                and perfil_id = ?
        ', [$id]))->first() ?? [];
    }

    public static function obterPorNome($perfil)
    {
        return collect(DB::select('
            select
                *
            from
                perfil
            where
                1 = 1
                and excluido_por is null
                and nome = ?
        ', [$perfil]))->first();
    }

    public static function criar($registro): int
    {
        return DB::table('perfil')->insertGetId($registro);
    }

    public static function atualizar($id, $registro)
    {
        DB::table('perfil')
            ->where('perfil_id', $id)
            ->update($registro);
    }

}
