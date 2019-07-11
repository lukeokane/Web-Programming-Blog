<?php

 
 require_once 'includes/session.php';
 require_once 'includes/configuration.php';
 
   if ($_SESSION['userType'] != 1)
     {
     header('location:index.php');
     exit();
     }


 $blogID = filter_input(INPUT_POST, 'blogid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);

 if (isset($blogID))
     {
     $query = "SELECT allowComments FROM blogs WHERE ID = :blogID";
     $stmt = $db->prepare($query);

     $stmt->bindValue(':blogID', $blogID);
     $stmt->execute();
     $state = $stmt->fetch(PDO::FETCH_ASSOC);
     $stmt->closeCursor();

     $newState;

     if ($state['allowComments'] == 0)
         {
         $newState = 1;
         } else
         {
         $newState = 0;
         }

     $query = "UPDATE blogs set allowComments = :newState WHERE ID = :blogID";
     $stmt = $db->prepare($query);

     $stmt->bindValue(':blogID', $blogID);
     $stmt->bindValue(':newState', $newState);
     $stmt->execute();
     $JSON->newState = $newState;
     $JSONencoded = json_encode($JSON);
     echo $JSONencoded;
     }
   
 