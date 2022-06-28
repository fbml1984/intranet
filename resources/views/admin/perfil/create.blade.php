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
                            <a class="btn btn-sm btn-secondary text-white" href="{{ route('admin.perfil.index') }}">
                                <span><i class="fa-solid fa-list"></i></span>
                                Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::model(null, ['route' => ['admin.perfil.create'], 'novalidate', 'class' => 'needs-validation mt-3']) !!}
    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
                                    <label for="name">Nome</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="form-floating">
                                    {!! Form::text('rota', null, ['class' => 'form-control', 'required']) !!}
                                    <label for="nickname">Rota</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('admin.includes.botoes')
    {!! Form::close() !!}
@endsection
@section('scripts')
@endsection
