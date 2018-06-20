<?php

namespace ThisApp\Entities;

class Rol
{
  private $id_rol,
          $rol_name,
          $rol_description;
         
         
          

//SETTERS
  
  public function setId($id_rol){
      $this->id_rol = $id_rol;
  }
  public function setName($rol_name){
      $this->rol_name = $rol_name;
  }
  public function setDescription($rol_description){
      $this->rol_description = $rol_description;
  }

//GETTERS

 public function getId(){
      return $this->id_rol;
  }  
  public function getName(){
      return $this->rol_name;
  }  
  public function getDescription(){
      return $this->rol_description;
  }  

   public function inserts(){
    return array(              
      "rol_name "=>$this->rol_name, 
      "description "=>$this->rol_description                  
      );
  }
 
}