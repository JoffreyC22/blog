<?php

namespace App\Managers;

use \PDO as PDO;
use App\Models\Database as Database;
use App\Models\Comment as Comment;
use App\Models\Post as Post;

class PostManager extends Manager{

  public static function getAuthor(Post $post){
    $sql = "SELECT username FROM users WHERE id=?";
    $db = Database::executeQuery($sql, array($post->getUserId()));
    $data = $db->fetchColumn();
    return ($data !== false) ? $data : false;
  }

  public static function comments($post_id){
    $comments = null;
    $sql = 'SELECT * FROM comments WHERE post_id=? and valid=1';
    $db = Database::executeQuery($sql, array($post_id));
    while ($data = $db->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }

  public static function commentsToValidate($post_id){
    $comments = null;
    $sql = 'SELECT * FROM comments WHERE post_id=? and valid=0';
    $db = Database::executeQuery($sql, array($post_id));
    while ($data = $db->fetch(PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }

  public static function save(Post $post){

    $sql = 'INSERT INTO posts (title,content,created_at,updated_at,user_id) VALUES (?, ?, ?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $date, $date, $post->getUserId()));
  }

  public static function update(Post $post){

    $post_id = $post->getId();
    $sql = 'UPDATE posts SET title=?, content=?, updated_at=? WHERE id=?';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($post->getTitle(), $post->getContent(), $date, $post_id));
  }

  public static function delete(Post $post){

    $post_id = $post->getId();
    $sql = 'DELETE FROM posts WHERE id=?';
    Database::executeQuery($sql, array($post_id));
  }
}
