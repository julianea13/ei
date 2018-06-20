<?php
namespace ThisApp\Controllers;

use \ThisApp\Core\View;
use \ThisApp\Aplication\System\Config;

class Err {

  private $_request,
      $_qs;

  public function __construct($request = null){
    $this->_request =  $request;
    $this->_qs = $request->all();
  }

  public function index($actions = null)
  {
    $this->show(array('404'));
  }

  public function show($actions = null)
  {
    $errors = Config::get('system/errors');
    if(isset($actions[0]) && in_array($actions[0], $errors))
      View::show($actions[0],array());
    else
      $this->index();
  }
}
