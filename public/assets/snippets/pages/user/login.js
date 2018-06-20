var SnippetLogin = function() {
    var e = $("#m_login"),
    q = function(){
        $(document).on("change", "#departamento", function(e) {
            e.preventDefault();
            var id_dpto = $(this).val()
            $("#departamento option:selected").each(function () {
                var t = $(this).closest("form");
                i(t, "", "", true)
                $.ajax({
                    url : '/Ciudad/get/'+id_dpto,
                    type : 'POST',
                    success :function(data){
                        if(data == "err"){
                            i(t, "danger", "No fué posible obtener las ciudades, intenta más tarde.")
                             $("#ciudad").html("<option value>Seleccione departamento</option>");
                             $("#ciudad").selectpicker('refresh'); 
                             return
                        }
                        if(id_dpto == ""){
                            $("#ciudad").html("<option value>Seleccione departamento</option>");
                            $("#ciudad").selectpicker('refresh'); 
                            return
                        }
                        //iterar los resultado y construir los option, y hacer lo appends
                        $("#ciudad").html("<option value>Ciudad</option>");         
                        $.each(data,function(i,v){
                            $("#ciudad").append("<option value="+v.id_ciudad+">"+v.ciudad+"</option>");
                            $("#ciudad").selectpicker('refresh');
                        });
                    }
                });
            });
        })
    },
    z = function(){
        $.extend( $.validator.messages, {
           required: "Este campo es requerido.",
           maxlength: $.validator.format( "Ingrese un máximo de {0} caracteres." ),
           minlength: $.validator.format( "Por favor ingrese al menos {0} caracteres." ),
           rangelength: $.validator.format( "Ingrese al menos {0} y un máximo de {1} caracteres." ),
           email: "Por favor ingrese una dirección válida de correo electrónico",
           url: "Por favor ingrese una URL válida.",
           date: "Por favor ingrese una fecha válida.",
           number: "Por favor ingrese un número.",
           digits: "Por favor ingrese solo números.",
           equalTo: "Por favor repita el mismo valor.",
           range: $.validator.format( "Ingrese un valor entre {0} y {1}." ),
           max: $.validator.format( "Ingrese un valor inferior o igual a {0}." ),
           min: $.validator.format( "Ingrese un valor mayor o igual que {0}." ),
           creditcard: "Por favor ingrese un número de tarjeta de crédito válido."
       });    
    },
    i = function(e, i, a, erase) {
        erase = erase || false;
        if(erase){
            e.find(".alert").remove()
            return
        }
        var t = $('<div class="m-alert m-alert--outline alert alert-' + i + ' alert-dismissible" role="alert">\t\t\t<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\t\t\t<span></span>\t\t</div>');
        e.find(".alert").remove(), t.prependTo(e), t.animateClass("fadeIn animated"), t.find("span").html(a)            
        $("html, body").stop().animate({scrollTop:0}, 500, 'swing', function(){});
    },
    a = function() {
        e.removeClass("m-login--forget-password"), e.removeClass("m-login--signin"), e.addClass("m-login--signup"), e.find(".m-login__signup").animateClass("fadeIn animated")
    },
    t = function() {
        e.removeClass("m-login--forget-password"), e.removeClass("m-login--signup"), e.addClass("m-login--signin"), e.find(".m-login__signin").animateClass("fadeIn animated")
    },
    r = function() {
        e.removeClass("m-login--signin"), e.removeClass("m-login--signup"), e.addClass("m-login--forget-password"), e.find(".m-login__forget-password").animateClass("fadeIn animated")
    },
    n = function() {
        $("#m_login_forget_password").click(function(e) {
            e.preventDefault(), r()
        }), $("#m_login_forget_password_cancel").click(function(e) {
            e.preventDefault(), t()
        }), $("#m_login_signup").click(function(e) {
            e.preventDefault(), a()
        }), $("#m_login_signup_cancel").click(function(e) {
            e.preventDefault(), t()
        })
    },
    l = function() {
        $("#m_login_signin_submit").click(function(e) {
            e.preventDefault();
            var a = $(this),
            t = $(this).closest("form");
            t.validate({
                rules: {
                    correo: {
                        required: true,
                        email: true
                    },
                    clave: {
                        required: true
                    }
                }
            }), t.valid() && (a.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), t.ajaxSubmit({
                url: "/login/in",
                success: function(rpta, r, n, l) {
                    console.log(rpta)
                    setTimeout(function() {                            
                        if (rpta == "ok")
                            window.location.replace("/propuesta");
                        else{
                            if (rpta == 'activate'){
                                a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), i(t, "warning", "Activa tu cuenta para ingresar.")  
                            }else{
                                if(rpta == 'date'){
                                    a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), i(t, "warning", "La convocatoria ya finalizó, gracias por tu interés.")  
                                }else{
                                    a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), i(t, "danger", "Datos incorrectos. Por favor intenta de nuevo.")  
                                }                                
                            }
                        }                          
                    }, 1000)
                }
            }))
        })
    },
    s = function() {
        $("#m_login_signup_submit").click(function(a) {
            a.preventDefault();                
            var r = $(this),
            n = $(this).closest("form");
            n.validate({
                lang: 'es',
                rules: {
                    nombre: {
                        required: true
                    },
                    tipo_documento: {
                        required: true
                    },
                    documento: {
                        required: true,
                        digits: true
                    },
                    celular: {
                        required: true,
                        digits: true
                    },
                    rol: {
                        required: true
                    },
                    campo: {
                        required: true
                    },
                    experiencia: {
                        required: true,
                        digits: true,
                        max: 67
                    },
                    institucion: {
                        required: true
                    },
                    correo: {
                        required: true,
                        email: true
                    },
                    pass: {
                        required: true,
                        minlength: 6
                    },
                    repass: {
                        required: true,
                        equalTo: "#pass"
                    },
                    agree: {
                        required: true
                    }
                }
            }), n.valid() && (r.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), n.ajaxSubmit({
                url: "/login/nuevo",
                success: function(rpta, l, s, o) {
                    console.log(rpta)
                    setTimeout(function() {                            
                        if (rpta == "ok"){
                                //window.location.replace("/");
                                r.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), n.clearForm(), n.validate().resetForm(), t();                                
                                var a = e.find(".m-login__signin form");
                                var a2 = e.find(".m-login__signup form");   
                                a.clearForm(), a.validate().resetForm(), i(a, "success", "Gracias. Para completar tu registro por favor verifica tu correo, <strong>recuerda revisar los correos no deseados</strong>.")
                                a2.clearForm(), a2.validate().resetForm()
                            }else{                
                                r.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1);                                
                                var a = e.find(".m-login__signup form");                                                
                                if( rpta == "exists"){
                                    i(a, "warning", "Este correo ya fué registrado.")
                                }else{
                                    if(rpta == 'date'){
                                        i(a, "warning", "La convocatoria ya finalizó, gracias por tu interés.")  
                                    }else{
                                        i(a, "danger", "Su registro no pudo completarse, intente más tarde.")
                                    }
                                }
                            }
                        }, 1000)
                }
            }))
        })
    },
    o = function() {
        $("#m_login_forget_password_submit").click(function(a) {
            a.preventDefault();
            var r = $(this),
            n = $(this).closest("form");
            n.validate({
                rules: {
                    correo: {
                        required: true,
                        email: true
                    }
                }
            }), n.valid() && (r.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), n.ajaxSubmit({
                url: "/login/recuperar",
                success: function(rpta, l, s, o) {
                    console.log(rpta)
                    setTimeout(function() {
                     if (rpta == "ok") {
                       r.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1), n.clearForm(), n.validate().resetForm(), t();
                       var a = e.find(".m-login__signin form");
                       var a2 = e.find(".m-login__forget-password form");
                       a2.clearForm(), a2.validate().resetForm()
                       a.clearForm(), a.validate().resetForm(), i(a, "success", "Te enviamos una nueva clave a tu correo.")
                   }else{
                    r.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1);
                    var a = e.find(".m-login__forget-password form");
                    if (rpta == "activate"){                                
                        i(a, "warning", "Debes activar tu cuenta primero.")
                    }else{
                        if(rpta == 'date'){
                            i(a, "warning", "La convocatoria ya finalizó, gracias por tu interés.")  
                        }else{
                            i(a, "danger", "Correo no encontrado.")
                        }
                    }
                }
            }, 2e3)
                }
            }))
        })
    };
    return {
        init: function() {
            z(), n(), l(), s(), o(), q()
        }
    }
}();
jQuery(document).ready(function() {
    SnippetLogin.init()
});