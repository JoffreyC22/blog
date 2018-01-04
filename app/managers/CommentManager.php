<?php

namespace App\Managers;

use \PDO as PDO;
use App\Models\Database as Database;
use App\Models\Comment as Comment;

class CommentManager {

  public static function updateStatus(Comment $comment){
    $sql = 'UPDATE comments SET valid=? WHERE id=?';
    Database::executeQuery($sql, array($comment->getValid(), $comment->getId()));
  }

  public static function save(Comment $comment, $post_id){
    $sql = 'INSERT INTO comments (content,created_at,post_id,user_id,valid) VALUES (?, ?, ?, ?, ?)';
    $date = date('Y-m-d H:i:s');
    Database::executeQuery($sql, array($comment->getContent(), $date, $post_id, $comment->getUserId(), 0));
  }

  public static function delete(Comment $comment){
    $comment_id = $comment->getId();
    $sql = 'DELETE FROM comments WHERE id=?';
    Database::executeQuery($sql, array($comment_id));
  }
}
