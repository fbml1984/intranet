<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SGO extends Model
{

    public static function listar()
    {
        $query = "
            SELECT
                DOC.CODCOLIGADA,
                RTRIM(LTRIM(DOC.NOME))NOME,
                DOC.IDPRJ as id,
                DOC.CODPRJ,
                RTRIM(LTRIM(DOC.NOMEFANTASIA)) NOMEFANTASIA,
                DOC.STATUS,
                RTRIM(LTRIM(DOC.nome_arquivo)) nome_arquivo,
                RTRIM(LTRIM(DOC.usuario_arquivo)) usuario_arquivo,
                FORMAT(DOC.data_arquivo, 'dd/MM/yyyy hh:mm:ss') data_arquivo
            FROM
                CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.GS_VIEW_DOCUMENTACAO DOC
            ORDER
                BY DOC.data_arquivo DESC
        ";
        return executeQuery($query);
    }

    public static function obterDocumentosProjeto($idProjeto)
    {
        $query = "
            SELECT
                    MPRJDIARIODOC.IDDIARIO,
                    MPRJDIARIODOC.IDDOC,
                    MPRJDIARIODOC.IDPRJ,
                    MPRJDIARIODOC.DESCRICAO as NOME,
                    Lower(MPRJDIARIODOC.NOMEARQUIVO) as NOMEARQUIVO,
                    MCLASSDIARIO.CODCLASSDIARIO,
                    Lower(MCLASSDIARIO.DESCRICAO) as DESCRICAO,
                    MPRJDIARIODOC.RECCREATEDBY AS CRIADO,
                    MPRJDIARIODOC.RECCREATEDON AS CRIADOEM
                FROM
                    CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.MPRJDIARIODOC,
                    CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.MPRJDIARIO,
                    CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.MCLASSDIARIO
                WHERE 1 = 1
                    AND MPRJDIARIODOC.IDPRJ= MPRJDIARIO.IDPRJ
                    AND MPRJDIARIODOC.CODCOLIGADA = MPRJDIARIO.CODCOLIGADA
                    AND MPRJDIARIODOC.IDDIARIO = MPRJDIARIO.IDDIARIO
                    AND MPRJDIARIODOC.CODCOLIGADA = MCLASSDIARIO.CODCOLIGADA
                    AND MPRJDIARIO.CODCLASSDIARIO = MCLASSDIARIO.CODCLASSDIARIO
                    AND MPRJDIARIODOC.IDPRJ = {$idProjeto}
        ";
        return executeQuery($query);
    }

    public static function listarDocumentosDiretorios()
    {
        $query = '
            SELECT
                *
            FROM
                controle_de_documentos.dbo.arquivos_mapeados doc,
                controle_de_documentos.dbo.diretorios_mapeados dir
            WHERE
                dir.id_diretorio = doc.id_diretorio
        ';
        return executeQuery($query);
    }

    public static function requisitar()
    {
        $chave = md5(microtime());
        $query = "INSERT INTO CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.SGO_CONTROLE_ACESSO_DOCUMENTOS(CHAVE) VALUES ('{$chave}')";
        executeQuery($query);
        return $chave;
    }
}
