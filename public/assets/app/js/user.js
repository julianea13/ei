var SnippetUser = function() {
       var frm_delete = $("#frm_confirm_delete"),
       
        newUser = function() {
            $("#btn_new").on("click", function(e) {
                e.preventDefault();

                var button = $(this),
                    form = $(this).closest("form");
                form.validate({
                    lang: 'es',
                    rules: {
                        user: {
                            required: true,
                            maxlength: 15
                        },
                        name: {
                            required: true,
                            maxlength: 50
                        },
                        last_name: {
                            required: true,
                            maxlength: 50
                        },
                        email: {
                            required: true,
                            maxlength: 50
                        },
                        rol: {
                            required: true,
                        },                      
                       
                        image_profile: {
                            required: true,
                        },
                        pass: {
                            required: true,
                            maxlength: 64
                        },
                        repass: {
                            required: true,
                            equalTo: "#pass"
                        }

                    }
                }), form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), form.ajaxSubmit({
                    url: "/usuarios/crear/",
                    success: function(rpta, l, s, o) {
                        button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)                        
                            if (rpta.status == 200) { 
                            img_update.processQueue();                             
                            window.location.replace("/usuarios");   
                            } else {
                                notify(rpta, 'la la-warning', 'danger')
                                console.log(rpta)

                            } 
                      
                    }
                }))
            })
        },
        editUser = function() {
            $("#btn_edit").on("click", function(e) {
                e.preventDefault();

                var button = $(this),
                    form = $(this).closest("form");
                form.validate({
                    lang: 'es',
                       rules: {
                        
                        full_name: {
                            required: true,
                            maxlength: 50
                        },
                        last_name: {
                            required: true,
                            maxlength: 50
                        },   
                         repass: {                           
                            equalTo: "#passw"
                        }
                     

                    }
                }), form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), form.ajaxSubmit({
                    url: "/usuarios/edit/",
                    success: function(rpta, l, s, o) {
                        button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        img_update.processQueue();
                            if (rpta.status == 200) { 

                            window.location.replace("/usuarios/detalle/"+$('#default').val()    );   
                            } else {
                                notify('Hubo un error al registrar el usuario', 'la la-warning', 'danger')
                                console.log(rpta)

                            } 
                       
                    }
                }))
            })
        },
       
        restoreUser = function() {
            $(".restore").on("click", function(e) {
                e.preventDefault();
                console.log('ingreso')
                var button = $(this),
                    form = $(this).closest("form");
                form.validate({
                    lang: 'es',
                }), form.valid() && (button.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), form.ajaxSubmit({
                    url: "/usuarios/restore/",
                    success: function(rpta, l, s, o) {
                        button.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        setTimeout(function() {
                            if (rpta == "ok") {
                                notify('Se ha restaurado el usuario', 'flaticon-exclamation-2', 'success')
                                setTimeout(function() {
                                    window.location.replace("/usuarios");
                                }, 2000);
                            } else {
                                notify(rpta, 'la la-warning', 'danger')
                                console.log(rpta)
                            }
                        }, 1000)
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
         hideUser = function() {
            frm_delete.submit(function(e) {
                e.preventDefault()
                var id = $(this).data('idcontainer')
                var usuario = $('#id_user_delete').val()
                console.log(usuario)
                $("input[name='id_user_delete']").val($('#id_user_delete').val())
                $(this).ajaxSubmit({
                    url: "/usuarios/delete/",
                    success: function(rpta, r, n, l) {                    
                        reloadToken(rpta.token)
                        console.log(rpta)
                        $('#m_modal_confirm_delete').modal('hide')
                        if (rpta.status == 200) {
                            desactivar(id,rpta.token,usuario)

                            notify('El usuario ha sido desactivado', 'flaticon-exclamation-2', 'success') 

                        } else{
                            notify('El usuario no ha sido desactivado', 'flaticon-exclamation-2', 'danger')
                            console.log(rpta)
                        }  
                                              
                    }
                })
            })
        },
        showUser = function() {
            $("#frm_confirm_restore").submit(function(e) {
                e.preventDefault()
                var id = $(this).data('idcontainer')
               var usuario = $('#id_user_restore').val()
                  console.log(usuario)
                  $("input[name='id_user_restore']").val($('.confirm_action_activate').data('confirm'))
                $(this).ajaxSubmit({
                    url: "/usuarios/restore/",
                    success: function(rpta, r, n, l) {
                        
                        reloadToken(rpta.token)
                        
                        $('#m_modal_confirm_active').modal('hide')
                        if (rpta.status == 200) {
                            activar(id,rpta.token,usuario)

                            notify('El usuario ha sido restaurado', 'flaticon-exclamation-2', 'success') 

                        } else{
                            notify('El usuario no ha sido restaurado', 'flaticon-exclamation-2', 'danger')
                        }  
                                              
                    }
                })
            })
        },
        reloadToken = function(token) {
            $("input[name='token']").val(token);
        },

        deleteConfirm = function(){
           $(document).on('click', '.confirm_action_data', function(e){
                
            e.preventDefault();
            const dataConfirm = $(this).data('confirm');
            const dataId = $(this).data('idcontainer');
            $('#id_user_delete').val(dataConfirm);
            $('#frm_confirm_delete').data('idcontainer',dataId);
            })
        },
        restoreConfirm = function(){
            $(document).on('click', '.confirm_action_activate', function(e){                
            e.preventDefault();
            const dataConfirm = $(this).data('confirm');
            const dataId = $(this).data('idcontainer');
          
            $('#id_user_restore').val(dataConfirm);
            $('#frm_confirm_restore').data('idcontainer',dataId);
            })
        }


    return {
        init: function() {
            es(), newUser(), editUser(), restoreUser(),hideUser(),deleteConfirm(),restoreConfirm(),showUser()
        }
    }
}()
jQuery(document).ready(function() {
    SnippetUser.init()
})