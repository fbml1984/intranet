@extends('admin.layouts.default')
@section('content')
    @include('admin.includes.pagetitle')
    <div class="row">
        <div class="col-12">
            @include('admin.includes.message')
        </div>
    </div>
    @if (in_array('admin.user.create', $permissoesUsuario))
        <div class="row pt-4">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header border-0 bg-white py-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <a class="btn btn-sm btn-info text-white" href="{{ route('admin.user.create') }}">
                                    <span><i class="fa-solid fa-plus"></i></span>
                                    Adicionar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <section class="section">
        <div class="row">
            <div class="col-lg-12 py-4">
                <div class="card card-table border-0">
                    <div class="card-body">

                        <p class="d-none d-lg-block bg-light border-start border-primary text-muted border-3 cfs-6 mb-4 py-4 px-3">
                            Para pesquisar, digite o valor desejado no campo "Pesquisar". Para limpar a pesquisa, apague o valor digitado
                        </p>

                        <table class="nowrap datatable table-hover order-column mb-0 table" data-scroll-x="true" data-ajax="{{ route('admin.usuario.datatables') }}">
                            <thead>
                                <tr>
                                    <th width="30px" data-direction="DESC" data-type="number" data-name="id" data-sortable="false" scope="col">ID</th>
                                    <th data-name="nome" scope="col">Nome</th>
                                    <th data-name="cpf" scope="col">CPF</th>
                                    <th data-name="email" scope="col">E-mail</th>
                                    <th width="150px" data-name="data_criacao" data-sortable="false" class="not-exportable" scope="col">Data Criação</th>
                                    <th width="150px" data-name="data_alteracao" data-sortable="false" class="not-exportable" scope="col">Data Atualização</th>
                                    <th width="90px" data-type="button" data-url0="{{ route('admin.usuario.edit', '#id#') }}" data-icon0="fa-solid fa-pen-to-square" data-url1="{{ route('admin.usuario.view', '#id#') }}" data-icon1="fa-solid fa-eye" data-name="id" data-sortable="false" class="not-exportable" scope="col">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        let datatable = $('table').smartDataTable()
    </script>
@endsection
