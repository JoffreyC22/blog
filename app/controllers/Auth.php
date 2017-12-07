<?php

namespace App\Controllers;

use App\Models\User as User;
use App\Models\Alert as Alert;
use App\Models\Mail as Mail;

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

  public static function checkPasswords(){
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    if ($password !== $password_confirmation) {
      return false;
    }
    return true;
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
      $validPasswords = self::checkPasswords();
      if (!$validPasswords) {
        $message = 'passwords_not_maching';
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
          $userToRegister->setValid(0);
          $userToRegister->setToken(User::generateToken(20));
          $userToRegister->save($userToRegister);
          Mail::send($userToRegister);
          $message = 'done';
        }
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
          if (!$user->getValid()) {
            $message = 'not_confirmed';
          } else {
            $_SESSION['user']['id'] = $user->getId();
            $_SESSION['user']['firstname'] = $user->getFirstname();
            $_SESSION['user']['lastname'] = $user->getLastname();
            $_SESSION['user']['email'] = $user->getEmail();
            $_SESSION['user']['username'] = $user->getUsername();
            $_SESSION['user']['password'] = $user->getPassword();
            $message = $user->getFirstname();
          }
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

  public function validateAccount(){
    if (empty($_GET['token'])) {
      $alert = new Alert('danger', 'Ce compte n\'existe pas.');
      $this->renderMessage($alert);
    } else {
      $token = $_GET['token'];
      if (User::getUserWithToken($token)) {
        $user = User::getUserWithToken($token);
        if ($user->getValid()) {
          $alert = new Alert('danger', 'Ce compte n\'existe pas.');
          $this->renderMessage($alert);
        } else {
          $user->setValid(1);
          $user->updateStatus($user);
          $alert = new Alert('success', 'Votre compte a bien été activé.');
          $this->renderMessage($alert);
        }
      } else {
        $alert = new Alert('danger', 'Ce compte n\'existe pas.');
        $this->renderMessage($alert);
      }
    }
  }
}
