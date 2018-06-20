var updateCredentials = function() {
    var form = $("#frm_edit_access_credentials"),
    enviar = function() {        
        $("#btn_update_credentials").click(function(e) {
            e.preventDefault();                
            var btn = $(this);           
            form.validate({
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
                        maxlength: 20
                    }
                }
            }), form.valid() && (btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true), form.ajaxSubmit({
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
    };
    return {
        init: function() {
            enviar()
        }
    }
}();
jQuery(document).ready(function() {
    updateCredentials.init()
});