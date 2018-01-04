<?php

namespace App\Models;

use \PDO as PDO;

class User extends Modele{

  private $id;
  private $firstname;
  private $lastname;
  private $email;
  private $username;
  private $password;
  private $isValid;
  private $token;
  private $role;

  public function __construct($valeurs = array())
  {
    parent::__construct($valeurs);
  }

  public function getId(){
    return $this->id;
  }

  public function getFirstname(){
    return $this->firstname;
  }

  public function getLastname(){
    return $this->lastname;
  }

  public function getEmail(){
    return $this->email;
  }

  public function getUsername(){
    return $this->username;
  }

  public function getPassword(){
    return $this->password;
  }

  public function getValid(){
    return $this->isValid;
  }

  public function getToken(){
    return $this->token;
  }

  public function getRole(){
    return $this->role;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function setFirstname($firstname){
    $this->firstname = $firstname;
  }

  public function setLastname($lastname){
    $this->lastname = $lastname;
  }

  public function setEmail($email){
    $this->email = $email;
  }

  public function setUsername($username){
    $this->username = $username;
  }

  public function setPassword($password){
    $this->password = $password;
  }

  public function setValid($isValid){
    $this->isValid = $isValid;
  }

  public function setToken($token){
    $this->token = $token;
  }

  public function setRole($role){
    $this->role = $role;
  }
}
