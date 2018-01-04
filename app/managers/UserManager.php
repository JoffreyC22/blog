<?php

namespace App\Managers;

use \PDO as PDO;
use App\Models\Database as Database;
use App\Models\User as User;

class UserManager {

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

  public static function save(User $user){

    $sql = 'INSERT INTO users (firstname,lastname,email,username,password,is_valid,token,role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    Database::executeQuery($sql, array($user->firstname, $user->lastname, $user->email, $user->username, $user->password, $user->isValid, $user->token, $user->role));
  }

  public static function updateStatus(User $user){
    $sql = 'UPDATE users SET is_valid=? WHERE id=?';
    Database::executeQuery($sql, array($user->getValid(), $user->getId()));
  }
}
