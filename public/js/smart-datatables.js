(function ($) {

    $.fn.smartDataTable = function (options) {

        const _self = this

        const _settings = $.extend({
            export: {
                excel: {
                    text: 'Excel',
                    titleAttr: 'Excel',
                    className: 'btn btn-secondary',
                    enabled: true
                },
                pdf: {
                    text: 'PDF',
                    titleAttr: 'PDF',
                    className: 'btn btn-secondary',
                    enabled: true
                },
                print: {
                    text: 'Print',
                    titleAttr: 'Print',
                    className: 'btn btn-secondary',
                    enabled: true
                }
            }
        }, options)

        const _language = {
            sEmptyTable: 'Nenhum registro encontrado',
            sInfo: 'Mostrando de <span class="fw-bold">_START_</span> até <span class="fw-bold">_END_</span> registros do total de <span class="fw-bold">_TOTAL_</span> registros',
            sInfoEmpty: 'Mostrando 0 até 0 de 0 registros',
            sInfoFiltered: '(Filtrados de _MAX_ registros)',
            sInfoPostFix: '',
            sInfoThousands: '.',
            sLengthMenu: 'Exibir _MENU_ registros por página',
            sLoadingRecords: 'Carregando...',
            sProcessing: 'Processando...',
            sZeroRecords: 'Nenhum registro encontrado',
            sSearch: '',
            sSearchPlaceholder: 'Pesquisar',
            oPaginate: {
                sNext: '>',
                sPrevious: '<',
                sFirst: '<<',
                sLast: '>>'
            },
            oAria: {
                sSortAscending: ': Ordenar colunas de forma ascendente',
                sSortDescending: ': Ordenar colunas de forma descendente'
            },
            select: {
                rows: {
                    _: '<span class="fw-bold">%d linhas selecionadas</span>',
                    0: '',
                    1: '<span class="fw-bold">1 linha selecionada</span>'
                }
            }
        }

        let _columnDefs = [],
            _order = [],
            _columns = []

        $('thead > tr > th', _self).each(function (index, collumn) {

            const _data = $(collumn).data(),
                //Column attributes
                _name = (_data.hasOwnProperty('name') && _data.name !== '') ? _data.name : '',
                _datatype = (_data.hasOwnProperty('type') && _data.type !== '') ? _data.type : '',
                _icon = (_data.hasOwnProperty('icon') && _data.icon !== '') ? _data.icon : 'fa-solid fa-arrow-up-right-from-square',
                _url = (_data.hasOwnProperty('url') && _data.url !== '') ? _data.url : '',
                _target = (_data.hasOwnProperty('target') && _data.target !== '') ? _data.target : '_self',
                _direction = (_data.hasOwnProperty('direction') && _data.direction !== '') ? _data.direction : '',
                _orderable = _data.hasOwnProperty('orderable') ? Boolean(_data.orderable) : true,
                _searchable = _data.hasOwnProperty('searchable') ? Boolean(_data.searchable) : true,
                _sortable = _data.hasOwnProperty('sortable') ? Boolean(_data.sortable) : true,
                _visible = _data.hasOwnProperty('visible') ? Boolean(_data.visible) : true

            let buttons = []
            let i = 0
            while (_data.hasOwnProperty('url' + i) && _data.hasOwnProperty('icon' + i)) {
                buttons.push({ 'url': eval('_data.url' + i), 'icon': eval('_data.icon' + i) })
                i++
            }

            let _isCheckbox = function () {
                return _datatype === 'checkbox'
            }

            if (_direction !== '') {
                _order.push([index, _direction])
            }

            _columnDefs.push({
                orderable: _orderable,
                searchable: _searchable,
                sortable: _sortable,
                visible: _visible,
                className: _isCheckbox() ? 'select-checkbox' : '',
                targets: index
            })

            _columns.push({
                name: _name,
                data: _name,
                render: function (_data, type, row) {
                    try {

                        const _buildRowForm = function () {
                            const _id = moment().format('x')
                            let _html = `<form id="form-${_id}" action="${_url.replace('#id#', row.id)}">`
                            _html += `<input name="id" value="${row.id}" type="hidden">`
                            _html += `<input name="key" value="${_name}" type="hidden">`
                            _html += `<input name="value" value="${_data}" class="form-control form-control-sm" type="text">`
                            _html += `</form>`
                            return _html
                        }

                        switch (_datatype) {
                            case 'input':
                                return _buildRowForm()
                            case 'modal':
                                return `<a class="btn d-inline-flex align-items-center justify-content-center p-1 ms-2" href="javascript:void(0)" onclick="abrirPaginaModal(this, '${_url.replace('#id#', _data)}')"><i class="${_icon}"></i></a>`
                            case 'linkData':
                                return `<a class="btn d-inline-flex align-items-center justify-content-center p-1 ms-2" target="${_target}" href="${_data}"><i class="${_icon}"></i></a>`
                            case 'link':
                                let _href = _url !== '' ? _url.replace('#id#', _data) : _data
                                return `<a class="btn d-inline-flex align-items-center justify-content-center p-1 ms-2" target="${_target}" href="${_href}"><i class="${_icon}"></i></a>`
                            case 'button':
                                let _html = ''
                                if (buttons.length > 0) {
                                    for (let i = 0; i < buttons.length; i++) {
                                        _html += `<a class="btn d-inline-flex align-items-center justify-content-center p-1 me-3" target="${_target}" href="${buttons[i].url.replace('#id#', _data)}"><i class="${buttons[i].icon}"></i></a>`
                                    }
                                }
                                return _html
                            case 'checkbox':
                                return `<input type="hidden" name="data[][id]" value="${row.id}" />`
                            case 'string':
                            default:
                                return _data
                        }
                    } catch (e) {
                        console.log(e)
                        return _data
                    }
                }
            })
        })

        let _table = _self.DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            stateSave: false,
            stateDuration: -1,
            rowId: 'extn',
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: _columnDefs,
            columns: _columns,
            order: _order,
            language: _language,
        })

        const initColumnsForms = function (_table) {
            $('form', _table).each(function () {
                let url = $(this).attr('action'),
                    _id = $(this).find('input[name="id"]').val(),
                    _key = $(this).find('input[name="key"]').val(),
                    _token = $('input[name="_token"]').val().trim()

                $('input[name="value"]', this).on('change', function () {
                    let _value = $(this).val()
                    $.ajax({
                        url: url,
                        data: {
                            '_token': _token,
                            'id': _id,
                            'key': _key,
                            'value': _value
                        },
                        type: 'POST',
                    })
                        .done(function (response) {
                            if (
                                response.erro !== undefined &&
                                response.mensagem !== undefined &&
                                response.mensagem !== null
                            ) {
                                criarMensagemToast(
                                    response.mensagem,
                                    response.erro ? 'danger' : 'success'
                                )
                            }
                        })
                        .fail(function (xhr, status, errorThrown) {
                            criarMensagemToast(errorThrown, 'danger')
                        })
                })
            })
        }

        _table.on('init.dt', function () {
            initColumnsForms(this)
        })

        return {
            table: _table,
            enableHighLight: function () {
                $('tbody', _self).on('mouseenter', 'td', function () {
                    const colIdx = _table.cell(this).index()?.column
                    $(_table.cells().nodes()).removeClass('highlight')
                    $(_table.column(colIdx).nodes()).addClass('highlight')
                })
                return this
            },
            enableButtons: function (_selector) {
                let _buttons = []

                if (_settings.export.excel.enabled) {
                    _buttons.push({
                        text: _settings.export.excel.text,
                        titleAttr: _settings.export.excel.titleAttr,
                        className: _settings.export.excel.className,
                        extend: 'excel',
                        exportOptions: {
                            modifier: {
                                page: 'all',
                                search: 'none'
                            },
                            columns: ':visible:not(.select-checkbox):not(:last-child):not(.not-exportable)',
                            format: {
                                body: function (data, column, row, node) {
                                    data = data.toString().replace(/(<([^>]+)>)/ig, '')
                                    if (/^((-)?\d*(\,|\.)?\d+)(,\d{2}$)?$/.test(String(data))) {
                                        return data.toString().replace('.', '').replace(',', '.').replace('R$ ', '')
                                    }
                                    return data.replace('R$ ', '')
                                }
                            }
                        }
                    })
                }

                if (_settings.export.pdf.enabled) {
                    _buttons.push({
                        text: _settings.export.pdf.text,
                        titleAttr: _settings.export.pdf.titleAttr,
                        className: _settings.export.pdf.className,
                        extend: 'pdf',
                        orientation: 'portrait',
                        exportOptions: {
                            modifier: {
                                page: 'all',
                                search: 'none'
                            },
                            columns: ':visible:not(.select-checkbox):not(:last-child):not(.not-exportable)'
                        },
                        customize: function (doc) {
                            doc.pageMargins = [10, 10, 10, 10]
                            doc.defaultStyle.fontSize = 6
                            doc.styles.tableHeader.fontSize = 7
                            doc.styles.title.fontSize = 9
                            // Remove spaces around page title
                            doc.content[0].text = doc.content[0].text.trim()
                            // Create a footer
                            doc['footer'] = (function (page, pages) {
                                return {
                                    columns: [
                                        'This is your left footer column',
                                        {
                                            // This is the right column
                                            alignment: 'right',
                                            text: ['page ', {
                                                text: page.toString()
                                            }, ' of ', {
                                                    text: pages.toString()
                                                }]
                                        }
                                    ],
                                    margin: [10, 0]
                                }
                            })
                            // Styling the table: create style object
                            const objLayout = {}
                            // Horizontal line thickness
                            objLayout['hLineWidth'] = function (i) {
                                return .5
                            }
                            // Vertikal line thickness
                            objLayout['vLineWidth'] = function (i) {
                                return .5
                            }
                            // Horizontal line color
                            objLayout['hLineColor'] = function (i) {
                                return '#aaa'
                            }
                            // Vertical line color
                            objLayout['vLineColor'] = function (i) {
                                return '#aaa'
                            }
                            // Left padding of the cell
                            objLayout['paddingLeft'] = function (i) {
                                return 4
                            }
                            // Right padding of the cell
                            objLayout['paddingRight'] = function (i) {
                                return 4
                            }
                            // Inject the object in the document
                            doc.content[1].layout = objLayout
                        }
                    })
                }

                if (_settings.export.print.enabled) {
                    _buttons.push({
                        text: _settings.export.print.text,
                        titleAttr: _settings.export.print.titleAttr,
                        className: _settings.export.print.className,
                        extend: 'print',
                        exportOptions: {
                            modifier: {
                                page: 'all',
                                search: 'none'
                            },
                            columns: ':visible:not(.select-checkbox):not(:last-child):not(.not-exportable)'
                        }
                    })
                }
                new $.fn.dataTable.Buttons(_table, {
                    buttons: _buttons
                })
                _table.buttons(0, null).container().appendTo(_selector)

                return this
            },
            addButton: function (_buttons) {
                _table.button().add(0, _buttons)
                return this
            },
            getSelectedRows: function () {
                let _selectedRows = []
                _table.rows().every(function () {
                    let row = this.node(),
                        _data = _table.row(this).data()
                    if (row !== null && row.classList.contains('selected')) {
                        _selectedRows.push(_data)
                    }
                })
                return _selectedRows
            },
            getTable: function () {
                return _table
            },
            getApi: function () {
                return _table.api()
            },
            addCallback: function (_callback, _function) {
                _table.on(_callback, _function)
                return this
            }
        }
    }

    //

    // const datatables = document.querySelectorAll('.datatable', true)

    // datatables.forEach(datatable => {

    //     table.on('draw.dt', function (e) {

    //         // const token = $('input[name="_token"]').val()

    //         // $('.formCampoEditavel').each(function () {
    //         //     $(this).on('change', function () {
    //         //         $.ajax({
    //         //             url: $(this).attr('action'),
    //         //             data: JSON.stringify({
    //         //                 '_token': token
    //         //             }),
    //         //             type: 'POST',
    //         //             contentType: 'application/json',
    //         //             dataType: 'json'
    //         //         })
    //         //             .done(function (response) {
    //         //                 if (
    //         //                     response.erro != undefined &&
    //         //                     response.mensagem != undefined &&
    //         //                     response.mensagem != null &&
    //         //                     response.mensagem != ''
    //         //                 ) {
    //         //                     criarMensagemToast(
    //         //                         response.mensagem,
    //         //                         response.erro ? 'danger' : 'success'
    //         //                     )
    //         //                 }
    //         //             })
    //         //             .fail(function (xhr, status, errorThrown) {
    //         //                 criarMensagemToast(errorThrown, 'danger')
    //         //             })
    //         //     })
    //         // })
    //         // if (typeof urlOrder != 'undefined' && urlOrder != null && urlOrder != '') {

    //         //     const formData = []

    //         //     $(e.target).find('tbody').find('tr').each(function (index) {
    //         //         formData.push({
    //         //             'id': $(this).find('input[name="id"]').val(),
    //         //             'ordem': index
    //         //         })
    //         //     })

    //         //     $.ajax({
    //         //         url: urlOrder,
    //         //         data: JSON.stringify({
    //         //             '_token': token,
    //         //             'registros': formData
    //         //         }),
    //         //         type: 'POST',
    //         //         contentType: 'application/json',
    //         //         dataType: 'json'
    //         //     })
    //         //         .done(function (response) {
    //         //             if (
    //         //                 response.erro != undefined &&
    //         //                 response.mensagem != undefined &&
    //         //                 response.mensagem != null &&
    //         //                 response.mensagem != ''
    //         //             ) {
    //         //                 criarMensagemToast(
    //         //                     response.mensagem,
    //         //                     response.erro ? 'danger' : 'success'
    //         //                 )
    //         //             }
    //         //         })
    //         //         .fail(function (xhr, status, errorThrown) {
    //         //             criarMensagemToast(errorThrown, 'danger')
    //         //         })
    //         // }
    //     })


    // })


}(jQuery))
