<?php

require_once 'includes/session.php';
require_once 'CONSTANTS.php';
require_once 'includes/configuration.php';
require_once 'methods_library.php';
// grab recaptcha library
require_once "recaptchalib.php";

$blogID = filter_input(INPUT_POST, 'blogID', FILTER_VALIDATE_INT);
$userName = $_SESSION['userName'];
$content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);


if (preg_match(BANNED_WORDS_REGEX, $content))
         {
    $_SESSION['bad_input'] = true;
     $_SESSION['bad_input_message'] = "Your comment contains bad language! Thing before you post." ;
    echo "<SCRIPT LANGUAGE='JavaScript'> window.location.href= 'view_blog.php?id=$blogID' </SCRIPT> ";
         }
               else if (is_greater_than($content, COMMENT_MAX_LENGTH))
             {
             $_SESSION['bad_input'] = true;
             $_SESSION['bad_input_message'] = "Your comment is too long, it can only be up to " . COMMENT_MAX_LENGTH . " characters long." ;
              echo "<SCRIPT LANGUAGE='JavaScript'> window.location.href= 'view_blog.php?id=$blogID' </SCRIPT> ";
             }
             else if (is_less_than($content, COMMENT_MIN_LENGTH))
                 {
                 $_SESSION['bad_input'] = true;
             $_SESSION['bad_input_message'] = "Your comment is too short, it has to be at least " . COMMENT_MIN_LENGTH . " characters long." ;
              echo "<SCRIPT LANGUAGE='JavaScript'> window.location.href= 'view_blog.php?id=$blogID' </SCRIPT> ";
                 }
         else
             {
// your secret key
$secret = "6LdhMx8UAAAAAPayIVKcBpSPdL4Hz59nmNcrFwZ9";
// empty response
$response = null;
// check secret key
$reCaptcha = new ReCaptcha($secret);
// if submitted check response
if ($_POST["g-recaptcha-response"])
{
    $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
    );
}



if ($response != null /*&& $response->success -- ONLY WORKS ON LOCALHOST*/)
{
    
    $query = "INSERT INTO blog_comments (userName, blogID, content, likes, dislikes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $comment = array($userName, $blogID, $content, 0, 0);
    $stmt->execute($comment);
    
    $query2 = "UPDATE usersweb SET commentCount = (select count(userName) from blog_comments where userName = :userName) WHERE userName = :userName";
    $stmt2 = $db->prepare($query2);
    $stmt2->execute(array(':userName' => $userName));
    unset($db);

    echo "<SCRIPT LANGUAGE='JavaScript'> window.location.href= 'view_blog.php?id=$blogID' </SCRIPT> ";
    exit();
}
else
{
    echo "<SCRIPT LANGUAGE='JavaScript'> window.location.href= 'view_blog.php?id=$blogID' </SCRIPT> ";
    exit();
}
             }



