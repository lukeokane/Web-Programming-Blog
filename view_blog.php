<?php
require_once 'includes/session.php';
require_once 'includes/configuration.php';
require_once 'CONSTANTS.php';
require_once 'Blog.php';
require_once 'User.php';
require_once 'Comment.php';

if ($_SESSION['userType'] != 1)
{
    header('location:index.php');
    exit();
}

$userName = filter_var($_SESSION['userName'], FILTER_SANITIZE_STRING);
$ID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// update views
$increment = filter_var(1, FILTER_VALIDATE_INT);
$queryUpdateViews = "UPDATE blogs SET views = views + :increment WHERE ID = :ID";
$stmtViews = $db->prepare($queryUpdateViews);
$stmtViews->execute(array(':ID' => $ID, ':increment' => $increment));

// get selected Blog by ID
$queryID = "SELECT * FROM blogs WHERE ID = :ID";
$stmt = $db->prepare($queryID);
$stmt->bindValue(':ID', $ID, PDO::PARAM_INT);
$stmt->execute();
$userBlog = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Blog");
$stmt->closeCursor();
//echo '<pre>'; print_r($userBlog); echo '</pre>';

if (count(array_filter($userBlog, 'strlen')) < 1)
    {
     header('location:home.php');
     exit();
    }


// get the top 5 Blogs
$queryTopBlogs = "SELECT * FROM blogs ORDER BY likes DESC LIMIT 5";
$stmt2 = $db->query($queryTopBlogs);
$topBlogs = $stmt2->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Blog");
//echo '<pre>'; print_r($topBlogs); echo '</pre>';
//
// get the 5 most viewed Blogs
$queryMostViewedBlogs = "SELECT * FROM blogs ORDER BY views DESC LIMIT 5";
$stmt3 = $db->query($queryMostViewedBlogs);
$mostViewedBlogs = $stmt3->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Blog");
//echo '<pre>'; print_r($mostViewedBlogs); echo '</pre>';
//
// get all comments for selected blog
$queryBlogComments = "SELECT * FROM blog_comments WHERE blogID = :ID ORDER BY date DESC";
$stmt4 = $db->prepare($queryBlogComments);
$stmt4->bindValue(':ID', $ID, PDO::PARAM_INT);
$stmt4->execute();
$blogComments = $stmt4->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Comment");
$stmt4->closeCursor();
//echo '<pre>'; print_r($blogComments); echo '</pre>';
// check if user has voted on the blog.
$queryBlogComments2 = "SELECT vote from blog_votes WHERE blogID = :blogID AND userName = :userName";
$stmt5 = $db->prepare($queryBlogComments2);
$stmt5->bindValue(':userName', $userName);
$stmt5->bindValue(':blogID', $ID, PDO::PARAM_INT);
$stmt5->execute();
$blogVote = $stmt5->fetch(PDO::FETCH_ASSOC);
$stmt5->closeCursor();
//echo '<pre>'; print_r($blogVote); echo '</pre>';
// get tags for selected blog
$blogTags = $userBlog[0]->getTags();
//echo '<pre>'; print_r($blogTags); echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Blog - Home</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet" type="text/css"/>
        <link href="css/hover.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/blog-home.css" rel="stylesheet">
        <link href="css/blog-post.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            #options
            {
                text-align: center;
            }

            #comment_options
            {
                height:30px;
                font-size: 125%;
                float:right;
                padding: 0.5em;
            }

            #like_blog.hvr-icon-bob:before, #like_comment.hvr-icon-bob:before
            {
                content: "\f164";
            }

            #dislike_blog.hvr-icon-bob:before, #dislike_comment.hvr-icon-bob:before
            {
                content: "\f165";
            }

            .media{
                text-align: justify;
            }

            .img-responsive-blog-imgs {
                display: block;
                margin-left: auto;
                margin-right: auto;           
                max-height: 350px;
                max-width: 700px;}

            #alert_box
            {
                top: 10%;
                position: fixed;
                width: 90%;
                margin-left: 5%;
                margin-right: 5%;
                z-index: 2000;
            }

            /* Formatting result items */
            .result p
            {
                margin: 0;
                padding: 7px 10px;
                border: 1px solid #CCCCCC;
                border-top: none;
                cursor: pointer;

                background: white;
            }
            .result p:hover
            {
                background: #f2f2f2;
            }
            
            .tags a, .tags a:hover{
                background-color: #337ab7;
            }
            
            .media-object
            {
                max-height: 64px;
                max-width: 64px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.search-box input[type="text"]').on("keyup input", function () {
                    /* Get input value on change */
                    var inputVal = $(this).val();
                    var resultDropdown = $(this).siblings(".result");
                    if (inputVal.length) {
                        $.get("backend-search.php", {term: inputVal}).done(function (data) {
                            // Display the returned data in browser
                            resultDropdown.html(data);
                        });
                    } else {
                        resultDropdown.empty();
                    }
                });

                // Set search input value on click of result item
                $(document).on("click", ".result p", function () {
                    $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                    $(this).parent(".result").empty();
                });
            });
        </script>

    </head>

    <body>
        <!-- Navigation -->
        <nav id="navbar" data-blogid="<?php echo $ID ?>"  class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div id="alert_box"></div>
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="home.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home</a>
                    <?php
                    if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                    {
                        echo "<a href='#' class='navbar-brand' role='button' data-toggle='modal' data-target='#profile-modal'>"
                        . "<span class='glyphicon glyphicon-user' aria-hidden='true'></span>&nbsp;Profile</a></button>";
                    }
                    else
                    {
                        echo "<a class='navbar-brand' href='index.php'><span class='glyphicon glyphicon-log-in' aria-hidden='true'>"
                        . "</span>&nbsp;Sign In</a>";
                    }
                    ?>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav">

                        <?php
                        if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                        {
                            echo "<li><a href='post_blog.php'>Create Blog</a></li>";
                            echo "<li><a href='logout.php'>Logout</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!-- Page Content -->
        <div class="container">

            <div class="row">
                <!-- Blog Post Content Column -->
                <div class="col-lg-8">

                    <!-- Blog Post -->

                    <!-- Title -->
                    <h1><?php echo htmlspecialchars($userBlog[0]->getTitle(), ENT_QUOTES, 'utf-8'); ?></h1>

                    <!-- Author -->
                    <p class="lead">

                        by <a href="#" id="view_profile_modal"  data-username="<?php echo htmlspecialchars($userBlog[0]->getUserName(), ENT_QUOTES, 'utf-8'); ?>" role="button" data-toggle="modal" data-target="#profile-modal2"><?php echo htmlspecialchars($userBlog[0]->getUserName(), ENT_QUOTES, 'utf-8'); ?></a><span id="like_blog" <?php
                        if ($blogVote['vote'] === "LIKE")
                        {
                            echo "style='color: #00B2EE;'";
                        }
                        ?> class="hvr-icon-bob"></span><span id="blog_like_count"  class="label label-default"><?php echo htmlspecialchars($userBlog[0]->getLikes(), ENT_QUOTES, 'utf-8'); ?></span></span><span id="dislike_blog" <?php
                        if ($blogVote['vote'] === "DISLIKE")
                        {
                            echo "style='color: #00B2EE;'";
                        }
                        ?> class="hvr-icon-bob"></span><span id="blog_dislike_count" class="label label-default"><?php echo htmlspecialchars($userBlog[0]->getDislikes(), ENT_QUOTES, 'utf-8'); ?></span></p>
                    </p>
                    <hr>

                    <!-- Date/Time -->
                    <p><span class="glyphicon glyphicon-time"></span> Posted on: <?php echo htmlspecialchars($userBlog[0]->getDate(), ENT_QUOTES, 'utf-8'); ?></p>

                    <hr>

                    <!-- Preview Image -->
                    <img class="img-responsive-blog-imgs" src="<?php echo htmlspecialchars($userBlog[0]->getImageURL(), ENT_QUOTES, 'utf-8'); ?>" alt="">

                    <hr>

                    <!-- Post Content -->
                    <p class="lead">
                        <?php
                        $content = htmlspecialchars($userBlog[0]->getContent(), ENT_QUOTES, 'utf-8');
                        preg_match_all(BLOGCONTENT_CUSTOM_URL, $content, $matches, PREG_SET_ORDER, 0);
                        $replacement = '<a href="http://$4">$8</a>';
                        echo preg_replace(BLOGCONTENT_CUSTOM_URL, $replacement, $content);
                        ?>
                    </p>

                    <div class="item">
                        <div class="item-content-block">
                            <div class="block-title">Tags</div>
                        </div>
                        <div class="item-content-block tags">
                            <?php
                            if (!empty($blogTags))
                            {
                                $tagArray = explode(',', $blogTags);
                                foreach ($tagArray as $tag)
                                {
                                    echo "<a class='btn btn-primary'>" . htmlspecialchars($tag, ENT_QUOTES, 'utf-8') . "</a>";
                                }
                            }
                            else
                            {
                                echo "<a>None</a>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Blog Comments -->
                    
                    <!-- Comments / Tags Form -->
                    <?php if ($_SESSION['bad_input']){ echo '<div class="alert alert-danger text-center">' . $_SESSION['bad_input_message'] . "</div>"; } $_SESSION['bad_input'] = false; $_SESSION['bad_input_message'] = "";?>
                    <div class="well">

                        <h4><?php
                            if ($userBlog[0]->getAllowComments() == TRUE)
                            {
                                echo "Leave a Comment";
                            }
                            else
                            {
                                echo "Comments have been disabled";
                            }
                            ?></h4>
                        <form role="form" id="commentBox" action="insert_comment.php" method="POST">
                            <input type="hidden" name="blogID" value="<?php echo htmlspecialchars($userBlog[0]->getID(), ENT_QUOTES, 'utf-8'); ?>" />
                            <div class="form-group">
                                <textarea id='comment_box' <?php
                                if ($userBlog[0]->getAllowComments() == FALSE)
                                {
                                    echo " disabled";
                                }
                                ?>
                                          class="form-control" name="comment" rows="3" placeholder="Enter comment here..." required ></textarea>
                            </div>

                            <?php
                            if ($userBlog[0]->getAllowComments() == TRUE)
                            {
                                echo ' <div class="g-recaptcha" data-sitekey="6LdhMx8UAAAAACsOAXIy2BsrZ3k4FKp-yw7lNLIP"></div>
                                                        <br> <button type="submit" class="btn btn-primary">Submit</button>';
                            }
                            if ($userBlog[0]->getUserName() == $userName)
                            {
                                echo ' <a id="delete_blog" class="btn btn-primary">Delete Post</a> <a id="toggle_comments" class="btn btn-primary">';
                                if ($userBlog[0]->getAllowComments() == FALSE)
                                {
                                    echo "Enable Commenting";
                                }
                                else
                                {
                                    echo "Disable Commenting";
                                }
                                echo '</a>';
                            }
                            ?>
                            <br>
                        </form>
                    </div>


                    <hr>

                    <!-- Posted Comments -->

                    <!-- Comments -->
                    <div id='comments'>
                        <?php
                        if ($stmt4->rowCount() > 0)
                        {
                            foreach ($blogComments as $blogComment)
                            {

                                // check if user has voted on blog.
                                $queryCommentVote = "SELECT vote from comment_votes where userName = :userName and commentID = :commentID";
                                $stmt5 = $db->prepare($queryCommentVote);
                                $stmt5->bindValue(':userName', $userName);
                                $stmt5->bindValue(':commentID', $blogComment->getID(), PDO::PARAM_INT);
                                $stmt5->execute();
                                $commentVote = $stmt5->fetch(PDO::FETCH_ASSOC);
                                $stmt5->closeCursor();
                                
                                 // get user image
                                $queryCommentPic = "SELECT picURL from usersweb where userName = :userName";
                                $stmt6 = $db->prepare($queryCommentPic);
                                $stmt6->bindValue(':userName', $userName);
                                $stmt6->execute();
                                $commentPic = $stmt6->fetch(PDO::FETCH_ASSOC);
                                $stmt6->closeCursor();
                          
                                
                                echo "<div class='media'>"
                                . "<a class='pull-left' role='button' data-toggle='modal' data-target='#profile-modal2' data-username='" . htmlspecialchars($blogComment->getUserName(), ENT_QUOTES, 'utf-8') . "' id='view_profile_modal2' ><img class='media-object' src='".  htmlspecialchars($commentPic['picURL'] , ENT_QUOTES, 'utf-8'). "' alt=''></a>"
                                . "<div data-commentid='" . htmlspecialchars($blogComment->getID(), ENT_QUOTES, 'utf-8') . "' class='media-body'>"
                                . "<div  id='comment_options'><span id='like_comment'";
                                if ($commentVote['vote'] === "LIKE")
                                {
                                    echo "style='color: #00B2EE'";
                                }
                                echo "class='hvr-icon-bob'></span><span id='comment_like_count' class='label label-default'>" . htmlspecialchars($blogComment->getLikes(), ENT_QUOTES, 'utf-8') . "</span></span><span id='dislike_comment'";
                                if ($commentVote['vote'] === "DISLIKE")
                                {
                                    echo "style='color: #00B2EE'";
                                }
                                echo "class='hvr-icon-bob'></span><span id='comment_dislike_count' class='label label-default'>" . htmlspecialchars($blogComment->getDislikes(), ENT_QUOTES, 'utf-8') . "</span></div>"
                                . "<h4 class='media-heading'>" . htmlspecialchars($blogComment->getUserName(), ENT_QUOTES, 'utf-8')
                                . "<small> Posted on: " . htmlspecialchars($blogComment->getDate(), ENT_QUOTES, 'utf-8') . "</small></h4>"
                                . htmlspecialchars($blogComment->getContent(), ENT_QUOTES, 'utf-8') . " </div></div>";
                            }
                        }
                        else
                        {
                            echo "<div class='media'><div class='media-body' style='font-weight:bold;'>No comments yet!</div></div>";
                        }
                        ?>
                        <hr>
                    </div>
                </div>

                <!-- Blog Sidebar Widgets Column -->
                <div  class="col-md-4">

                    <!-- Blog Search Well -->
                    <div class="well">
                        <h4>Blog Search</h4>

                        <div class="search-box">
                            <input type="text" class="form-control" autocomplete="off" placeholder="Search our website..." >
                            <div class="result"></div>                           
                        </div>

                        <div>
                            <input type="text" class="form-control"name="daterange" placeholder="Choose date range (optional)" />
                        </div>
                        <!-- /.input-group -->
                    </div>

                    <!-- Most Popular Blogs Well -->
                    <div class="well">
                        <h4>Most Popular</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <?php
                                    foreach ($topBlogs as $topBlog)
                                    {
                                        echo "<li><a href='view_blog.php?id=" . htmlspecialchars($topBlog->getID(), ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($topBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>

                    <!-- Most Viewed Blogs Well -->
                    <div class="well">
                        <h4>Most Viewed</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <?php
                                    foreach ($mostViewedBlogs as $mostViewedBlog)
                                    {
                                        echo "<li><a href='view_blog.php?id=" . htmlspecialchars($mostViewedBlog->getID(), ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($mostViewedBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>

                </div>

            </div>
            <!-- /.row -->

            <hr>

            <!-- Footer -->
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; Your Website 2014</p>
                    </div>
                </div>
                <!-- /.row -->
            </footer>

        </div>
        <!-- /.container -->

        <!-- BEGIN # MODAL Profile (Current User) -->
        <div class="modal fade" id="profile-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <img class="img-rounded" id="img_logo" src="http://s3.amazonaws.com/churchplantmedia-cms/pinedale_christian_church/blog-header.jpg" alt="">
                        <button type="button" class="close" data-dismiss="modal" >
                            <span class="glyphicon glyphicon-remove-sign" aria-hidden="true" ></span>
                        </button>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="well well-sm" style="margin-left: 2%;">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-4">
                                            <img src="<?php echo htmlspecialchars($_SESSION['picURL'], ENT_QUOTES, 'utf-8'); ?>" alt="" class="img-rounded img-responsive" />   
                                        </div>
                                        <div class="col-sm-6 col-md-8" >
                                            <br>
                                            <p><span class="glyphicon glyphicon-user"></span> Username: <?php echo htmlspecialchars($_SESSION['userName'], ENT_QUOTES, 'utf-8'); ?></p>
                                            <p><i class="glyphicon glyphicon-envelope"></i> Email: <?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'utf-8'); ?></p>
                                            <p><span class="glyphicon glyphicon-time"></span> Joined on: <?php echo htmlspecialchars($_SESSION['joinDate'], ENT_QUOTES, 'utf-8'); ?></p>
                                            <p><span class="glyphicon glyphicon-pencil"></span> Posts: <?php echo htmlspecialchars($_SESSION['postCount'], ENT_QUOTES, 'utf-8'); ?></p>
                                            <p><span class="glyphicon glyphicon-comment"></span> Comments: <?php echo htmlspecialchars($_SESSION['commentCount'], ENT_QUOTES, 'utf-8'); ?></p>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"> View My Blogs</button>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END # MODAL PROFILE -->

        <!--BEGIN # MODAL Profile 2 (Chosen User)--> 
        <div class="modal fade" id="profile-modal2" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <img class="img-rounded" id="img_logo2" src="http://s3.amazonaws.com/churchplantmedia-cms/pinedale_christian_church/blog-header.jpg" alt="">
                        <button type="button" class="close" data-dismiss="modal" >
                            <span class="glyphicon glyphicon-remove-sign" aria-hidden="true" ></span>
                        </button>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="well well-sm" style="margin-left: 2%;">
                                    <div class="row">

                                        <div class="col-sm-6 col-md-4">
                                            <img id="modal2picURL" src="" alt="" class="img-rounded img-responsive" />   
                                        </div>
                                        <div id="modal2" class="col-sm-6 col-md-8">
                                            <br>
                                            <p id="modal2userName"></p>
                                            <p id="modal2email"></p>
                                            <p id="modal2joinDate"></p>
                                            <p id="modal2postCount" ></p>
                                            <p id="modal2commentCount" ></p>
                                        </div>                                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"> View My Blogs</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END # MODAL PROFILE 2 -->

        <!-- jQuery -->

        <script src="js/jquery-3.2.0.min.js"></script>
        <script src="js/function_library.js"></script>
        <script src="js/view_blog.js"></script>
        <script>
            window.onload = function ()
            {
                var $alert_box = $('#alert_box');
                var recaptcha = document.forms["commentBox"]["g-recaptcha-response"];
                recaptcha.required = true;
                recaptcha.oninvalid = function (e)
                {
                    $alert_box.removeClass();
                    $alert_box.empty();
                    $alert_box.addClass('alert alert-warning text-center ');
                    $alert_box.append('<strong>The captcha must be completed!</strong>');
                    $alert_box.fadeOut();
                    $alert_box.fadeIn();
                    $alert_box.delay(5000).fadeOut();
                };
            };
        </script>
        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>

    </body>

</html>
