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

  public static function getUserWithToken($token){ /** Retourne l'utilisateur correspondant au token **/
    $user = null;
    $sql = 'SELECT * FROM users WHERE token=?';
    $db = Database::executeQuery($sql, array($token));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $user = new User($data);

    return ($data !== false) ? $user : false;
  }

  public static function generateToken($length){ /** Génération d'un token pour valider le compte **/

    $token = bin2hex(random_bytes($length));
    return $token;
  }

  public function save(User $user){

    $sql = 'INSERT INTO users (firstname,lastname,email,username,password,is_valid,token) VALUES (?, ?, ?, ?, ?, ?, ?)';
    Database::executeQuery($sql, array($user->firstname, $user->lastname, $user->email, $user->username, $user->password, $user->isValid, $user->token));
  }

  public function updateStatus(User $user){
    $sql = 'UPDATE users SET is_valid=? WHERE id=?';
    Database::executeQuery($sql, array($user->getValid(), $user->getId()));
  }
}
