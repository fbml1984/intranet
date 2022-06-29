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
                            <a class="btn btn-sm btn-info text-white" href="{{ route('rotina.update_routines') }}">
                                <span><i class="fa-solid fa-sync fa-spin"></i></span>
                                Atualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12 py-4">
                <div class="card card-table border-0">
                    <div class="card-body">
                        {!! Form::hidden('_token', csrf_token()) !!}
                        <p class="d-none d-lg-block bg-light border-start border-primary text-muted border-3 cfs-6 mb-4 py-4 px-3">
                            <sup>DICA 1</sup>Para pesquisar, digite o valor desejado no campo "Pesquisar". Para limpar a pesquisa, apague o valor digitado</br>
                            <sup>DICA 2</sup> Ao editar o valor de algum campo, é necessário clicar fora do campo editado e aguardar a mensagem, para que as alterações sejam salvas
                        </p>

                        <table class="nowrap datatable table-hover order-column mb-0 table" data-scroll-x="true" data-ajax="{{ route('admin.rotina.datatables') }}">
                            <thead>
                                <tr>
                                    <th width="30px" data-direction="DESC" data-type="number" data-name="id" data-sortable="false" scope="col">ID</th>
                                    <th data-type="input" data-name="nome" data-url="{{ route('admin.rotina.edit', '#id#') }}" scope="col">Nome</th>
                                    <th data-type="input" data-name="icone" data-url="{{ route('admin.rotina.edit', '#id#') }}" scope="col">Ícone</th>
                                    <th data-name="controller" scope="col">Controller</th>
                                    <th width="150px" data-name="data_criacao" data-sortable="false" class="not-exportable" scope="col">Data Criação</th>
                                    <th width="150px" data-name="data_alteracao" data-sortable="false" class="not-exportable" scope="col">Data Atualização</th>
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
