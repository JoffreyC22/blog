<?php
require_once('./config.php');

$blog = new Blog();

if (!empty($_GET['action'])) {
  $action = $_GET['action'];
  $blog->$action();
} else {
  $blog->renderHome();
}
?>
