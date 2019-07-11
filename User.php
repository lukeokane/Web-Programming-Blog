<?php

require_once 'CONSTANTS.php';
require_once 'methods_library.php';
require_once 'login_and_register_library.php';
require_once 'QuickToString.php';

class User
{
    use QuickToString;

    private $userName;
    private $password;
    private $email;
    private $joinDate;
    private $postCount;
    private $commentCount;
    private $picURL;
    private $userType;
    private static $count = 0;

    public function __construct($userName, $email, $password, $joinDate, $postCount, $commentCount, $picURL, $userType = 1)
    {
        $this->setUserName($userName);
        $this->setPassword($password);
        $this->setEmail($email);
        $this->setJoinDate($joinDate);
        $this->setPostCount($postCount);
        $this->setCommentCount($commentCount);
        $this->setPicURL($picURL);
        $this->setUserType($userType);
        self::$count++; // add current number of users online in the footer?
    }

    public function __destruct()
    {
        $this->setUserName("");
        $this->setPassword("");
        $this->setEmail("");
        $this->setJoinDate("");
        $this->setPostCount(0);
        $this->setCommentCount(0);
        $this->setPicURL("");
        $this->setUserType(0);
        self::$count--;
    }
    
     public function jsonSerialize() {
        return [               
           'userName'  => $this->getUserName(),
        'email' => $this->getEmail(),
        'joinDate' => $this->getJoinDate(),
        'postCount' =>$this->getPostCount(),
        'commentCount' => $this->getCommentCount(),
        'picURL' => $this->getPicURL()
        ];
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getJoinDate()
    {
        return $this->joinDate;
    }

    public function getPostCount()
    {
        return $this->postCount;
    }

    public function getCommentCount()
    {
        return $this->commentCount;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function getPicURL()
    {
        return $this->picURL;
    }
    
    public static function getCount()
    {
        return self::$count;
    }

    private function setUserName($userName)
    {
        if ($userName == NULL 
                || is_less_than($userName, USERNAME_MIN_LENGTH) 
                || is_greater_than($userName, USERNAME_MAX_LENGTH)
                || !valid_username($userName))
        {
            $this->userName = "Default Name";
        }
        else
        {
            $this->userName = $userName;
        }
    }

    private function setPassword($password)
    {
        if ($password == NULL
                || is_less_than($password, PASSWORD_MIN_LENGTH) 
                || is_greater_than($password, PASSWORD_MAX_LENGTH)
                || !valid_password($password))
        {
            $this->password = "Default Password";
        }
        else
        {
            $this->password = $password;
        }
    }

    private function setEmail($email)
    {
        if ($email == NULL 
                || is_less_than($email, EMAIL_MIN_LENGTH) 
                || is_greater_than($email, EMAIL_MAX_LENGTH)
                || !valid_email($email))
        {
            $this->email = "Default Email";
        }
        else
        {
            $this->email = $email;
        }
    }

    private function setJoinDate($joinDate)
    {
        if ($joinDate == NULL || $joinDate == "")
        {
            $this->joinDate = "Default Join Date";
        }
        else
        {
            $this->joinDate = $joinDate;
        }
    }

    private function setPostCount($postCount)
    {
        if ($postCount == NULL || !is_numeric($postCount))
        {
            $this->postCount = 0;
        }
        else
        {
            $this->postCount = $postCount;
        }
    }

    private function setCommentCount($commentCount)
    {
        if ($commentCount == NULL || !is_numeric($commentCount))
        {
            $this->postCount = 0;
        }
        else
        {
            $this->postCount = $commentCount;
        }
    }

    private function setPicURL($picURL)
    {
        if ($picURL == NULL || is_numeric($picURL)
                || is_less_than($picURL, PICURL_MIN_LENGTH) 
                || is_greater_than($picURL, PICURL_MAX_LENGTH))
        {
            $this->picURL = "Default Pic URL";
        }
        else
        {
            $this->picURL = $picURL;
        }
    }
    
    private function setUserType($userType)
    {
        if ($userType == NULL || !is_numeric($userType))
        {
            $this->userType = 0;
        }
        else
        {
            $this->userType = $userType;
        }
    }

}