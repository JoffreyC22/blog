<?php

namespace App\Controllers;

use App\Controllers\Controller as Controller;
use App\Models\User as User;
use App\Models\Alert as Alert;

class Auth extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public function renderLogin(){

    $template = $this->twig->loadTemplate('login.twig');
    echo $template->render([]);
  }

  public function renderRegister(){

    $template = $this->twig->loadTemplate('register.twig');
    echo $template->render([]);
  }

  public function login(){
    if (!empty($_POST)) {
      $username = $_POST['username'];
      $password = sha1($_POST['password']);
      if (empty($username) || empty($password)) {
        $message = 'not_complete';
      } else {
        $user = User::getFirst($username, $password);
        if ($user) {
          $_SESSION['user']['id'] = $user->getId();
          $_SESSION['user']['username'] = $user->getUsername();
          $_SESSION['user']['password'] = $user->getPassword();
          $message = $user->getUsername();
        } else {
          $message = 'not_complete';
        }
      }
      echo $message;
    }
  }

  public function logout(){
    if (isset($_SESSION['user'])) {
      $_SESSION = array();
      $alert = new Alert('success', 'Vous êtes bien déconnecté.');
      $this->renderMessage($alert);
    } else {
      $alert = new Alert('danger', 'Vous n\'êtes pas connecté.');
      $this->renderMessage($alert);
    }
  }
}
