<?php

 include_once ("includes/session.php");
 //remove all session variables
 session_unset();
 //destory the session
 session_destroy();
 
 header('location:index.php');
