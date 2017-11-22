<?php
session_start();
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

if (!empty($_POST)){
  $title = $_POST['title'];
  $content = $_POST['content'];
  if (empty($title) || empty($content)) {
    $message = 'not_complete';
  } else {
    $action = !empty($_GET['action']) ? $_GET['action'] : null;
    if($action){
        switch($action){
            case 'save' :
            $post = new Post();
            $post->setTitle($_POST['title']);
            $post->setContent($_POST['content']);
            Post::save($post);
            $message = 'done';
        }
    };
  }
  echo $message;
} else {
  $template = $twig->loadTemplate('post-create-edit.twig');
  echo $template->render([]);
}
?>
