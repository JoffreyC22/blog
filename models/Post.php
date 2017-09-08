<?php

require_once('Database.php');
require_once('Comment.php');

class Post{

  private $id;
  private $title;
  private $content;
  private $created_at;

  public function __construct($valeurs = array())
  {
      if(!empty($valeurs))
          $this->hydrate($valeurs);
  }

  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }

  public function id(){
    return $this->id;
  }

  public function title(){
    return $this->title;
  }

  public function content(){
    return $this->content;
  }

  public function created_at(){
    return $this->created_at;
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

  public function setCreated_at($created_at){
    $this->created_at = $created_at;
  }

  public static function all(){

    $db = Database::connect();
    $request = $db->query('SELECT * FROM posts ORDER by id DESC');
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $posts[] = new Post($data);
    }
    return $posts;
  }

  public static function whereId($post_id){

    $db = Database::connect();
    $request = $db->query('SELECT * FROM posts WHERE id ='.$post_id);
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $post = new Post($data);
    }
    return $post;
  }

  public function comments(){

    $db = Database::connect();
    $request = $db->query('SELECT * FROM comments WHERE post_id ='.$this->id);
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }
}
