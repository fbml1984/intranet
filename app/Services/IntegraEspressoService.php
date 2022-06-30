<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\EspressoLancamento;
use App\Models\Base\EspressoSubcategoria;
use App\Models\Base\EspressoTag;
use App\Models\Base\EspressoUsuario;
use Carbon\Carbon;
use Exception;
use RuntimeException;

class IntegraEspressoService
{
    private $controleAcessoService;

    private $espressoAPIService;

    public function __construct(ControleAcessoService $controleAcessoService, EspressoAPIService $espressoAPIService)
    {
        $this->controleAcessoService = $controleAcessoService;
        $this->espressoAPIService    = $espressoAPIService;
    }

    public function obterUsuarios()
    {
        return EspressoUsuario::listar();

    }

    public function atualizarUsuarios($parametros)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $registros = $this->espressoAPIService->usuarios($parametros);
            $this->_criarUsuarios($registros, $usuarioLogado);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function obterSubcategorias()
    {
        return EspressoSubcategoria::listar();
    }

    public function atualizarSubcategorias($parametros)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $registros = $this->espressoAPIService->subcategorias($parametros);
            $this->_criarSubcategorias($registros, $usuarioLogado);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function obterTags()
    {
        return EspressoTag::listar();
    }

    public function atualizarTags($parametros)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $registros = $this->espressoAPIService->tags($parametros);
            $this->_criarTags($registros, $usuarioLogado);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function obterDespesas()
    {
        return EspressoLancamento::listarPorTipo(Parametros::INTEGRA_TIPO_REGISTRO_DESPESA);
    }

    public function atualizarDespesas($parametros)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $registros = $this->espressoAPIService->despesas($parametros);
            $this->_criarLancamentos($registros, $usuarioLogado, Parametros::INTEGRA_TIPO_REGISTRO_DESPESA);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function obterAdiantamentos()
    {
        return EspressoLancamento::listarPorTipo(Parametros::INTEGRA_TIPO_ADIANTAMENTO);
    }

    public function atualizarAdiantamentos($parametros)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $registros = $this->espressoAPIService->adiantamentos($parametros);
            $this->_criarLancamentos($registros, $usuarioLogado, Parametros::INTEGRA_TIPO_ADIANTAMENTO);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function aprovar($registroId)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            EspressoLancamento::aprovar($registroId, $usuarioLogado->usuario_id ?? 1);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function rejeitar($registroId)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            EspressoLancamento::rejeitar($registroId, $usuarioLogado->usuario_id ?? 1);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    public function excluir($registroId)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            EspressoLancamento::excluir($registroId, $usuarioLogado->usuario_id ?? 1);
        } catch (Exception $e) {
            throw new RuntimeException($e);
        }
    }

    private function _criarUsuarios($registros, $usuarioLogado)
    {
        foreach ($registros as $registro) {
            $atributos = $registro['attributes'];
            $registro  = [
                'espresso_usuario_id' => $registro['id'],
                'nome'                => $atributos['name'],
                'email'               => $atributos['email'],
                'cpf'                 => $atributos['cpf'],
                'status'              => $atributos['status'],
                'data_criacao'        => Carbon::now()->toDateTimeString(),
                'criado_por'          => $usuarioLogado->usuario_id ?? 1,
            ];
            EspressoUsuario::criarSeNaoExistir($registro);
        }
    }

    private function _criarSubcategorias($registros, $usuarioLogado)
    {
        foreach ($registros as $registro) {
            $atributos = $registro['attributes'];
            $registro  = [
                'espresso_subcategoria_id' => $registro['id'],
                'nome'                     => $atributos['name'],
                'status'                   => $atributos['status'],
                'data_criacao'             => Carbon::now()->toDateTimeString(),
                'criado_por'               => $usuarioLogado->usuario_id ?? 1,
            ];
            EspressoSubcategoria::criarSeNaoExistir($registro);
        }
    }

    private function _criarTags($registros, $usuarioLogado)
    {
        foreach ($registros as $registro) {
            $atributos = $registro['attributes'];
            $registro  = [
                'espresso_tag_id' => $registro['id'],
                'nome'            => $atributos['name'],
                'status'          => $atributos['status'],
                'data_criacao'    => Carbon::now()->toDateTimeString(),
                'criado_por'      => $usuarioLogado->usuario_id ?? 1,
            ];
            EspressoTag::criarSeNaoExistir($registro);
        }
    }

    private function _criarLancamentos($registros, $usuarioLogado, $tipo)
    {
        foreach ($registros as $registro) {
            $atributos       = $registro['attributes'] ?? [];
            $relacionamentos = $registro['relationships'] ?? [];

            $_tags = isset($relacionamentos['tags']['data']) ? count($relacionamentos['tags']['data']) : 1;

            $_value     = isset($atributos['value']) && !empty($atributos['value']) ? $atributos['value'] : 0;
            $_custo     = isset($atributos['cost']) && !empty($atributos['cost']) ? $atributos['cost'] : $_value;
            $_custoBase = isset($atributos['base_cost']) && !empty($atributos['base_cost']) ? $atributos['base_cost'] : 0;
            $custo      = $_custo > 0 && $_tags > 0 ? $_custo / $_tags : $_custo;
            $custoBase  = $_custoBase > 0 && $_tags > 0 ? $_custoBase / $_tags : $_custoBase;

            $espressoId        = (int) $registro['id'];
            $espressoUsuarioId = (int) $relacionamentos['user']['data']['id'];
            $tagId             = !empty($relacionamentos['tags']['data'][0]['id'] ?? null) ? (int) $relacionamentos['tags']['data'][0]['id'] : null;
            $subcategoriaId    = !empty($relacionamentos['subcategory']['data']['id'] ?? null) ? (int) $relacionamentos['subcategory']['data']['id'] : null;
            $relatorioId       = !empty($relacionamentos['report']['data']['id'] ?? null) ? (int) $relacionamentos['report']['data']['id'] : null;
            $data              = $atributos['performed_at'] ?? $atributos['date'] ?? null;
            $custo             = $custo;
            $custoBase         = $custoBase;
            $urlAnexo          = $atributos['attachment_url'] ?? null;

            $registro = [
                'espresso_id'              => $espressoId,
                'espresso_usuario_id'      => $espressoUsuarioId,
                'espresso_tag_id'          => $tagId,
                'espresso_subcategoria_id' => $subcategoriaId,
                'espresso_relatorio_id'    => $relatorioId,
                'tipo'                     => $tipo,
                'data'                     => $data,
                'custo'                    => $custo,
                'custo_base'               => $custoBase,
                'url_anexo'                => $urlAnexo,
                'movimento_id'             => null,
                'item_movimento_id'        => null,
                'rejeitado'                => 0,
                'data_criacao'             => Carbon::now()->toDateTimeString(),
                'criado_por'               => $usuarioLogado->usuario_id ?? 1,
                'data_integracao'          => null,
                'integrado_por'            => null,
            ];

            EspressoLancamento::criarSeNaoExistir($registro);
        }
    }

}
