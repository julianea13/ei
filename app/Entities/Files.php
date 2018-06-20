<?php

namespace ThisApp\Entities;

class Files
{
  private $id,
          $name,
          $file_type,
          $active,
          $belongs,
          $contenido,
          $description,
          $id_belongs;
          


//SETTERS
  
  public function setId($id){
      $this->id = $id;
  } 
   public function setName($name){
      $this->name = $name;
  } 
   public function setFileType($file_type){
      $this->file_type = $file_type;
  } 
   public function setBelongs($belongs){
      $this->belongs = $belongs;
  } 
   public function setIdBelongs($id_belongs){
      $this->id_belongs = $id_belongs;
  } 
   public function setActive($active){
      $this->active = $active;
  } 
   public function setContenido($contenido){
      $this->contenido = $contenido;
  } 
   public function setDescription($description){
      $this->description = $description;
  } 
     
  
  

//GETTERS

  public function getId(){
      return $this->id;
  }  
  public function getName(){
      return $this->name;
  }  
  public function getFileType(){
      return $this->file_type;
  }
  public function getBelongs(){
      return $this->belongs;
  }
  public function getIdBelongs(){
      return $this->id_belongs;
  }
  public function getActive(){
      return $this->active;
  }
   public function getDescription(){
      return $this->description;
  }
   public function getContenido(){
      return $this->contenido;
  }   


   public function inserts(){
    return array(
      "name"=>$this->name,        
      "file_type"=>$this->file_type,
      "belongs"=>$this->belongs,
      "id_belongs"=>$this->id_belongs,
      "active"=>$this->active,     
      "contenido"=>$this->contenido,     
      "description"=>$this->description     
      );    
  }

}