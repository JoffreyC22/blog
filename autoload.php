<?php
  function loadClass($class){
    if(file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    }
    if(file_exists('Blog.php')) {
        require_once('Blog.php');
    }
   }
  spl_autoload_register('loadClass');
