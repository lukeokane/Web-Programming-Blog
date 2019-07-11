<?php
require_once 'includes/session.php';
require_once 'includes/configuration.php';
require_once 'CONSTANTS.php';


if ($_SESSION['userType'] != 1)
{
    $_SESSION['signed_in'] = false;
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php $post['blog_title'] ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/blog-post.css" rel="stylesheet">
        <link href="css/hover.css" rel="stylesheet">
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


        <style>
            #li .edit {
                display: none;
            }

            #li.edit .edit {
                display: initial;
            }

            #li.edit .noedit {
                display: none;
            }

            .img-responsive {
                display: block;
                margin-left: auto;
                margin-right: auto;           
                max-height: 500px;
                max-width: 700px;}

            #blog_content{
                display:inline-block;
                border: solid 1px #ccc;
                min-height: 200px;
                width: 100%;
                border-radius: 6px;
            }

            #blog_tags {
                display:inline-block;
                border: solid 1px #ccc;
                min-height: 42px;
                width: 100%;
                border-radius: 6px;
            }
            #alert_box
            {

                top: 10%;
                position: fixed;
                width: 90%;
                margin-left: 5%;
                margin-right: 5%;
                z-index: 2000;

            }

        </style>
    </head>

    <body>

        <!-- Navigation -->
        <nav id="navbar" data-si="<?php echo $_SESSION['userType']; ?>" data-next_amount="0" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">

                        <?php
                        if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1)
                        {
                            echo "<li><a href='logout.php'>Logout</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
=

        <!-- Page Content -->
        <div class="container">
            <div class="row">
                <!-- Blog Post Content Column -->
                <div class="col-lg-8">

                    <!-- Blog Post -->

                    <!-- Title -->
                    <h2>A blog eh?...</h2>
                    <hr>
                    <div class="form-group">
                        <div class="notice notice-info">
                            <strong>Title</strong> - The name of your blog post.
                        </div>
                        <input id="blog_title" class="form-control input-lg" id="inputlg" placeholder="your blog title"type="text">
                    </div>

                    <hr>

                    <!-- Preview Image -->
                    <img id="preview_image" class="img-responsive" alt="">
                    <div id="li" class="notice notice-info">

                        <strong>Image</strong> - Upload an image to your blog.
                        <details><summary>Image Limits</summary>
                            <div> <?php echo "Maximum " . PICURL_MAX_HEIGHT . " x " . PICURL_MAX_WIDTH . " image size."; ?>
                                <br>
                                <?php echo "Allowed image types: " . implode(",", PICURL_ALLOWED_EXTENSIONS); ?></div>
                        </details>
                        <br>
                        <a id="upload_image"class="editDescription noedit btn btn-primary" >Add Image<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <a id="change_upload_image" class="saveEdit edit btn btn-primary" >Change<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <a id="cancel_upload_image" class="cancelEdit edit btn btn-primary" >Remove Image<span class="glyphicon glyphicon-chevron-right"></span></a>

                    </div>
                    <input style="visibility: hidden;" id='image_upload' type="file" name="image_upload">
                    <hr>
                    <div id="li" class="notice notice-info">
                        <strong>Blog</strong>
                    </div>
                    <a id="add_external_url_button" class=" btn btn-primary  " >Add external URL</a>
                    <!-- Post Content -->
                    <div id="blog_content"  contentEditable="true"></div>
                    <hr>
                    <div id="li" class="notice notice-info">
                        <strong>Tags</strong> - Seperate each tag by a comma (,)
                    </div>
                    <!-- Post Tags -->
                    <div id="blog_tags"  contentEditable="true"> </div>
                    <hr>
                    <a id="submit_blog" class=" btn btn-primary btn-lg btn-block" >Submit<span class="glyphicon glyphicon-chevron-right"></span></a>





                </div>

                <!-- Blog Sidebar Widgets Column -->
                <div class="col-md-4">


                    <br>
                    <!-- Side Widget Well -->


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

        <!-- jQuery -->
        <script src="js/jquery-3.2.0.min.js"></script>
        <script src="js/function_library.js"></script>
        <script src="js/post_blog.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
    </body>

</html>
