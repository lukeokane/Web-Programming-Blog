

$(document).ready(function ()
    {
     
    var $like_blog = $('#like_blog');
var $dislike_blog = $('#dislike_blog');
var $blog_like_count = $('#blog_like_count');
var $blog_dislike_count = $('#blog_dislike_count');
var $like_comment = $('#like_comment');
var $dislike_comment = $('#dislike_comment');
var blogID = $('#navbar').data('blogid');
var blog_vote_types = ["LIKE", "DISLIKE"];
var comment_vote_types = ["LIKE", "DISLIKE"];
var active_color = '#00B2EE';
var inactive_color = '#666';
var blog_return_limit = 5;
var $alert_box = $('#alert_box');


  $('#delete_blog').on('click', function ()
            {
                var ajaxResponse = null;
        var credentials = {blogid: blogID};
        $.ajax({
            type: 'POST',
            url: 'delete_blog.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (response)
                {
                  if (response.result === "success")
                                {

                                    $alert_box.addClass("alert alert-success text-center ");
                                    $alert_box.append("<strong>Your blog has been deleted</strong>");

                                    setTimeout(function ()
                                        {
                                            window.location.href = "home.php"; //will redirect to your blog page (an ex: blog.html)
                                        }, 3000);


                                }
                },
            error: function ()
                {
                   
                },
            complete: function ()
                {

                }

        });
        return ajaxResponse;

            });

        $('#toggle_comments').on('click', function ()
            {
                var newStateObject = toggle_comment_status(blogID);

                if (newStateObject.newState === 0)
                    {                      
                                             $alert_box.empty();
                $alert_box.removeClass();
                $alert_box.addClass("alert alert-success text-center ");
                $alert_box.append("<strong>Comments are now disabled.</strong>");
                $alert_box.fadeOut();
                $alert_box.fadeIn();
                
                 setTimeout(function ()
                                        {
                                            window.location.href = "view_blog.php?id=" + blogID; //will redirect to your blog page (an ex: blog.html)
                                        }, 2000);

                    } else if (newStateObject.newState === 1)
                    {                                       
                                                 $alert_box.empty();
                $alert_box.removeClass();
                $alert_box.addClass("alert alert-success text-center ");
                $alert_box.append("<strong>Comments are now enabled.</strong>");
                $alert_box.fadeOut();
                $alert_box.fadeIn();
                
                                         setTimeout(function ()
                                        {
                                            window.location.href = "view_blog.php?id=" + blogID; //will redirect to your blog page (an ex: blog.html)
                                        }, 2000);
                    }
                    else
                    {
                        connectionError($alert_box);
                    }
            });

        $('.container').delegate('#view_profile_modal', 'click', function (e)
            {


                var username = $(this).text();
                display_user_profile(username);

            });


        $like_blog.on('click', function ()
            {
                var response = change_user_blog_vote(blogID, blog_vote_types[0]);
                if (response.result === "success")
                    {
                        blog_vote_icons(response.voteState);
                        change_blog_votes(response.like_count, response.dislike_count);
                    }
                    else if (response.result === "nsi")
                    {
                        window.location.href = "index.php";
                    }
                    else
                    {
                         connectionError($alert_box);
                    }


            });

        $dislike_blog.on('click', function ()
            {
                var response = change_user_blog_vote(blogID, blog_vote_types[1]);
                if (response.result === "success")
                    {
                        blog_vote_icons(response.voteState);
                        change_blog_votes(response.like_count, response.dislike_count);
                    }
                    else if (response.result === "nsi")
                    {
                        window.location.href = "index.php";
                    }
                     else
                    {
                        connectionError($alert_box);
                    }

            });


//To avoid replicating code for changing CSS of blog_vote
// On method call, both icons are deactivated to avoid senseless, un-needed, nitty-gritty logic
// IF number === 1, set like_blog icon to active
// IF number === 2, set dislike_blog icon to active
        function blog_vote_icons(number)
            {
                $('#like_blog, #dislike_blog').css('color', inactive_color);
                if (number === 1)
                    {
                        $like_blog.css('color', active_color);
                    } else if (number === 2)
                    {
                        $dislike_blog.css('color', active_color);
                    }
            }


        function comment_vote_icons(number, comment)
            {
                $(comment).find('#like_comment').css('color', inactive_color);
                $(comment).find('#dislike_comment').css('color', inactive_color);
                if (number === 1)
                    {
                        $(comment).find('#like_comment').css('color', active_color);
                    } else if (number === 2)
                    {
                        $(comment).find('#dislike_comment').css('color', active_color);
                    }
            }

        function change_blog_votes(LIKE_COUNT, DISLIKE_COUNT)
            {
                $blog_like_count.empty();
                $blog_like_count.append(LIKE_COUNT);
                $blog_dislike_count.empty();
                $blog_dislike_count.append(DISLIKE_COUNT);
            }

        function change_comment_votes(LIKE_COUNT, DISLIKE_COUNT, comment)
            {
                $(comment).find('#comment_like_count').empty();
                $(comment).find('#comment_like_count').append(LIKE_COUNT);
                $(comment).find('#comment_dislike_count').empty();
                $(comment).find('#comment_dislike_count').append(DISLIKE_COUNT);

            }



        $('.container').delegate('#like_comment', 'click', function (e)
            {
                var commentID = $(this).closest('.media-body').data('commentid');
                var response = change_user_comment_vote(commentID, comment_vote_types[0]);
                if (response.result === "success")
                    {
                        comment_vote_icons(response.voteState, $(this).closest('.media-body'));
                        change_comment_votes(response.like_count, response.dislike_count, $(this).closest('.media-body'));
                    }
                     else if (response.result === "nsi")
                    {
                        window.location.href = "index.php";
                    }
                     else
                    {
                        connectionError($alert_box);
                    }

            });

        $('#comments').delegate('#dislike_comment', 'click', function (e)
            {
                var commentID = $(this).closest('.media-body').data('commentid');
                var response = change_user_comment_vote(commentID, comment_vote_types[1]);
                if (response.result === "success")
                    {
                        comment_vote_icons(response.voteState, $(this).closest('.media-body'));
                        change_comment_votes(response.like_count, response.dislike_count, $(this).closest('.media-body'));
                    }
                     else if (response.result === "nsi")
                    {
                        window.location.href = "index.php";
                    }
                     else
                    {
                        connectionError($alert_box);
                    }
            });

        $('#comments').delegate('#view_profile_modal2', 'click', function (e)
            {

                var username = $(this).data('username');
                display_user_profile(username);

            });
    });

