<?php

namespace ThisApp\Entities;

class User
{
  private $id,
          $name, 
          $last_name,        
          $nick,
          $salt,
          $password,
          $id_rol,
          $image,
          $active,
          $email;


//SETTERS
  
  public function setId($id){
      $this->id = $id;
  }  
   public function setName($name){
      $this->name = $name;
  }  
   public function setLastName($last_name){
      $this->last_name = $last_name;
  }  
   public function setNick($nick){
      $this->nick = $nick;
  }  
   public function setSalt($salt){
      $this->salt = $salt;
  }  
   public function setPassword($password){
      $this->password = $password;
  }  
   public function setIdRol($id_rol){
      $this->id_rol = $id_rol;
  } 
   public function setImage($image){
      $this->image = $image;
  }  
   public function setActive($active){
      $this->active = $active;
  } 
   public function setEmail($email){
      $this->email = $email;
  }

   

//GETTERS

  public function getId(){
      return $this->id;
  } 
   public function getName(){
      return $this->name;
  } 
   public function getLastName(){
      return $this->last_name;
  } 
   public function getNick(){
      return $this->nick;
  } 
   public function getSalt(){
      return $this->salt;
  } 
   public function getPassword(){
      return $this->password;
  } 
  public function getIdRol(){
      return $this->id_rol;
  } 
  public function getImage(){
      return $this->image;
  } 
   public function getActive(){
      return $this->active;
  } 
   public function getEmail(){
      return $this->email;
  } 
    

  public function inserts(){
    return array(
      "name"=>$this->name,
      "last_name"=>$this->last_name, 
      "nick"=>$this->nick,
      "salt"=>$this->salt,
      "id_rol"=>$this->id_rol,
      "image"=>$this->image,      
      "password"=>$this->password,
      "active"=>$this->active,
      "email"=>$this->email
      );    
  }
}