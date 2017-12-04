<?php

namespace App\Models;

abstract class Modele{

  protected function __construct($datas) {
      if(!empty($datas)){
        $this->hydrate($datas);
      }
  }

  protected function hydrate(array $object){
    foreach ($object as $key => $value) {
      if (strpos($key,'_') !== false) {
        $key = $this->camelize($key);
      }
      $method = 'set'.ucfirst($key);
        if(method_exists($this, $method)){
          $this->$method($value);
        }
    }
  }

  protected function camelize($word, $separator = '_'){
    return str_replace($separator, '', ucwords($word, $separator));
  }

}
