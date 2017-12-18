<?php

namespace App\Models;

use \PDO as PDO;

class Post extends Modele{

  private $id;
  private $title;
  private $content;
  private $createdAt;
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

  public function setUserId($userId){
    $this->userId = $userId;
  }

  public function getAuthor(){
    $author = null;
    $sql = "SELECT username FROM users WHERE id=?";
    $db = Database::executeQuery($sql, array($this->getUserId()));
    $data = $db->fetchColumn();
    return ($data !== false) ? $data : false;
  }

  public function comments($post_id){

    $comments = null;
    $sql = 'SELECT * FROM comments WHERE post_id=?';
    $db = Database::executeQuery($sql, array($post_id));
    while ($data = $db->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }

  public function save(Post $post){

    $sql = 'INSERT INTO posts (title,content,created_at) VALUES (?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $date));
  }

  public function update(Post $post){

    $post_id = $post->getId();
    $sql = 'UPDATE posts SET title=?, content=? WHERE id=?';
    Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $post_id));
  }

  public function delete(Post $post){

    $post_id = $post->getId();
    $sql = 'DELETE FROM posts WHERE id=?';
    Database::executeQuery($sql, array($post_id));
  }
}
