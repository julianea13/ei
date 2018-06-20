var DropzoneDemo=function() {
    var e=function() {
        Dropzone.options.mDropzoneOne= {            
            paramName:"file",
            maxFiles:1,
            maxFilesize:5,
            accept:function(e, o) {
                "justinbieber.jpg"==e.name?o("Naha, you don't."): o();
                console.log("uno");
            }
        }
        ,
        Dropzone.options.mDropzoneTwo= {
            paramName:"file",
            maxFiles:10,
            maxFilesize:10,
            accept:function(e, o) {
                "justinbieber.jpg"==e.name?o("Naha, you don't."): o();
                console.log("dos");
            }
        }
        ,
        Dropzone.options.mDropzoneThree= {
            paramName:"file",
            maxFiles:10,
            maxFilesize:10,
            acceptedFiles:"image/*,application/pdf,.psd",
            accept:function(e, o) {
                "justinbieber.jpg"==e.name?o("Naha, you don't."): o();
                console.log("tres");
            }
        }
    }
    ;
    return {
        init:function() {
            e()
            console.log(e)
        }
    }
}
();
DropzoneDemo.init();