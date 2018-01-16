<?php

namespace App\Controllers;

use App\Models\Post as Post;
use App\Models\Comment as Comment;
use App\Managers\CommentManager as CommentManager;
use App\Managers\PostManager as PostManager;
use App\Authorization as Authorization;

class Blog extends Controller{ /** Controlleur du blog **/

  public function __construct(){
    parent::__construct();
  }

  public static function getRegisteredUsersActions(){
    $registeredUsersActions = [];
    $actions = get_class_methods('App\Controllers\Blog');
    foreach ($actions as $action) {
      if (stristr($action, 'edit') || stristr($action, 'delete') || stristr($action, 'comment') || stristr($action, 'add')) {
        $registeredUsersActions[] = $action;
      }
    }
    return $registeredUsersActions;
  }

  public function renderHome() { /** Home blog **/
    $posts = PostManager::all('posts');

    $template = $this->twig->loadTemplate('index.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPosts() { /** Tous les posts **/
    $posts = PostManager::all('posts');

    $template = $this->twig->loadTemplate('posts.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPost(){ /** Un post **/
    $post = PostManager::whereId($_GET['id'], 'posts');
    $comments = PostManager::comments($post->getId());

    $template = $this->twig->loadTemplate('post-view.twig');
    echo $template->render(array(
      'post' => $post,
      'comments' => $comments
    ));
  }


  public function addPostView(){ /** Vue pour ajouter un post **/
    $template = $this->twig->loadTemplate('post-create-edit.twig');
    echo $template->render([]);
  }

  public function addPost(){ /** Action pour ajouter un post **/
    if (!empty($_POST)){
      $title = $_POST['title'];
      $content = $_POST['content'];
      if (empty($title) || empty($content)) {
        $message = 'not_complete';
      } else {
        $post = new Post();
        $post->setTitle($_POST['title']);
        $post->setContent($_POST['content']);
        $post->setUserId(Authorization::getCurrentUser()->getId());
        PostManager::save($post);
        $message = 'done';
      }
      echo $message;
    }
  }

  public function editPostView(){ /** Vue pour éditer un post **/
    $post_id = $_GET['id'];
    $post = PostManager::whereId($post_id, 'posts');

    $template = $this->twig->loadTemplate('post-create-edit.twig');
    echo $template->render([
      'post' => $post
    ]);
  }

  public function editPost(){ /** Action éditer un post **/
    $loggedUser = Authorization::getCurrentUser();
    $post_id = $_GET['id'];
    $post = PostManager::whereId($post_id, 'posts');
    if ($post->getUserId() != $loggedUser->getId() && $loggedUser->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      if (!empty($_POST)){
        $title = $_POST['title'];
        $content = $_POST['content'];
        if (empty($title) || empty($content)) {
          $message = 'not_complete';
        } else {
          $post->setTitle($_POST['title']);
          $post->setContent($_POST['content']);
          PostManager::update($post);
          $message = 'done';
        }
        echo $message;
      }
    }
  }

  public function deletePost(){ /** Supprimer un post **/
    $loggedUser = Authorization::getCurrentUser();
    $post_id = $_GET['id'];
    $post = PostManager::whereId($post_id, 'posts');
    if ($post->getUserId() != $loggedUser->getId() && $loggedUser->getRole() != 'admin') {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comments = PostManager::comments($post_id);
      if (!empty($comments)) {
        foreach ($comments as $comment) {
          CommentManager::delete($comment);
        }
      }
      if (PostManager::delete($post) === null) {
        $message = 'done';
      } else {
        $message = 'echec';
      }
      echo $message;
    }
  }

  public function commentPost(){ /** Commenter un post **/
    $post_id = $_GET['id'];
    $user = Authorization::getCurrentUser();
    if (!$user) {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      if (!empty($_POST)){
        $author = $user->getId();
        $content = $_POST['content'];
        if (empty($author) || empty($content)) {
          $message = 'not_complete';
        } else {
          $comment = new Comment();
          $comment->setUserId($author);
          $comment->setContent($content);
          CommentManager::save($comment, $post_id);
          $message = 'done';
        }
        echo $message;
      }
    }
  }

  public function deleteComment(){ /** Supprimer un commentaire **/
    $user = Authorization::getCurrentUser();
    if (!$user) {
      $message = 'wrong_permissions';
      echo $message;
    } else {
      $comment_id = $_GET['id'];
      $comment = CommentManager::whereId($comment_id, 'comments');
      if ($comment->getUserId() != $user->getId()) {
        $message = 'wrong_permissions';
      } else {
        $delete = CommentManager::delete($comment);
        if (!$delete) {
          $message = 'done';
        } else {
          $message = 'echec';
        }
      }
      echo $message;
    }
  }



}



 ?>
