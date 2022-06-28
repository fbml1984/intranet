<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcaoService;
use App\Services\PerfilService;
use App\Services\RotinaService;
use App\Services\SistemaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Yajra\DataTables\Facades\DataTables;

class RotinaController extends Controller
{

    private $rotinaService;

    private $sistemaService;

    private $acaoService;

    private $perfilService;

    public function __construct(RotinaService $rotinaService, SistemaService $sistemaService, AcaoService $acaoService, PerfilService $perfilService)
    {
        $this->rotinaService  = $rotinaService;
        $this->sistemaService = $sistemaService;
        $this->acaoService    = $acaoService;
        $this->perfilService  = $perfilService;
    }

    public function index()
    {
        return view('admin.rotina.index');
    }

    public function datatables()
    {
        return DataTables::of($this->rotinaService->listar())->toJson();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required',
            'key'   => 'required',
            'value' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'erro'     => true,
                'mensagem' => implode('<br/>', $validator->errors()->all()),
            ]);
        }
        try {
            $routine = $validator->validated();
            $_rotina = $this->rotinaService->obterPorId($id);
            if (empty($_rotina)) {
                throw new RuntimeException('Registro nao encontrado');
            }
            $this->rotinaService->atualizar($id, $routine);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registro atualizado com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }

    public function update_routines(): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $mensagem = [];
            $sistemas = $this->rotinaService->obterRotas();
            foreach ($sistemas as $sistema => $controllers) {
                $sistemaId = $this->sistemaService->obterOuCriar($sistema);
                foreach ($controllers as $rotina => $acoes) {
                    $rotinaId = $this->rotinaService->obterOuCriar($sistemaId, $rotina);
                    foreach ($acoes as $acao) {
                        $acaoId     = $this->acaoService->obterOuCriar($rotinaId, $acao);
                        $mensagem[] = "Ação {$acao} #{$acaoId} criada com sucesso na rotina {$rotina} #{$rotinaId} do sistema {$sistema} #{$sistemaId}";
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Registros atualizados com sucesso: ' . implode('<br/>', $mensagem));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function order(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rotinas' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'erro'     => true,
                'mensagem' => implode('<br/>', $validator->errors()->all()),
            ]);
        }
        try {
            $rotinas = $validator->validated();
            $rotinas = $rotinas['rotinas'];
            $this->rotinaService->orderLines($rotinas);
            return response()->json([
                'erro'     => false,
                'mensagem' => 'Registros atualizados com sucesso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
            ]);
        }
    }
}
