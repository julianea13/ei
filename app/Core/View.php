<?php
namespace ThisApp\Core;
//Necesarias para crear la master
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\Token;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Hash;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
//Remove on production
use \Twig_Extension_Debug;
/**
*
*/
class View
{

    static function show($view, $data = []){
    // $session = false;
    // if (Session::exists(Config::get('session/session_name'))) { 
    $usd = null;   
    if(Session::exists("user")){
      $usd = Session::get("user");      
      $usd['id'];
    }
    $system_metas = Config::get("system/metas");
    if(array_key_exists("system_metas", $data))
      $system_metas = $data["system_metas"];

    //SE ESTA ENVUADO DEMASIADA INFORMACION DE LA SESION, CONTROLAR ESO
    
    $masterData = array(
      "token" => Token::create(),
      "flash" => Session::getFlash(),
      "system_metas" => $system_metas,
      "usd" => $usd ,
      "id_user_usd"=>Hash::encrypt($usd['id'])  
    );
    //Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem(__DIR__.'/../Views');
    $twig = new Twig_Environment($loader, array('debug' => true));
    $twig->addExtension(new Twig_Extension_Debug());
    // $twig = new Twig_Environment($loader, array(
    // 'cache' => '../app/Views/cache',
    // ));
    $template = $twig->load($view.".html");
    echo $template->render(array_merge($data, $masterData));
    exit;
  }
}