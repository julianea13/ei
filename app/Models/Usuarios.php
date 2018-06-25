<?php
namespace ThisApp\Models;

use \ThisApp\Aplication\System\DB;
use \ThisApp\Aplication\Security\Hash;
use \ThisApp\Aplication\System\Config;
use \ThisApp\Aplication\Security\Session;
use \ThisApp\Aplication\Security\ErrorLog;
use \ThisApp\Entities\History;
use \ThisApp\Entities\User as eUser;


class Usuarios
{
  public $_data,
         $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }
public function getAll(){  
   $where = Session::get('user')['rol'] == '1' ? '' : ' where u.active = 1'; 
   
   $sql = "SELECT u.*, (SELECT Count(id) from proyect where id_cf = u.id ) as 'proyectos' FROM users u ".$where;
   
    if ($this->_db->query($sql)->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->results();
  }

    public function edituser($user, $rol) 
  {   
    $eUser = new eUser();    
    if($rol == 1){ 
    $eUser->setName($user['full_name']);
    $eUser->setLastName($user['last_name']);
    $eUser->setImage($user['image_profile']);
    $eUser->setIdRol($user['rol']);     

     if($user['passw'] == ""){
       $pass = $user['default1'];
       $eUser->setPassword($pass);
     
     if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updatesB())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
     }else{
        $salt = Hash::salt(32);
        $pass = Hash::make($user['passw'], $salt);
        $eUser->setSalt($salt);
        $eUser->setPassword($pass);       
      if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updates())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
     }
  
    }else{    
    if($user['passw']==""){
         $pass = Session::get('user')['password'];
    }else{
        $pass = Hash::make($user['passw'],Session::get('user')['salt']);
    }   
    $eUser->setName($user['full_name']);
    $eUser->setLastName($user['last_name']);
    $eUser->setImage($user['image_profile']);
    $eUser->setPassword($pass);     
      
    if (!$this->_db->update("users",array("field"=> "id", "value"=>$user['default'] ),$eUser->updatesA())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    }
    
  }
  public function getUserById($id){     
    if ($this->_db->query("SELECT * FROM users where id= :id", array("id" => $id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->first();
  }
  public function getSingle($id){    
   
   $sql = "SELECT * FROM users  where id = :id";
   
    if ($this->_db->query($sql, array("id"=>$id))->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');

    return $this->_db->first();
  }
    public function getRol()
  {   
    $sql = "SELECT * from rol";
    if ($this->_db->query($sql)->error())
      ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');
    return $this->_db->results();
  }
  public function setUser(eUser $user)
  {

    if (!$this->_db->insert('users', $user->inserts())->error()) {
      $rpta = $this->_db->lastId();
     return true;
    }else{
      return false;
    }
  }
    public function userExists($field, $value, $activo = 1){
    $table = 'users';
    $sql = "SELECT COUNT(id) as cant FROM {$table} WHERE {$field} = :value";
    if ($this->_db->query($sql, array("value" => $value))->error() === false ) {
        if($this->_db->first()->cant == 0 )
          return false;
        else
          return true;
      }else{
        return false;
      }
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
 public function getIdP(){
   if ($this->_db->query("SELECT MAX(id) AS 'users' from users")->error() == true)
     ErrorLog::throwNew( $this->_db->errDesc(), debug_backtrace(), '500');   
    return $this->_db->first();
  }  

  public function delete($id) 
  {   
    $eUser = new eUser(); 

    $eUser->setActive('0');       
    if (!$this->_db->update("users",array("field"=> "id", "value"=>$id),$eUser->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }
  public function restore($id)  
  {   
    $eUser = new eUser(); 

    $eUser->setActive('1');       
    if (!$this->_db->update("users",array("field"=> "id", "value"=>$id),$eUser->delete())->error()) {
      $rpta = $this->_db->lastId();
      return true;
    }else{
      return false;
    }
    
  }
}