<?php

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\PerfilController as AdminPerfilController;
use App\Http\Controllers\Admin\RotinaController as AdminRotinaController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\IntegraEspresso\IntegraEspressoController as IntegraEspressoController;
use App\Http\Controllers\SGO\SGOController as SGOController;
use App\Http\Controllers\SIG\SIGController as SIGController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//O middleware logado controla o acesso as areas do sistema apenas para usuarios que estejam logados
//O middleware usuario.permission controla o acesso do usuario logado as areas do sistema que ele possui ControleAcesso
//O middleware rotina.sistema controlas as URLs comuns que nao precisam de permissao para serem acessadas

Route::get('/rotinas/atualizar', [AdminRotinaController::class, 'update_routines'])->name('rotina.update_routines')->middleware('rotina.sistema');

Route::prefix('admin')->middleware('menu')->group(function () {

    Route::prefix('sgo')->group(function () {
        Route::name('sgo.')->group(function () {
            Route::middleware(['usuario.admin.authenticated', 'usuario.permission'])->group(function () {
                Route::get('/', [SGOController::class, 'index'])->name('index');
                Route::get('/datatables', [SGOController::class, 'datatables'])->name('datatables');
                Route::get('/{idPrj}/documentos', [SGOController::class, 'list'])->name('documentos.listar');
                Route::get('/diario/{idDiario}/documento/{idDocumento}', [SGOController::class, 'view'])->name('documento.visualizar');
            });
        });
    });

    Route::prefix('sig')->group(function () {
        Route::name('sig.')->group(function () {
            Route::middleware(['usuario.admin.authenticated', 'usuario.permission'])->group(function () {
                Route::get('/', [SIGController::class, 'index'])->name('index');
                Route::get('/datatables', [SIGController::class, 'datatables'])->name('datatables');
                Route::get('/documento/{id}', [SIGController::class, 'view'])->name('documento.visualizar');
            });
        });
    });

    Route::prefix('integra-espresso')->group(function () {
        Route::name('integra_espresso.')->group(function () {
            Route::middleware(['usuario.admin.authenticated', 'usuario.permission'])->group(function () {
                //
                Route::get('/atualizar-usuarios', [IntegraEspressoController::class, 'atualizar_usuarios'])->name('atualizar_usuarios');
                Route::get('/atualizar-subcategorias', [IntegraEspressoController::class, 'atualizar_subcategorias'])->name('atualizar_subcategorias');
                Route::get('/atualizar-tags', [IntegraEspressoController::class, 'atualizar_tags'])->name('atualizar_tags');
                Route::get('/atualizar-despesas', [IntegraEspressoController::class, 'atualizar_despesas'])->name('atualizar_despesas');
                Route::get('/atualizar-adiantamentos', [IntegraEspressoController::class, 'atualizar_adiantamentos'])->name('atualizar_adiantamentos');
                //
                Route::get('/', [IntegraEspressoController::class, 'index'])->name('index');
                Route::get('/datatables/{origem}', [IntegraEspressoController::class, 'datatables'])->name('datatables');
                Route::post('/aprovar', [IntegraEspressoController::class, 'aprovar'])->name('aprovar');
                Route::post('/rejeitar', [IntegraEspressoController::class, 'rejeitar'])->name('rejeitar');
                Route::post('/excluir', [IntegraEspressoController::class, 'destroy'])->name('destroy');
                Route::get('/usuarios', [IntegraEspressoController::class, 'usuarios'])->name('usuarios');
                Route::get('/subcategorias', [IntegraEspressoController::class, 'subcategorias'])->name('subcategorias');
                Route::get('/tags', [IntegraEspressoController::class, 'tags'])->name('tags');
                Route::get('/despesas', [IntegraEspressoController::class, 'index'])->name('despesas');
                Route::get('/adiantamentos', [IntegraEspressoController::class, 'adiantamentos'])->name('adiantamentos');
            });
        });
    });

    Route::name('admin.')->group(function () {

        Route::name('login.')->group(function () {
            Route::get('/', [AdminLoginController::class, 'index'])->name('index')->middleware('rotina.sistema');
            Route::post('/login', [AdminLoginController::class, 'login'])->name('validate')->middleware('rotina.sistema');

            Route::middleware(['usuario.admin.authenticated', 'usuario.permission'])->group(function () {
                Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout')->middleware('rotina.sistema');
            });

        });

        Route::middleware(['usuario.admin.authenticated', 'usuario.permission'])->group(function () {

            //Rotinas
            Route::name('rotina.')->prefix('rotina')->group(function () {
                Route::get('/', [AdminRotinaController::class, 'index'])->name('index');
                Route::any('/datatables', [AdminRotinaController::class, 'datatables'])->name('datatables');
                Route::get('/{id}/editar', [AdminRotinaController::class, 'edit'])->name('edit');
                Route::post('/{id}/editar', [AdminRotinaController::class, 'update'])->name('update');
                Route::post('/ordenar', [AdminRotinaController::class, 'order'])->name('order');
            });

            //Usuarios
            Route::name('usuario.')->prefix('usuario')->group(function () {
                Route::get('/', [AdminUsuarioController::class, 'index'])->name('index');
                Route::get('/datatables', [AdminUsuarioController::class, 'datatables'])->name('datatables');
                Route::get('/adicionar', [AdminUsuarioController::class, 'create'])->name('create');
                Route::post('/adicionar', [AdminUsuarioController::class, 'store'])->name('store');
                Route::get('/{id}/editar', [AdminUsuarioController::class, 'edit'])->name('edit');
                Route::post('/{id}/editar', [AdminUsuarioController::class, 'update'])->name('update');
                Route::get('/{id}/excluir', [AdminUsuarioController::class, 'destroy'])->name('destroy');
                Route::get('/{id}/visualizar', [AdminUsuarioController::class, 'view'])->name('view');
                Route::post('/definir-permissoes', [AdminUsuarioController::class, 'set_profile'])->name('set_profile');
            });

            //Perfis de acesso
            Route::name('perfil.')->prefix('perfil')->group(function () {
                Route::get('/', [AdminPerfilController::class, 'index'])->name('index');
                Route::get('/datatables', [AdminPerfilController::class, 'datatables'])->name('datatables');
                Route::get('/adicionar', [AdminPerfilController::class, 'create'])->name('create');
                Route::post('/adicionar', [AdminPerfilController::class, 'store'])->name('store');
                Route::get('/{id}/editar', [AdminPerfilController::class, 'edit'])->name('edit');
                Route::post('/{id}/editar', [AdminPerfilController::class, 'update'])->name('update');
                Route::get('/{id}/excluir', [AdminPerfilController::class, 'destroy'])->name('destroy');
            });

        });
    });
});
