(function () {
    'use strict'

    let toggleSidebar = document.querySelector('.menu')
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function (e) {
            document.body.classList.toggle('toggle-sidebar')
        })
    }

    let overlay = document.querySelector('.vertical-overlay')
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            document.body.classList.toggle('toggle-sidebar')
        })
    }

    let backtotop = document.querySelector('.back-to-top')
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active')
            } else {
                backtotop.classList.remove('active')
            }
        }
        window.addEventListener('load', toggleBacktotop)
        document.addEventListener('scroll', toggleBacktotop)
    }
})()

const abrirPaginaModal = function (el, url) {
    $.ajax({
        url: url,
        data: null,
        type: 'GET',
        dataType: 'json',
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
            } else {
                document.getElementById('modal').innerHTML = response.retorno
                const modal = new bootstrap.Modal(
                    document.getElementById('documentosModal'), {
                        keyboard: false,
                    }
                );
                modal.show()
            }
        })
        .fail(function (xhr, status, errorThrown) {
            criarMensagemToast(errorThrown, 'danger')
        })
}

const criarMensagemToast = function (mensagem, cor, duracao) {
    const id = Math.floor(Date.now() / 1000)
    const toast = document.getElementById('alertas-toast');
    toast.innerHTML = `<div class="toast-container position-absolute bottom-0 end-0 p-3">
                            <div id="${id}" class="toast align-items-center text-white bg-${cor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">${mensagem}</div>
                                </div>
                            </div>
                        </div>`
    bootstrap.Toast.getOrCreateInstance(document.getElementById(id), {
        delay: typeof duracao !== 'undefined' ? duracao : 1000,
        autohide: typeof duracao === 'undefined'
    }).show()
}

const excluirRegistro = function (el, url, mensagem) {
    if (confirm(mensagem)) {
        $.ajax({
            url: url,
            data: null,
            type: "GET",
            dataType: "json"
        })
            .done(function (retorno) {
                if (retorno.erro !== undefined && retorno.mensagem !== undefined && retorno.mensagem !== null) {
                    criarMensagemToast(retorno.mensagem, retorno.erro ? 'danger' : 'success')
                    if (!retorno.erro) {
                        el.parentNode.parentNode.remove()
                    }
                }

            })
            .fail(function (xhr, status, errorThrown) {
                criarMensagemToast(errorThrown, 'danger')
            })
    }
}

const salvarCampo = function (field) {
    const url = $(field).parents('tr').find('input[name="url"]').val().trim()
    const token = $(field).parents('tr').find('input[name="_token"]').val().trim()
    const nome = $(field).parents('tr').find('input[name="nome"]').val().trim()
    const icone = $(field).parents('tr').find('input[name="icone"]').val().trim()
    $.ajax({
        url: url,
        data: {
            '_token': token,
            'nome': nome,
            'icone': icone
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
}

$(function () {
    if (typeof $.fn.mask !== 'undefined') {
        const behavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ?
                    '(00) 00000-0000' :
                    '(00) 0000-00009'
            },
            options = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(behavior.apply({}, arguments), options)
                },
            };
        $('.phone').mask(behavior, options)
    }

    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2()
    }

    $('[title]').each(function () {
        this.dataset.bsToggle = 'tooltip'
        this.dataset.bsPlacement = 'top'
    })
})
