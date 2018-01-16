<?php

namespace App\Controllers;

class Redirect{

  public static function action($action){
    header('Location:'.BASE_URL.'action='.$action);
  }

  public static function home(){
    header('Location:'.BASE_URL);
  }
}
