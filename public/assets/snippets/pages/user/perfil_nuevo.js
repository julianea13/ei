var newProfile = function() {
    var form = $("#frm_new_user");        
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
    nuevo = function() {
        $("#btn_perfil_nuevo").click(function(e) {
            e.preventDefault();                
            var btn = $(this);           
            form.validate({
                lang: 'es',
                rules: {
                    nombre: {
                        required: true,
                        maxlength: 45
                    },
                    correo: {
                        required: true,
                        maxlength: 50
                    }
                }
            }), form.valid() && (btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true), form.ajaxSubmit({
                url: "/user/create",
                success: function(rpta, l, s, o) {
                    console.log(rpta)
                    if(rpta != "ok"){
                        if(rpta != "exists"){
                          if(rpta == "correo")
                            notifyGlobal("warning", "No pudimos enviarle correo, informale sobre la invitación", "Usuario creado", "la la-warning")
                          else
                            notifyGlobal("danger", "Informa de inmediato al proveedor del sistema", "Algo anda mal",  "la la-warning")                            
                        }else{
                            notifyGlobal("warning", "El correo que ingresaste ya está registrado", "El correo ya existe",  "la la-warning")
                        }                    
                    }else{
                        window.location.replace("/propuesta");
                        //notifyGlobal("primary", "Sus datos fueron actualzados correctamente", "Datos actualizados",  "la la-save")
                    }
                    setTimeout(function() {        
                        btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false);                                
                    }, 1000)
                }
            }))
        })         
    };
    return {
        init: function() {
            language(), nuevo()
        }
    }
}();
jQuery(document).ready(function() {
    newProfile.init()
});