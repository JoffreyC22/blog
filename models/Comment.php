<?php

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

  public function getId(){
    return $this->id;
  }

  public function getAuthor(){
    return $this->author;
  }

  public function getContent(){
    return $this->content;
  }

  public function getCreated_at(){
    return $this->created_at;
  }

  public function getPost_id(){
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
    $comments = null;
    $request = $db->query('SELECT * FROM comments ORDER by id DESC');
    while ($data = $request->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }

  public static function whereId($comment_id){

    $comment = null;
    $sql = 'SELECT * FROM comments WHERE id=?';
    $db = Database::executeQuery($sql, array($comment_id));
    $data = $db->fetch(PDO::FETCH_ASSOC);
    $comment = new Comment($comment);
    return ($data != false) ? $comment : false;
  }

  public function save(Comment $comment, $post_id){

    $sql = 'INSERT INTO comments (author,content,created_at,post_id) VALUES (?, ?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    $db = Database::executeQuery($sql, array($comment->getAuthor(), $comment->getContent(), $date, $post_id));
  }

  public function delete(Comment $comment){

    $comment_id = $comment->getId();
    $sql = 'DELETE FROM comments WHERE id=?';
    $db = Database::executeQuery($sql, array($comment_id));
  }
}
