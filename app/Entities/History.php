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
          $id_cf;
        
          


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
   public function setIdCf($id_cf){
      $this->id_cf = $id_cf;
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
      return $this->id_cf;
  }  
   


   public function inserts(){
    return array(
      "ca"=>$this->ca,   
      "description"=>$this->description,   
      "id_belongs"=>$this->id_belongs,   
      "belongs"=>$this->belongs,   
      "active"=>$this->active,   
      "id_cf"=>$this->id_cf,   
      "tags"=>$this->tags   
      );    
  }
    public function updates(){
    return array(
        
      "description"=>$this->description, 
      "tags"=>$this->tags   
      );    
  }

}