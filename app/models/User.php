<?php

namespace App\Models;

use App\Models\Database as Database;

class User extends Modele{

  private $id;
  private $username;
  private $password;

  public function __construct($valeurs = array())
  {
    parent::__construct($valeurs);
  }

  public function getId(){
    return $this->id;
  }

  public function getUsername(){
    return $this->username;
  }

  public function getPassword(){
    return $this->password;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function setUsername($username){
    $this->username = $username;
  }

  public function setPassword($password){
    $this->password = $password;
  }
}
