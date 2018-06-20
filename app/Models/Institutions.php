<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Entities\Institution;

class Institutions
{
  public $_data,
         $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  public function getCustom($value){

    $sql ="SELECT i.image, i.institution_name, t.type_name, i.image_inst, i.id_institution FROM institutions i JOIN institution_type t WHERE t.id_type = i.id_type and i.id_institution = :id_institution";
    if($this->_db->query($sql, array("id_institution" => $value))->error())
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->first();
  }

    public function edit($institution)
  {

      $eInstitution = new Institution();

      $eInstitution->setInstitutionName($institution['name']);
      $eInstitution->setIdType($institution['type']);
      $eInstitution->setImage($institution['shield']);
      $eInstitution->setImageInst($institution['portada']);

    if (!$this->_db->update("institutions",array("field"=> "id_institution", "value"=>$institution['id_institution'] ),$eInstitution->updates())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }

  }

  public function delete($id)
  {

    $eInst = new Institution();

    $eInst->setActive('0');
    if (!$this->_db->update("institutions",array("field"=> "id_institution", "value"=>$id),$eInst->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }

  }
    public function restore($id)
  {
    $eInst = new Institution();

    $eInst->setActive('1');
    if (!$this->_db->update("institutions",array("field"=> "id_institution", "value"=>$id),$eInst->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }

  }

  public function getinstitutions($id){
    $sql = "SELECT * FROM institution where id = :id";
   if ($this->_db->query($sql, array('id' => $id))->error() == true)
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

   return $this->_db->first();
  }

  public function getAll(){
    if ($this->_db->query("SELECT (select count(id) from proyect where id_institution = i.id) as 'proyectos' , i.* from institution i")->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->results();
  }

  public function getId(){
   if ($this->_db->query("SELECT MAX(id_institution) AS 'institution' from institutions")->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->first();
  }

  public function getType(){
    $sql = "SELECT * FROM institution_type";
    if($this->_db->query($sql)->error())
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }

 public function getInstitutionById($id){
    if ($this->_db->query("SELECT * FROM institutions where id_institution= :id", array("id" => $id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->first();
  }
  //setters



public function setInst(Institution $ins)
  {
    if (!$this->_db->insert('institutions', $ins->inserts())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }

  public function serIntitution($comment, $id_clover){

    $user = Session::get("user");
    $ca = date('Y-m-d H:i:s');
    $eC = new eComment();
    $eC->setIdCf(intval($user['id']));
    $eC->setCa($ca);
    $eC->setIdClover(intval($id_clover));
    $eC->setComent($comment);
    if(!$this->_db->insert('clover_comments', $eC->inserts())->error()){
      return true;
    }else{
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500', null, true);
      return false;
    }
  }

  private function makeEntity($institution){
      $eInstitution = new Institution();

      $eInstitution->setIdInstitution($institution->id_institution);
      $eInstitution->setInstitutionName($institution->institution_name);
      $eInstitution->setPhone($institution->phone);
      $eInstitution->setAdress($institution->adress);
      $eInstitution->setType($institution->type);
      $eInstitution->setCa($institution->ca);
      $eInstitution->setIdCf($institution->id_cf);

      return  $eInstitution;
  }



}
