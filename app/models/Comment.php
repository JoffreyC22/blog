<?php

namespace App\Models;

use \PDO as PDO;

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

  public function updateStatus(Comment $comment){
    $sql = 'UPDATE comments SET valid=? WHERE id=?';
    Database::executeQuery($sql, array($comment->getValid(), $comment->getId()));
  }

  public function getAuthor(){
    $sql = "SELECT username FROM users WHERE id=?";
    $db = Database::executeQuery($sql, array($this->getUserId()));
    $data = $db->fetchColumn();
    return ($data !== false) ? $data : false;
  }

  public function save(Comment $comment, $post_id){

    $sql = 'INSERT INTO comments (content,created_at,post_id,user_id,valid) VALUES (?, ?, ?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($comment->getContent(), $date, $post_id, $comment->getUserId(), 0));
  }

  public function delete(Comment $comment){

    $comment_id = $comment->getId();
    $sql = 'DELETE FROM comments WHERE id=?';
    Database::executeQuery($sql, array($comment_id));
  }
}
