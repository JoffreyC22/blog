<?php

use App\Controllers\Routeur as Routeur;

require_once('./boot.php');

$routeur = new Routeur();
$routeur->init();

?>
