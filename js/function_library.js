 function connectionError(alert_box)
            {
                alert_box.empty();
                alert_box.removeClass();
                alert_box.addClass("alert alert-danger text-center ");
                alert_box.append("<strong>Error!</strong> There is a problem connecting to the server...");
                alert_box.fadeOut();
                alert_box.fadeIn();
                alert_box.delay(5000).fadeOut();
            }


function toggle_comment_status(blogID)
    {
        var ajaxResponse = null;
        var credentials = {blogid: blogID};
        $.ajax({
            type: 'POST',
            url: 'toggle_comment_status.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (response)
                {
                    ajaxResponse = response;
                },
            error: function ()
                {
                    return null;
                },
            complete: function ()
                {

                }

        });
        return ajaxResponse;
    }

function get_blog_views(blogID)
    {
        var ajaxResponse = null;
        var credentials = {blogid: blogID};
        $.ajax({
            type: 'POST',
            url: 'get_blog_views.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (response)
                {
                    ajaxResponse = response;
                },
            error: function ()
                {
                    return null;
                },
            complete: function ()
                {

                }

        });
        return ajaxResponse;
    }

function change_user_comment_vote(commentID, vote)
    {
        var ajaxResponse = null;
        var credentials = {vote: vote, commentid: commentID};
        $.ajax({
            type: 'POST',
            url: 'comment_vote.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (response)
                {
                    ajaxResponse = response;
                },
            error: function ()
                {
                    return null;
                },
            complete: function ()
                {

                }

        });
        return ajaxResponse;
    }


function change_user_blog_vote(blogID, vote)
    {
        var ajaxResponse = null;
        var credentials = {blogid: blogID, vote: vote};
        $.ajax({
            type: 'POST',
            url: 'blog_vote.php',
            data: credentials,
            async: false,
            dataType: 'json',
            success: function (response)
                {
                    ajaxResponse = response;
                },
            error: function ()
                {
                    return null;
                },
            complete: function ()
                {

                }

        });
        return ajaxResponse;
    }


//Function used in home.php and view_blog.php to display other user accounts in a modal without having to refresh page.
function display_user_profile(username)
    {
        var credentials = {userName: username};
        $.ajax({
            type: 'POST',
            url: 'get_user_by_name.php',
            data: credentials,
            dataType: 'json',
            success: function (response)
                {
                    $('#modal2').children().each(function ()
                        {
                            $(this).empty();
                        });
                    $('#modal2userName').append("<span class='glyphicon glyphicon-user'></span> Username: " + response.userName);
                    $('#modal2email').append("<i class='glyphicon glyphicon-envelope'></i> Email: " + response.email);
                    $('#modal2joinDate').append("<span class='glyphicon glyphicon-time'></span> Joined on: " + response.joinDate);
                    $('#modal2postCount').append("<span class='glyphicon glyphicon-pencil'></span> Posts: " + response.postCount);
                    $('#modal2commentCount').append("<span class='glyphicon glyphicon-comment'></span> Comments: " + response.commentCount);
                    $('#modal2picURL').attr("src", response.picURL);
                    $(this).trigger('click');
                },
            error: function ()
                {

                },
            complete: function ()
                {

                }

        });
    }

