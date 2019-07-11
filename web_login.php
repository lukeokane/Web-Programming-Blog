<?php

require_once 'includes/session.php';
require_once 'includes/configuration.php';
require_once 'CONSTANTS.php';
require_once 'methods_library.php';
require_once 'login_and_register_library.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

echo json_encode(login($db, $email, $password));

function login($db, $email, $password)
    {
    
    $JSON = new stdClass();
// ------ To avoid potential SQL Injection being inputted into PDO statements
if (!preg_match(POTENTIAL_SQL_INJECTION_CHARS, $password))
{
    $JSON->result = "input_error";
    $JSON->response = "Incorrect credentials";
}
else
{
    try
    {
        $query = "SELECT EXISTS (SELECT * FROM usersweb WHERE email = :email) AS result";

        $statement = $db->prepare($query);
        $statementInputs = array("email" => $email);
        $statement->execute($statementInputs);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        if ($result['result'] === "1")
        {
            try
            {
                $query = "SELECT * FROM usersweb WHERE email = :email";
                $statement = $db->prepare($query);
                $statementInputs = array("email" => $email);
                $statement->execute($statementInputs);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();

                
                $hashed_password = filter_var($result['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
               

                if (password_verify($password, $hashed_password))
                {
                    $_SESSION['browser'] = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);
                    $_SESSION['ipaddr'] = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_URL);

                    $_SESSION['userName'] = filter_var($result['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $_SESSION['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
                    $_SESSION['joinDate'] = $result['joinDate']; // sanitize timestamp ???
                    $_SESSION['postCount'] = filter_var($result['postCount'], FILTER_VALIDATE_INT);
                    $_SESSION['commentCount'] = filter_var($result['commentCount'], FILTER_VALIDATE_INT);
                    $_SESSION['picURL'] = filter_var($result['picURL'], FILTER_SANITIZE_URL);
                    $_SESSION['userType'] = filter_var($result['userType'], FILTER_VALIDATE_INT);

                    $JSON->result = "success";
                    $JSON->response = "";
                }
                else
                {
                    $JSON->result = "input_error";
                    $JSON->response = "Incorrect credentials";
                }
            } catch (Exception $ex)
            {
                $JSON->result = "technical_error";
                $JSON->response = "";
                //For developer debugging:
                //echo $ex
            }
        }
        else
        {
            $JSON->result = "input_error";
            $JSON->response = "Incorrect credentials";
        }
    } catch (Exception $ex)
    {
        $JSON->result = "technical_error";
        $JSON->response = "";
        //For developer debugging:
        //echo $ex
    }
}
return $JSON;
    }

?>