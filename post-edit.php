<?php
session_start();
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

$post_id = $_GET['id'];
$post = Post::whereId($post_id);

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
            $post->setTitle($_POST['title']);
            $post->setContent($_POST['content']);
            $post->update($post);
            $message = 'done';
        }
    };
  }
  echo $message;
} else {
  $template = $twig->loadTemplate('post-create-edit.twig');
  echo $template->render([
    'post' => $post
  ]);
}
?>
