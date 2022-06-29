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

                        <p class="d-none d-lg-block bg-light border-start border-primary text-muted border-3 cfs-6 mb-4 py-4 px-3">
                            Para pesquisar, digite o valor desejado no campo "Pesquisar". Para limpar a pesquisa, apague o valor digitado
                        </p>

                        <table class="nowrap datatable table-hover order-column mb-0 table" data-scroll-x="true" data-ajax="{{ route('sig.datatables') }}">
                            <thead>
                                <tr>
                                    <th width="30px" data-direction="DESC" data-type="number" data-name="id" data-sortable="false" scope="col">ID</th>
                                    <th width="100px" data-name="diretorio" scope="col">Diretório</th>
                                    <th data-name="arquivo" scope="col">Arquivo</th>
                                    <th width="150px" data-name="data_mapeamento" data-sortable="false" class="not-exportable" scope="col">Data Mapemamento</th>
                                    <th width="100px" data-type="link" data-icon="fa-solid fa-file-lines" data-url="{{ route('sig.documento.visualizar', '#id#') }}" data-target="_blank" data-name="id" data-sortable="false" scope="col">Visualizar</th>
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
    </script>
@endsection
