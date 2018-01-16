<?php

namespace App;

use App\Models\Alert as Alert;

class Object {

  public function __construct(){
    $loader = new \Twig_Loader_Filesystem('templates');
    $this->twig = new \Twig_Environment($loader, array(
     'cache' => false,
     'debug' => true
    ));
    $this->twig->addGlobal('session', $_SESSION);
  }

  public function renderMessage(Alert $alert){ /** Vue message **/
    $template = $this->twig->loadTemplate('alert.twig');
    echo $template->render([
      'alert' => array(
        'type' => $alert->getType(),
        'message' => $alert->getMessage()
      )
    ]);
  }
}
