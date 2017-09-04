<?php
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

$posts = Post::all();

$template = $twig->loadTemplate('index.twig');
echo $template->render(array(
  'posts' => $posts
));
?>
