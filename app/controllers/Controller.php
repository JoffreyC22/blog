<?php

namespace App\Controllers;

use App\Models\ErrorMessage as ErrorMessage;

abstract class Controller{

  public function __construct(){
    $loader = new \Twig_Loader_Filesystem('templates');
    $this->twig = new \Twig_Environment($loader, array(
     'cache' => false,
     'debug' => true
    ));
  }

  public function renderError(ErrorMessage $error){ /** Vue erreur **/
    $template = $this->twig->loadTemplate('error.twig');
    echo $template->render([
      'error' => array(
        'type' => $error->getType(),
        'message' => $error->getMessage()
      )
    ]);
  }

}
