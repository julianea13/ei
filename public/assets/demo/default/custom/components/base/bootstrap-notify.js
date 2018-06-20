//== Class definition

var BootstrapNotifyDemo = function() {

    //== Private functions

    // basic demo
    var demo = function(mensaje, icono, tipo) {
        // init bootstrap switch
        $('[data-switch=true]').bootstrapSwitch();
        // handle the demo
        $('.m_notify_btn').click(function() {
            var content = {};
            //Agregar Mensaje
            content.message = mensaje;

            // Agregar icono
            content.icon = 'icon ' + icono;

            console.log($('#m_notify_placement_from').val());
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


        });
    }

    return {
        // public functions
        init: function() {
            demo();
        }
    };
}();

jQuery(document).ready(function() {
    BootstrapNotifyDemo.init();
});