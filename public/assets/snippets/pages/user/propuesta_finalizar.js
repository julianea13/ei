var propuesta = function() {
    var id_prop = "",
        url = "/propuesta/finalizar/",
        type = "POST",
    finalizar = function(){
        $(document).on("click", ".endit-all", function(e) {
            e.preventDefault()
            id_prop = $(this).data("idp")
                $.ajax({
                    url : url+id_prop,
                    type : type,
                    success :function(data){
                        console.log(data)
                        if(data != "ok"){
                            if(data == "docs"){
                                notifyGlobal("warning", "No puedes finalizar si te faltan documentos.", "Carga la documentación", "la la-warning")
                            }else{
                                if(data == "fields")
                                    notifyGlobal("warning", "Diligencia todo el formulario para finalizar la propuesta.", "Tienes información pendiente", "la la-warning")
                                else
                                    notifyGlobal("danger", "Informa de inmediato al proveedor del sistema. ", "Algo anda mal", "la la-warning")                                    
                            }
                        }else{
                            notifyGlobal("brand", "Tu propuesta fue registrada, !buena suerte! ", "¡Felicitaciones!",  "la la-warning")
                            reorder();
                        }
                    }
                });
        })
    },
    reorder = function(){
        $("#end_prop_"+id_prop).remove(),
        $("#edit_prop_"+id_prop).remove(),
        $("#document_upload_msg").remove(),
        $(".document_upload_container").remove();
    };
    return {
        init: function() {
            finalizar()
        }
    }
}();

$(document).ready(function() {
    propuesta.init()
});