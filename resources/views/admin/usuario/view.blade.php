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

    {!! Form::model($usuario) !!}
    {!! Form::hidden('usuario_id') !!}
    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-5">Dados do usuário</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nome</label>
                                {!! Form::text('nome', null, ['class' => 'form-control-plaintext', 'required', 'placeholder' => 'name']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Apelido</label>
                                {!! Form::text('apelido', null, ['class' => 'form-control-plaintext', 'placeholder' => 'Apelido']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Data de Nascimento</label>
                                {!! Form::text('dt_nascimento', null, ['class' => 'form-control-plaintext', 'placeholder' => 'Data de Nascimento']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Gênero</label>
                                {!! Form::text('sexo', null, ['class' => 'form-control-plaintext', 'placeholder' => 'Sexo']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">E-mail</label>
                                {!! Form::text('email', null, ['class' => 'form-control-plaintext', 'placeholder' => 'E-mail']) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">CPF</label>
                                {!! Form::text('cpf', null, ['class' => 'form-control-plaintext', 'placeholder' => 'CPF', 'data-mask' => '000.000.000-00']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row align-self-end pt-3">
        <div class="col-12 d-flex justify-content-between">
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.usuario.index') }}">
                <span><i class="fa-solid fa-angle-left"></i></span>
                Voltar
            </a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
@endsection
