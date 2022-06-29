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
                    <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.usuarios') }}">
                        Usuários
                    </a>
                    <a class="btn btn-sm btn-primary disabled p-1" href="">
                        Tags
                    </a>
                    <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.subcategorias') }}">
                        Subcategorias
                    </a>
                    <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.despesas') }}">
                        Despesas
                    </a>
                    <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.adiantamentos') }}">
                        Adiantamentos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header border-0 bg-white py-3">
                    <div id="buttons" class="d-flex align-items-center"></div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12 py-4">
                <div class="card card-table border-0">
                    <div class="card-body">

                        <p class="bg-light border-start border-primary text-muted border-3 cfs-6 mb-4 py-4 px-3">
                            Para pesquisar, digite o valor desejado no campo "Pesquisar". Para limpar a pesquisa, apague o valor digitado.</br>
                        </p>

                        <table class="nowrap datatable table-hover order-column mb-0 table" data-scroll-x="true" data-ajax="{{ route('integra_espresso.datatables', 'tags') }}">
                            <thead>
                                <tr>
                                    <th width="30px" data-direction="DESC" data-type="number" data-name="id" data-sortable="false" scope="col">ID</th>
                                    <th data-name="nome" scope="col">Nome</th>
                                    <th width="150px" data-name="data_criacao" data-sortable="false" class="not-exportable" scope="col">Data Criação Registro</th>
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
        const urlAtualizar = '{{ route('integra_espresso.atualizar_tags') }}'
        let datatable = $('table').smartDataTable({
                export: {
                    excel: {
                        text: '<i class="fa-regular fa-xl fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-info',
                        enabled: true
                    },
                    pdf: {
                        text: '<i class="fa-regular fa-xl fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-info',
                        enabled: true
                    },
                    print: {
                        text: '<i class="fa-solid fa-xl fa-print"></i>',
                        titleAttr: 'Print',
                        className: 'btn btn-info',
                        enabled: true
                    }
                }
            })
            .enableButtons('#buttons')
            .addButton([{
                text: '<i class="fa-solid fa-cloud-arrow-down fa-xl"></i>',
                titleAttr: 'Sincronizar com App Espresso',
                className: 'btn btn-primary',
                action: function(e, dt, node, conf) {
                    criarMensagemToast(
                        'Buscando novos registros no sistema APP Espresso. Aguarde...',
                        'info',
                        360000
                    )
                    $.ajax({
                            url: urlAtualizar,
                            type: 'GET'
                        })
                        .done(function(response) {
                            if (
                                response.erro != undefined &&
                                response.mensagem != undefined &&
                                response.mensagem != null &&
                                response.mensagem != ''
                            ) {
                                criarMensagemToast(
                                    response.mensagem,
                                    response.erro ? 'danger' : 'success'
                                )
                            }
                            datatable.getTable().ajax.reload()
                        })
                        .fail(function(xhr, status, errorThrown) {
                            criarMensagemToast(errorThrown, 'danger')
                            datatable.getTable().ajax.reload()
                        })
                }
            }])
    </script>
@endsection
