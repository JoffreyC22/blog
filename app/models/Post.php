<?php

namespace App\Models;

use App\Managers\PostManager as PostManager;

class Post extends Modele{

  private $id;
  private $title;
  private $content;
  private $createdAt;
  private $updatedAt;
  private $userId;

  public function __construct($valeurs = array())
  {
    parent::__construct($valeurs);
  }

  public function getId(){
    return $this->id;
  }

  public function getTitle(){
    return $this->title;
  }

  public function getContent(){
    return $this->content;
  }

  public function getCreatedAt(){
    return $this->createdAt;
  }

  public function getUpdatedAt(){
    return $this->updatedAt;
  }

  public function getUserId(){
    return $this->userId;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function setTitle($title){
    $this->title = $title;
  }

  public function setContent($content){
    $this->content = $content;
  }

  public function setCreatedAt($createdAt){
    $this->createdAt = $createdAt;
  }

  public function setUpdatedAt($updatedAt){
    $this->updatedAt = $updatedAt;
  }

  public function setUserId($userId){
    $this->userId = $userId;
  }

  public function getAuthor(Post $post){
    $author = PostManager::getAuthor($post);
    return $author;
  }

  public function commentsToValidate(){
    $comments = PostManager::commentsToValidate($this->id);
    return $comments;
  }
}
