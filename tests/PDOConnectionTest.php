<?php
 
 require_once '../PDOConnection.php';
/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-05-01 at 09:43:28.
 */
class PDOConnectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PDOConnection
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new PDOConnection("lukeokan_lukeokane", "localhost", "root", "");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object->close();
    }

    /**
     * @covers PDOConnection::get_user_by_name
     * @todo   Implement testGet_user_by_name().
     */
    public function testGet_user_by_nameSuccess()
    {
        $expectedResult = '{"userName":"lukeokane","email":"lukecjokane@gmail.com","joinDate":"2017-04-20 11:29:44","postCount":"8","commentCount":"0","picURL":"http:\/\/assets3.lfcimages.com\/uploads\/placeholders\/6683__1925__logo-125-splash-new-padded.png"}';
        
        $userName = "lukeokane";
        $result = $this->object->get_user_by_name($userName);
         
        $this->assetEquals($expectedResult, $result);    
    }
    
    
    /**
     * @covers PDOConnection::get_user_by_name
     * @todo   Implement testGet_user_by_name().
     */
    public function testGet_user_by_nameSuccess2()
    {
        $expectedResult = '{"userName":"lukeokane","email":"lukecjokane@gmail.com","joinDate":"2017-04-20 11:29:44","postCount":"8","commentCount":"0","picURL":"http:\/\/assets3.lfcimages.com\/uploads\/placeholders\/6683__1925__logo-125-splash-new-padded.png"}';
        
        $userName = "lukeokane";
        $result = $this->object->get_user_by_name($userName);
         
        $this->assetEquals($expectedResult, $result);
             
        
    }

    /**
     * @covers PDOConnection::get_user_vote
     * @todo   Implement testGet_user_vote().
     */
    public function testGet_user_byNameFailure()
    {
          $expectedResult = '{"result":"failure"}';
        
        $userName = "luk<script>alert('hello')</script>eokane";
        $result = $this->object->get_user_by_name($userName);
         
        $this->assetEquals($expectedResult, $result);
        
    }

}
