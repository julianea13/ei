<?php

namespace ThisApp\Entities;

class Proyect
{
  private $id,
          $name,
          $description,
          $tag,
          $id_Category,
          $active,
          $id_cf,
          $ca,
          $id_institution;





//SETTERS

  public function setId($id){
      $this->id = $id;
  }
   public function setName($name){
      $this->name = $name;
  }
   public function setDescription($description){
      $this->description = $description;
  }
    public function setTag($tag){
      $this->tag = $tag;
  }
    public function setIdCategory($id_Category){
      $this->id_Category = $id_Category;
  }
   public function setActive($active){
      $this->active = $active;
  }
   public function setIdCf($id_cf){
      $this->id_cf = $id_cf;
  }
   public function setCa($ca){
      $this->ca = $ca;
  }
   public function setIdInstitution($id_institution){
      $this->id_institution = $id_institution;
  }






//GETTERS

  public function getId(){
      return $this->id;
  }
  public function getName(){
      return $this->name;
  }

    public function getDescription(){
      return $this->description;
  }
    public function getTag(){
      return $this->tag;
  }
    public function getIdCateory(){
      return $this->id_Category;
  }
   public function getActive(){
      return $this->active;
  }
   public function getIdCf(){
      return $this->id_cf;
  }
   public function getCa(){
      return $this->ca;
  }
   public function getIdInstitution(){
      return $this->id_institution;
  }




   public function inserts(){
    return array(
      "name"=>$this->name,
      "description"=>$this->description,
      "tag"=>$this->tag,
      "id_Category"=>$this->id_Category,
      "active"=>$this->active,
      "id_cf"=>$this->id_cf,
      "ca"=>$this->ca,
      "id_institution"=>$this->id_institution
      );
  }
   public function updates(){
    return array(
      "name"=>$this->name,
      "description"=>$this->description,
      "tag"=>$this->tag
      );
  }

}
