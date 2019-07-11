<?php
//Get blogs for the front page.
 
 
 require_once 'includes/configuration.php';
 require_once 'Blog.php';
 require_once 'includes/session.php';

     $next_amount = filter_input(INPUT_POST, 'next_amount', FILTER_VALIDATE_INT);
     $query = "SELECT * FROM blogs ORDER BY date DESC LIMIT :next_amount,2";
     $statement = $db->prepare($query);
     $statement->bindValue(':next_amount', $next_amount, PDO::PARAM_INT);
     $statement->execute();
//PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE do not allow conversion to JSON.
     $results = $statement->fetchAll(PDO::FETCH_ASSOC);

     $statement->closeCursor();
     
     $array = array();
          foreach ($results as $result)
         {
         $result['content'] = substr($result['content'],0, strlen($result['content']) / 4) . "...";
         
                                                     
preg_match_all(BLOGCONTENT_CUSTOM_URL,  $result['content'], $matches, PREG_SET_ORDER, 0);

  $replacement = "<a href='http://$4'>$8</a>";

 $result['content'] = preg_replace(BLOGCONTENT_CUSTOM_URL, $replacement,  $result['content']);
         
 array_push($array, $result);
         }
     $json = json_encode($array);
     echo $json;
   