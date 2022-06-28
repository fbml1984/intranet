<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EspressoUsuario extends Model
{

    public static function listar()
    {
        return DB::select('
            select
                eu.espresso_usuario_id id,
                eu.nome,
                DATE_FORMAT(eu.data_criacao, "%d/%m/%Y %H:%i:%s") data_criacao
            from
                espresso_usuario eu
            order by
                eu.data_criacao desc
        ');
    }

    public static function buscarPorId($id)
    {
        return DB::select('
            select
                *
            from
                espresso_usuario eu
            where
                eu.espresso_usuario_id = ?
        ', [$id]);
    }

    public static function criar($registro)
    {
        return DB::table('espresso_usuario')->insertGetId($registro, 'espresso_usuario_id');
    }

    public static function criarSeNaoExistir($registro)
    {
        $_registro = self::buscarPorId($registro['espresso_usuario_id']);
        if (empty($_registro)) {
            self::criar($registro);
        }
    }

}
