<?php

class Routeur{ /** Controlleur du routeur **/

  public function init(){

    $blog = new Blog();

    if (!empty($_GET['action'])) {
      $action = $_GET['action'];
      $blog->$action();
    } else {
      $blog->renderHome();
    }
  }

}
