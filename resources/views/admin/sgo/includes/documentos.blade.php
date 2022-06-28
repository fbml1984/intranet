<div class="modal" tabindex="-1" id="documentosModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Documentos do Projeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($diretorios as $titulo => $diretorio)
                    <h6 class="text-uppercase text-dark m-0 py-3">{{ $titulo }}</h6>
                    <div class="h-100 bg-light rounded-3 overflow-auto p-2 text-white">
                        <table class="table-responsive nowrap table">
                            <thead>
                                <tr>
                                    <th width="50px" scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th width="200px" scope="col">Criado por</th>
                                    <th width="200px" scope="col">Criado em</th>
                                    <th width="50px" scope="col">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diretorio as $arquivo)
                                    <tr>
                                        <td>{{ $arquivo->IDDOC }}</td>
                                        <td>{{ $arquivo->NOME }}</td>
                                        <td>{{ $arquivo->CRIADO }}</td>
                                        <td>{{ date('d/m/Y H:i:s', strtotime($arquivo->CRIADOEM)) }}</td>
                                        <td>
                                            <a class="btn d-inline-flex align-items-center justify-content-center ms-2 p-1" href="{{ route('sgo.documento.visualizar', ['idDiario' => $arquivo->IDDIARIO, 'idDocumento' => $arquivo->IDDOC]) }}" target="_blank">
                                                <i class="fa-solid fa-file-arrow-down"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
