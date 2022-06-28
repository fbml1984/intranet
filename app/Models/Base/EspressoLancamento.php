<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EspressoLancamento extends Model
{

    public static function listarPorTipo($tipo)
    {
        return DB::select('
            select
                el.espresso_id id,
                eu.nome usuario,
                et.nome tag,
                es.nome subcategoria,
                DATE_FORMAT(el.data, "%d/%m/%Y") data,
                CONCAT(\'R$ \',   REPLACE(REPLACE(REPLACE(FORMAT(custo, 2), \'.\', \'|\'), \',\', \'.\'), \'|\', \',\')) custo,
                CONCAT(\'R$ \',   REPLACE(REPLACE(REPLACE(FORMAT(custo_base, 2), \'.\', \'|\'), \',\', \'.\'), \'|\', \',\')) custo_base,
                el.url_anexo,
                DATE_FORMAT(el.data_criacao, "%d/%m/%Y %H:%i:%s") data_criacao,
                DATE_FORMAT(el.data_integracao, "%d/%m/%Y %H:%i:%s") data_integracao
            from
                espresso_lancamento el
                inner join espresso_usuario eu on (eu.espresso_usuario_id = el.espresso_usuario_id)
                left join espresso_tag et on (et.espresso_tag_id = el.espresso_tag_id)
                left join espresso_subcategoria es on (es.espresso_subcategoria_id = el.espresso_subcategoria_id)
            where
                1 = 1
                and el.tipo = ?
                and el.data_integracao is null
                and el.data_exclusao is null
            order by
                el.data desc
        ', [$tipo]);
    }

    public static function buscarPorId($id)
    {
        return collect(DB::select('
            select
                *
            from
                espresso_lancamento el
            where
                el.espresso_id = ?
                and el.data_exclusao is null
        ', [$id]))->first();
    }

    public static function criar($registro)
    {
        DB::table('espresso_lancamento')->insert($registro);
    }

    public static function criarSeNaoExistir($registro)
    {
        $_registro = DB::table('espresso_lancamento')
            ->where('espresso_id', $registro['espresso_id'])
            ->get()
            ->first();
        if (empty($_registro)) {
            self::criar($registro);
        }
    }

    public static function aprovar($espressoId, $usuarioId)
    {
        return DB::update('
            update
                espresso_lancamento
            set
                rejeitado = ?,
                data_integracao = NOW(),
                integrado_por = ?
            where
                espresso_id = ?
        ', [0, $usuarioId, $espressoId]);
    }

    public static function rejeitar($espressoId, $usuarioId)
    {
        return DB::update('
            update
                espresso_lancamento
            set
                rejeitado = ?,
                data_integracao = NOW(),
                integrado_por = ?
            where
                espresso_id = ?
        ', [1, $usuarioId, $espressoId]);
    }

    public static function excluir($espressoId, $usuarioId)
    {
        return DB::update('
            update
                espresso_lancamento
            set
                data_exclusao = NOW(),
                excluido_por = ?
            where
                espresso_id = ?
        ', [$usuarioId, $espressoId]);
    }

}
