<?php

namespace ThisApp\Entities;

class Institution
{
  private $id,
  $name,
  $image,
  $shield,
  $municipio,
  $active;




  //SETTERS

  public function setId($id){
    $this->id = $id;
  }
  public function setName($name){
    $this->name = $name;
  }

  public function setImage($image){
    $this->image = $image;
  }
  public function setShield($shield){
    $this->shield = $shield;
  }
  public function setActive($active){
    $this->active = $active;
  }
  public function setMunicipio($municipio){
    $this->municipio = $municipio;
  }




  //GETTERS

  public function getId(){
    return $this->id;
  }
  public function getName(){
    return $this->name;
  }
  public function getImage(){
    return $this->image;
  }
  public function getShield(){
    return $this->shield;
  }
  public function getActive(){
    return $this->id_cf;
  }
  public function  getMunicipio(){
    return $this->municipio;
  }


  public function inserts(){
    return array(
      "id"=>$this->id,
      "name"=>$this->name,
      "image"=>$this->image,
      "shield"=>$this->shield,
      "active"=>$this->active,
      "municipio"=>$this->municipio
    );
  }
  public function updates(){
    return array(
      "name"=>$this->name,
      "image"=>$this->image,
      "shield"=>$this->shield,
      "municipio"=>$this->municipio
    );
  }
  public function delete(){
    return array(
      "active"=>$this->active
      );
  }

}
