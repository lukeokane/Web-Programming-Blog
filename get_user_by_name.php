<?php

 
 
require_once 'includes/session.php';
require_once 'includes/configuration.php';
require_once 'User.php';
require_once 'login_and_register_library.php';

$userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);

echo json_encode(get_user_by_name($db, $userName));

function get_user_by_name($db, $userName)
    {
if (valid_username($userName))
{
    $queryUser = "SELECT * FROM usersweb WHERE userName = :userName";
    $stmt = $db->prepare($queryUser);

    $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
    $stmt->closeCursor();
    
    $JSON = $user[0]->jsonSerialize();
    return $JSON;
}
else
{
    $JSON->result = "failure";
    return $JSON;
}
    }


?>

