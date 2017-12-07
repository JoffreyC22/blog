<?php

namespace App\Models;

class Mail{

  public static function send(User $user){

    $email = $user->getEmail();
    $subject = 'Inscription sur le blog - Joffrey Capitaine';
    $eol="\n";

    $headers = 'From: Joffrey Capitaine - Blog <no-reply@joffreyc.fr>'.$eol;
    $headers .= 'Reply-To: Joffrey Capitaine <no-reply@joffreyc.fr'.$eol;
    $mime_boundary=md5(time());
    $headers .= 'MIME-Version: 1.0'.$eol;
    $headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;
    $content  = 'Bonjour,'.$eol;
    $content .= 'Veuillez confirmer votre inscription sur le blog en cliquant sur ce lien : '.Configuration::baseUrl.'controller=Auth&action=validateAccount&token='.$user->getToken() .$eol;
    $content .= 'Cordialement,'.$eol;
    $content .= 'Joffrey Capitaine.'.$eol;

    return mail($email, $subject, $content, $headers);
  }
}
