<?php
session_start();

require_once('./vendor/autoload.php'); /** Autoload de twig **/

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'blog');

define('BASE_URL', 'http://blog.dev/index.php?');
