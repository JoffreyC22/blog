<?php

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

  public function getId(){
    return $this->id;
  }

  public function getTitle(){
    return $this->title;
  }

  public function getContent(){
    return $this->content;
  }

  public function getCreated_at(){
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
    return ($data != false) ? $post : false;
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
    $db = Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $date));
  }

  public function update(Post $post){

    $post_id = $post->getId();
    $sql = 'UPDATE posts SET title=?, content=? WHERE id=?';
    $db = Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $post_id));
  }

  public function delete(Post $post){

    $post_id = $post->getId();
    $sql = 'DELETE FROM posts WHERE id=?';
    $db = Database::executeQuery($sql, array($post_id));
  }
}
