<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SIG extends Model
{
    public static function listar()
    {
        $query = "
            SELECT
                doc.id_arquivo id,
                dir.diretorio,
                doc.arquivo,
                FORMAT(doc.data_mapeamento, 'dd/MM/yyyy hh:mm:ss') data_mapeamento
            FROM
                controle_de_documentos.dbo.arquivos_mapeados doc,
                controle_de_documentos.dbo.diretorios_mapeados dir
            WHERE
                dir.id_diretorio = doc.id_diretorio
        ";
        return executeQuery($query);
    }

    public static function obterArquivoPorId($id)
    {
        $query = "
            SELECT
                id_arquivo id,
                arquivo nome,
                extensao
            FROM
                controle_de_documentos.dbo.arquivos_mapeados
            WHERE
                id_arquivo = {$id}
        ";
        return collect(executeQuery($query))->first();
    }

    public static function obterChave()
    {
        $chave = md5(microtime());
        $query = "INSERT INTO CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.SGO_CONTROLE_ACESSO_DOCUMENTOS(CHAVE) VALUES ('{$chave}')";
        executeQuery($query);
        return $chave;
    }

    public static function utilizar($chave)
    {
        $query = "DELETE FROM CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.SGO_CONTROLE_ACESSO_DOCUMENTOS where chave='{$chave}'";
        executeQuery($query);
    }
}
