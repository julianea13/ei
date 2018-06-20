<?php

namespace ThisApp\Entities;

class History
{
  private $id,
          $ca,
          $description,
          $id_belongs,
          $belongs,
          $active,
          $tags,
          $ic_cf;
        
          


//SETTERS
  
  public function setId($id){
      $this->id = $id;
  } 
   public function setCa($ca){
      $this->ca = $ca;
  } 
   public function setDescription($description){
      $this->description = $description;
  } 
   public function setIdBelongs($id_belongs){
      $this->id_belongs = $id_belongs;
  } 
   public function setBelongs($belongs){
      $this->belongs = $belongs;
  } 
   public function setActive($active){
      $this->active = $active;
  } 
   public function setIdCf($ic_cf){
      $this->ic_cf = $ic_cf;
  }
  public function setTags($tags){
      $this->tags = $tags;
  } 
 
     
  
  

//GETTERS

  public function getId(){
      return $this->id;
  }  
  public function getCa(){
      return $this->ca;
  } 
  public function getTags(){
      return $this->tags;
  }  
  public function getDescription(){
      return $this->description;
  }  
  public function getIdBelongs(){
      return $this->id_belongs;
  }  
  public function getBelongs(){
      return $this->belongs;
  }  
  public function getActive(){
      return $this->active;
  }  
  public function getIdCf(){
      return $this->ic_cf;
  }  
   


   public function inserts(){
    return array(
      "ca"=>$this->ca,   
      "description"=>$this->description,   
      "id_belongs"=>$this->id_belongs,   
      "belongs"=>$this->belongs,   
      "active"=>$this->active,   
      "ic_cf"=>$this->ic_cf   
      );    
  }

}