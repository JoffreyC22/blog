<?php

class Routeur{ /** Controlleur du routeur **/

  public function init(){

    $blog = new Blog();

    if (!empty($_GET['action'])) {
      $action = $_GET['action'];
      $actions = $this->getActionsPossibles('Blog');
      if (!in_array($action, $actions)) {
        $error = new ErrorMessage('danger', 'Cette action n\'existe pas.');
        $blog->renderError($error);
      } else {
        if ($action == 'renderPost' || $action == 'editPost' || $action == 'editPostView' || $action == 'deletePost') {
          $post_id = $_GET['id'];
          if (!(is_numeric($post_id)) || $post_id == '0') {
            $error = new ErrorMessage('danger', 'Le post doit être numérique.');
            $blog->renderError($error);
          } else {
            $post = Post::whereId($post_id);
            if (!$post) {
              $error = new ErrorMessage('danger', 'Ce post n\'existe pas.');
              $blog->renderError($error);
            } else {
              $blog->$action();
            }
          }
        } else {
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
