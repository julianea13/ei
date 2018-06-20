<?php
namespace ThisApp\Aplication\System;

use \ThisApp\Aplication\Security\Token;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Aplication\Security\Session;

class Response
{
  static function ajax ($status, $detail){
    header('Content-Type: application/json');
    exit(json_encode(array("status"=>$status, "detail" => $detail,  "token"=>Token::create())));
  }

  static function validate($ajax, $entity, $action){
    if(!$ajax)      
      ErrorLog::throwNew('Acceso denegado',debug_backtrace(), '403' );

    $permisos = Session::get('user')['permisos'];
    foreach ($permisos as $k => $v){
      if($v->nombre == $entity){
        if($v->$action != 1){
          header('Content-Type: application/json');
          exit(json_encode(array("status"=>403, "detail" => 'Acceso denegado',  "token"=>Token::create())));          
        }
      }
    }    
  }

}

  