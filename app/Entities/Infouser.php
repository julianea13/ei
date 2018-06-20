<?php

namespace ThisApp\Entities;

class Infouser
{
  private $id,
          $cont,
          $id_user;
        
        
          


//SETTERS
  
  public function setId($id){
      $this->id = $id;
  } 
   public function setCont($cont){
      $this->cont = $cont;
  } 
   public function setIdUser($id_user){
      $this->id_user = $id_user;
  } 
  
 
     
  
  

//GETTERS

  public function getId(){
      return $this->id;
  }  
  public function getCont(){
      return $this->cont;
  }  
   
    public function getIdUser(){
      return $this->id_user;
  }  
   
 


   public function inserts(){
    return array(
      "cont"=>$this->cont,   
      "id_user"=>$this->id_user 
      );    
  }

}