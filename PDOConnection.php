<?php

 require_once 'includes/configuration.php';
 require_once 'includes/session.php';
 require_once 'CONSTANTS.php';
 require_once 'User.php';
 require_once 'login_and_register_library.php';

 class PDOConnection
     {

     private $pdoConnection;
     private $databaseName;
     private $hostName;
     private $userName;
     private $userPassword;
     private $statement;

     function __construct($databaseName, $hostName, $userName, $userPassword)
         {
         $this->databaseName = $databaseName;
         $this->hostName = $hostName;
         $this->userName = $userName;
         $this->userPassword = $userPassword;

         $dsn = "mysql:dbname=$databaseName;host=$hostName";
         try
             {
             $this->pdoConnection = new PDO($dsn, $this->userName, $this->userPassword);
             $this->pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             } catch (PDOException $e)
             {
             echo "Connection creation error:" . $e->getMessage() . PHP_EOL;
             echo "DSN: " . $dsn . PHP_EOL;
             }
         }

     function setStatement($sql)
         {
         try
             {
             $this->statement = $this->pdoConnection->prepare($sql);
             } catch (PDOException $e)
             {
             echo "Set statement error:" . $e->getMessage() . PHP_EOL;
             echo "SQL: " . $sql . PHP_EOL;
             }
         }

     function execute($arrayData)
         {
         try
             {
             if (!empty($arrayData) && !is_null($this->statement))
                 {
                 return $this->statement->execute($arrayData);
                 }
             } catch (PDOException $e)
             {
             echo "Execute statement error:" . $e->getMessage() . PHP_EOL;
             echo "Data: " . implode(",", $arrayData) . PHP_EOL;
             }
         }

     function query($className)
         {
         try
             {
             if (is_string($className) && !empty($className) && !is_null($this->statement))
                 {
                 $this->statement->execute($arrayData);
                 return $this->statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
                 }
             } catch (PDOException $e)
             {
             echo "Execute statement error:" . $e->getMessage() . PHP_EOL;
             echo "Data: " . implode(",", $arrayData) . PHP_EOL;
             }
         }

     function close()
         {
         try
             {
             unset($this->pdoConnection);
             unset($this->statement);
             } catch (PDOException $e)
             {
             echo "Close error:" . $e->getMessage() . PHP_EOL;
             }
         }

     function fetchAllClass($class)
         {
         return $this->statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
         }

     function get_user_by_name($userName)
         {
         //Check if username is valid to avoid SQL injection.
         if (valid_username($userName))
             {
             $this->setStatement("SELECT * FROM usersweb WHERE userName = :userName");
             $array = array(":userName" => $userName);
             $this->execute($array);
             $user = $this->fetchAllClass("User");
             $this->close();
             //Make object into a JSON string for client to receive.
             $JSON = $user[0]->jsonSerialize();
             } else
             {
             $JSON->result = "failure";
             }

         return json_encode($JSON);
         }

     function get_user_vote($userName, $blogID)
         {
         $JSON = new stdClass(); 
        $this->setStatement("SELECT vote FROM blog_votes WHERE userName = :userName AND blogID = :blogID");
         $array = array(":userName" => $userName, ":blogID" => $blogID);
         $this->execute($array);
         $vote = $this->statement->fetch(PDO::FETCH_ASSOC);

         return $vote;
         }

     function delete_blog_vote($userName, $blogID)
         {
                     
         try
             {
             $this->setStatement("DELETE FROM blog_votes WHERE userName = :userName AND blogID = :blogID");
             $array = array(":userName" => $userName, ":blogID" => $blogID);
             $this->execute($array);
             $JSON->result = "success";
             $JSON->voteState = "0";
             } catch (Exception $ex)
             {
             $JSON->result = "failure";
             }
         return $JSON;
         }

     function update_blog_votes($userName, $blogID, $voteInput)
         {
         
         try
             {
             $this->setStatement("UPDATE blog_votes SET vote = :vote WHERE userName = :userName AND blogID = :blogID");
             $array = array(":userName" => $userName, ":blogID" => $blogID, ':vote' => $voteInput);
             $this->execute($array);

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
             $JSON->result   = "failure";
             }
         return $JSON;
         }
         
         function insert_blog_vote($userName, $blogID, $voteInput)
             {
                     
             try
                 {
              $this->setStatement("INSERT into blog_votes (userName, blogID, vote) VALUES (:userName, :blogID, :vote)");
             $array = array(":userName" => $userName, ":blogID" => $blogID, ':vote' => $voteInput);
             $this->execute($array);

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
             return $JSON;
             }
             
             function get_blog_votes($blogID)
                 {
                 $this->setStatement("SELECT vote, count(vote) as amount FROM blog_votes WHERE blogID = :blogID group by vote");
                  $array = array(":blogID" => $blogID);
         $this->execute($array);

         $votes = $this->statement->fetchAll(PDO::FETCH_ASSOC);
         $votes_array = array();
         foreach ($votes as $vote)
             {
             $votes_array[$vote['vote']] = $vote['amount'];
             }
             return $votes_array;
         }
         
         function update_blog_votes_counter($votes_array, $blogID)
             {
             $this->setStatement("UPDATE blogs SET likes = :likes, dislikes = :dislikes WHERE ID = :blogID");
             $array = array(":blogID" => $blogID, ':likes' => $votes_array["LIKE"], ':dislikes' => $votes_array["DISLIKE"] );
     $this->execute($array);

     
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
     return $JSON;
             }

     function blog_vote($userName, $blogID, $voteInput)
         {
   
//
//     $user = "UPDATE blogs SET likes = :likes, dislikes = :dislikes WHERE ID = :blogID";
//     $stmt6 = $db->prepare($user);
//     $stmt6->bindValue(':likes', $votes_array["LIKE"]);
//     $stmt6->bindValue(':dislikes', $votes_array["DISLIKE"]);
//     $stmt6->bindValue(':blogID', $blogID);
//     $stmt6->execute();
//     $stmt6->closeCursor();
//     
//          if ($votes_array["LIKE"] == null)
//         {
//         $votes_array["LIKE"] = 0;
//         }
//         if ($votes_array["DISLIKE"] == null)
//         {
//         $votes_array["DISLIKE"] = 0;
//         }
//     $JSON->like_count = $votes_array["LIKE"];
//     $JSON->dislike_count = $votes_array["DISLIKE"];
//     } else
//     {
//     $JSON->result = "failure";
//     }
//
// return $JSON;
         }

     }
 