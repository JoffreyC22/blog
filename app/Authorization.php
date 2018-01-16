<?php

namespace App;

use App\Models\User as User;

class Authorization {

  public static function isLogged(){
    if (isset($_SESSION['user'])) {
      return true;
    }
    return false;
  }

  public static function getCurrentUser(){
    $data = null;
    if (isset($_SESSION['user'])) {
      $data = $_SESSION['user'];
      $user = new User($data);
    }
    return ($data !== null) ? $user : '';
  }
}
