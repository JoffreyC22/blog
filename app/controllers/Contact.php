<?php

namespace App\Controllers;

use App\Models\Alert as Alert;
use App\Models\Mail as Mail;

class Contact extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public function renderContact(){

    $template = $this->twig->loadTemplate('contact.twig');
    echo $template->render([]);
  }

}
