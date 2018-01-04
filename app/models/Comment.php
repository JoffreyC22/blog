<?php

namespace App\Models;

class Comment extends Modele{

  private $id;
  private $content;
  private $createdAt;
  private $postId;
  private $userId;
  private $valid;

  public function __construct($valeurs = array())
  {
    parent::__construct($valeurs);
  }

  public function getId(){
    return $this->id;
  }

  public function getContent(){
    return $this->content;
  }

  public function getCreatedAt(){
    return $this->createdAt;
  }

  public function getPostId(){
    return $this->postId;
  }

  public function getUserId(){
    return $this->userId;
  }

  public function getValid(){
    return $this->valid;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function setContent($content){
    $this->content = $content;
  }

  public function setCreatedAt($createdAt){
    $this->createdAt = $createdAt;
  }

  public function setPostId($postId){
    $this->postId = $postId;
  }

  public function setUserId($userId){
    $this->userId = $userId;
  }

  public function setValid($valid){
    $this->valid = $valid;
  }
}
