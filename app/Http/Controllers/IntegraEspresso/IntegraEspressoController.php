<?php

namespace App\Http\Controllers\IntegraEspresso;

use App\Http\Controllers\Controller;
use App\Services\IntegraEspressoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IntegraEspressoController extends Controller
{

    private $integraEspressoService;

    public function __construct(IntegraEspressoService $integraEspressoService)
    {
        $this->integraEspressoService = $integraEspressoService;
    }

    public function index()
    {
        return view('admin.integra_espresso.index');

    }

    public function adiantamentos()
    {
        return view('admin.integra_espresso.adiantamentos');

    }

    public function usuarios()
    {
        return view('admin.integra_espresso.usuarios');
    }

    public function tags()
    {
        return view('admin.integra_espresso.tags');
    }

    public function subcategorias()
    {
        return view('admin.integra_espresso.subcategorias');
    }

    public function datatables($origem)
    {
        switch ($origem) {
            case 'usuarios':
                return DataTables::of($this->integraEspressoService->obterUsuarios())->toJson();
            case 'tags':
                return DataTables::of($this->integraEspressoService->obterTags())->toJson();
            case 'subcategorias':
                return DataTables::of($this->integraEspressoService->obterSubcategorias())->toJson();
            default:
            case 'despesas':
                return DataTables::of($this->integraEspressoService->obterDespesas())->toJson();
            case 'adiantamentos':
                return DataTables::of($this->integraEspressoService->obterAdiantamentos())->toJson();

        }
    }

    public function atualizar_usuarios()
    {
        try {
            $parametros = [
                'filter[status]' => 1,
                'page[size]'     => 30,
            ];
            $this->integraEspressoService->atualizarUsuarios($parametros);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros obtidos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function atualizar_subcategorias()
    {
        try {
            $parametros = [
                'include'    => 'category',
                // 'filter[status]'         => 1,
                // 'filter[created_at][ge]' => '2022-06-01',
                // 'filter[created_at][le]' => '2022-06-10',
                'page[size]' => 30,
            ];
            $this->integraEspressoService->atualizarSubcategorias($parametros);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros obtidos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function atualizar_tags()
    {
        try {
            $parametros = [
                // 'filter[status]'         => 1,
                // 'filter[created_at][ge]' => '2022-06-01',
                // 'filter[created_at][le]' => '2022-06-10',
                'page[size]' => 30,
            ];
            $this->integraEspressoService->atualizarTags($parametros);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros obtidos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function atualizar_adiantamentos()
    {
        try {
            $parametros = [
                'include'                => 'user,report,team,cost_center_allocations',
                'filter[status]'         => 1,
                'filter[created_at][ge]' => '2022-06-01',
                'filter[created_at][le]' => '2022-06-10',
                'page[size]'             => 30,
            ];
            $this->integraEspressoService->atualizarAdiantamentos($parametros);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros obtidos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function atualizar_despesas()
    {
        try {
            $parametros = [
                'include'                => 'user,report,category,subcategory,tags',
                'filter[status]'         => 0, //Identificador do status: -1 ou 0 | Nome do status: rejected (-1), accepted (0)
                'filter[created_at][ge]' => '2022-06-01',
                'filter[created_at][le]' => date('Y-m-d'),
                'page[size]'             => 30,
            ];
            $this->integraEspressoService->atualizarDespesas($parametros);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros obtidos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => false,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function aprovar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registros' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'erro'     => true,
                'mensagem' => 'Parâmetros inválidos',
            ]);
        }
        try {
            $_request = $validator->validated();
            foreach ($_request['registros'] as $registro) {
                $this->integraEspressoService->aprovar($registro);
            }
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros aprovados com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function rejeitar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registros' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'erro'     => true,
                'mensagem' => 'Parâmetros inválidos',
            ]);
        }
        try {
            $_request = $validator->validated();
            foreach ($_request['registros'] as $registro) {
                $this->integraEspressoService->rejeitar($registro);
            }
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros rejeitados com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registros' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'erro'     => true,
                'mensagem' => 'Parâmetros inválidos',
            ]);
        }
        try {
            $_request = $validator->validated();
            foreach ($_request['registros'] as $registro) {
                $this->integraEspressoService->excluir($registro);
            }
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros excluídos com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

}
