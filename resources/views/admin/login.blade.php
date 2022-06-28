@extends('admin.layouts.login')
@section('content')
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="box-login d-flex flex-column align-items-center justify-content-center">
                    <figure class="logo-escoteiros d-flex flex-column align-items-center justify-content-center position-relative">
                        {{-- <img src="{{ asset('img/site/svg/campground-solid.svg') }}" class="img-fluid" alt="Escoteiros"> --}}
                        <span class="fs-2 fw-bold text-dark ms-2 p-0">modulo.admin</span>
                    </figure>
                    <div class="card mb-3">
                        <div class="card-body">
                            {!! Form::model(null, ['route' => ['admin.login.validate'], 'novalidate', 'class' => 'row g-3 needs-validation']) !!}
                            <div class="col-12">
                                <div class="input-group has-validation">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </span>
                                    {!! Form::text('usuario', null, ['class' => 'form-control', 'required', 'placeholder' => 'Usuário']) !!}
                                    <div class="invalid-feedback">
                                        Preencha o campo usuário
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group has-validation">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                    {!! Form::password('token', ['class' => 'form-control', 'placeholder' => 'Token']) !!}
                                    <div class="invalid-feedback">
                                        Preencha o campo Token
                                    </div>
                                </div>
                                @include('admin.includes.message')
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-sm w-100" type="submit">Login</button>
                            </div>
                            <div class="col-12">
                                Problemas com o acesso? Fale com a gente
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="footer text-dark text-center">
                        &copy; Copyright {{ date('Y') }} <span class="fw-bold">modulo.admin</span> | Todos os direitos reservados.
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('scripts')
@endsection
