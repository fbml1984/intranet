<?php

namespace App\Models;

use App\Models\Base\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model implements Base
{
    public static function obterRegistros()
    {
        return DB::select('
            select
                *
            from
                company cmp
            where
                cmp.deleted_at is null
        ');
    }

    public static function obterRegistroPorId($id)
    {
        return collect(DB::select('
            select
                *

            from
                company cmp
            where
                cmp.company_id = ?
        ', [$id]))->first() ?? [];
    }

    public static function criarRegistro($registro, $usuarioLogado)
    {
        return DB::table('company')->insertGetId([
            'name'         => $registro['name'],
            'description'  => $registro['description'],
            'data_criacao' => DB::raw('NOW()'),
            'created_by'   => $usuarioLogado->usuario_id,
        ]);
    }

    public static function atualizarRegistro($id, $registro, $usuarioLogado)
    {
        return DB::table('company')
            ->where('company_id', $id)
            ->update([
                'name'             => $registro['name'],
                'description'      => $registro['description'],
                'data_alteracao' => DB::raw('NOW()'),
                'updated_by'       => $usuarioLogado->usuario_id,
            ]);
    }

    public static function deletarRegistro($id, $usuarioLogado)
    {
        return DB::table('company')
            ->where('company_id', $id)
            ->where('deleted_at', null)
            ->update([
                'deleted_at' => DB::raw('NOW()'),
                'deleted_by' => $usuarioLogado->usuario_id,
            ]);
    }
}
