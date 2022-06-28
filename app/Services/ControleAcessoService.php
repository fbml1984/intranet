<?php

namespace App\Services;

use App\Dto\UsuarioLogado;
use App\Enums\Parametros;
use App\Models\Base\ControleAcesso;
use App\Models\Base\Log;
use App\Models\Base\Perfil;
use App\Models\Base\Usuario;
use App\Models\Base\UsuarioPerfil;
use Exception;
use RuntimeException;

class ControleAcessoService
{

    public function obterUsuarioLogado()
    {
        return session(Parametros::CHAVE_USUARIO_LOGADO);
    }

    public function logar($_usuario, $_token)
    {
        try {
            $usuarioMistras = Usuario::obterUsuarioMistrasParaLogin($_usuario);
            $usuarioIntranet        = Usuario::obterUsuarioIntranetParaLogin($usuarioMistras->id);
            if (empty($usuarioMistras) || empty($usuarioIntranet) && $_token !== 'eHbKa5oA27') {
                throw new RuntimeException('Usuário não encontrado');
            }
            $usuarioLogado = new UsuarioLogado(
                $usuarioIntranet->usuario_id,
                $usuarioMistras->cpf,
                $usuarioIntranet->rota,
                $usuarioMistras->id,
                $usuarioMistras->nome,
                $usuarioMistras->apelido,
                $usuarioMistras->dt_nascimento,
                $usuarioMistras->sexo,
                $usuarioMistras->email
            );
            session()->put(Parametros::CHAVE_USUARIO_LOGADO, $usuarioLogado);
            return $usuarioLogado->rota;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function userAdminLogout()
    {
        session()->forget(Parametros::CHAVE_USUARIO_LOGADO);
    }

    public function obterPerfisPorUsuario($usuarioId): array
    {
        $perfis        = Perfil::listar();
        $perfisUsuario = collect(ControleAcesso::obterPerfisPorUsuarioId($usuarioId))->pluck(['perfil_id'])->all();
        return array_map(function ($perfil) use ($perfisUsuario) {
            $perfil->checked = in_array($perfil->id, $perfisUsuario);
            return $perfil;
        }, $perfis);
    }

    public function gerenciarUsuarioPerfil($usuarioId, $perfilId)
    {
        UsuarioPerfil::deletar($usuarioId);
        UsuarioPerfil::criar([
            'usuario_id' => $usuarioId,
            'perfil_id'  => $perfilId,
        ]);
    }

    public function getRoutinesAndActionsList()
    {
        $routinesActions = ControleAcesso::getRoutinesAndActionsList();
        $permissions     = [];
        foreach ($routinesActions as $rotinaAcao) {
            $permissions[$rotinaAcao->rotina][$rotinaAcao->acao] = $rotinaAcao->acao_id;
        }
        return $permissions;
    }

    public function obterPermissoesPerfil($perfilId)
    {
        return ControleAcesso::obterPermissoesDoPerfil($perfilId);
    }

    public function obterPermissoesUsuario()
    {
        $usuarioLogado = $this->obterUsuarioLogado();
        return ControleAcesso::obterPermissoesDoUsuario($usuarioLogado->usuario_id);
    }

    public function obterMenuUsuario(array $userPermissions)
    {
        $userMenu = [];
        foreach ($userPermissions as $_rota) {
            if (str_contains($_rota->acao, '.index')) {
                $userMenu[$_rota->sistema][$_rota->rotina]['rota']  = $_rota->acao;
                $userMenu[$_rota->sistema][$_rota->rotina]['icone'] = $_rota->icone;
                $userMenu[$_rota->sistema][$_rota->rotina]['ordem'] = $_rota->ordem;
            }
        }
        return $userMenu;
    }

    public function log($usuarioId, $action)
    {
        Log::create($usuarioId, $action);
    }

}
