<?php

namespace ThisApp\Entities;

class Category
{
  private $id,
          $name,
          $active;
          


//SETTERS
  
  public function setId($id){
      $this->id = $id;
  } 
   public function setName($name){
      $this->name = $name;
  } 
   public function setActive($active){
      $this->active = $active;
  } 
     
  
  

//GETTERS

  public function getId(){
      return $this->id;
  }  
  public function getName(){
      return $this->name;
  }  
  public function getActive(){
      return $this->active;
  }   


   public function inserts(){
    return array(
      "name"=>$this->name,        
      "active"=>$this->active
      );    
  }

}