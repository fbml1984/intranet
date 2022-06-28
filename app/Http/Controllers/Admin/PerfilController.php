<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Parametros;
use App\Http\Controllers\Controller;
use App\Services\ControleAcessoService;
use App\Services\PerfilService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PerfilController extends Controller
{

    private $perfilService;
    private $controleAcessoService;

    public function __construct(PerfilService $perfilService, ControleAcessoService $controleAcessoService)
    {
        $this->perfilService         = $perfilService;
        $this->controleAcessoService = $controleAcessoService;
    }

    public function index()
    {
        return view('admin.perfil.index');
    }

    public function datatables()
    {
        return DataTables::of($this->perfilService->listar())->toJson();
    }

    public function create()
    {
        return view('admin.perfil.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
            'rota' => 'required|max:50',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $registro = $validator->validated();
            $id       = $this->perfilService->criar(
                $registro['nome'],
                $registro['rota'],
                Parametros::PODE_SER_EXCLUIDO
            );
            return redirect()->route('admin.perfil.edit', $id)->with('success', 'Registro criado com sucesso. Agora, defina as permissões');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $perfil = $this->perfilService->obterPorPerfilId($id);
        if (empty($perfil)) {
            return redirect()->route('admin.perfil.index')->withErrors('Registro não encontrado');
        }
        $permissoesPerfil = $this->controleAcessoService->obterPermissoesPerfil($id);
        $permissoes       = $this->controleAcessoService->getRoutinesAndActionsList();
        return view('admin.perfil.edit')->with(compact('perfil', 'permissoesPerfil', 'permissoes'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome'         => 'required|max:100',
            'rota'         => 'required|max:50',
            'perfil_acoes' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $perfil = $validator->validated();
            $this->perfilService->atualizar($id, $perfil);
            DB::commit();
            return redirect()->back()->with('success', 'Registro atualizado com sucesso');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $_registro = $this->perfilService->obterPorPerfilId($id);
            if (empty($_registro)) {
                if (request()->ajax()) {
                    return response()->json([
                        'erro'     => true,
                        'mensagem' => 'Registro não encontrado',
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Registro não encontrado');
                }
            }
            if ($_registro->pode_ser_excluido == Parametros::NAO_PODE_SER_EXCLUIDO) {
                if (request()->ajax()) {
                    return response()->json([
                        'erro'     => true,
                        'mensagem' => 'Este registro não pode ser excluído',
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Este registro não pode ser excluído');
                }
            }
            $this->perfilService->deletar($id);
            if (request()->ajax()) {
                return response()->json([
                    'erro'     => false,
                    'mensagem' => 'Registro excluído com sucesso',
                ]);
            } else {
                return redirect()->back()->with('message', 'Registro excluído com sucesso');
            }
        } catch (Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'erro'     => true,
                    'mensagem' => $e->getMessage(),
                ]);
            } else {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }
}
