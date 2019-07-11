<?php

 require_once 'PDOConnection.php';
 require_once 'includes/session.php';

 $blogID = filter_input(INPUT_POST, 'blogid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
 $voteInput = filter_input(INPUT_POST, 'vote', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
 $userName = filter_var($_SESSION['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);



 $PDOConnection = new PDOConnection("redacted", "redacted", "redacted", "redacted");

//echo $PDOConnection->get_user_by_name("lukeokane");
//print_r($PDOConnection->blog_vote("lukeokane", 16, "LIKE"));

 if (isset($blogID) && isset($voteInput) && in_array($voteInput, BLOG_VOTE_TYPES))
     {
     
     $vote = $PDOConnection->get_user_vote($userName, $blogID);
     if (count(array_filter($vote, 'strlen')) === 1)
         {
         if ($vote['vote'] == $voteInput)
             {

             $JSON = $PDOConnection->delete_blog_vote($userName, $blogID);
             } else
             {

             $JSON = $PDOConnection->update_blog_votes($userName, $blogID, $voteInput);
             }
         } else
         {

         $JSON = $PDOConnection->insert_blog_vote($userName, $blogID, $voteInput);
         }
     $votes_array = $PDOConnection->get_blog_votes($blogID);

     $JSON2 = $PDOConnection->update_blog_votes_counter($votes_array, $blogID);

     
     $myjson = json_encode($JSON);
     $myjson2 = json_encode($JSON2);
     $decode = json_decode($myjson, true);
     $decode2 = json_decode($myjson2, true);

     $decode['like_count'] = $decode2['like_count'];
     $decode['dislike_count'] = $decode2['dislike_count'];
     echo json_encode($decode);
     
     $PDOConnection->close();
     } else
     {
     $JSON->result = "failure";
     }
     
     
 
 