<?php

namespace App\Http\Middleware;

use App\Enums\Parametros;
use App\Services\AcaoService;
use App\Services\ControleAcessoService;
use App\Services\RotinaService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class ValidateUserPermission
{
    private $controleAcessoService;
    private $rotinaService;
    private $acaoService;

    public function __construct(ControleAcessoService $controleAcessoService, RotinaService $rotinaService, AcaoService $acaoService)
    {
        $this->controleAcessoService = $controleAcessoService;
        $this->rotinaService         = $rotinaService;
        $this->acaoService           = $acaoService;
    }

    public function handle(Request $request, Closure $next)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        try {
            $permissoesUsuario = $this->controleAcessoService->obterPermissoesUsuario();
            if (empty($permissoesUsuario)) {
                return redirect()->back()->withErrors('Usuário sem permissões no sistema')->withInput();
            }
            $action  = Route::getCurrentRoute()->getAction();
            $_action = $action['as'];
            $this->controleAcessoService->log($usuarioLogado->usuario_id, $_action);
            $rotina            = $this->rotinaService->obterPorAcao($_action);
            $nomeRotina        = $rotina->nome;
            $userMenu          = $this->controleAcessoService->obterMenuUsuario($permissoesUsuario);
            $permissoesUsuario = collect($permissoesUsuario)->pluck(['acao'])->toArray();
            if (in_array($_action, $permissoesUsuario) || in_array(Parametros::SYSTEM_ROUTINE, $action['middleware'])) {
                View::share(compact('userMenu', 'nomeRotina', 'permissoesUsuario'));
                return $next($request);
            }
        } catch (Exception $e) {
            dd($e);
            return redirect()->route($usuarioLogado->getRota())->with('error', $e->getMessage());
        }
        return redirect()->route($usuarioLogado->getRota())->with('error', 'Você não tem permissão para executar esta ação');
    }
}
