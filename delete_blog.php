<?php
 
 require_once 'PDOConnection.php';
 
require_once 'includes/session.php';
 
$PDO = new PDOConnection("redacted", "redacted", "redacted", "redacted");
 $blogID = filter_input(INPUT_POST, 'blogid', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
$userName = $_SESSION['userName'];
if (isset($blogID)&& $_SESSION['userType'] === 1)
    {
$PDO->setStatement("DELETE FROM blogs WHERE ID = :blogID;");
$array = array(":blogID" => $blogID);
$PDO->execute($array);

$PDO->setStatement("UPDATE usersweb SET postCount = (select count(userName) from blogs where userName = :userName) where userName = :userName;");
$array = array(":userName" => $userName);
$PDO->execute($array);
$JSON->result = "success";
echo json_encode($JSON);
    }

