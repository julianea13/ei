
var SnippetNotify = function() {
  
         notify = function(mensaje, icono, tipo) {
            // init bootstrap switch
            $('[data-switch=true]').bootstrapSwitch();
            // handle the demo

            var content = {};
            //Agregar Mensaje
            content.message = mensaje;
            // Agregar icono
            content.icon = 'icon ' + icono;           
            var notify = $.notify(content, {
                // notify sate color success,danger,warning...
                type: tipo,
                allow_dismiss: false,
                newest_on_top: false,
                mouse_over: true,
                showProgressbar: false,
                spacing: 10,
                timer: 2000,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                offset: {
                    x: 30,
                    y: 30
                },
                delay: 1000,
                z_index: 10000,
                animate: {
                    enter: 'animated ' + 'bounce',
                    exit: 'animated ' + 'bounce'
                }
            });
        }

    return {
        init: function() {
           
        }
    }
}()
jQuery(document).ready(function() {
    SnippetNotify.init()
})