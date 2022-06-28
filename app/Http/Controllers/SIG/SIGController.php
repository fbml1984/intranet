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
            $chave = SIG::requisitar();
            return redirect()->away(env('API_URL') . "/controle_documentos/sgd?id={$id}&hash={$chave}");
        } catch (Exception $e) {
            dd($e);
        }
    }
}
