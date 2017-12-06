<?php

namespace App\Controllers;

use App\Controllers\Blog as Blog;
use App\Controllers\Auth as Auth;
use App\Models\Alert as Alert;
use App\Models\Modele as Modele;

class Routeur extends Controller{ /** Controlleur du routeur **/

  public function init(){

    if (!empty($_GET['controller']) && !empty($_GET['action'])) {
      $routableControllers = $this->getRoutableControllers();
      if (!in_array($_GET['controller'], $routableControllers)) { /** Erreur si le controller demandé n'existe pas **/
        $error = new Alert('danger', 'Cette page n\'existe pas.');
        $this->renderMessage($error);
      }
      $controllerName = __NAMESPACE__ .'\\'.$_GET['controller'];
      $controller = new $controllerName();
      $action = $_GET['action'];
      $actions = $this->getActionsPossibles($controller);
      if ($_GET['controller'] == 'Auth' && Auth::isLogged() && $action != 'logout') { /** Interdit les pages auth si l'utilisateur est déjà connecté **/
        $error = new Alert('danger', 'Vous êtes déjà connecté.');
        $this->renderMessage($error);
      }
      if (!in_array($action, $actions)) {
        $error = new Alert('danger', 'Cette action n\'existe pas.'); /** Erreur si l'action demandée n'existe pas **/
        $this->renderMessage($error);
      } else {
        if ($this->getActionsToVerify($controller) && !empty($_GET['id'])) { /** Si il y a un id dans la requête **/
          $id = $_GET['id'];
          $class = $this->getClass($action);
          $validity = $this->checkIdValidity($id, $class); /** On regarde si cet id est bien numérique et qu'il correspond bien a l'entité demandée **/
          if ($validity) {
            $controller->$action();
          }
        } else {
          $action = $_GET['action']; /** Si pas d'id dans la requete, on renvoit directement vers l'action **/
          $controller->$action();
        }
      }
    } else { /** Si pas de controller, direction home **/
      $blog = new Blog();
      $blog->renderHome();
    }
  }

  private function getActionsPossibles($classname){ /** La route existe si elle n'est pas statique et qu'elle n'est pas une méthode du parent **/

    $parentClass = get_parent_class($classname);
    $reflectionClass = new \ReflectionClass($classname);
    $methods = array();
    foreach ($reflectionClass->getMethods() as $m) {
      if (!$m->isStatic()) {
        $methods[] = $m->name;
      }
    }
    $methods = array_diff($methods, get_class_methods($parentClass));
    return $methods;
  }

  private function getRoutableControllers(){ /** Declarer chaque controller ici pour pouvoir utiliser ses méthodes en tant que route **/
    $routableControllers = ['Blog', 'Auth'];
    return $routableControllers;
  }

  private function getActionsToVerify(Controller $controller){ /** Retourne les actions pour lesquelles une vérification est nécessaire (param id) **/
    $arrActionsToVerify = [];
    $actions = $this->getActionsPossibles($controller);
    foreach ($actions as $action) {
      if (strpos($action, 'edit') || strpos($action, 'delete') || $action === 'renderPost' || strpos($action, 'comment')) {
        $arrActionsToVerify[] = $action;
      }
    }

    return $arrActionsToVerify;
  }

  private function getClass($action){ /** Retourne la classe correspondant a l'action (avec ou sans le namespace) **/
    $arrAction = preg_split('/(?=[A-Z])/',$action);
    $class = [
      'withNamespace' => 'App\Models\\'.$arrAction[1],
      'name' => $arrAction[1],
      'databaseName' => Modele::getDbName($arrAction[1])
    ];

    return $class;
  }

  private function checkIdValidity($id, $class){ /** Verifie la validité de l'id passé en paramètre **/
    if (!(is_numeric($id)) || $id == '0') {
      $error = new Alert('danger', 'Le '.strtolower($class['name']).' doit être numérique.');
      $this->renderMessage($error);
    } else {
      $entity = $class['withNamespace']::whereId($id, $class['databaseName']);
      if (!$entity) {
        $error = new Alert('danger', 'Ce '.strtolower($class['name']).' n\'existe pas.');
        $this->renderMessage($error);
      } else {
        return true;
      }
    }
  }

}
