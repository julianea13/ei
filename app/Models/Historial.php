<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;


class Historial
{
  public $_data,
         $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }
public function getHistorial($belongs,$id_belongs){     
   $sql ="SELECT * FROM history where id_belongs = :id_belongs and belongs = :belongs";
    if ($this->_db->query($sql, array("id_belongs"=>$id_belongs,"belongs"=>$belongs))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }
 

}