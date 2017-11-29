<?php
session_start();

require_once('autoload.php'); /** Mon autoload **/
require_once('./vendor/autoload.php'); /** Autoload de twig **/

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'blog');
