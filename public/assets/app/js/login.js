var SnippetLogin = function() {
    //propiedades
    var frm_login = $("#frm_login"),
        //metodos
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
        login = function() {
            frm_login.submit(function(e) {
                e.preventDefault()
                $(this).ajaxSubmit({
                    url: "/login/in",
                    success: function(rpta, r, n, l) {
                        reloadToken(rpta.token)
                        console.log(rpta)
                        message(rpta.status)

                    }
                })
            })
        },
        newUser = function() {
            $("#btn_create_user").on("click", function(e) {
                e.preventDefault();

                var button = $(this),
                    form = $(this).closest("form");
                form.validate({
                    lang: 'es',
                    rules: {
                        nick: {
                            required: true,
                            maxlength: 15
                        },
                        user_name: {
                            required: true,
                            maxlength: 50
                        },
                        user_last_name: {
                            required: true,
                            maxlength: 50
                        },
                        email: {
                            required: true,
                            maxlength: 50
                        },
                        gender: {
                            required: true,
                        },
                        franchise: {
                            required: true,
                        },
                        image_profile: {
                            required: true,
                        },
                        passw: {
                            required: true,
                            maxlength: 64
                        },
                        repass: {
                            required: true,
                            equalTo: "#passw"
                        }

                    }
                }), form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), form.ajaxSubmit({
                    url: "/Login/nuevo/",
                    success: function(rpta, l, s, o) {
                        reloadToken(rpta.token)
                        responseRegister(rpta.status, rpta.detail)
                        button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        if (rpta.status == 200) {
                            $("#m_modal_5").modal('show');
                            $("#m_modal_register").modal('hide')
                        } else {
                            console.log(rpta)

                        }

                    }
                }))
            })
        },
        restore = function() {
            $("#btn_restore_pass").click(function(e) {
                e.preventDefault();
                var this_btn = $(this),
                    this_frm = $(this).closest("form");
                this_frm.validate({
                    lang: 'es',
                    rules: {
                        correo: {
                            required: true,
                            email: true
                        }
                    }
                }), this_frm.valid() && (this_btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), this_frm.ajaxSubmit({
                    url: "/login/recuperar",
                    success: function(rpta, r, n, l) {
                        this_btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        reloadToken(rpta.token);
                        if (rpta.status == 200) {
                            $("#correo").val('');
                            $("#m_modal_6").modal('show');
                            $("#m_modal_4").modal('hide')
                            console.log(rpta+'bien');
                        } else {
                            responseCodeHandler(rpta.status);
                            console.log(rpta+'error');

                        }
                      
                    }
                }))
            })
        },
        message = function(rpta) {
            switch (rpta) {
                case 1:
                    //$('.alert').remove()
                    //$('<div class="alert alert-success animated fadeIn" role="alert"><strong>Okay</div>').appendTo('#info-login')
                    window.location.replace("/proyectos");
                    break
                case 2:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Debes activar la cuenta</div>').appendTo('#info-login')
                    break
                case 200:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Contraseá incorrecta</div>').appendTo('#info-login')
                    break
                case 201:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Correo invalido</div>').appendTo('#info-login')
                    break
                case 403:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Missing Token</div>').appendTo('#info-login')
                    break
                case 405:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Error al ingresar</div>').appendTo('#info-login')
                    break
            }
        },
        responseCodeHandler = function(rpta) {
            switch (rpta) {
                case 200:
                    $('.alert').remove()
                    $('<div class="alert alert-success animated fadeIn" role="alert"><strong>Te enviamos una nueva contraseña, revisa tu correo.</div>').appendTo('#info-login')
                    break
                case 403:
                    $('.alert').remove()
                    $('<div class="alert alert-warning animated fadeIn" role="alert"><strong>Parece que no tienes permisos para realizar esta acción</div>').appendTo('#info-login')
                    break
                case 500:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>Hubo un inconveniente, pero trabajamos en solucionarlo.</div>').appendTo('#info-login')
                    break
            }
        },
        responseRegister = function(rpta, message) {
            switch (rpta) {
                case 403:
                    $('.alert').remove()
                    $('<div class="alert alert-warning animated fadeIn" role="alert"><strong>Parece que no tienes permisos para realizar esta acción</div>').appendTo('.register_alert_container')
                    break
                case 202:
                    $('.alert').remove()
                    $('<div class="alert alert-danger animated fadeIn" role="alert"><strong>' + message + '.</div>').appendTo('.register_alert_container')
                    break
            }
        },
        reloadToken = function(token) {
            $("input[name='token']").val(token);
        }
    return {
        init: function() {
            es(), login(), restore(), newUser()
        }
    }
}()
jQuery(document).ready(function() {
    SnippetLogin.init()
})