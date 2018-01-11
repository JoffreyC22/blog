<?php

namespace App\Managers;

use \PDO as PDO;
use App\Models\Database as Database;
use App\Models\Comment as Comment;
use App\Models\Post as Post;
use App\Models\User as User;

class Manager{

  public static function all($class){

    $db = Database::connect();
    $results = null;
    $request = $db->query("SELECT * FROM $class ORDER by id DESC");
    $formatedClass = self::getClassName($class);
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $results[] = new $formatedClass($data);
    }
    return $results;
  }

  public static function whereId($id, $class){

    $result = null;
    $sql = "SELECT * FROM $class WHERE id=?";
    $formatedClass = self::getClassName($class);
    $db = Database::executeQuery($sql, array($id));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $result = new $formatedClass($data);
    return ($data !== false) ? $result : false;
  }


  public static function getClassName($dbClass){ /** Retourne la classe depuis le nom de table en BDD (ex posts devient Post) **/
    $className = 'App\Models'.'\\'.ucwords(substr($dbClass, 0, -1));
    return $className;
  }

  public static function getDbName($className){ /** Inverse de getClassName **/
    $dbName = strtolower($className.'s');
    return $dbName;
  }

}
