<?php

namespace App\Http\Controllers\SIG;

use App\Http\Controllers\Controller;
use App\Models\SIG;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class SIGController extends Controller
{
    public function index()
    {
        return view('admin.sig.index');
    }

    public function datatables()
    {
        return DataTables::of(SIG::listar())->toJson();
    }

    public function view($id)
    {
        try {
            $_arquivo = SIG::obterArquivoPorId($id);
            $chave    = SIG::obterChave();
            $url      = env('API_URL') . "/documentos/arquivo?arquivo_id={$id}&hash={$chave}";
            $arquivo  = file_get_contents($url);
            $base64   = true;

            if (empty($_arquivo) || empty($_arquivo)) {
                //throw
            }

            abrirArquivo($_arquivo->nome, $_arquivo->extensao, $arquivo, $base64);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
