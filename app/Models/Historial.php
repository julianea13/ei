<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Entities\History;


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
  public function getSingle($id){     
   $sql ="SELECT h.description as 'history_description', h.tags,h.active,h.id_cf,h.id as 'id_history',f.*,u.nick,u.name,u.last_name FROM history h join files f on f.id_belongs = h.id and f.belongs = 3 JOIN users u on u.id = h.id_cf where h.id=:id;";
    if ($this->_db->query($sql, array("id"=>$id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->first();
  }
  public function edit($history) 
  {   
    
      $eHistory = new History();    
      $eHistory->setDescription($history['text_long']);
      $eHistory->setTags($history['tags']);    
       
    if (!$this->_db->update("history",array("field"=> "id", "value"=>$history['default']),$eHistory->updates())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }

   
public function getId(){
   if ($this->_db->query("SELECT `auto_increment` as 'history' FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'history'")->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');   
    return $this->_db->first();
  }
   public function getIdCf($id){     
    if ($this->_db->query("SELECT id_cf FROM history where id = :id", array('id'=>$id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->first();
  }

  public function setHistory(History $History)
  {    
    if (!$this->_db->insert('history', $History->inserts())->error()) {
      $rpta = $this->_db->lastId();
      return true;      
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }

}