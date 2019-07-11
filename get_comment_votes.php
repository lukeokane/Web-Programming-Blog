<?php
require_once 'includes/configuration.php';
 $commentID = filter_input(INPUT_POST, 'commentid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
 if(isset($commentID))
     {
 $query = "SELECT vote, count(vote) as amount FROM comment_votes WHERE commentID = :commentID group by vote";
 $stmt = $db->prepare($query);

 $stmt->bindValue(':commentID', $commentID);
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
     

 