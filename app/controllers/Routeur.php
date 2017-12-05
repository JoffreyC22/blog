<?php

namespace App\Controllers;

use App\Controllers\Blog as Blog;
use App\Models\ErrorMessage as ErrorMessage;
use App\Models\Modele as Modele;

class Routeur{ /** Controlleur du routeur **/

  public function init(){

    if (!empty($_GET['controller'])) {
      $controller = __NAMESPACE__ .'\\'.$_GET['controller'];
      $controller = new $controller();
      if (!empty($_GET['action'])) {
        $action = $_GET['action'];
        $actions = $this->getActionsPossibles($controller);
        if (!in_array($action, $actions)) {
          $error = new ErrorMessage('danger', 'Cette action n\'existe pas.'); /** Erreur si l'action demandée n'existe pas **/
          $controller->renderError($error);
        } else {
          if ($this->getActionsToVerify($controller) && !empty($_GET['id'])) { /** Si il y a un id dans la requête **/
            $id = $_GET['id'];
            $class = $this->getClass($action);
            $this->checkIdValidity($controller, $id, $class); /** On regarde si cet id est bien numérique et qu'il correspond bien a l'entité demandée **/
          } else {
            $action = $_GET['action']; /** Si pas d'id dans la requete, on renvoit directement vers l'action **/
            $controller->$action();
          }
        }
      }
    } else { /** Si pas de controller, direction home **/
      $blog = new Blog();
      $blog->renderHome();
    }
  }

  private function getActionsPossibles($classname){ /** Retourne les actions possible pour tel controller **/
    $actions = get_class_methods($classname);

    return $actions;
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

  private function checkIdValidity(Controller $controller, $id, $class){ /** Verifie la validité de l'id passé en paramètre **/
    if (!(is_numeric($id)) || $id == '0') {
      $error = new ErrorMessage('danger', 'Le '.strtolower($class['name']).' doit être numérique.');
      $controller->renderError($error);
    } else {
      $entity = $class['withNamespace']::whereId($id, $class['databaseName']);
      if (!$entity) {
        $error = new ErrorMessage('danger', 'Ce '.strtolower($class['name']).' n\'existe pas.');
        $controller->renderError($error);
      } else {
        $action = $_GET['action'];
        $controller->$action();
      }
    }
  }

}
