<?php

namespace App\Controllers;

use App\Models\Configuration as Configuration;

class Redirect{

  public static function action($action){
    header('Location:'.Configuration::__BASE_URL__.'action='.$action);
  }

  public static function home(){
    header('Location:'.Configuration::__BASE_URL__);
  }
}
