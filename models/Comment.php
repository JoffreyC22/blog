<?php

require_once('Database.php');

class Comment{

  private $id;
  private $author;
  private $content;
  private $created_at;
  private $post_id;

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

  public function author(){
    return $this->author;
  }

  public function content(){
    return $this->content;
  }

  public function created_at(){
    return $this->created_at;
  }

  public function post_id(){
    return $this->post_id;
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

  public function setCreated_at($created_at){
    $this->created_at = $created_at;
  }

  public function setPost_id($post_id){
    $this->post_id = $post_id;
  }

  public static function all(){

    $db = Database::connect();
    $request = $db->query('SELECT * FROM comments ORDER by id DESC');
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }
}
