<?php

namespace App\Controllers;

use App\Models\Configuration as Configuration;

class Redirect{

  public static function action($action){
    header('Location:'.Configuration::BASE_URL.'action='.$action);
  }

  public static function home(){
    header('Location:'.Configuration::BASE_URL);
  }
}
