<?php

namespace App\Models;

use \PDO as PDO;
use App\Models\Database as Database;
use App\Models\Comment as Comment;

class Post extends Modele{

  private $id;
  private $title;
  private $content;
  private $createdAt;

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

  public static function all(){

    $db = Database::connect();
    $posts = null;
    $request = $db->query('SELECT * FROM posts ORDER by id DESC');
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $posts[] = new Post($data);
    }
    return $posts;
  }

  public static function whereId($post_id){

    $post = null;
    $sql = 'SELECT * FROM posts WHERE id=?';
    $db = Database::executeQuery($sql, array($post_id));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $post = new Post($data);
    return ($data !== false) ? $post : false;
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
