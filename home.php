<?php
require_once 'includes/session.php';
require_once 'includes/configuration.php';
require_once 'CONSTANTS.php';
require_once 'Blog.php';
require_once 'User.php';
require_once 'Comment.php';

// get all Blogs
$queryAllBlogs = "SELECT * FROM blogs ORDER BY date DESC LIMIT 2";
$stmt = $db->query($queryAllBlogs);
$blogs = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Blog");
//echo '<pre>'; print_r($blogs); echo '</pre>';
//
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
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Blog Home</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet" type="text/css"/>
        <link href="css/hover.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/blog-home.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .img-responsive-blog-imgs {
                display: block;
                margin-left: auto;
                margin-right: auto;           
                max-height: 350px;
                max-width: 700px;}

            #alert_box
            {
                z-index: 1000;
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
        <div id="alert_box" ></div>
        <!-- Navigation -->
        <nav id="navbar" data-si="<?php echo $_SESSION['userType']; ?>" data-next_amount="0" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
            <template id="blog_display_not_signed_in">
                <h2><a href='index.php'>{{title}}</a></h2>

                <p class="lead">
                    by <a href="#" id="view_profile_modal" role="button" data-toggle="modal" data-target="#profile-modal2">{{userName}}</a></p>
                <p><span class="glyphicon glyphicon-time">                           
                    </span> Posted on {{date}}</p>
                <hr>
                <img class="img-responsive-blog-imgs" src="{{imageURL}}" alt="">
                <hr>
                <p>{{content}}</p>
                <a class='btn btn-primary' href='index.php{{ID}}'>Read More <span class='glyphicon glyphicon-chevron-right'></span></a>
            </template>
            <template id="blog_display_signed_in">
                <h2><a href='view_blog.php?id={{ID}}'>{{title}}</a></h2>

                <p class="lead">
                    by <a href="#" id="view_profile_modal" role="button" data-toggle="modal" data-target="#profile-modal2">{{userName}}</a></p>
                <p><span class="glyphicon glyphicon-time">                           
                    </span> Posted on {{date}}</p>
                <hr>
                <img class="img-responsive-blog-imgs" src="{{imageURL}}" alt="">
                <hr>
                <p class="content">{{content}}</p>
                <a class='btn btn-primary' href='view_blog.php?id={{ID}}'>Read More <span class='glyphicon glyphicon-chevron-right'></span></a>
                <hr>
            </template>
            <div class="row">

                <!-- Blog Entries Column -->
                <div class="col-md-8">

                    <h1 class="page-header">
                        Recent Posts
                        <small>Dundalk Institute of Technology</small>
                    </h1>
                    <ul class="pager">
                        <li class="previous">
                            <a >&larr; Newer</a>
                        </li>
                        <li class="next">
                            <a >&rarr; Older</a>
                        </li>
                    </ul>

                    <!-- Blog Posts -->
                    <div id="blog_posts">
                        <?php foreach ($blogs as $blog): ?>
                            <?php
                            if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                            {
                                echo "<h2><a href='view_blog.php?id=" . htmlspecialchars($blog->getID(), ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($blog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></h2>";
                            }
                            else
                            {
                                echo "<h2><a href='index.php'>" . htmlspecialchars($blog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></h2>";
                            }
                            ?>

                            <p class="lead">
                                by <a href="#" id="view_profile_modal" role="button" data-toggle="modal" data-target="#profile-modal2"><?php echo htmlspecialchars($blog->getUserName(), ENT_QUOTES, 'utf-8'); ?></a></p>
                            <p><span class="glyphicon glyphicon-time">                           
                                </span> Posted on <?php echo htmlspecialchars($blog->getDate(), ENT_QUOTES, 'utf-8'); ?></p>
                            <hr>
                            <img class="img-responsive-blog-imgs" src="<?php echo htmlspecialchars($blog->getImageURL(), ENT_QUOTES, 'utf-8'); ?>" alt="">
                            <hr>
                            <p><?php
                                $content = htmlspecialchars(substr($blog->getContent(), 0, strlen($blog->getContent()) / 4) . "...", ENT_QUOTES, 'utf-8');


                                preg_match_all(BLOGCONTENT_CUSTOM_URL, $content, $matches, PREG_SET_ORDER, 0);

                                $replacement = '<a href="http://$4">$8</a>';

                                echo preg_replace(BLOGCONTENT_CUSTOM_URL, $replacement, $content);
                                ?></p>

                            <?php
                            if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                            {
                                echo "<a class='btn btn-primary' href='view_blog.php?id=" . htmlspecialchars($blog->getID(), ENT_QUOTES, 'utf-8') . "'>Read More <span class='glyphicon glyphicon-chevron-right'></span></a>";
                            }
                            else
                            {
                                echo "<a class='btn btn-primary' href='index.php'>Read More <span class='glyphicon glyphicon-chevron-right'></span></a>";
                            }
                            ?>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                    <!-- Pager -->
                    <ul class="pager">
                        <li class="previous">
                            <a >&larr; Newer</a>
                        </li>
                        <li class="next">
                            <a >&rarr; Older</a>
                        </li>
                    </ul>

                </div>

                <!-- Blog Sidebar Widgets Column -->
                <div class="col-md-4">

                    <!-- Blog Search Well -->
                    <div class="well">
                        <h4>Blog Search</h4>

                        <div class="search-box">
                            <input type="text" class="form-control" autocomplete="off" placeholder="Search our website..." >
                            <div class="result"></div>                           
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
                                    if ($stmt2->rowCount() > 0)
                                    {
                                        foreach ($topBlogs as $topBlog)
                                        {
                                            if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                                            {
                                                echo "<li><a href='view_blog.php?id=" . htmlspecialchars($topBlog->getID(), ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($topBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                            }
                                            else
                                            {
                                                echo "<li><a href='index.php'>" . htmlspecialchars($topBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                            }
                                        }
                                    }
                                    else
                                    {
                                        echo "<div class='media'><div class='media-body' style='font-weight:bold;'>No blogs yet!</div></div></div>";
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
                                    if ($stmt3->rowCount() > 0)
                                    {
                                        foreach ($mostViewedBlogs as $mostViewedBlog)
                                        {
                                            if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                                            {
                                                echo "<li><a href='view_blog.php?id=" . htmlspecialchars($mostViewedBlog->getID(), ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($mostViewedBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                            }
                                            else
                                            {
                                                echo "<li><a href='index.php'>" . htmlspecialchars($mostViewedBlog->getTitle(), ENT_QUOTES, 'utf-8') . "</a></li>";
                                            }
                                        }
                                    }
                                    else
                                    {
                                        echo "<div class='media'><div class='media-body' style='font-weight:bold;'>No blogs yet!</div></div></div>";
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
                        <p>Luke & Shauns Blog <?php echo date("Y"); ?> &copy;</p>
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
                        <img class="img-rounded" id="img_logo" src="images/profile-header.png" alt="">
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

        <!-- BEGIN # MODAL Profile 2 (Chosen User) -->
        <div class="modal fade" id="profile-modal2" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <img class="img-rounded" id="img_logo2" src="images/profile-header.png" alt="">
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
        <script src="js/mustache.js"></script>
        <script src="js/function_library.js"></script>
        <script src="js/home.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>

