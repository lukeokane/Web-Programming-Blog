<?php

require_once 'CONSTANTS.php';
require_once 'methods_library.php';
require_once 'login_and_register_library.php';
require_once 'QuickToString.php';

class Comment
{
    use QuickToString;

    private $userName;
    private $ID;
    private $blogID;
    private $content;
    private $date;
    private $likes;
    private $dislikes;

    public function __construct($userName, $ID, $blogID, $content, $date, $likes, $dislikes)
    {
        $this->setUserName($userName);
        $this->setID($ID);
        $this->setBlogID($blogID);
        $this->setContent($content);
        $this->setDate($date);
        $this->setLikes($likes);
        $this->setDislikes($dislikes);
    }

    public function __destruct()
    {
        $this->setUserName("");
        $this->setID(0);
        $this->setBlogID(0);
        $this->setContent("");
        $this->setDate(0);
        $this->setLikes(0);
        $this->setDislikes(0);
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function getID()
    {
        return $this->ID;
    }
    
    public function getBlogID()
    {
        return $this->blogID;
    }
    
    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getDislikes()
    {
        return $this->dislikes;
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
    
    private function setContent($content)
    {
        if ($content == NULL 
                || is_less_than($content, COMMENT_MIN_LENGTH) 
                || is_greater_than($content, COMMENT_MAX_LENGTH))
        {
            $this->userName = "Default Content";
        }
        else
        {
            $this->content = $content;
        }
    }

    private function setID($ID)
    {
        if ($ID == NULL || !is_numeric($ID))
        {
            $this->ID = 0;
        }
        else
        {
            $this->ID = $ID;
        }
    }
    
    private function setBlogID($blogID)
    {
        if ($blogID == NULL || !is_numeric($blogID))
        {
            $this->blogID = 0;
        }
        else
        {
            $this->blogID = $blogID;
        }
    }

    private function setDate($date)
    {
        if ($date == NULL || $date == "")
        {
            $this->date = "Default Date";
        }
        else
        {
            $this->date = $date;
        }
    }

    private function setLikes($likes)
    {
        if ($likes == NULL || !is_numeric($likes))
        {
            $this->likes = 0;
        }
        else
        {
            $this->likes = $likes;
        }
    }

    private function setDislikes($dislikes)
    {
        if ($dislikes == NULL || !is_numeric($dislikes))
        {
            $this->dislikes = 0;
        }
        else
        {
            $this->dislikes = $dislikes;
        }
    }

}
