<?php
require_once('./vendor/autoload.php');
require_once('./models/Post.php');
require_once('./twigloading.php');

$template = $twig->loadTemplate('post-create-edit.twig');
echo $template->render([]);
?>
