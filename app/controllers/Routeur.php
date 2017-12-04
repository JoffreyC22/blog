<?php

namespace App\Controllers;

use App\Controllers\Blog as Blog;
use App\Models\ErrorMessage as ErrorMessage;

class Routeur{ /** Controlleur du routeur **/

  public function init(){

    $blog = new Blog();

    if (!empty($_GET['action'])) {
      $action = $_GET['action'];
      $actions = $this->getActionsPossibles('App\Controllers\Blog');
      if (!in_array($action, $actions)) {
        $error = new ErrorMessage('danger', 'Cette action n\'existe pas.');
        $blog->renderError($error);
      } else {
        if ($action ==='renderPost' || $action === 'editPost' || $action === 'editPostView' || $action === 'deletePost' || $action === 'commentPost' || $action === 'deleteComment'
        || $action === 'editComment' && !empty($_GET['id'])) {
          $id = $_GET['id'];
          $arrAction = preg_split('/(?=[A-Z])/',$action);
          $typeId = $arrAction[1];
          if (!(is_numeric($id)) || $id == '0') {
            $error = new ErrorMessage('danger', 'Le '.strtolower($typeId).' doit être numérique.');
            $blog->renderError($error);
          } else {
            switch ($typeId) {
              case 'Post':
                $class = 'App\Models\Post';
                break;

              case 'Comment':
                $class = 'App\Models\Comment';
                break;
            }
            $entity = $class::whereId($id);
            if (!$entity) {
              $error = new ErrorMessage('danger', 'Ce '.strtolower($typeId).' n\'existe pas.');
              $blog->renderError($error);
            } else {
              $action = $_GET['action'];
              $blog->$action();
            }
          }
        } else {
          $action = $_GET['action'];
          $blog->$action();
        }
      }
    } else {
      $blog->renderHome();
    }
  }

  public function getActionsPossibles($classname){
    $actions = get_class_methods($classname);
    return $actions;
  }

}
