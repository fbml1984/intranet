<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EspressoTag extends Model
{

    public static function listar()
    {
        return DB::select('
            select
                et.espresso_tag_id id,
                et.nome,
                DATE_FORMAT(et.data_criacao, "%d/%m/%Y %H:%i:%s") data_criacao
            from
                espresso_tag et
            order by
                et.data_criacao desc
        ');
    }

    public static function buscarPorId($id)
    {
        return DB::select('
            select
                *
            from
                espresso_tag et
            where
            et.espresso_tag_id = ?
        ', [$id]);
    }

    public static function criar($registro)
    {
        return DB::table('espresso_tag')->insertGetId($registro, 'espresso_tag_id');
    }

    public static function criarSeNaoExistir($registro)
    {
        $_registro = self::buscarPorId($registro['espresso_tag_id']);
        if (empty($_registro)) {
            self::criar($registro);
        }
    }

}
