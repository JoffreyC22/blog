<?php

namespace App\Models;

class Alert{

  public function __construct($type, $message){
    $this->type = $type;
    $this->message = $message;
  }

  private $type;
  private $message;

  public function getType(){
    return $this->type;
  }

  public function getMessage(){
    return $this->message;
  }
}
