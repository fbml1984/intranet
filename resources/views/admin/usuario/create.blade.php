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

    {!! Form::model(null, ['route' => ['admin.usuario.store'], 'novalidate', 'class' => 'needs-validation']) !!}
    {!! Form::hidden('usuario_id') !!}
    {!! Form::hidden('user_company_detail_id') !!}
    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <h6 class="fw-bold m-0">Dados pessoais</h6>
                    </div>
                    <div class="card-body pt-0">

                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => 'name', 'id' => 'name']) !!}
                                    <label for="name">Nome</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('nickname', null, ['class' => 'form-control', 'placeholder' => 'Apelido', 'id' => 'nickname']) !!}
                                    <label for="nickname">Apelido</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-4 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('document_number', null, ['class' => 'form-control', 'placeholder' => 'CPF', 'id' => 'document_number', 'data-mask' => '000.000.000-00']) !!}
                                    <label for="document">CPF</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('birthdate', null, ['class' => 'form-control', 'placeholder' => 'Data de Nascimento', 'id' => 'birthdate', 'data-mask' => '00/00/0000']) !!}
                                    <label for="birthdate">Data de Nascimento</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::select('gender', ['M' => 'Masculino', 'F' => 'Feminino'], null, ['class' => 'form-select', 'id' => 'gender']) !!}
                                    <label for="gender">Gênero</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <h6 class="fw-bold m-0">Dados de contato</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail', 'id' => 'email']) !!}
                                    <label for="email">E-mail</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Telefone', 'id' => 'phone', 'data-mask' => '(00) 0000-00009']) !!}
                                    <label for="phone">Telefone</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <h6 class="fw-bold m-0">Dados de acesso</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Login', 'id' => 'username']) !!}
                                    <label for="username">Login</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-md-0 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Senha', 'id' => 'password']) !!}
                                    <label for="password">Senha</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row pt-3">
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
