<?php

namespace App\Controllers;

class Redirect{

  public static function action($action){
    header('Location:'.BASE_URL.'action='.$action);
  }
}
