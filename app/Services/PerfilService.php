<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\ControleAcesso;
use App\Models\Base\Perfil;
use App\Models\Base\PerfilAcao;
use Carbon\Carbon;

class PerfilService
{
    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function listar(): array
    {
        return Perfil::listar();
    }

    public function obterPorPerfilId($id)
    {
        return Perfil::obterPorPerfilId($id);
    }

    public function criar($nomePerfil, $rota, $podeSerExcluido): int
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();
        $registro      = [
            'nome'              => $nomePerfil,
            'rota'              => $rota,
            'pode_ser_excluido' => $podeSerExcluido,
            'data_criacao'      => Carbon::now()->toDateTimeString(),
            'criado_por'        => $usuarioLogado->usuario_id ?? Parametros::ID_USUARIO_SISTEMA,
            'data_alteracao'    => Carbon::now()->toDateTimeString(),
            'alterado_por'      => $usuarioLogado->usuario_id ?? Parametros::ID_USUARIO_SISTEMA,
        ];
        return Perfil::criar($registro);
    }

    public function atualizar($id, $registro)
    {

        $permissoes    = $registro['perfil_acoes'] ?? [];
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();

        $registro = [
            'nome'           => $registro['nome'],
            'rota'           => $registro['rota'],
            'data_alteracao' => Carbon::now()->toDateTimeString(),
            'alterado_por'   => $usuarioLogado->usuario_id,
        ];
        Perfil::atualizar($id, $registro);

        if (!empty($permissoes)) {
            $permissoesPerfil = $this->controleAcessoService->obterPermissoesPerfil($id);
            sort($permissoesPerfil);
            sort($permissoes);
            foreach ($permissoesPerfil as $acaoId) {
                if (!in_array($acaoId, $permissoes)) {
                    PerfilAcao::deletar($id, $acaoId);
                    unset($permissoesPerfil[$acaoId]);
                }
            }
            foreach ($permissoes as $acaoId) {
                if (!in_array($acaoId, $permissoesPerfil)) {
                    ControleAcesso::criarPerfilAcao($id, $acaoId);
                }
            }
        }
    }

    public function deletar($id)
    {
        $usuarioLogado = $this->controleAcessoService->obterUsuarioLogado();

        $registro = [
            'data_exclusao' => Carbon::now()->toDateTimeString(),
            'excluido_por'  => $usuarioLogado->usuario_id,
        ];
        Perfil::atualizar($id, $registro);
    }

    public function obterOuCriar($nomePerfil): int
    {
        $registro = Perfil::obterPorNome($nomePerfil);
        if (!empty($registro)) {
            $id = $registro->perfil_id;
        } else {
            $id = $this->criar($nomePerfil, 'admin.usuario.index', Parametros::NAO_PODE_SER_EXCLUIDO);
        }
        return $id;
    }

}
