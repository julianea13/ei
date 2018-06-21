var snippedInstitutiono = function() {

    var frm_delete = $("#frm_confirm_delete"),
        newInstitution = function() {
            $("#enviar").on("click", function(e) {
                e.preventDefault();

                var button = $(this),
                    form = $(this).closest("form");
                form.validate({
                    lang: 'es',
                    rules: {
                         name: {
                            required: true,
                            maxlength: 45
                        },
                         category: {
                            required: true,
                        },
                         institution: {
                            required: true,
                        },
                         text_long: {
                            required: true,
                        },
                         tags: {
                            required: true,
                        }
                    }
                }), form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), form.ajaxSubmit({
                    url: "/instituciones/crear/",
                    proccessData: false,
                    success: function(rpta, l, s, o) {
                        console.log(rpta)
                        button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        if (rpta.status == 200) {
                             img_update.processQueue();
                            window.location.replace("/instituciones/detalle/" + $('#next').val());

                        } else {
                            notify('Hubo un error al crear el institucion', 'la la-warning', 'danger')
                        }

                    }
                }))
            })
        },
        editInstitution = function() {
            $("#update").on("click", function(e) {
                e.preventDefault();

                let button = $(this),
                    form = $(this).closest('form')
                //$('#editPoyect');

                form.validate({
                    lang: 'es',
                    rules: {
                        name: {
                            required: true,
                            maxlength: 20
                        },
                        text_short: {
                            required: true,
                            maxlength: 200
                        },
                        text_long: {
                            required: true
                        }
                    }
                })
                form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0),
                    form.ajaxSubmit({
                        url: "/instituciones/edit/",
                        success: function(rpta, l, s, o) {
                            button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                            if (rpta.status == 200) {
                                img_update.processQueue();
                                const id_proyect = $("#id").val();
                                window.location.replace("/instituciones/detalle/" + id_proyect);
                            } else {
                                notify(rpta, 'la la-warning', 'danger')
                                console.log(rpta)
                            }
                        }
                    }))

            })
        },
        es = function() {
            $.extend($.validator.messages, {
                required: "Este campo es requerido.",
                maxlength: $.validator.format("Ingrese un máximo de {0} caracteres."),
                minlength: $.validator.format("Por favor ingrese al menos {0} caracteres."),
                rangelength: $.validator.format("Ingrese al menos {0} y un máximo de {1} caracteres."),
                email: "Por favor ingrese una dirección válida de correo electrónico",
                url: "Por favor ingrese una URL válida.",
                date: "Por favor ingrese una fecha válida.",
                number: "Por favor ingrese un número.",
                digits: "Por favor ingrese solo números.",
                equalTo: "Por favor repita el mismo valor.",
                range: $.validator.format("Ingrese un valor entre {0} y {1}."),
                max: $.validator.format("Ingrese un valor inferior o igual a {0}."),
                min: $.validator.format("Ingrese un valor mayor o igual que {0}."),
                creditcard: "Por favor ingrese un número de tarjeta de crédito válido."
            });
        },
        hideclover = function() {
            frm_delete.submit(function(e) {
                e.preventDefault()
                var id = $(this).data('idcontainer')
                var institucion = $('#id_clover_delete').val()
                // console.log(institucion)
                // $("input[name='id_clover_delete']").val($('#id_clover_delete').val())
                $(this).ajaxSubmit({
                    url: "/instituciones/delete/",
                    success: function(rpta, r, n, l) {
                        reloadToken(rpta.token)

                        $('#m_modal_confirm_delete').modal('hide')
                        if (rpta.status == 200) {
                            desactivar(id, rpta.token, institucion)

                            notify('El institucion ha sido desactivado', 'flaticon-exclamation-2', 'success')

                        } else {
                            notify('El institucion no ha sido desactivado', 'flaticon-exclamation-2', 'danger')
                        }

                    }
                })
            })
        },
        showclover = function() {
            $("#frm_confirm_restore").submit(function(e) {
                e.preventDefault()
                var id = $(this).data('idcontainer')
                //este no se esta poniendo ene l val
                var institucion = $('#id_clover_restore').val()
                // console.log(institucion)
                // $("input[name='id_clover_restore']").val($('.confirm_action_activate').data('confirm'))
                $(this).ajaxSubmit({
                    url: "/instituciones/restore/",
                    success: function(rpta, r, n, l) {

                        reloadToken(rpta.token)

                        $('#m_modal_confirm_active').modal('hide')
                        if (rpta.status == 200) {
                            activar(id, rpta.token, institucion)

                            notify('El institucion ha sido restaurado', 'flaticon-exclamation-2', 'success')

                        } else {
                            notify('El institucion no ha sido restaurado', 'flaticon-exclamation-2', 'danger')
                        }

                    }
                })
            })
        },
        reloadToken = function(token) {
            $("input[name='token']").val(token);
        },

        deleteConfirm = function() {
            $(document).on('click', '.confirm_action_data', function(e) {

                e.preventDefault();
                const dataConfirm = $(this).data('confirm');
                const dataId = $(this).data('idcontainer');
                $('#id_clover_delete').val(dataConfirm);
                $('#frm_confirm_delete').data('idcontainer', dataId);
            })
        },
        restoreConfirm = function() {
            $(document).on('click', '.confirm_action_activate', function(e) {
                e.preventDefault();
                const dataConfirm = $(this).data('confirm');
                const dataId = $(this).data('idcontainer');

                $('#id_clover_restore').val(dataConfirm);
                $('#frm_confirm_restore').data('idcontainer', dataId);
            })
        }



    return {
        init: function() {
            es(), newInstitution(), editInstitution(), hideclover(), showclover(), deleteConfirm(), restoreConfirm()
        }
    }
}()
jQuery(document).ready(function() {
    snippedInstitutiono.init()
})
