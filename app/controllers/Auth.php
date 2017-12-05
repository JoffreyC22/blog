<?php

namespace App\Controllers;

use App\Controllers\Controller as Controller;

class Auth extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public function renderLogin(){

    $template = $this->twig->loadTemplate('login.twig');
    echo $template->render([]);
  }
}
