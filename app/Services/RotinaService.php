<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\Rotina;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class RotinaService
{

    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function obterRotas()
    {
        $controllers = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('controller', $action)) {
                $controller = explode('\\', $action['controller']);
                //As rotas anotadas com o middleware rotina.sistema nao devem entrar no sistema como rotas que podem ser liberadas, pois,
                //fazem parte da manutencao do sistema ou possuem acesso obrigatorio a todos os perfis
                $middlewares = $action['middleware'];
                if (!in_array(Parametros::SYSTEM_ROUTINE, $middlewares) && in_array('menu', $middlewares)) {
                    $actions                                    = explode('@', $controller[sizeof($controller) - 1]);
                    $controllers[$controller[3]][$actions[0]][] = $action['as'];
                }
            }
        }
        return $controllers;
    }

    public function obterOuCriar($sistemaId, $rotina)
    {
        $_rotina = Rotina::obterPorController($rotina);
        if (!empty($_rotina)) {
            $rotinaId = $_rotina->rotina_id;
        } else {
            $rotinaId = $this->criar($sistemaId, $rotina);
        }
        return $rotinaId;
    }

    public function listar()
    {
        return Rotina::listar();
    }

    public function obterPorId($id)
    {
        return Rotina::obterPorId($id);
    }

    public function obterPorController($controller)
    {
        return Rotina::obterPorController($controller);
    }

    public function obterPorAcao($acao)
    {
        return Rotina::obterPorAcao($acao);
    }

    public function criar($sistemaId, $registro): int
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        $registro      = [
            'sistema_id'     => $sistemaId,
            'nome'           => str_replace('Controller', '', $registro),
            'controller'     => $registro,
            'icone'          => null,
            'ordem'          => 0,
            'data_criacao'   => Carbon::now()->toDateTimeString(),
            'criado_por'     => $usuarioLogado->usuario_id ?? 1,
            'data_alteracao' => Carbon::now()->toDateTimeString(),
            'alterado_por'   => $usuarioLogado->usuario_id ?? 1,
        ];
        return Rotina::criar($registro);
    }

    public function atualizar($id, $registro)
    {
        $registro = [
            $registro['key'] => $registro['value'],
            'data_alteracao' => Carbon::now()->toDateTimeString(),
        ];
        Rotina::atualizar($id, $registro);
    }

    public function obterAcaoPorNomeERotinaId($action, $routineId)
    {
        return Rotina::getActionLineByRoutineIdAndActionName($routineId, $action);
    }

    public function createActionForRoutine($action, $routineId)
    {
        return Rotina::createActionForRoutine($routineId, $action);
    }

    public function orderLines($routines)
    {
        foreach ($routines as $rotina) {
            Rotina::updateOrder($rotina['ordem'], $rotina['id']);
        }
    }

}
