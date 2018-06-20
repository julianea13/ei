$(document).ready(function() {
  var url=window.location.pathname.replace(/^\/([^\/]*).*$/, '$1');
console.log(url);
    // CODIGO VER MAS
    $('.ie-search').children(".hide-toshow").slice(0, 6).show();
    $('.pt-search').children(".hide-toshow").slice(0, 6).show();
    $('.co-search').children(".hide-toshow").slice(0, 6).show();
    console.log($('.ie-search').children(".hide-toshow:hidden").length)
    $('.ie-search').children(".hide-toshow:hidden").length < 1 ? $('#loadMorePr').addClass('hide') : $('#loadMorePr').removeClass('hide')
    $('.pt-search').children(".hide-toshow:hidden").length < 1 ? $('#loadMorePa').addClass('hide') : $('#loadMorePa').removeClass('hide')
    $('.co-search').children(".hide-toshow:hidden").length < 1 ? $('#loadMoreCo').addClass('hide') : $('#loadMoreCo').removeClass('hide')
    $("#loadMorePr").on('click', function(e) {
        console.log('entra');
        e.preventDefault();
        $('.ie-search').children(".hide-toshow:hidden").slice(0, 6).slideDown();
        console.log($('.ie-search').children(".hide-toshow:hidden").length)
        $('.ie-search').children(".hide-toshow:hidden").length == 0 ? $('#loadMorePr').addClass('hide') : $('#loadMorePr').removeClass('hide')

    });
    $("#loadMorePa").on('click', function(e) {
        console.log('entra');
        e.preventDefault();
        $('.pt-search').children(".hide-toshow:hidden").slice(0, 6).slideDown();
        $('.pt-search').children(".hide-toshow:hidden").length == 0 ? $('#loadMorePa').addClass('hide') : $('#loadMorePa').removeClass('hide')
    });
    $("#loadMoreCo").on('click', function(e) {
        console.log('entra');
        e.preventDefault();
        $('.co-search').children(".hide-toshow:hidden").slice(0, 6).slideDown();
        $('.co-search').children(".hide-toshow:hidden").length == 0 ? $('#loadMoreCo').addClass('hide') : $('#loadMoreCo').removeClass('hide')
    });

    //  FIN CODIGO VER MAS
    //  -------------------------------------------------
    // CODIGO ALERTS


    // FIN CODIGO ALERTS

    // VALIDATE FORM


    $('#val-inf').click(function() {
        var nombre = $('#new-user-name').val();
        var emil = $('#exampleInputEmail1').val()
        var pass = $('#exampleInputPassword1').val()
        var emailTest = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!/^[a-zA-Z\s]*$/.test(nombre) || nombre == '') {
            console.log('Leters only');
            $('.name-check-error').removeClass('hide')
            $('#new-user-name').addClass('error-box')
        } else {
            $('.name-check-error').addClass('hide')
            $('#new-user-name').removeClass('error-box')
        }
        if (!emailTest.test(emil) || emil == '') {
            $('.mail-check-error').removeClass('hide')
            $('#exampleInputEmail1').addClass('error-box')
        } else {
            $('.mail-check-error').addClass('hide')
            $('#exampleInputEmail1').removeClass('error-box')
        }
        if (!(emil == $('#repeatemail').val()) || $('#repeatemail').val() == '') {
            $('.mailch-check-error').removeClass('hide')
            $('#repeatemail').addClass('error-box')
        } else {
            $('.mailch-check-error').addClass('hide')
            $('#repeatemail').removeClass('error-box')
        }
        if (!(pass.match(/[a-z]/) && pass.match(/[0-9]/) && pass.length > 4) || pass == '') {
            $('.pass-check-error').removeClass('hide')
            $('#exampleInputPassword1').addClass('error-box')
        } else {
            $('.pass-check-error').addClass('hide')
            $('#exampleInputPassword1').removeClass('error-box')
        }
        if (!(pass == $('#repeat-pass').val()) || $('#repeat-pass').val() == '') {
            $('.passch-check-error').removeClass('hide')
            $('#repeat-pass').addClass('error-box')
        } else {
            $('.passch-check-error').addClass('hide')
            $('#repeat-pass').removeClass('error-box')
        }
        if ($('.m-form').find('.error-box').length > 0) {
          $('.alert-default').removeClass('hide')
        }

    })

    // END VALIDATE FORM
//icon toggle
$('#m_aside_left_minimize_toggle').addClass('m-brand__toggler')
$('#m_aside_left_minimize_toggle').click(function(){
  if ($(this).hasClass('m-brand__toggler')) {
    console.log('Tiene');
    $('#m_ver_menu').addClass('toggle-menus')
    $('#m_ver_menu').removeClass('normal-menu')   
    $('.tt').addClass('hide')
    $('.icon-tittle').addClass('midlin')

    $(this).removeClass('m-brand__toggler');

  }else {
    console.log('no tiene');
    $(this).addClass('m-brand__toggler')
    $('#m_ver_menu').addClass('normal-menu')
      $('.tt').removeClass('hide')
      $('.icon-tittle').removeClass('midlin')
  }

})






//icon toggle
//Botones activos meniuu

// $('.m-menu__nav').find('li').each(function(){
//   if($(this).attr('name') == url){
//     console.log($('.m-menu__nav').find('li').attr('name'));
//     $(this).addClass('m-menu__item--active')
//     $(this).addClass('theme_trebol_active')
//   }
// })


//botones activos meniuu

});

 function desactivar(id,token,value){
  $('#'+id).addClass('inactive');
   let _delete =  $('#'+id).find('.confirm_action_data')
      $(_delete).replaceWith(' <button type="button" class="m-btn btn btn-primary confirm_action_activate" data-token="'+token+'" data-idcontainer="'+id+'" data-toggle="modal" data-confirm="'+value+'"  data-target="#m_modal_confirm_active"><i class="la la-check"></i></button> ')

}

function activar(id,token,value){
    console.log('id: '+id+'\n'+'token: '+token+'\n'+'value: '+value);
  $('#'+id).removeClass('inactive');
   let _restore =  $('#'+id).find('.confirm_action_activate')
   $(_restore).replaceWith(' <button type="button" class="m-btn btn btn-danger confirm_action_data" data-token="'+token+'" data-idcontainer="'+id+'" data-toggle="modal" data-confirm="'+value+'"  data-target="#m_modal_confirm_delete"><i class="la la-remove"></i></button>')
}
