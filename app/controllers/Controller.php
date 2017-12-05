<?php

namespace App\Controllers;

use App\Models\Alert as Alert;

abstract class Controller{

  public function __construct(){
    $loader = new \Twig_Loader_Filesystem('templates');
    $this->twig = new \Twig_Environment($loader, array(
     'cache' => false,
     'debug' => true
    ));
    $this->twig->addGlobal('session', $_SESSION);
  }

  public function renderMessage(Alert $message){ /** Vue message **/
    $template = $this->twig->loadTemplate('alert.twig');
    echo $template->render([
      'message' => array(
        'type' => $error->getType(),
        'message' => $error->getMessage()
      )
    ]);
  }

}
