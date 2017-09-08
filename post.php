<?php
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

$post = Post::whereId($_GET['id']);
$comments = $post->comments();

$template = $twig->loadTemplate('post-view.twig');
echo $template->render(array(
  'post' => $post,
  'comments' => $comments
));
?>
