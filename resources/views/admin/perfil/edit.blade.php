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

    {!! Form::model($perfil, ['route' => ['admin.perfil.update', $perfil->perfil_id], 'novalidate', 'class' => 'needs-validation mt-3']) !!}
    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <h6 class="fw-bold m-0">Dados do perfil</h6>
                    </div>
                    <div class="card-body pt-0">
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

    <section class="section pt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <h6 class="card-title fw-bold m-0">Permissões</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-12">
                                @php
                                    $i = 0;
                                @endphp
                                <div class="row">
                                    @foreach ($permissoes as $rotina => $acoes)
                                        @if ($i > 0 && $i % 4 == 0)
                                            @include('admin.perfil.includes.separador')
                                        @endif
                                        <div class="col-md-3 col-12 mb-md-0 mb-3">
                                            <div class="card h-100 shadow-none">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">{{ $rotina }}</h6>
                                                    <ul class="list-group border-0">
                                                        @foreach ($acoes as $acao => $acaoId)
                                                            @php
                                                                $checked = false;
                                                                if (in_array($acaoId, $permissoesPerfil, true)) {
                                                                    $checked = true;
                                                                }
                                                            @endphp
                                                            <label class="list-group-item d-flex align-items-center border-0 px-0 py-1" for="acao{{ $acaoId }}">
                                                                {!! Form::checkbox('perfil_acoes[]', $acaoId, $checked, ['class' => 'fs-6 form-check-input me-2 mt-0', 'id' => 'acao' . $acaoId]) !!}
                                                                {{ $acao }}
                                                            </label>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
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
