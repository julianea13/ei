<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Entities\files as oFiles;

class Files
{
  public $_data,
         $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }
public function getFile($belongs,$id_belongs){     
   $sql ="SELECT * FROM files where id_belongs = :id_belongs and belongs = :belongs";
    if ($this->_db->query($sql, array("id_belongs"=>$id_belongs,"belongs"=>$belongs))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }
  public function getCountFile($id,$belongs){     
   $sql ="SELECT  DISTINCT f.id_belongs,f.file_type FROM files f JOIN history h on f.id_belongs = h.id JOIN proyect p on h.id_belongs = p.id  where p.id = :id and f.belongs = :belongs";
    if ($this->_db->query($sql, array("id"=>$id,"belongs"=>$belongs))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }
  public function getFileAttr($belongs,$id_belongs){     
   $sql ="SELECT DISTINCT file_type FROM files where id_belongs = :id_belongs and belongs = :belongs;";
    if ($this->_db->query($sql, array("id_belongs"=>$id_belongs,"belongs"=>$belongs))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }

}