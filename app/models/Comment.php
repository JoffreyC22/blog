<?php

namespace App\Models;

use \PDO as PDO;

class Comment extends Modele{

  private $id;
  private $author;
  private $content;
  private $createdAt;
  private $postId;

  public function __construct($valeurs = array())
  {
    parent::__construct($valeurs);
  }

  public function getId(){
    return $this->id;
  }

  public function getAuthor(){
    return $this->author;
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

  public function setId($id){
    $this->id = $id;
  }

  public function setAuthor($author){
    $this->author = $author;
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

  public function save(Comment $comment, $post_id){

    $sql = 'INSERT INTO comments (author,content,created_at,post_id) VALUES (?, ?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($comment->getAuthor(), $comment->getContent(), $date, $post_id));
  }

  public function delete(Comment $comment){

    $comment_id = $comment->getId();
    $sql = 'DELETE FROM comments WHERE id=?';
    Database::executeQuery($sql, array($comment_id));
  }
}
