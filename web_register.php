<?php

 require_once 'includes/configuration.php';
 require_once 'CONSTANTS.php';
 require_once 'methods_library.php';
 require_once 'login_and_register_library.php';

 $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

 $password = filter_input(INPUT_POST, 'password');

 $password_check = filter_input(INPUT_POST, 'password_check');

 $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

 $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

 echo json_encode(register_check($db, $username, $password, $password_check, $email, $image));

 function register_check($db, $username, $password, $password_check, $email, $image)
     {
     
     
      $username_length = strlen($username);
       $password_length = strlen($password);
 if (is_greater_than($username, USERNAME_MAX_LENGTH))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your username is " . $username_length . " characters long, it must be at most " . USERNAME_MAX_LENGTH;
     } else if (is_less_than($username, USERNAME_MIN_LENGTH))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your username is " . $username_length . " characters long, it must be at least " . USERNAME_MIN_LENGTH;
     } else if (!valid_username($username))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your username has illegal characters ( @_+.!$%()- are only permitted )";
     } else if (!valid_email($email))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your email address is invalid.";
     } else if (is_greater_than($password, PASSWORD_MAX_LENGTH))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your password is " . $password_length . " characters long, it must be at most " . PASSWORD_MAX_LENGTH;
     } else if (is_less_than($password, PASSWORD_MIN_LENGTH))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your password is " . $password_length . " characters long, it must be at least " . PASSWORD_MIN_LENGTH;
     } else if (!valid_password($password))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your password must contain at least a mixed case letters and at least one number. Password can contain " . PASSWORD_SPECIAL_CHARACTERS;
     } else if (!string_equals_case_sensitive($password, $password_check))
     {
     $JSON->result = "input_error";
     $JSON->response = "Your passwords do not match.";
     } else
     {
     try
         {
         $query = "SELECT EXISTS (SELECT * FROM usersweb WHERE userName = :userName) AS result";

         $statement = $db->prepare($query);
         $statementInputs = array("userName" => $username);
         $statement->execute($statementInputs);
         $result = $statement->fetch(PDO::FETCH_ASSOC);
         $statement->closeCursor();
         if ($result['result'] === "1")
             {
             $JSON->result = "input_error";
             $JSON->response = "This username already exists";
             } else
             {
             try
                 {
                 $query = "SELECT EXISTS (SELECT * FROM usersweb WHERE email = :email) AS result";

                 $statement = $db->prepare($query);
                 $statementInputs = array("email" => $email);
                 $statement->execute($statementInputs);
                 $result = $statement->fetch(PDO::FETCH_ASSOC);
                 $statement->closeCursor();
                 if ($result['result'] === "1")
                     {
                     $JSON->result = "input_error";
                     $JSON->response = "This email already exists";
                     } else
                     {
                     $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                     $email = strtolower($email);
                     if (!isset($image) || $images === "")
                         {
                         $dir = "images/defaultProfilePicture.jpg";
                         }
                         else
                             {
                     $dir = "images/" . $username . ".png";
                             }


                     $query = "INSERT INTO usersweb (userName, email, password, picURL) VALUES (:userName, :email, :password, :picURL)";
                     $statement = $db->prepare($query);
                     $statementInputs = array("userName" => $username,
                         "email" => $email,
                         "password" => $hashed_password,
                         "picURL" => $dir);
                     $statement->execute($statementInputs);
                     $statement->closeCursor();


                     $imageData = base64_decode($image);
                     $source = imagecreatefromstring($imageData);
                     $angle = 0;
                     $rotate = imagerotate($source, $angle, 0); // if want to rotate the image
                     $imageSave = imagejpeg($rotate, $dir, 100);
                     imagedestroy($source);

                     $JSON->result = "success";
                     $JSON->response = "";
                     }
                 } catch (Exception $ex)
                 {
                 $JSON->result = "technical_error";
                 $JSON->response = "";
                 //For developer debugging:
                 //echo $ex
                 }
             }
         } catch (PDOException $e)
         {
         $JSON->result = "technical_error";
         $JSON->response = "";
         //For developer debugging:
         //echo $ex
         }
     }
     return $JSON;
     }

?>