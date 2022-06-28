<?php

namespace App\Models;

use App\Models\Base\CompanyBaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model implements CompanyBaseModel
{
    public static function obterRegistros($usuarioLogado)
    {
        return DB::select('
            select
                *
            from
                team tea
            where
                tea.company_id = ?
                and tea.deleted_at is null
        ', [$usuarioLogado->company_id]);
    }

    public static function obterRegistroPorId($id, $usuarioLogado)
    {
        return collect(DB::select('
            select
                *

            from
                team tea
            where
                tea.team_id = ?
                and tea.company_id = ?
        ', [$id, $usuarioLogado->company_id]))->first() ?? [];
    }

    public static function criarRegistro($registro, $usuarioLogado)
    {
        return DB::table('team')->insertGetId([
            'name'         => $registro['name'],
            'description'  => $registro['description'],
            'company_id'   => $usuarioLogado->company_id,
            'data_criacao' => DB::raw('NOW()'),
            'created_by'   => $usuarioLogado->usuario_id,
        ]);
    }

    public static function atualizarRegistro($id, $registro, $usuarioLogado)
    {
        return DB::table('team')
            ->where('team_id', $id)
            ->where('company_id', $usuarioLogado->company_id)
            ->update([
                'name'             => $registro['name'],
                'description'      => $registro['description'],
                'data_alteracao' => DB::raw('NOW()'),
                'updated_by'       => $usuarioLogado->usuario_id,
            ]);
    }

    public static function deletarRegistro($id, $usuarioLogado)
    {
        return DB::table('team')
            ->where('team_id', $id)
            ->where('deleted_at', null)
            ->update([
                'deleted_at' => DB::raw('NOW()'),
                'deleted_by' => $usuarioLogado->usuario_id,
            ]);
    }

}
