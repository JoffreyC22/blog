<?php

namespace App\Models;

class Database extends \PDO {

    private static $db = DB_NAME;
    private static $host = DB_HOST;
    private static $user = DB_USER;
    private static $pass = DB_PASSWORD;
    private static $instance;

    public static function connect() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new \PDO('mysql:dbname='.self::$db.';host='.self::$host, self::$user, self::$pass,array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
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
