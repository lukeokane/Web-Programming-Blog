<?php

 require_once 'includes/session.php';
 require_once 'includes/configuration.php';
 require_once 'CONSTANTS.php';


 $commentID = filter_input(INPUT_POST, 'commentid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
 $voteInput = filter_input(INPUT_POST, 'vote', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
 $userName = filter_var($_SESSION['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 
 if ($_SESSION['userType'] != 1)
     {
     $JSON->result = "nsi";
     } else
     {
    $JSON = comment_vote($db, $userName, $voteInput, $commentID);
     }


 echo json_encode($JSON);

//VOTING STATES to return to client.
// 0 = NO VOTE
// 1 = LIKE
// 2 = DISLIKE
 function comment_vote($db, $userName, $voteInput, $commentID)
     {
     if (isset($voteInput) && isset($commentID) && in_array($voteInput, COMMENT_VOTE_TYPES))
         {
         $query = "SELECT vote FROM comment_votes WHERE userName = :userName AND commentID = :commentID";
         $stmt = $db->prepare($query);

         $stmt->bindValue(':userName', $userName);
         $stmt->bindValue(':commentID', $commentID);
         $stmt->execute();

         $vote = $stmt->fetch(PDO::FETCH_ASSOC);
         $stmt->closeCursor();

         if ($stmt->rowCount() == 1)
             {
             if ($vote['vote'] == $voteInput)
                 {
                 try
                     {
                     $query = "DELETE FROM comment_votes WHERE userName = :userName AND commentID = :commentID";
                     $stmt = $db->prepare($query);

                     $stmt->bindValue(':userName', $userName);
                     $stmt->bindValue(':commentID', $commentID);
                     $stmt->execute();
                     $stmt->closeCursor();

                     $JSON->result = "success";
                     $JSON->voteState = "0";
                     } catch (Exception $ex)
                     {
                     $JSON->result = "failure";
                     }
                 } else
                 {

                 try
                     {


                     $query = "UPDATE comment_votes SET vote = :vote WHERE userName = :userName AND commentID = :commentID";
                     $stmt = $db->prepare($query);

                     $stmt->bindValue(':userName', $userName);
                     $stmt->bindValue(':commentID', $commentID);
                     $stmt->bindValue(':vote', $voteInput);
                     $stmt->execute();
                     $stmt->closeCursor();

                     $JSON->result = "success";
                     if ($voteInput == "LIKE")
                         {
                         $JSON->voteState = 1;
                         } else
                         {
                         $JSON->voteState = 2;
                         }
                     } catch (Exception $ex)
                     {
                     $JSON->result = "failure";
                     }
                 }
             } else
             {
             try
                 {


                 $query = "INSERT into comment_votes (userName, commentID, vote) VALUES (:userName, :commentID, :vote)";
                 $stmt = $db->prepare($query);

                 $stmt->bindValue(':userName', $userName);
                 $stmt->bindValue(':commentID', $commentID);
                 $stmt->bindValue(':vote', $voteInput);
                 $stmt->execute();
                 $stmt->closeCursor();

                 $JSON->result = "success";
                 if ($voteInput == "LIKE")
                     {
                     $JSON->voteState = 1;
                     } else
                     {
                     $JSON->voteState = 2;
                     }
                 } catch (Exception $ex)
                 {
                 $JSON->result = "failure";
                 }
             }
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

         $user = "UPDATE blog_comments SET likes = :likes, dislikes = :dislikes WHERE ID = :commentID";
         $stmt = $db->prepare($user);
         $stmt->bindValue(':likes', $votes_array["LIKE"]);
         $stmt->bindValue(':dislikes', $votes_array["DISLIKE"]);
         $stmt->bindValue(':commentID', $commentID);
         $stmt->execute();
         $stmt->closeCursor();

         if ($votes_array["LIKE"] == null)
             {
             $votes_array["LIKE"] = 0;
             }
         if ($votes_array["DISLIKE"] == null)
             {
             $votes_array["DISLIKE"] = 0;
             }
         $JSON->like_count = $votes_array["LIKE"];
         $JSON->dislike_count = $votes_array["DISLIKE"];
         } else
         {
         $JSON->result = "failure";
         }
         
         return $JSON;
     }
 