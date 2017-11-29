<?php

class Blog{ /** Controlleur du blog **/

  public function __construct(){
    $loader = new Twig_Loader_Filesystem('templates');
    $this->twig = new Twig_Environment($loader, array(
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
    $comments = $post->comments();

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
      return $message;
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
      return $message;
    }
  }

  public function deletePost(){ /** Supprimer un post **/
    $post_id = $_GET['id'];
    $post = Post::whereId($post_id);
    $delete = $post->delete($post);
    if (!$delete) {
      $message = 'done';
    } else {
      $message = 'echec';
    }
    echo $message;
  }

  public function renderError($error){ /** Vue erreur **/
    $template = $this->twig->loadTemplate('error.twig');
    echo $template->render([
      'error' => array(
        'type' => $error->getType(),
        'message' => $error->getMessage()
      )
    ]);
  }



}



 ?>
