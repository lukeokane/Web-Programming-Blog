<?php

require_once "../Blog.php";

class BlogTest extends PHPUnit_Framework_TestCase
{

    private $blog;

    protected function setUp()
    {
        $this->blog = new Blog("test blog", 100, "test name", "test content", "12/12/2017", 2, 5, 20, "test,some,tags,here"
                , "http://www.testing.com", 1);
    }

    protected function tearDown()
    {
        unset($this->blog);
    }

    public function testCreateBlog()
    {        
        $this->assertEquals("test blog", $this->getTitle);
        $this->assertEquals(100, $this->getID);
        $this->assertEquals("test name", $this->getUserName);
        $this->assertEquals("test content", $this->getContent);
        $this->assertEquals("12/12/2017", $this->getDate);
        $this->assertEquals(2, $this->getLikes);
        $this->assertEquals(5, $this->getDislikes);
        $this->assertEquals(20, $this->getViews);
        $this->assertEquals("test,some,tags,here", $this->getTags);
        $this->assertEquals("http://www.testing.com", $this->getImageURL);
        $this->assertEquals(1, $this->getAllowComments);

    }
    
    public function testCreateBlogNull()
    {
        
        $this->assertEquals("Default Title", $this->setTitle(NULL));
        $this->assertEquals(0, $this->setID(NULL));
        $this->assertEquals("Default Name", $this->setUserName(NULL));
        $this->assertEquals("Default Content", $this->setContent(NULL));
        $this->assertEquals("Default Date", $this->setDate(NULL));
        $this->assertEquals(0, $this->setLikes(NULL));
        $this->assertEquals(0, $this->setDislikes(NULL));
        $this->assertEquals(0, $this->setViews(NULL));
        $this->assertEquals("Default Tags", $this->setTags(NULL));
        $this->assertEquals("img/defaultBlogImage.png", $this->setImageURL(NULL));
        $this->assertEquals(FALSE, $this->setAllowComments(NULL));

    }


}
