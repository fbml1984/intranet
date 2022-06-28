<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ControleAcesso extends Model
{

    public static function getRoutinesAndActionsList(): array
    {
        return DB::select('
            SELECT
                ro.nome rotina,
                ro.icone,
                ro.ordem,
                ac.acao_id,
                ac.nome acao
            FROM
                acao ac,
                rotina ro
            WHERE
                0 = 0
                AND ac.rotina_id = ro.rotina_id
            ORDER BY
                ro.ordem
        ');
    }

    public static function obterPermissoesDoPerfil($perfilId): array
    {
        $permissions = DB::select('
            SELECT
                pa.acao_id
            FROM
                perfil pe,
                perfil_acoes pa
            WHERE
                0 = 0
                AND pe.perfil_id = pa.perfil_id
                AND pe.perfil_id = ?
        ', [$perfilId]);
        return collect($permissions)->pluck(['acao_id'])->all();
    }

    public static function obterPermissoesDoUsuario($userId): array
    {
        return DB::select('
            select
                distinct sis.nome sistema,
                rot.nome rotina,
                rot.icone,
                rot.ordem,
                aca.nome acao
            from
                usuario_perfis usp,
                perfil_acoes pea,
                acao aca,
                rotina rot,
                sistema sis
            where
                1 = 1
                and usp.perfil_id = pea.perfil_id
                and pea.acao_id = aca.acao_id
                and aca.rotina_id = rot.rotina_id
                and sis.sistema_id = rot.sistema_id
                and usp.usuario_id = ?
            order by
                rot.ordem
        ', [$userId]);
    }

    public static function obterPerfisPorUsuarioId($id): array
    {
        return DB::select('
            SELECT
                perfil_id
            FROM
                usuario_perfis
            WHERE
                1 = 1
                AND usuario_id = ?
        ', [$id]);
    }

    public static function criarPerfilAcao($perfilId, $acaoId)
    {
        DB::table('perfil_acoes')->insert([
            'perfil_id' => $perfilId,
            'acao_id'   => $acaoId,
        ]);
    }

    public static function deletarPerfilAcao($perfilId, $acaoId)
    {
        DB::table('perfil_acoes')
            ->where('perfil_id', $perfilId)
            ->where('acao_id', $acaoId)
            ->delete();
    }

}
