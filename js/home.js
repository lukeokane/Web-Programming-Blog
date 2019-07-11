/* global Mustache */

$(document).ready(function ()
    {
        var getnextXblogs = 2;
        var blog_counter = 0;
        var notsi = $('#blog_display_not_signed_in').html();
        var si = $('#blog_display_signed_in').html();
        var $blogpostsdiv = $('#blog_posts');
        var blog_max_limit_reached = false;
        var blog_min_limit_reached = false;
        var $alert_box = $('#alert_box'); 
            
            function blogLimitReached()
            {
                $alert_box.empty();
                $alert_box.removeClass();
                $alert_box.addClass("alert alert-warning text-center ");
                $alert_box.append("<strong>Warning!</strong> No more results.");
                $alert_box.fadeOut();
                $alert_box.fadeIn();
                $alert_box.delay(2000).fadeOut();
            }


        function addThumbnails(data, template)
            {
                $blogpostsdiv.append(Mustache.render(template, data));
            }

        $('.next').on('click', function ()
            {
                if (blog_max_limit_reached === true)
                    {
                       blogLimitReached();
                    } else
                    {
                        if (blog_min_limit_reached === true)
                            {
                                blog_min_limit_reached = false;
                                blog_counter += 2;

                            }
                        blog_counter += 2;
                        var data = {
                            next_amount: blog_counter
                        };

                        $.ajax({
                            type: 'POST',
                            url: 'get_blogs.php',
                            dataType: 'json',
                            data: data,
                            async: false,
                            success: function (languages)
                                {
                                    if (jQuery.isEmptyObject(languages) === true)
                                        {
                                           blogLimitReached();
                                            blog_max_limit_reached = true;
                                            blog_counter -= getnextXblogs;
                                        } else
                                        {

                                            var template;
                                            if ($('#navbar').data('si') === 1)
                                                {
                                                    template = si;
                                                } else
                                                {
                                                    template = notsi;
                                                }
                                            $blogpostsdiv.empty();                                         
                                            $.each(languages, function (i, language)
                                                {

                                                    addThumbnails(language, template);

                                                });

                                            $blogpostsdiv.children('.content').each(function ()
                                                {
                                                    var urlRegex = /\[[U|u][R|r][L|l]=((http[s]?\:\/\/)?(www[\.-])?[a-zA-Z0-9]+\.[a-zA-Z]{2,3}(\.[a-zA-Z]{2,3})?(\/[.a-zA-Z]*)*)\]\[([a-zA-Z0-9 ]+)]/g;
                                                    var content = $(this).text().replace(new RegExp(urlRegex, "g"), "<a href='$1' >$6</a>");
                                                    $(this).empty();
                                                    $(this).append(content);
                                                });
                                            blog_max_limit_reached = false;

                                        }
                                },
                            error: function ()
                                {
                                    connectionError($alert_box);
                                },
                            complete: function ()
                                {

                                }
                        });
                    }
            });

        $('.previous').on('click', function ()
            {
                if (blog_min_limit_reached === true)
                    {
                        blogLimitReached();
                    } else
                    {
                        blog_counter = blog_counter - 2;
                        var data = {
                            next_amount: blog_counter
                        };

                        $.ajax({
                            type: 'POST',
                            url: 'get_blogs.php',
                            dataType: 'json',
                            data: data,
                            async: false,
                            success: function (languages)
                                {
                                    if (jQuery.isEmptyObject(languages) === true)
                                        {
                                            blogLimitReached();
                                            blog_min_limit_reached = true;
                                            blog_counter + 4;
                                        } else
                                        {
                                            $blogpostsdiv.empty();
                                            $.each(languages, function (i, language)
                                                {
                                                    addThumbnails(language, si);

                                                });

                                            $blogpostsdiv.children('.content').each(function ()
                                                {
                                                    var urlRegex = /\[[U|u][R|r][L|l]=((http[s]?\:\/\/)?(www[\.-])?[a-zA-Z0-9]+\.[a-zA-Z]{2,3}(\.[a-zA-Z]{2,3})?(\/[.a-zA-Z]*)*)\]\[([a-zA-Z0-9 ]+)]/g;
                                                    var content = $(this).text().replace(new RegExp(urlRegex, "g"), "<a href='$1' >$6</a>");
                                                    $(this).empty();
                                                    $(this).append(content);
                                                });

                                            blog_max_limit_reached = false;
                                            blog_min_limit_reached = false;
                                        }
                                },
                            error: function ()
                                {
                            connectionError($alert_box);
                                },
                            complete: function ()
                                {

                                }
                        });
                    }
            });

        $('.container').delegate('#view_profile_modal', 'click', function (e)
            {
                var username = $(this).text();
                display_user_profile(username);
            });
    });
