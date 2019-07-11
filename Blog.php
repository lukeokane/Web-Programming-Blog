<?php

require_once 'CONSTANTS.php';
require_once 'methods_library.php';
require_once 'login_and_register_library.php';
require_once 'QuickToString.php';

class Blog
{

    use QuickToString;

    private $title;
    private $ID;
    private $userName;
    private $content;
    private $date;
    private $likes;
    private $dislikes;
    private $views;
    private $tags;
    private $imageURL;
    private $allowComments;

    public function __construct($title, $ID, $userName, $content, $date, $likes, $dislikes, $views, $tags, $imageURL, $allowComments)
    {
        $this->setTitle($title);
        $this->setID($ID);
        $this->setUserName($userName);
        $this->setContent($content);
        $this->setDate($date);
        $this->setLikes($likes);
        $this->setDislikes($dislikes);
        $this->setViews($views);
        $this->setTags($tags);
        $this->setImageURL($imageURL);
        $this->setAllowComments($allowComments);
    }

    public function __destruct()
    {
        $this->setTitle("");
        $this->setID(0);
        $this->setUserName("");
        $this->setContent("");
        $this->setDate("");
        $this->setLikes(0);
        $this->setDislikes(0);
        $this->setViews(0);
        $this->setTags("");
        $this->setImageURL("");
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function getUserName()
    {
        return $this->userName;
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

    public function getViews()
    {
        return $this->views;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getImageURL()
    {
        return $this->imageURL;
    }

    function getAllowComments()
    {
        return $this->allowComments;
    }

    private function setTitle($title)
    {
        if ($title == NULL || is_less_than($title, TITLE_MIN_LENGTH) || is_greater_than($title, TITLE_MIN_LENGTH))
        {
            $this->userName = "Default Title";
        }
        else
        {
            $this->title = $title;
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

    private function setUserName($userName)
    {
        if ($userName == NULL || is_less_than($userName, USERNAME_MIN_LENGTH) || is_greater_than($userName, USERNAME_MAX_LENGTH) || !valid_username($userName))
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
        if ($content == NULL || is_less_than($content, BLOGCONTENT_MIN_LENGTH) || is_greater_than($content, BLOGCONTENT_MAX_LENGTH))
        {
            $this->userName = "Default Content";
        }
        else
        {
            $this->content = $content;
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

    private function setViews($views)
    {
        if ($views == NULL || !is_numeric($views))
        {
            $this->views = 0;
        }
        else
        {
            $this->views = $views;
        }
    }

    private function setTags($tags)
    {
        if ($tags == NULL || is_less_than($tags, TAG_MIN_LENGTH) || is_greater_than($tags, TAG_MAX_LENGTH))
        {
            $this->tags = "Default Tags";
        }
        else
        {
            $this->tags = $tags;
        }
    }

    private function setImageURL($imageURL)
    {
        if ($imageURL == NULL || is_numeric($imageURL) || is_less_than($imageURL, PICURL_MIN_LENGTH) || is_greater_than($imageURL, PICURL_MAX_LENGTH))
        {
            $this->imageURL = "img/defaultBlogImage.png";
        }
        else
        {
            $this->imageURL = $imageURL;
        }
    }

    function setAllowComments($allowComments)
    {
        if ($allowComments === 0 || $allowComments === NULL)
        {
            $this->allowComments = FALSE;
        }
        else
        {
            $this->allowComments = TRUE;
        }
    }

}
