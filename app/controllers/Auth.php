<?php

namespace App\Controllers;

use App\Controllers\Controller as Controller;
use App\Models\User as User;
use App\Models\Alert as Alert;

class Auth extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public static function isLogged(){
    if (isset($_SESSION['user'])) {
      return true;
    }
    return false;
  }

  public function renderLogin(){

    $template = $this->twig->loadTemplate('login.twig');
    echo $template->render([]);
  }

  public function renderRegister(){

    $template = $this->twig->loadTemplate('register.twig');
    echo $template->render([]);
  }

  public function register(){
    $checkRequiredFields = $this->checkRequiredFields(['firstname', 'lastname', 'email', 'username', 'password']);
    if (!$checkRequiredFields) {
      $message = 'not_complete';
    } else {
      foreach ($_POST as $key => $value) {
        ${$key} = $value;
      }
      $user = User::exists($email);
      if ($user) {
        $message = 'already_exists';
      } else {
        $userToRegister = new User();
        $userToRegister->setFirstname($firstname);
        $userToRegister->setLastname($lastname);
        $userToRegister->setEmail($email);
        $userToRegister->setUsername($username);
        $userToRegister->setPassword(sha1($password));
        $userToRegister->save($userToRegister);
        $message = 'done';
      }
    }
    echo $message;
  }

  public function login(){
    $checkRequiredFields = $this->checkRequiredFields(['email', 'password']);
    if (!$checkRequiredFields) {
      $message = 'not_complete';
    } else {
        foreach ($_POST as $key => $value) {
          if ($key == 'password') {
            ${$key} = sha1($value);
          } else {
            ${$key} = $value;
          }
        }
        $user = User::getFirst($email, $password);
        if ($user) {
          $_SESSION['user']['id'] = $user->getId();
          $_SESSION['user']['firstname'] = $user->getFirstname();
          $_SESSION['user']['lastname'] = $user->getLastname();
          $_SESSION['user']['email'] = $user->getEmail();
          $_SESSION['user']['username'] = $user->getUsername();
          $_SESSION['user']['password'] = $user->getPassword();
          $message = $user->getFirstname();
        } else {
          $message = 'not_existing';
        }
    }
    echo $message;
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
