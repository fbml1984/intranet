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

            $chave   = SIG::obterChave();
            $url     = env('API_URL') . "/controle_documentos/sgd_v2?id={$id}&hash={$chave}";
            $arquivo = file_get_contents($url);

            if (!empty($arquivo)) {

                $_arquivo = SIG::obterArquivoPorId($id);
                if (empty($_arquivo)) {
                    //throw
                }

                switch ($_arquivo->extensao) {
                    case "pdf":
                        header("Content-type: application/pdf");
                        break;
                    case "doc":
                    case "docx":
                        header('Content-Type: application/octet-stream');
                        break;
                    case "xls":
                    case "xlsx":
                        header('Content-Type: application/vnd.ms-excel');
                        break;
                }
                header("Content-Disposition: inline; filename=\"{$_arquivo->arquivo}\"");
                echo file_get_contents("data://application/{$_arquivo->extensao};base64," . $arquivo);
                die;
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}
