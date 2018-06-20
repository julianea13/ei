var SnippetLogin = function() {
    var form = $("#frm_propuesta_nueva"),
        first = true,
        list_participantes = [],
    ciudades = function(){
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
    error = function(type, msj, erase) {
        erase = erase || false;
        if(erase){
            form.find(".alert").remove()
            return
        }
        var alertElement = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\t\t\t<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\t\t\t<span>'+msj+'</span>\t\t</div>');
        form.find(".alert").remove(), alertElement.prependTo(form), alertElement.animateClass("fadeIn animated")          
        $("html, body").stop().animate({scrollTop:0}, 500, 'swing', function(){});
    },
    enviar = function() {
        //CONTROL PARA EL BOTON ENVIAR Y FINALIZAR
        $("#btn_propuesta_nueva_send").click(function(e) {
            e.preventDefault();                
            var btn = $(this);           
            form.validate({
                lang: 'es',
                rules: {
                    titulo: {
                        required: true,
                        maxlength: 50
                    },
                    estado: {
                        required: false,
                    },
                    resumen: {
                        required: false,
                        maxlength: 200
                    },
                    antecendentes: {
                        required: false,
                        maxlength: 200
                    },
                    problema: {
                        required: false,
                         maxlength: 200
                    },
                    justificacion: {
                        required: false,
                         maxlength: 200
                    },
                    obj_generales: {
                        required: false,
                        maxlength: 120
                    },
                    obj_especificos: {
                        required: false,
                        maxlength: 120
                    },
                    m1: {
                        required: false,
                        maxlength: 120
                    },
                    m2: {
                        required: false,
                        maxlength: 120
                    },
                    m3: {
                        required: false,
                        maxlength: 120
                    },
                    impacto: {
                        required: false,
                        maxlength: 120
                    },
                    productos: {
                        required: false,
                        maxlength: 120
                    },
                    riesgos: {
                        required: false,
                        maxlength: 120
                    },
                    presupuesto: {
                        required: false,
                        digits: true
                    },
                    bibliografia: {
                        required: false,
                        maxlength: 120
                    },
                    obsevaciones: {
                        required: false,
                        maxlength: 200
                    }
                }
            }), form.valid() && (btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true), form.ajaxSubmit({
                url: "/propuesta/guardar",
                success: function(rpta, l, s, o) {
                     console.log(rpta); 
                    if(rpta != "ok"){                        
                        if(rpta != "err"){
                            notifyGlobal("warning", "Intenta de nuevo más tarde", "Algo salió mal", "la la-warning")
                        }else{
                            notifyGlobal("danger", "Algo anda mal", "Informa de inmediato al proveedor del sistema. ", "la la-warning")
                        }
                    }
                    setTimeout(function() {                            
                        if (rpta == "ok"){
                                // btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false);
                                // form.clearForm()
                                window.location.replace("/propuesta");
                            }else{                
                                btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false);                                
                            }
                        }, 1000)
                }
            }))
        })
    },
    valEmail = function(emailAddress){
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
    },
    participantes = function(){
        $(document).on("click", "#btn_crea_participante", function(e){
            e.preventDefault();
            console.log($(this).attr("id"));
            $("label, .m-form__help").removeClass("err");
            //btn_propuesta_save_continue
            var btn = $(this);
            btn.addClass("m-loader m-loader--right m-loader--light").attr("disabled", true)
            let nom = $('#nombre_participante'), mail = $('#correo_participante')
            let errCounter = 0
            
            if(nom.val() == ""){
                 nom.closest(".form-group").find("span.m-form__help").html("Debe ingresar un nombre").addClass('err')
                 nom.closest(".form-group").find("label").addClass('err') 
                 errCounter++
            }else{
                if(nom.val().length > 50){
                    nom.closest(".form-group").find("span.m-form__help").html("No debe superar los 50 caracteres").addClass('err')
                    nom.closest(".form-group").find("label").addClass('err')                
                    errCounter++
                }
            }
            
            if(mail.val() == "" ){
                mail.closest(".form-group").find("span.m-form__help").html("Debe ingresar un correo").addClass('err') 
                mail.closest(".form-group").find("label").addClass('err')
                errCounter++
            }else{
                if(mail.val().length > 50){
                    mail.closest(".form-group").find("span.m-form__help").html("No debe superar los 50 caracteres").addClass('err') 
                    mail.closest(".form-group").find("label").addClass('err')
                    errCounter++
                }else{
                    if(!valEmail(mail.val())){
                        mail.closest(".form-group").find("span.m-form__help").html("Debe ingresar un correo válido").addClass('err')
                        mail.closest(".form-group").find("label").addClass('err') 
                        errCounter++
                    }else{
                        if(exists(mail.val())){
                            mail.closest(".form-group").find("span.m-form__help").html("El participante ya existe").addClass('err')
                            mail.closest(".form-group").find("label").addClass('err') 
                            errCounter++
                        }
                    }
                }
            }           
           
            if(errCounter == 0){                
                if(crearParticipantes(nom.val(), mail.val().toLowerCase())){
                    nom.val(""), mail.val("")
                }else{

                }                
                 btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false)
            }else{
                 btn.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", false)
                console.log("nanai")
            }
        })
    },
    deleteParticipante = function(){
        $(document).on("click", "#btn_delete_participante", function(e){
            let id = $(this).data("delete-id")
            $.each(list_participantes, function(i, v){
                if(v.id == id){
                    list_participantes.splice(i, 1)
                    $("#"+id).remove()
                    $("#participantes").val(JSON.stringify(list_participantes))
                    $("#modal_confirm_delete_participante").modal("hide")
                    return false
                }
            })
            console.log(list_participantes)
        });
    },
    triggerModalDelete = function(){
        $(document).on("click", ".delete-participante", function(e){
            e.preventDefault()
            let titulo = $("#titulo_modal_confirm_delete_participante")
            let btn = $("#btn_delete_participante")
            let id = $(this).data("delete-id")
            let nombre = $(this).data("delete-name")
            titulo.html("¿Desea eliminar a "+nombre+"?")
            btn.data("delete-id", id)
        });
    },
    cargarParticipantes = function(p, firstLoad){
        if(!firstLoad)
            list_participantes.push(p)
        $("#participantes").val(JSON.stringify(list_participantes))
        $("#no_data_row").remove()
        $("#participantes_list tbody").append("<tr id='"+p.id+"'><td>"+p.nombre+"</td><td>&nbsp;</td><td>"+p.correo+"</td><td>&nbsp;</td><td><a class='delete-participante' data-delete-id='"+p.id+"' data-delete-name='"+p.nombre+"' href='#' data-toggle='modal' data-target='#modal_confirm_delete_participante'><span class='m-badge m-badge--danger'>&nbsp;Eliminar&nbsp;</span></a></td></tr>")
        return true
    },
    firstLoadParticipantes = function(){
        let part = $("#participantes").val()
        if(part.length > 0){
            list_participantes = JSON.parse(part)
            $.each(list_participantes, function(i, v){
                cargarParticipantes(v, true);
            })
        }
        console.log(list_participantes)
    },
    crearParticipantes =  function(nom, mail){
        idk = uniqk()
        nuevo_participante = {"id" : idk, "nombre" : nom, "correo" : mail}
        return cargarParticipantes(nuevo_participante, false)
    },
    uniqk = function(){
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
              .toString(16)
              .substring(1);
        }
        return s4() + s4() + s4() + s4();
    },
    exists = function(email){
        encounters = 0
        $.each( list_participantes, function(i, v){
            if(v.correo.toLowerCase() == email.toLowerCase()){
                encounters++
            }
        })
        return encounters != 0
    };
    return {
        init: function() {
            language(), ciudades(), enviar(), participantes(), firstLoadParticipantes(), triggerModalDelete(), deleteParticipante()
        }
    }
}();
jQuery(document).ready(function() {
    SnippetLogin.init()
});