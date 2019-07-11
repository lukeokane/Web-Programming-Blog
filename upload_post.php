<?php

 require_once 'includes/session.php';
 require_once 'includes/configuration.php';
 require_once 'CONSTANTS.php';
 require_once 'methods_library.php';

 
  $username = $_SESSION['userName'];

 $blog_image = $_POST['blog_image'];

 $blog_title = $_POST['blog_title'];

 $blog_content = $_POST['blog_content'];
 
 $blog_tags = $_POST['blog_tags'];
 
 if ($_SESSION['userType'] != 1)
     {
     $JSON->result = "nsi";
     } else
     {
     $JSON = upload_post($db, $username, $blog_image, $blog_title, $blog_content, $blog_tags);
     }

 echo json_encode($JSON);


 function upload_post($db, $username, $blog_image, $blog_title, $blog_content, $blog_tags)
     {

     $blog_title_length = strlen($blog_title);
     $blog_content_length = strlen($blog_content);

     if (is_greater_than($blog_title, TITLE_MAX_LENGTH))
         {
         $JSON->result = "input_error";
         $JSON->response = "The title is " . $blog_title_length . " characters long, it must be at most " . TITLE_MAX_LENGTH;
         } else if (is_less_than($blog_title, TITLE_MIN_LENGTH))
         {
         $JSON->result = "input_error";
         $JSON->response = "The title is " . $blog_title_length . " characters long, it must be at least " . TITLE_MIN_LENGTH;
         } else if (is_less_than($blog_content, BLOGCONTENT_MIN_LENGTH))
         {
         $JSON->result = "input_error";
         $JSON->response = "The blog is " . $blog_content_length . " characters long, it must be at least " . BLOGCONTENT_MIN_LENGTH;
         } else if (is_greater_than($blog_content, BLOGCONTENT_MAX_LENGTH))
         {
         $JSON->result = "input_error";
         $JSON->response = "The blog is " . $blog_content_length . " characters long, it must be at most " . BLOGCONTENT_MAX_LENGTH;
         } else if (preg_match(BANNED_WORDS_REGEX, $blog_content))
         {
         $JSON->result = "input_error";
         $JSON->response = "The blog has curse words!";
         } else
         {

         try
             {
             
             $re = '/( )/';
             $blog_tags = preg_replace($re, "", $blog_tags);

                              $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'lukeokan_lukeokane' AND   TABLE_NAME   = 'blogs';";
                 $statement = $db->prepare($query);
                 $statement->execute();
                 $result = $statement->fetch(PDO::FETCH_ASSOC);
                 $statement->closeCursor();
             
             if (!isset($blog_image) || $blog_image == "")
                 {
                 $dir = "images/defaultBlogImage.png";
                 } else
                 {

                 $imageData = base64_decode($blog_image);
                 $source = imagecreatefromstring($imageData);
                 $angle = 0;
                 $rotate = imagerotate($source, $angle, 0); // if want to rotate the image
                 $dir = "images/" . $result['AUTO_INCREMENT'] . ".png";
                 $imageSave = imagejpeg($rotate, $dir, 100);
                 imagedestroy($source);
                 }


             $query = "INSERT INTO blogs (userName, title, content, tags, imageURL) VALUES (:userName, :blog_title, :blog_content, :blog_tags, :blog_image_location)";
             $statement = $db->prepare($query);
             $statementInputs = array("userName" => $username, "blog_title" => $blog_title, "blog_content" => $blog_content,
                 "blog_image_location" => $dir, "blog_tags" => $blog_tags);

             $statement->execute($statementInputs);
             $statement->closeCursor();
   
             
             $query = "UPDATE usersweb SET postCount = (select count(userName) from blogs where userName = :userName) where userName = :userName;";
             $statement = $db->prepare($query);
             $statementInputs = array("userName" => $username);

             $statement->execute($statementInputs);
             $statement->closeCursor();
             $JSON->result = "success";
             $JSON->response = $result['AUTO_INCREMENT'];
             } catch (Exception $ex)
             {
             $JSON->result = "technical_error";
             $JSON->response = "";
             //For developer debugging:
             //echo $e
             }
         }

     return $JSON;
     }

?>