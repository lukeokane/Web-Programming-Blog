<?php
require_once 'includes/session.php';
require_once 'CONSTANTS.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Web Prog CA2 Project">
        <meta name="author" content="Shaun Conroy & Luke O'Kane">
        <title>Luke & Shaun CA Blog</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/stylish-portfolio.css" rel="stylesheet">
        <link href="css/loaders.css" rel="stylesheet" type="text/css">

        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            #sign_in_div, #sign_up_div
            {
                top: 15%;
                left: 0%;
                position:absolute;
            }
            #loading
            {
                position: absolute;
                top: 50%;
                width: 200px;
                height: 50px;
                z-index: 1000;
            }

            #li .edit {
                display: none;
            }

            #li.edit .edit {
                display: inherit;
            }

            #li.edit .noedit {
                display: none;
            }

        </style>


    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
                        <li>     

                        </li>
                        <li>    

                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!-- Header -->
        <header id="top" class="header">
            <div class="text-vertical-center">

                <div id="sign_in_div" class="col-md-2 col-md-offset-5">
                    <h1>Welcome</h1>
                    <h3>Feel free to sign in...</h3>
                    <h3 id="sign_in_alerts"></h3>
                    <br>
                    <div style="margin-bottom: 2%;" class="input-group input-group-lg">
                        <span class="input-group-addon" ><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="text" id="email_sign_in" class="form-control" placeholder="email" aria-describedby="sizing-addon1">
                    </div>

                    <input style="margin-bottom: 2%;" type="password"  class="form-control input-lg" id="password_sign_in" placeholder="password">

                    <a id="sign_in_button" class="btn btn-dark btn-lg btn-block">Sign in</a>                 
                    <a id="sign_up_modal_button" class=" btn btn-dark btn-lg btn-block">New here?...</a>

                    <div class="loading ball-pulse-sync">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                </div>

                <div id="sign_up_div" class="col-md-2 col-md-offset-5">
                    <h1>Sign Up!</h1>
                    <h3>A new comer eh?...</h3>
                    <h3 id="sign_up_alerts"></h3>
                    <br>
                    <input style="margin-bottom: 2%;" type="text" class="form-control" id="username_sign_up" placeholder="username (5 - 20 characters)">
                    <input style="margin-bottom: 2%;" type="password"  class="form-control" id="password_sign_up" placeholder="password (8 - 30 characters)">
                    <input style="margin-bottom: 2%;" type="password"  class="form-control" id="password_check_sign_up" placeholder="password again...">

                    <div style="margin-bottom: 2%;" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input id="email_sign_up" type="text" class="form-control" placeholder="email">
                    </div>
                    <div id="li">

                        <a id="upload_image"  style="margin-bottom: 2%;" class="editDescription noedit btn btn-dark btn-block" >Add Profile Pic (.png only)<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <details><summary>Image Limits</summary>
                            <div> <?php echo "Maximum " . PICURL_MAX_HEIGHT . " x " . PICURL_MAX_WIDTH . " image size."; ?>
                                <br>
                                <?php echo "Allowed image types: " . implode(",", PICURL_ALLOWED_EXTENSIONS); ?></div>
                        </details>
                        <a id="change_upload_image"  style="margin-bottom: 2%;" class="saveEdit edit btn btn-dark btn-block" >Change Profile Pic<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <a id="cancel_upload_image"  style="margin-bottom: 2%;" class="cancelEdit edit btn btn btn-dark btn-block" >Remove Image<span class="glyphicon glyphicon-chevron-right"></span></a>

                    </div>
                    
                    <form role="form" id="captchaDiv" method="POST">
                        <div class="g-recaptcha" data-sitekey="6LdhMx8UAAAAACsOAXIy2BsrZ3k4FKp-yw7lNLIP" data-callback="recaptchaCallback" data-theme="dark"  data-size="normal"></div>                  
                    </form>

                    <a id="sign_up_button"  class="btn btn-dark btn-block disabled">Create me!</a>
                    <a id="sign_in_modal_button"   class="btn btn-dark btn-block">I have an account!</a>
                    <input style="visibility: hidden;" id='image_upload' type="file" name="image_upload">

                    <div class="loading ball-pulse-sync">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>

            </div>
        </header>
        

        <!-- JS Files -->
        <script src="js/jquery-3.2.0.min.js"></script>
        <script src="js/signinpage.js"></script>
        <script src="js/jquery.validate.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <script>
            $(document).bind("contextmenu", function (e) {
                e.preventDefault();
            });
            $(document).keydown(function (e) {
                if (e.which === 123) {
                    return false;
                }
            });

            function recaptchaCallback()
            {
                if (grecaptcha.getResponse().length !== 0)
                {
                    $('#sign_up_button').removeClass("disabled");
                }
            }
            ;
            $(document).ready(function ()
            {
                $("#sign_up_div").hide();
                $(".loading").hide();
            }
            );
        </script>

    </body>

</html>
