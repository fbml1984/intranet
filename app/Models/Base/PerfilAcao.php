<?php

namespace App\Models\Base;

use Illuminate\Support\Facades\DB;

class PerfilAcao
{
    public static function listar(): array
    {
        return DB::select('
            select
                *
            from
                perfil_acoes pea,
                perfil per
            where
                1 = 1
                and per.perfil_id = pea.perfil_id
        ');
    }

    public static function deletar($perfilId, $acaoId)
    {
        DB::table('perfil_acoes')
            ->where('perfil_id', $perfilId)
            ->where('acao_id', $acaoId)
            ->delete();
    }

    public static function deleteAll($perfilId)
    {
        DB::select('
            delete from
                perfil_acoes pea
            where
                1 = 1
                and pea.perfil_id = ?
        ', [$perfilId]);
    }

    public static function createIfNotExists($perfilId, $acaoId)
    {
        $perfilAcao = DB::table('perfil_acoes')
            ->where('perfil_id', $perfilId)
            ->where('acao_id', $acaoId)
            ->get()
            ->first();

        if (empty($perfilAcao)) {
            DB::table('perfil_acoes')->insert([
                'perfil_id' => $perfilId,
                'acao_id'   => $acaoId,
            ]);
        }
    }

}
