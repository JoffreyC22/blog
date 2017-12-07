<?php

namespace App\Models;

use \PDO as PDO;

abstract class Modele{

  protected function __construct($datas) {
      if(!empty($datas)){
        $this->hydrate($datas);
      }
  }

  protected function hydrate(array $object){
    foreach ($object as $key => $value) {
      if (substr($key, 0, 3) === 'is_') {
        $key = str_replace('is_', '', $key);
      } elseif (strpos($key,'_') !== false) {
        $key = $this->camelize($key);
      }
      $method = 'set'.ucfirst($key);
        if(method_exists($this, $method)){
          $this->$method($value);
        }
    }
  }

  public static function all($class){

    $db = Database::connect();
    $results = null;
    $request = $db->query("SELECT * FROM $class ORDER by id DESC");
    $formatedClass = Modele::getClassName($class);
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $results[] = new $formatedClass($data);
    }
    return $results;
  }

  public static function whereId($id, $class){

    $result = null;
    $sql = "SELECT * FROM $class WHERE id=?";
    $formatedClass = Modele::getClassName($class);
    $db = Database::executeQuery($sql, array($id));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $result = new $formatedClass($data);
    return ($data !== false) ? $result : false;
  }

  protected function camelize($word, $separator = '_'){
    return str_replace($separator, '', ucwords($word, $separator));
  }

  public static function getClassName($dbClass){ /** Retourne la classe depuis le nom de table en BDD (ex posts devient Post) **/
    $className = __NAMESPACE__.'\\'.ucwords(substr($dbClass, 0, -1));
    return $className;
  }

  public static function getDbName($className){ /** Inverse de getClassName **/
    $dbName = strtolower($className.'s');
    return $dbName;
  }

}
