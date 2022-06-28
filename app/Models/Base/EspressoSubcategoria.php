<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EspressoSubcategoria extends Model
{

    public static function listar()
    {
        return DB::select('
            select
                es.espresso_subcategoria_id id,
                es.nome,
                DATE_FORMAT(es.data_criacao, "%d/%m/%Y %H:%i:%s") data_criacao
            from
                espresso_subcategoria es
            order by
                es.data_criacao desc
        ');
    }

    public static function buscarPorId($id)
    {
        return DB::select('
            select
                *
            from
                espresso_subcategoria es
            where
                es.espresso_subcategoria_id = ?
        ', [$id]);
    }

    public static function criar($registro)
    {
        return DB::table('espresso_subcategoria')->insertGetId($registro, 'espresso_subcategoria_id');
    }

    public static function criarSeNaoExistir($registro)
    {
        $_registro = self::buscarPorId($registro['espresso_subcategoria_id']);
        if (empty($_registro)) {
            self::criar($registro);
        }
    }

}
