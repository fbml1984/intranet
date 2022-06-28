<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ControleAcessoService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    private $controleAcessoService;

    public function __construct(ControleAcessoService $controleAcessoService)
    {
        $this->controleAcessoService = $controleAcessoService;
    }

    public function index()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'usuario' => 'required',
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            try {
                $usuario = $validator->validated();
                $route   = $this->controleAcessoService->logar($usuario['usuario'], $usuario['token']);
                if (!empty($route)) {
                    return redirect()->route($route);
                }
                return redirect()->route('admin.login.index')->with('info', 'Login não implementado');
            } catch (Exception $e) {
                return redirect()->back()->withErrors('Usuário não encontrado')->withInput();
            }
        } else {
            return redirect()->route('admin.login.index');
        }
    }

    public function logout(): RedirectResponse
    {
        $this->controleAcessoService->userAdminLogout();
        return redirect()->route('admin.login.index');
    }
}
