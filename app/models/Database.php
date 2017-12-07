<?php

namespace App\Models;

class Database extends \PDO {

    private static $instance;

    public static function connect() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new \PDO('mysql:dbname='.Configuration::__DB_NAME__.';host='.Configuration::__DB_HOST__, Configuration::__DB_USER__, Configuration::__DB_PASSWORD__,array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            }
            catch (\PDOException $e) {
                echo 'Connection à MySQL impossible : ', $e->getMessage();
            }
        }
        return self::$instance;
    }

    public static function executeQuery($sql, $params = null){
      if ($params === null) {
          $resultat = self::connect()->query($sql);
      } else {
          $resultat = self::connect()->prepare($sql);
          $resultat->execute($params);
      }
      return $resultat;
    }
}
