<?php

namespace App\Models;

use App\Models\Database as Database;
use \PDO as PDO;

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

  public static function getFirst($username, $password){ /** Retourne l'utilisateur en fonction de l'username et du mot de passe **/
    $user = null;
    $sql = 'SELECT * FROM users WHERE username=? AND password=?';
    $db = Database::executeQuery($sql, array($username, $password));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $user = new User($data);

    return ($data !== false) ? $user : false;
  }
}
