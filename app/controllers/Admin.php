<?php

namespace App\Controllers;

use App\Models\User as User;
use App\Models\Alert as Alert;
use App\Models\Post as Post;
use App\Models\Comment as Comment;

class Admin extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public function renderAdmin(){

    $user = Auth::getCurrentUser();
    if (!Auth::isLogged() || (Auth::isLogged() && $user->getRole() !== 'admin')) {
      $alert = new Alert('danger', 'Vous n\'avez pas les droits suffisants pour accéder à cette page.');
      $this->renderMessage($alert);
    } else {
      $template = $this->twig->loadTemplate('admin.twig');
      $posts = Post::all('posts');
      echo $template->render([
        'posts' => $posts
      ]);
    }
  }

  public function deleteComment(){ /** Supprimer un commentaire **/
    $user = Auth::getCurrentUser();
    if (!$user || $user->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comment_id = $_GET['id'];
      $comment = Comment::whereId($comment_id, 'comments');
      $delete = $comment->delete($comment);
      if (!$delete) {
        $message = 'done';
      } else {
        $message = 'echec';
      }
      echo $message;
    }
  }

  public function validateComment(){ /** Valider un commentaire **/
    $user = Auth::getCurrentUser();
    if (!$user || $user->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comment_id = $_GET['id'];
      $comment = Comment::whereId($comment_id, 'comments');
      $comment->setValid(1);
      $comment->updateStatus($comment);
      $message = 'done';
      echo $message;
    }
  }


}
