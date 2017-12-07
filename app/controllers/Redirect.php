<?php

namespace App\Controllers;

use App\Models\Configuration as Configuration;

class Redirect{

  public static function action($action){
    header('Location:'.Configuration::_BASE_URL_.'action='.$action);
  }

  public static function home(){
    header('Location:'.Configuration::_BASE_URL_);
  }
}
