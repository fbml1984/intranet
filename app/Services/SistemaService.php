<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\Sistema;
use Carbon\Carbon;

class SistemaService
{
    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function obterOuCriar($sistema)
    {
        $registro = Sistema::obterPorNamespace($sistema);
        if (!empty($registro)) {
            $id = $registro->sistema_id;
        } else {
            $id = $this->criar($sistema);
        }
        return $id;
    }

    public function criar($registro): int
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        $registro = [
            'nome'      => $registro,
            'namespace' => $registro,
            'icone'     => null,
            'ordem'     => 0,
            'data_criacao' => Carbon::now()->toDateTimeString(),
            'criado_por' => $usuarioLogado->usuario_id ?? Parametros::ID_USUARIO_SISTEMA,
            'data_alteracao' => Carbon::now()->toDateTimeString(),
            'alterado_por' => $usuarioLogado->usuario_id ?? Parametros::ID_USUARIO_SISTEMA,
        ];
        return Sistema::create($registro);
    }
}
