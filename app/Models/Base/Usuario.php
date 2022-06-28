<?php

namespace App\Models\Base;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Usuario
{

    public static function obterUsuarioIntranetParaLogin($codigo)
    {
        return collect(DB::select('
            select
                distinct usu.usuario_id,
                per.rota
            from
                usuario usu,
                usuario_perfis usp,
                perfil per
            where
                1 = 1
                and usp.usuario_id = usu.usuario_id
                and usp.perfil_id = per.perfil_id
                and usu.codigo_rm = ?
        ', [$codigo]))->first();
    }

    public static function obterUsuarioMistrasParaLogin($usuario)
    {
        return collect(executeQuery(
            "
                SELECT
                    *
                FROM
                    (SELECT
                        FO.CODCFO id,
                        FO.NOME nome,
                        FO.CGCCFO cpf,
                        PE.APELIDO apelido,
                        (CASE WHEN PE.SEXO = 'M' THEN 'MASCULINO' WHEN PE.SEXO = 'F' THEN 'FEMININO' ELSE 'NAO INFORMADO' END) sexo,
                        RTRIM(LTRIM(COALESCE(PE.EMAILPESSOAL, PE.EMAIL))) email,
                        FORMAT(PE.DTNASCIMENTO, 'dd/MM/yyyy') dt_nascimento
                    FROM
                        (SELECT REPLACE(REPLACE(REPLACE(FOR_.CGCCFO, '-', ''), '.', ''), '/', '') CGCCFO, FOR_.NOME, MAX(FOR_.CODCFO) CODCFO FROM CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.FCFO FOR_ WHERE FOR_.CGCCFO IS NOT NULL AND FOR_.ATIVO = 1 GROUP BY FOR_.CGCCFO, FOR_.NOME) FO
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PPESSOA PE ON (REPLACE(REPLACE(REPLACE(FO.CGCCFO, '-', ''), '.', ''), '/', '') = PE.CPF)
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PFUNC FU ON (FU.CODPESSOA = PE.CODIGO AND FU.CODSITUACAO = 'A')
                    WHERE
                        1 = 1
                    ) USU
                WHERE
                    (USU.cpf = '{$usuario}' OR USU.email = '{$usuario}')
            "
        ))->first();
    }

    public static function listar(): Collection
    {
        return collect(executeQuery(
            "
                SELECT
                    USU.ID id,
                    USU.NOME nome,
                    USU.CPF cpf,
                    USU.APELIDO apelido,
                    USU.SEXO sexo,
                    USU.EMAIL email,
                    USU.DT_NASCIMENTo data_nascimento,
                    USU.DATA_CRIACAO data_criacao,
                    USU.DATA_ALTERACAO data_alteracao
                FROM (
                        SELECT
                        FO.CODCFO ID,
                        FO.NOME,
                        FO.CGCCFO CPF,
                        PE.APELIDO,
                        (CASE WHEN PE.SEXO = 'M' THEN 'MASCULINO' WHEN PE.SEXO = 'F' THEN 'FEMININO' ELSE 'NAO INFORMADO' END) SEXO,
                        RTRIM(LTRIM(COALESCE(PE.EMAILPESSOAL, PE.EMAIL))) EMAIL,
                        FORMAT(PE.DTNASCIMENTO, 'dd/MM/yyyy') DT_NASCIMENTO,
                        FORMAT(GETDATE(), 'dd/MM/yyyy') DATA_CRIACAO,
                        FORMAT(GETDATE(), 'dd/MM/yyyy') DATA_ALTERACAO
                    FROM
                        (SELECT REPLACE(REPLACE(REPLACE(FOR_.CGCCFO, '-', ''), '.', ''), '/', '') CGCCFO, FOR_.NOME, MAX(FOR_.CODCFO) CODCFO FROM CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.FCFO FOR_ WHERE FOR_.CGCCFO IS NOT NULL AND FOR_.ATIVO = 1 AND FOR_.CODCOLIGADA = 3 GROUP BY FOR_.CGCCFO, FOR_.NOME) FO
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PPESSOA PE ON (FO.CGCCFO = PE.CPF)
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PFUNC FU ON (FU.CODPESSOA = PE.CODIGO AND FU.CODSITUACAO = 'A')
                    WHERE
                        1 = 1
                    UNION
                    SELECT
                        FO.CODCFO ID,
                        FO.NOME,
                        FO.CGCCFO CPF,
                        PE.APELIDO,
                        (CASE WHEN PE.SEXO = 'M' THEN 'MASCULINO' WHEN PE.SEXO = 'F' THEN 'FEMININO' ELSE 'NAO INFORMADO' END) SEXO,
                        RTRIM(LTRIM(COALESCE(PE.EMAILPESSOAL, PE.EMAIL))) EMAIL,
                        FORMAT(PE.DTNASCIMENTO, 'dd/MM/yyyy') DT_NASCIMENTO,
                        FORMAT(GETDATE(), 'dd/MM/yyyy') DATA_CRIACAO,
                        FORMAT(GETDATE(), 'dd/MM/yyyy') DATA_ALTERACAO
                    FROM
                        (SELECT REPLACE(REPLACE(REPLACE(FOR_.CGCCFO, '-', ''), '.', ''), '/', '') CGCCFO, FOR_.NOME, MAX(FOR_.CODCFO) CODCFO FROM CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.FCFO FOR_ WHERE FOR_.CGCCFO IS NOT NULL AND FOR_.ATIVO = 1 AND FOR_.CODCOLIGADA = 0 GROUP BY FOR_.CGCCFO, FOR_.NOME) FO
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PPESSOA PE ON (FO.CGCCFO = PE.CPF)
                        LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PFUNC FU ON (FU.CODPESSOA = PE.CODIGO AND FU.CODSITUACAO = 'A')
                    WHERE
                        1 = 1
                        AND FO.CODCFO = 08737

                ) USU ORDER BY USU.NOME
            "
        ));
    }

    public static function obterUsuarioMistrasPorCodigoFornecedor($codigo)
    {
        return collect(executeQuery("
            SELECT
                FO.CODCFO id,
                FO.NOME nome,
                FO.CGCCFO cpf,
                PE.APELIDO apelido,
                (CASE WHEN PE.SEXO = 'M' THEN 'MASCULINO' WHEN PE.SEXO = 'F' THEN 'FEMININO' ELSE 'NAO INFORMADO' END) sexo,
                RTRIM(LTRIM(COALESCE(PE.EMAILPESSOAL, PE.EMAIL))) email,
                FORMAT(PE.DTNASCIMENTO, 'dd/MM/yyyy') dt_nascimento
            FROM
                (SELECT REPLACE(REPLACE(REPLACE(FOR_.CGCCFO, '-', ''), '.', ''), '/', '') CGCCFO, FOR_.NOME, MAX(FOR_.CODCFO) CODCFO FROM CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.FCFO FOR_ WHERE FOR_.CGCCFO IS NOT NULL AND FOR_.ATIVO = 1 GROUP BY FOR_.CGCCFO, FOR_.NOME) FO
                LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PPESSOA PE ON (FO.CGCCFO = PE.CPF)
                LEFT JOIN CORPORERM_CLOUD.CI30A5_128116_RM_PD.DBO.PFUNC FU ON (FU.CODPESSOA = PE.CODIGO AND FU.CODSITUACAO = 'A')
            WHERE
                1 = 1
                AND FO.CODCFO = {$codigo}
        "))->first();
    }

    public static function obterUsuarioIntranetPorCodigoFornecedor($codigo)
    {
        return collect(DB::select('
            select
                *
            from
                usuario
            where
                1 = 1
                and codigo_rm = ?
        ', [$codigo]))->first();
    }

    public static function obterUsuarioIntranetPorCodigo($codigo)
    {
        return DB::table('usuario')
            ->where('codigo_rm', $codigo)
            ->get()
            ->first();
    }

    public static function criarUsuarioIntranet($registro)
    {
        if (empty($usuario)) {
            DB::table('usuario')->insert($registro);
        }
    }

}
