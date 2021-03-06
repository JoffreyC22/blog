<?php

namespace App\Controllers;

use App\Models\User as User;
use App\Models\Alert as Alert;
use App\Models\Post as Post;
use App\Models\Comment as Comment;
use App\Managers\CommentManager as CommentManager;
use App\Managers\PostManager as PostManager;
use App\Authorization as Authorization;

class Admin extends Controller{

  public function __construct(){
    parent::__construct();
  }

  public function renderAdmin(){

    $user = Authorization::getCurrentUser();
    if (!Authorization::isLogged() || (Authorization::isLogged() && $user->getRole() !== 'admin')) {
      $alert = new Alert('danger', 'Vous n\'avez pas les droits suffisants pour accéder à cette page.');
      $this->renderMessage($alert);
    } else {
      $template = $this->twig->loadTemplate('admin.twig');
      $posts = PostManager::all('posts');
      echo $template->render([
        'posts' => $posts
      ]);
    }
  }

  public function deleteComment(){ /** Supprimer un commentaire **/
    $user = Authorization::getCurrentUser();
    if (!$user || $user->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comment_id = $_GET['id'];
      $comment = CommentManager::whereId($comment_id, 'comments');
      $delete = CommentManager::delete($comment);
      if (!$delete) {
        $message = 'done';
      } else {
        $message = 'echec';
      }
      echo $message;
    }
  }

  public function validateComment(){ /** Valider un commentaire **/
    $user = Authorization::getCurrentUser();
    if (!$user || $user->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comment_id = $_GET['id'];
      $comment = CommentManager::whereId($comment_id, 'comments');
      $comment->setValid(1);
      CommentManager::updateStatus($comment);
      $message = 'done';
      echo $message;
    }
  }


}
