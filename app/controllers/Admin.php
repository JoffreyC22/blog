<?php

namespace App\Controllers;

use App\Models\User as User;
use App\Models\Alert as Alert;

class Admin extends Controller{

  public function __construct(){
    parent::__construct();
    $user = Auth::getCurrentUser();
    if (!Auth::isLogged() || (Auth::isLogged() && $user->getRole() !== 'admin')) {
      $alert = new Alert('danger', 'Vous n\'avez pas les droits suffisants pour accéder à cette page.');
      $this->renderMessage($alert);
    }
  }

  public function renderAdmin(){

    $template = $this->twig->loadTemplate('admin.twig');
    echo $template->render([]);
  }


}
