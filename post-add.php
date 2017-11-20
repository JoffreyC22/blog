<?php
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

if (!empty($_POST)){
  $action = !empty($_GET['action']) ? $_GET['action'] : null;
  if($action){
      switch($action){
          case 'save' :
          $post = new Post();
          $post->setTitle($_POST['title']);
          $post->setContent($_POST['content']);
          Post::save($post);
      }
  };
}

$template = $twig->loadTemplate('post-create-edit.twig');
echo $template->render([]);
?>
