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

  public function contact(){
    $checkRequiredFields = $this->checkRequiredFields(['firstname', 'lastname', 'email', 'message']);
    if (!$checkRequiredFields) {
      $message = 'not_complete';
    } else {
      foreach ($_POST as $key => $value) {
        ${$key} = $value;
      }
      Mail::sendMe($firstname, $lastname, $email, $message);
      $message = 'done';
    }
    echo $message;
  }

}
