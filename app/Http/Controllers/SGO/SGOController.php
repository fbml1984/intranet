<?php

namespace App\Http\Controllers\SGO;

use App\Http\Controllers\Controller;
use App\Models\SGO;
use Exception;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class SGOController extends Controller
{
    public function index()
    {
        return view('admin.sgo.index');
    }

    public function datatables()
    {
        return DataTables::of(SGO::listar())->toJson();
    }

    function list($idProjeto) {
        try {
            $documentos = SGO::obterDocumentosProjeto($idProjeto);
            $diretorios = [];
            foreach ($documentos as $documento) {
                $diretorios[$documento->DESCRICAO][] = $documento;
            }

            foreach ($diretorios as $diretorio) {
                usort($diretorio, function ($a, $b) {
                    $t1 = strtotime($a->CRIADOEM);
                    $t2 = strtotime($b->CRIADOEM);
                    return $t1 < $t2;
                });
            }

            ksort($diretorios);
            return collect([
                'erro'     => false,
                'mensagem' => null,
                'retorno'  => View::make('admin.sgo.includes.documentos', compact('diretorios'))->render(),
            ]);
        } catch (Exception $e) {
            return collect([
                'erro'     => true,
                'mensagem' => $e->getMessage(),
                'retorno'  => [],
            ]);
        }
    }

    public function view($idDiario, $idDocumento)
    {
        try {
            $chave = SGO::requisitar();
            return redirect()->away(env('API_URL') . "/controle_documentos/visualiza?diario={$idDiario}&documento={$idDocumento}&hash={$chave}");
        } catch (Exception $e) {
            dd($e);
        }
    }
}
