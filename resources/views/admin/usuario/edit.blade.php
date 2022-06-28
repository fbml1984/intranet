@extends('admin.layouts.default')
@section('content')
    @include('admin.includes.pagetitle')
    <div class="row">
        <div class="col-12">
            @include('admin.includes.message')
        </div>
    </div>
    <div class="row pt-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header border-0 bg-white py-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <a class="btn btn-sm btn-secondary text-white" href="{{ route('admin.usuario.index') }}">
                                <span><i class="fa-solid fa-list"></i></span>
                                Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::model($usuario, ['route' => ['admin.usuario.update', $usuario['id']], 'novalidate', 'class' => 'needs-validation']) !!}
    {!! Form::hidden('usuario_id') !!}
    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase">Dados do usuário</h6>
                        <p class="mb-3">
                            <sup>Observação</sup> <small>Com exceção do e-mail, os dados do RM não podem ser editados por esta aplicação</small> <br />
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nome</label>
                                {!! Form::text('nome', null, ['class' => 'form-control', 'required', 'placeholder' => 'name', 'readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apelido</label>
                                {!! Form::text('apelido', null, ['class' => 'form-control', 'placeholder' => 'Apelido', 'readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Data de Nascimento</label>
                                {!! Form::text('dt_nascimento', null, ['class' => 'form-control', 'placeholder' => 'Data de Nascimento', 'readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gênero</label>
                                {!! Form::text('sexo', null, ['class' => 'form-control', 'placeholder' => 'Sexo', 'readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CPF</label>
                                {!! Form::text('cpf', null, ['class' => 'form-control', 'placeholder' => 'CPF', 'data-mask' => '000.000.000-00', 'readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">E-mail</label>
                                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail']) !!}
                            </div>
                        </div>
                        @if (in_array(\App\Enums\Permissoes::PODE_DEFINIR_PERFIL, $permissoesUsuario))
                            <h6 class="text-uppercase mt-5">Permissões</h6>
                            <p class="mb-3">
                                <sup>Observação 1</sup> <small>Selecione o perfil do usuário de acordo com as permissões que ele pode executar</small> <br />
                                <sup>Observação 2</sup> <small>O perfil de desenvolvedor tem acesso a todas as funcionalidades do sistema e não deve ser utilizado pessoas não desenvolvedoras</small>
                            </p>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row d-lg-flex align-items-lg-stretch">
                                        @foreach ($perfis as $perfil)
                                            <div class="col-12 form-control border-0 shadow-none">
                                                <div class="form-check form-check-inline d-flex align-items-center">
                                                    {!! Form::radio('usuario_perfil', $perfil->id, $perfil->checked, ['class' => 'form-check-input mt-0 me-2', 'id' => 'perfil' . $perfil->id]) !!}
                                                    <label class="form-check-label d-flex align-self-stretch align-items-center" for="perfil{{ $perfil->id }}">
                                                        {{ $perfil->nome }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row align-self-end pt-3">
        <div class="col-12 d-flex justify-content-between">
            <a class="btn btn-sm btn-danger" href="{{ route('admin.usuario.index') }}">
                <span><i class="fa-solid fa-angle-left"></i></span>
                Cancelar
            </a>
            <button type="submit" class="btn btn-sm btn-success icon-right">
                Salvar alterações
                <span><i class="fa-solid fa-angle-right"></i></span>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
@endsection
