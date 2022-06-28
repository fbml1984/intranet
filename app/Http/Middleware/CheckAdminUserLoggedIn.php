<?php

namespace App\Http\Middleware;

use App\Services\ControleAcessoService;
use Closure;
use Illuminate\Http\Request;

class CheckAdminUserLoggedIn
{
    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function handle(Request $request, Closure $next)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        if (empty($usuarioLogado)) {
            return redirect()->route('admin.login.index');
        }
        return $next($request);
    }
}
