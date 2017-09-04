<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
  'cache' => false
));
try {
  $bdd = new PDO('mysql:host=172.17.0.2;dbname=blog;charset=utf8',
      'root', 'docker');
} catch (Exception $e) {
  echo $e->getMessage();
}
$posts = $bdd->query('SELECT * FROM posts');

$template = $twig->loadTemplate('index.twig');
echo $template->render(array(
  'posts' => $posts
));
?>
