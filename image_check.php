<?php

 require_once 'includes/session.php';
 require_once 'includes/configuration.php';
 require_once 'CONSTANTS.php';
 require_once 'methods_library.php';



 $image = $_FILES['file'];
 
 echo json_encode(image_check($image));
 
 function image_check($image)
     {
 $split = explode(".", $image['name']);
 $image_type = strtolower($split[1]);
 list($width, $height) = getimagesize($image['tmp_name']);

 if (!isset($image))
     {
     $JSON->result = "input_error";
     $JSON->response = "No image was uploaded.";
     } else if ($image['size'] > PICURL_MAX_BYTE_SIZE)
     {
     $JSON->result = "input_error";
     $JSON->response = "Your image size is too large! It must be less than " . (PICURL_MAX_BYTE_SIZE / 1024) / 1024 . "MB";
     } else if (!in_array($image_type, PICURL_ALLOWED_EXTENSIONS))
     {
     $JSON->result = "input_error";
     $JSON->response = "This image is a " . $image_type . ", we only accept " . implode(",", PICURL_ALLOWED_EXTENSIONS) . " files!";
     } else if ($width > PICURL_MAX_WIDTH || $height > PICURL_MAX_HEIGHT)
     {
     $JSON->result = "input_error";
     $JSON->response = "This image is " . $height . " x " . $width . " in size, it can be no bigger than " . PICURL_MAX_HEIGHT . " x " . PICURL_MAX_WIDTH;
     } else
     {
     $JSON->result = "success";
     $JSON->response = "";
     }
return $JSON;
     }
?>