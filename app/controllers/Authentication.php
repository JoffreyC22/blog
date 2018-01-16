<?php

namespace App\Controllers;

use App\Models\User as User;
use App\Models\Alert as Alert;
use App\Models\Mail as Mail;
use App\Managers\UserManager as UserManager;

class Authentication extends Controller{

  public function __construct(){
    parent::__construct();
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
        $user = UserManager::exists($email);
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
          $userToRegister->setToken(UserManager::generateToken(20));
          $userToRegister->setRole('user');
          UserManager::save($userToRegister);
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
        $user = UserManager::getFirst($email, $password);
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
            $_SESSION['user']['token'] = $user->getToken();
            $_SESSION['user']['role'] = $user->getRole();
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
      if (UserManager::getUserWithToken($token)) {
        $user = UserManager::getUserWithToken($token);
        if ($user->getValid()) {
          $alert = new Alert('danger', 'Ce compte n\'existe pas.');
          $this->renderMessage($alert);
        } else {
          $user->setValid(1);
          UserManager::updateStatus($user);
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
