<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ControleAcessoService;
use App\Services\PerfilService;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsuarioController extends Controller
{

    private $usuarioService;
    private $controleAcessoService;
    private $profileService;

    public function __construct(UsuarioService $usuarioService, ControleAcessoService $controleAcessoService, PerfilService $profileService)
    {
        $this->usuarioService        = $usuarioService;
        $this->controleAcessoService = $controleAcessoService;
        $this->perfilService         = $profileService;
    }

    public function index()
    {
        return view('admin.usuario.index');
    }

    /**
     * @throws Exception
     */
    public function datatables()
    {
        $usuarios = $this->usuarioService->listar();
        $this->usuarioService->gerenciarUsuarios($usuarios);
        return DataTables::of($usuarios)->toJson();
    }

    public function view($id)
    {
        $_usuarioRM = $this->usuarioService->obterUsuarioMistrasPorCodigoFornecedor($id);
        if (empty($_usuarioRM)) {
            return redirect()->route('admin.usuario.index')->withErrors('Registro não encontrado');
        }
        $_usuarioSistema = $this->usuarioService->obterUsuarioIntranetPorCodigoFornecedor($id);
        $usuario         = array_merge(collect($_usuarioRM)->toArray(), collect($_usuarioSistema)->toArray());
        return view('admin.usuario.view')->with(compact('usuario'));
    }

    public function edit($id)
    {
        $_usuarioRM = $this->usuarioService->obterUsuarioMistrasPorCodigoFornecedor($id);
        if (empty($_usuarioRM)) {
            return redirect()->route('admin.usuario.index')->withErrors('Registro não encontrado');
        }
        $_usuarioSistema = $this->usuarioService->obterUsuarioIntranetPorCodigoFornecedor($id);
        $usuario         = array_merge(collect($_usuarioRM)->toArray(), collect($_usuarioSistema)->toArray());
        $perfis          = $this->controleAcessoService->obterPerfisPorUsuario($usuario['usuario_id']);
        return view('admin.usuario.edit')->with(compact('usuario', 'perfis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id'     => 'required',
            'cpf'            => 'required',
            'usuario_perfil' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $registro = $request->all();
            $this->usuarioService->atualizar($id, $registro);
            if (isset($registro['usuario_perfil'])) {
                $this->controleAcessoService->gerenciarUsuarioPerfil($registro['usuario_id'], $registro['usuario_perfil']);
            }
            return redirect()->back()->with('success', 'Registro atualizado com sucesso');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function set_profile()
    {
        //
    }

}
