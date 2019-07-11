<?php
require_once 'includes/configuration.php';
 
$blogID = filter_input(INPUT_POST, 'blogid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
 if(isset($blogID))
     {
 $query = "SELECT vote, count(vote) as amount FROM blog_votes WHERE blogID = :blogID group by vote";
 $stmt = $db->prepare($query);

 $stmt->bindValue(':blogID', $blogID);
 $stmt->execute();

 $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
 $stmt->closeCursor();
$votes_array = array();
 foreach ($votes as $vote)
     {
    $votes_array[$vote['vote']] = $vote['amount'];
     }
    
 return $votes_array;
     }
     

 