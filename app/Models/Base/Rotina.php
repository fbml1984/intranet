<?php

namespace App\Models\Base;

use Illuminate\Support\Facades\DB;

class Rotina
{
    public static function listar()
    {
        return DB::select("
            select
                rot.rotina_id as id,
                rot.ordem,
                rot.icone,
                rot.nome,
                rot.controller,
                DATE_FORMAT(rot.data_criacao, '%d/%m/%Y %H:%i:%s') data_criacao,
                DATE_FORMAT(rot.data_alteracao, '%d/%m/%Y %H:%i:%s') data_alteracao
            from
                rotina rot
            order by
                rot.ordem
        ");
    }

    public static function obterPorId($id)
    {
        return collect(DB::select('
            select
                *
            from
                rotina rot
            where
                rot.rotina_id = ?
        ', [$id]))->first() ?? [];
    }

    public static function criar($registro)
    {
        return DB::table('rotina')->insertGetId($registro);
    }

    public static function atualizar($id, $registro)
    {
        DB::table('rotina')
            ->where('rotina_id', '=', $id)
            ->update($registro);
    }

    public static function delete($id)
    {
        // TODO: Implement deleteRegistry() method.
    }

    public static function obterPorController($controller)
    {
        return collect(DB::select('
            select
                *
            from
                rotina rot
            where
                rot.controller = ?
        ', [$controller]))->first() ?? [];
    }

    public static function updateOrder($routineOrder, $routineId)
    {
        DB::table('rotina')
            ->where('rotina_id', '=', $routineId)
            ->update([
                'ordem'          => $routineOrder,
                'data_alteracao' => DB::raw('NOW()'),
            ]);
    }

    public static function createActionForRoutine($routineId, $action)
    {
        return DB::table('action')->insertGetId([
            'rotina_id'    => $routineId,
            'name'         => $action,
            'data_criacao' => DB::raw('NOW()'),
        ]);
    }

    public static function obterPorAcao($acao)
    {
        return collect(DB::select('
            select
                rot.nome
            from
                acao aca,
                rotina rot
            where
                aca.rotina_id = rot.rotina_id
                and aca.nome = ?
        ', [$acao]))->first();
    }

    public static function getActionLineByRoutineIdAndActionName($routineId, $action)
    {
        return collect(DB::select('
            select
                *
            from
                action aca
            where
                aca.rotina_id = ?
                and aca.nome = ?
        ', [$routineId, $action]))->first() ?? [];
    }

}
