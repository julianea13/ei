<?php

namespace ThisApp\Entities;

class Municipio
{
  private $id,
          $name;
//SETTERS

  public function setId($id){
      $this->id = $id;
  }
   public function setName($name){
      $this->name = $name;
  }





//GETTERS

  public function getId(){
      return $this->id;
  }
  public function getName(){
      return $this->name;
  }



   public function inserts(){
    return array(
      "name"=>$this->name
      );
  }

}
