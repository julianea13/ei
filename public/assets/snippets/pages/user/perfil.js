var SnippetLogin = function() {
    var form_info = $("#frm_edit_current_user"),
        form_credentials = $("#frm_edit_access_credentials");
    ciudades = function(){
        $(document).on("change", "#departamento", function(e) {
            e.preventDefault();
            var id_dpto = $(this).val()
            $("#departamento option:selected").each(function () {
                erease()
                $.ajax({
                    url : '/Ciudad/get/'+id_dpto,
                    type : 'POST',
                    success :function(data){
                        if(data == "err"){
                            error(form_info, "danger", "No fué posible obtener las ciudades, intenta más tarde.")
                             $("#ciudad").html("<option value>Seleccione departamento</option>");
                             $("#ciudad").selectpicker('refresh'); 
                             return
                        }
                        if(id_dpto == ""){
                            $("#ciudad").html("<option value>Seleccione departamento</option>");
                            return
                        }
                        //iterar los resultado y construir los option, y hacer lo appends
                        $("#ciudad").html("<option value>Ciudad</option>");         
                        $.each(data,function(i,v){
                            $("#ciudad").append("<option value="+v.id_ciudad+">"+v.ciudad+"</option>");
                        });
                    }
                });
            });
        })
    },
    language = function(){
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
    error = function(type, msj) {
        var alertElement = $('<div class="alert-container form-group m-form__group row" ><div class="col-12"><div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\t\t\t<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\t\t\t<span>'+msj+'</span>\t\t</div></div></div>');
        form_info.find(".alert-container").remove(), alertElement.prependTo(form_info), alertElement.animateClass("fadeIn animated")          
        $("html, body").stop().animate({scrollTop:0}, 500, 'swing', function(){});
    },
    erease = function(){
        form_info.find(".alert-container").remove()
    },
    information = function() {
        $("#btn_save_update_user").click(function(e) {
            e.preventDefault();                
            var btn = $(this);           
            form_info.validate({
                lang: 'es',
                rules: {
                    nombre: {
                        required: true,
                        maxlength: 45
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
                    departamento: {
                        required: true
                    },
                    ciudad: {
                        required: true
                    }
                }
            }), form_info.valid() && (btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true), form_info.ajaxSubmit({
                url: "/user/actualizar",
                success: function(rpta, l, s, o) {
                    console.log(rpta)
                    if(rpta != "ok"){                        
                        if(rpta != "err")
                         notifyGlobal("warning", "Intenta de nuevo más tarde", "Algo salió mal", "la la-warning")
                        else
                           notifyGlobal("danger", "Informa de inmediato al proveedor del sistema. ", "Algo anda mal",  "la la-warning")                        
                    }else{
                        notifyGlobal("primary", "Sus datos fueron actualzados correctamente", "Datos actualizados",  "la la-save")
                    }
                    setTimeout(function() {        
                        btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false);                                
                    }, 1000)
                }
            }))
        })         
    },
    credentials = function() {        
        $("#btn_update_credentials").click(function(e) {
            e.preventDefault();                
            var btn = $(this);           
            form_credentials.validate({
                lang: 'es',
                rules: {                    
                    clavev: {
                        required: true,
                        maxlength: 20
                    },
                    claven: {
                        required: true,
                        maxlength: 20
                    },
                    reclaven: {
                        required: true,
                        maxlength: 20,
                        equalTo: "#reclaven"
                    }
                }
            }), form_credentials.valid() && (btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true), form_credentials.ajaxSubmit({
                url: "/user/credentials",
                success: function(rpta, l, s, o) {
                    console.log(rpta)
                    if(rpta != "ok"){                        
                        if(rpta != "diff"){                            
                            if(rpta == "pass")
                                notifyGlobal("warning", "Verifica la información e intenta de nuevo", "Datos no válidos", "la la-warning")
                            else
                                notifyGlobal("danger", "Informa de inmediato al proveedor del sistema. ", "Algo anda mal",  "la la-warning")
                        }else{
                           notifyGlobal("warning", "Verifica la información e intenta de nuevo", "Las claves no son iguales",  "la la-warning")                        
                        }
                    }else{
                        notifyGlobal("primary", "Sus datos fueron actualzados correctamente", "Datos actualizados",  "la la-save")
                    }

                    setTimeout(function() {
                        btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false);
                        form_credentials.clearForm();
                        form_credentials.resetForm();
                    }, 1000)
                }
            }))
        })         
    };
    return {
        init: function() {
            language(), ciudades(), information(), credentials()
        }
    }
}();
jQuery(document).ready(function() {
    SnippetLogin.init()
});