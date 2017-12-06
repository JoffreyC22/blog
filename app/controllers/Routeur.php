<?php

namespace App\Controllers;

use App\Controllers\Blog as Blog;
use App\Models\Alert as Alert;
use App\Models\Modele as Modele;

class Routeur extends Controller{ /** Controlleur du routeur **/

  public function init(){

    if (!empty($_GET['controller']) && !empty($_GET['action'])) {
      $controller = __NAMESPACE__ .'\\'.$_GET['controller'];
      $controller = new $controller();
      $action = $_GET['action'];
      $actions = $this->getActionsPossibles($controller);
        if (!in_array($action, $actions)) {
          $error = new Alert('danger', 'Cette action n\'existe pas.'); /** Erreur si l'action demandée n'existe pas **/
          $controller->renderMessage($error);
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

  private function getActionsPossibles($classname){ /** Retourne les actions possible pour tel controller sans le constructeur et la classe parente **/

    $parentClass = get_parent_class($classname);
    $methods = array_diff(get_class_methods($classname), get_class_methods($parentClass));
    return $methods;
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
