<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('models/Post.php');

$loader = new Twig_Loader_Filesystem('templates'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
  'cache' => false
));

$posts = Post::all();

$template = $twig->loadTemplate('index.twig');
echo $template->render(array(
  'posts' => $posts
));
?>
