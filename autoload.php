<?php
  function loadClass($class){
    if(file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    }
    elseif(file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    }
   }
  spl_autoload_register('loadClass');
