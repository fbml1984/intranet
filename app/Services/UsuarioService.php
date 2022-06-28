<?php

namespace App\Services;

use App\Enums\Parametros;
use App\Models\Base\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UsuarioService
{
    public function listar(): Collection
    {
        return Usuario::listar();
    }

    public function obterUsuarioIntranetPorCodigoFornecedor($codigo)
    {
        return Usuario::obterUsuarioIntranetPorCodigoFornecedor($codigo);
    }

    public function obterUsuarioMistrasPorCodigoFornecedor($codigo)
    {
        return Usuario::obterUsuarioMistrasPorCodigoFornecedor($codigo);
    }

    public function atualizar($id, $registro)
    {
        //
    }

    public function gerenciarUsuarios($usuarios)
    {
        foreach ($usuarios as $usuario) {
            $_usuario = Usuario::obterUsuarioIntranetPorCodigo($usuario->id);
            if (empty($_usuario)) {
                Usuario::criarUsuarioIntranet([
                    'codigo_rm'      => $usuario->id,
                    'data_criacao'   => Carbon::now()->toDateTimeString(),
                    'criado_por'     => Parametros::ID_USUARIO_SISTEMA,
                    'data_alteracao' => Carbon::now()->toDateTimeString(),
                    'alterado_por'   => Parametros::ID_USUARIO_SISTEMA,
                ]);
            }
        }
    }

}
