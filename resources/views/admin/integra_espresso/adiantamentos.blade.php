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
                    <div class="d-flex align-items-center">
                        <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.usuarios') }}">
                            Usuários
                        </a>
                        <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.tags') }}">
                            Tags
                        </a>
                        <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.subcategorias') }}">
                            Subcategorias
                        </a>
                        <a class="btn btn-sm btn-outline-muted" href="{{ route('integra_espresso.despesas') }}">
                            Despesas
                        </a>
                        <a class="btn btn-sm btn-primary disabled p-1" href="{{ route('integra_espresso.adiantamentos') }}">
                            Adiantamentos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header d-flex align-items-center justify-content-between border-0 bg-white py-3">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <div id="buttons" class="d-flex align-items-center">
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

                        <p class="bg-light border-start border-primary text-muted border-3 cfs-6 mb-4 py-4 px-3">
                            Para pesquisar, digite o valor desejado no campo "Pesquisar". Para limpar a pesquisa, apague o valor digitado.</br>
                            Você pode <u>selecionar as linhas da página corrente</u> clicando em qualquer coluna ou no checkbox. Para selecionar um intervalo de linhas, clique em qualquer linha, <u>segure a tecla SHIFT e clique na última linha</u> que deseja selecionar.
                        </p>

                        <table class="nowrap datatable table-hover order-column mb-0 table" data-scroll-x="true" data-select="multi+shift" data-ajax="{{ route('integra_espresso.datatables', 'adiantamentos') }}">
                            <thead>
                                <tr>
                                    <th width="30px" data-type="checkbox" data-name="id" data-sortable="false" class="not-exportable" scope="col"></th>
                                    <th width="30px" data-direction="DESC" data-type="number" data-name="id" data-sortable="false" scope="col">ID</th>
                                    <th data-name="usuario" scope="col">Usuário</th>
                                    <th width="150px" data-name="data" scope="col">Data</th>
                                    <th width="60px" data-name="custo" scope="col">Custo</th>
                                    <th width="150px" data-name="data_criacao" data-sortable="false" class="not-exportable" scope="col">Data Criação Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4"></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        const urlAtualizar = '{{ route('integra_espresso.atualizar_adiantamentos') }}',
            urlExcluir = '{{ route('integra_espresso.destroy') }}',
            urlRejeitar = '{{ route('integra_espresso.aprovar') }}',
            urlAprovar = '{{ route('integra_espresso.rejeitar') }}'

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
            .enableHighLight()
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
                },
                {
                    text: '<i class="fa-regular fa-xl fa-thumbs-down fa-shake" style="--fa-animation-duration: 5s;"></i>',
                    titleAttr: 'Rejeitar',
                    className: 'btn btn-danger',
                    action: function(e, dt, node, conf) {
                        let selecionados = datatable.getSelectedRows()
                        if (selecionados.length) {
                            if (confirm('Tem certeza que deseja rejeitar os registros selecionados? As alterações feitas não poderão ser revertidas.')) {
                                let ids = []
                                for (let i = 0; i < selecionados.length; i++) {
                                    ids.push(selecionados[i]?.id)
                                }
                                $.ajax({
                                        url: urlRejeitar,
                                        data: JSON.stringify({
                                            '_token': '{{ csrf_token() }}',
                                            'registros': ids
                                        }),
                                        type: 'POST',
                                        contentType: 'application/json',
                                        dataType: 'json'
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
                        } else {
                            alert('Selecione algum registro para rejeitar')
                        }
                    }
                }, {
                    text: '<i class="fa-regular fa-xl fa-thumbs-up fa-shake" style="--fa-animation-duration: 5s;"></i>',
                    titleAttr: 'Aprovar',
                    className: 'btn btn-success',
                    action: function(e, dt, node, conf) {
                        let selecionados = datatable.getSelectedRows()
                        if (selecionados.length) {
                            if (confirm('Tem certeza que deseja aprovar os registros selecionados? As alterações feitas não poderão ser revertidas.')) {
                                let ids = []
                                for (let i = 0; i < selecionados.length; i++) {
                                    ids.push(selecionados[i]?.id)
                                }
                                $.ajax({
                                        url: urlAprovar,
                                        data: JSON.stringify({
                                            '_token': '{{ csrf_token() }}',
                                            'registros': ids
                                        }),
                                        type: 'POST',
                                        contentType: 'application/json',
                                        dataType: 'json'
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
                        } else {
                            alert('Selecione algum registro para aprovar')
                        }
                    }
                }
            ])
            .addCallback('draw', function() {
                let api = new $.fn.dataTable.Api(this)

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0
                }

                let total = api.column(4).data().reduce(function(a, b) {
                    b = intVal(b.replace('R$ ', '').replace('.', '').replace(',', '.'))
                    return a + b
                }, 0)

                $(api.column(4).footer()).html(
                    total.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    })
                )
            })

        // {
        //     text: '<i class="fa-regular fa-xl fa-trash-can"></i>',
        //         titleAttr: 'Excluir',
        //             className: 'btn btn-warning',
        //                 action: function(e, dt, node, conf) {
        //                     if (confirm('Tem certeza que deseja excluir os registros selecionados? As alterações feitas não poderão ser revertidas.')) {
        //                         let selecionados = datatable.getSelectedRows()
        //                         if (selecionados.length) {
        //                             let ids = []
        //                             for (let i = 0; i < selecionados.length; i++) {
        //                                 ids.push(selecionados[i]?.id)
        //                             }
        //                             $.ajax({
        //                                 url: urlExcluir,
        //                                 data: JSON.stringify({
        //                                     '_token': '{{ csrf_token() }}',
        //                                     'registros': ids
        //                                 }),
        //                                 type: 'POST',
        //                                 contentType: 'application/json',
        //                                 dataType: 'json'
        //                             })
        //                                 .done(function (response) {
        //                                     if (
        //                                         response.erro != undefined &&
        //                                         response.mensagem != undefined &&
        //                                         response.mensagem != null &&
        //                                         response.mensagem != ''
        //                                     ) {
        //                                         criarMensagemToast(
        //                                             response.mensagem,
        //                                             response.erro ? 'danger' : 'success'
        //                                         )
        //                                     }
        //                                     datatable.getTable().ajax.reload()
        //                                 })
        //                                 .fail(function (xhr, status, errorThrown) {
        //                                     criarMensagemToast(errorThrown, 'danger')
        //                                     datatable.getTable().ajax.reload()
        //                                 })
        //                         }
        //                     }
        //                 }
        // },
    </script>
@endsection
