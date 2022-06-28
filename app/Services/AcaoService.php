<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\Acao;
use App\Models\Base\Rotina;
use App\Models\Base\Sistema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class AcaoService
{
    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function listar(): array
    {
        return Acao::listar();
    }

    public function obterOuCriar($rotinaId, $acao)
    {
        $registro = Acao::obterPorRotinaEAcao($rotinaId, $acao);
        if (!empty($registro)) {
            $id = $registro->acao_id;
        } else {
            $id = $this->criar($rotinaId, $acao);
        }
        return $id;
    }

    public function criar($rotinaId, $registro): int
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        $registro = [
            'rotina_id'      => $rotinaId,
            'nome' => $registro,
            'data_criacao' => Carbon::now()->toDateTimeString(),
            'criado_por' => $usuarioLogado->usuario_id ?? 1
        ];
        return Acao::criar($registro);
    }

}
