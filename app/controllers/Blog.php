<?php

namespace App\Controllers;

use App\Models\Post as Post;
use App\Models\Comment as Comment;
use App\Models\ErrorMessage as ErrorMessage;

class Blog{ /** Controlleur du blog **/

  public function __construct(){
    $loader = new \Twig_Loader_Filesystem('templates');
    $this->twig = new \Twig_Environment($loader, array(
     'cache' => false,
     'debug' => true
    ));
  }

  public function renderHome() { /** Home blog **/
    $posts = Post::all();

    $template = $this->twig->loadTemplate('index.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPosts() { /** Tous les posts **/
    $posts = Post::all();

    $template = $this->twig->loadTemplate('posts.twig');
    echo $template->render(array(
      'posts' => $posts
    ));
  }

  public function renderPost(){ /** Un post **/
    $post = Post::whereId($_GET['id']);
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
    $post = Post::whereId($post_id);

    $template = $this->twig->loadTemplate('post-create-edit.twig');
    echo $template->render([
      'post' => $post
    ]);
  }

  public function editPost(){ /** Action éditer un post **/
    $post_id = $_GET['id'];
    $post = Post::whereId($post_id);

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
    $post = Post::whereId($post_id);
    $comments = $post->comments();
    foreach ($comments as $comment) {
      $comment->delete($comment);
    }
    $delete = $post->delete($post);
    if (!$delete) {
      $message = 'done';
    } else {
      $message = 'echec';
    }
    echo $message;
  }

  public function renderError(ErrorMessage $error){ /** Vue erreur **/
    $template = $this->twig->loadTemplate('error.twig');
    echo $template->render([
      'error' => array(
        'type' => $error->getType(),
        'message' => $error->getMessage()
      )
    ]);
  }

  public function commentPost(){ /** Commenter un post **/
    $post_id = $_GET['id'];

    if (!empty($_POST)){
      $author = $_POST['author'];
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
    $comment = Comment::whereId($comment_id);
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
