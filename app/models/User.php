<?php

namespace App\Models;

use App\Models\Database as Database;
use \PDO as PDO;

class User extends Modele{

  private $id;
  private $firstname;
  private $lastname;
  private $email;
  private $username;
  private $password;

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

  public static function getFirst($email, $password){ /** Retourne l'utilisateur en fonction de l'e-mail et du mot de passe **/
    $user = null;
    $sql = 'SELECT * FROM users WHERE email=? AND password=?';
    $db = Database::executeQuery($sql, array($email, $password));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $user = new User($data);

    return ($data !== false) ? $user : false;
  }

  public static function exists($email){ /** Email **/
    $user = null;
    $sql = "SELECT * FROM users WHERE email=?";
    $db = Database::executeQuery($sql, array($email));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $user = new User($data);

    return ($data !== false) ? $user : false;
  }

  public function save(User $user){

    $sql = 'INSERT INTO users (firstname,lastname,email,username,password) VALUES (?, ?, ?, ?, ?)';
    Database::executeQuery($sql, array($user->firstname, $user->lastname, $user->email, $user->username, $user->password));
  }
}
