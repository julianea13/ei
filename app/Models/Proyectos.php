<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Entities\Proyect;
use \ThisApp\Entities\Files;

class Proyectos
{
  public $_data,
         $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }
public function getSingle($id){     
   $sql ="SELECT p.*, i.name as 'institution_name', i.municipio,i.active,i.image,i.shield,i.id as 'id_institution' FROM proyect p JOIN institution i on p.id_institution = i.id WHERE p.id=:id";
    if ($this->_db->query($sql, array("id"=>$id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->first();
  }
  public function getCategory(){     
   $sql ="SELECT * FROM category";
    if ($this->_db->query($sql)->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }
  public function getInstitution(){     
   $sql ="SELECT * FROM institution";
    if ($this->_db->query($sql)->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }

public function edit($proyect) 
  {   

      $eProyect = new Proyect();    
      $eProyect->setCloverName($proyect['name']);
      $eProyect->setShortDescription($proyect['text_short']);     
      $eProyect->setLongDescription($proyect['text_long']); 
      $eProyect->setCloverImage($proyect['image_pro']);     
    if (!$this->_db->update("clovers",array("field"=> "id_clover", "value"=>$proyect['id'] ),$eProyect->updates())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }

 public function getIdCf($id){     
    if ($this->_db->query("SELECT id_cf FROM proyect where id = :id", array('id'=>$id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->first();
  }
    public function delete($id) 
  {   
    $eClover = new Proyect(); 

    $eClover->setActive('0');       
    if (!$this->_db->update("clovers",array("field"=> "id_clover", "value"=>$id),$eClover->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }
    public function restore($id) 
  {   
    $eClover = new Proyect(); 

    $eClover->setActive('1');       
    if (!$this->_db->update("clovers",array("field"=> "id_clover", "value"=>$id),$eClover->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }
  
  public function getAll(){  
    $where = Session::get('user')['rol'] == '1' ? '' : ' and c.active = 1 '; 
   $sql ="SELECT p.id,p.name,p.description,p.tag,i.image,p.active,i.name as institutionname ,c.name as categoryname from proyect p JOIN institution i on i.id = p.id_institution JOIN category c on c.id = p.id_Category;".$where." ORDER BY c.active DESC";
    if ($this->_db->query($sql)->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->results();
  }
 

public function setProyect(Proyect $Proyect)
  { 
    if (!$this->_db->insert('proyect', $Proyect->inserts())->error()) {
      $rpta = $this->_db->lastId();
      return true;      
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }

  public function setArchives(Files $Files)
  { 
    if (!$this->_db->insert('files', $Files->inserts())->error()) {
      $rpta = $this->_db->lastId();
      return true;      
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }
  public function setTags(Tags $tags)
  { 
    
    if (!$this->_db->insert('tags', $tags->inserts())->error()) {
      $rpta = $this->_db->lastId();
      return true;      
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }
  

  
public function getId(){
   if ($this->_db->query("SELECT `auto_increment` as 'proyect' FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'proyect'")->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');   
    return $this->_db->first();
  }

  private function makeEntity($clover){
      $eClover = new Proyect();

      $eClover->setId($clover->id);
      $eClover->setName($clover->name);
      $eClover->setDescription($clover->description);
      $eClover->setTag($clover->tag);
      $eClover->setIdCategory($clover->id_Category);
      $eClover->setActive($clover->active);
      $eClover->setIdCf($clover->id_cf);
      $eClover->setCa($clover->ca);
      $eClover->setIdInstitution($clover->id_institution);      

      return  $eClover;   
  }

  

}