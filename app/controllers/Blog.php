<?php

namespace App\Controllers;

use App\Models\Post as Post;
use App\Models\Comment as Comment;

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
    $posts = Post::all('posts');

    $template = $this->twig->loadTemplate('index.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPosts() { /** Tous les posts **/
    $posts = Post::all('posts');

    $template = $this->twig->loadTemplate('posts.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPost(){ /** Un post **/
    $post = Post::whereId($_GET['id'], 'posts');
    $comments = $post->comments($post->getId());

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
        $post->save($post);
        $message = 'done';
      }
      echo $message;
    }
  }

  public function editPostView(){ /** Vue pour éditer un post **/
    $post_id = $_GET['id'];
    $post = Post::whereId($post_id, 'posts');

    $template = $this->twig->loadTemplate('post-create-edit.twig');
    echo $template->render([
      'post' => $post
    ]);
  }

  public function editPost(){ /** Action éditer un post **/
    $post_id = $_GET['id'];
    $post = Post::whereId($post_id, 'posts');

    if (!empty($_POST)){
      $title = $_POST['title'];
      $content = $_POST['content'];
      if (empty($title) || empty($content)) {
        $message = 'not_complete';
      } else {
        $post->setTitle($_POST['title']);
        $post->setContent($_POST['content']);
        $post->update($post);
        $message = 'done';
      }
      echo $message;
    }
  }

  public function deletePost(){ /** Supprimer un post **/
    $post_id = $_GET['id'];
    $post = Post::whereId($post_id, 'posts');
    $comments = $post->comments($post_id);
    foreach ($comments as $comment) {
      $comment->delete($comment);
    }
    if ($post->delete($post) == null) {
      $message = 'done';
    } else {
      $message = 'echec';
    }
    echo $message;
  }

  public function commentPost(){ /** Commenter un post **/
    $post_id = $_GET['id'];
    $user = Auth::getCurrentUser();

    if (!empty($_POST)){
      $author = $user->username;
      $content = $_POST['content'];
      if (empty($author) || empty($content)) {
        $message = 'not_complete';
      } else {
        $comment = new Comment();
        $comment->setAuthor($author);
        $comment->setContent($content);
        $comment->save($comment, $post_id);
        $message = 'done';
      }
      echo $message;
    }
  }

  public function deleteComment(){ /** Supprimer un commentaire **/
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



 ?>
