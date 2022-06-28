<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SystemRoutine
{
    public function handle(Request $request, Closure $next)
    {
        //Nao precisa ter logica, serve apenas como identificador de uma rota que pertence as rotinas padroes do sistema
        return $next($request);
    }
}
