$(function ()
    {

        var $alert_box = $('#alert_box');

        $('#add_external_url_button').on('click', function ()
            {
                $('#blog_content').append("[url=your link here][your text here]");
            });

        var imageBase64 = "";
        var blog_title;
        var blog_content;
        var blog_tags;
        $('#submit_blog').on('click', function ()
            {
                blog_title = $('#blog_title').val();
                blog_content = $('#blog_content').text();
                blog_tags = $('#blog_tags').text();
                
                if (imageBase64 !== "")
                {
                    var split = imageBase64.split(",");
                    imageBase64 = split[1];
                    
                }
                var data = {
                    blog_title: blog_title,
                    blog_image: imageBase64,
                    blog_content: blog_content,
                    blog_tags: blog_tags
                };
                $.ajax({
                    url: 'upload_post.php',
                    type: "POST",
                    data: data,
                    dataType: "json",
                    success: function (data)
                        {
                            $alert_box.empty();
                            $alert_box.removeClass();
                            if (data.result === "input_error")
                                {

                                    $alert_box.addClass("alert alert-warning text-center ");
                                    $alert_box.append(data.response);


                                } else if (data.result === "success")
                                {

                                    $alert_box.addClass("alert alert-success text-center ");
                                    $alert_box.append("<strong>Success!</strong> Your blog has been posted");

                                    setTimeout(function ()
                                        {
                                           // window.location.href = "view_blog.php?id=" + data.response; //will redirect to your blog page (an ex: blog.html)
                                        }, 3000);


                                }
                        },
                    error: function (data)
                        {
                            connectionError($alert_box);
                        },
                    complete: function (data)
                        {

                            $alert_box.fadeOut();
                            $alert_box.fadeIn();
                            $alert_box.delay(5000).fadeOut();
                        }
                });





            });


        $('#upload_image').on('click', function ()
            {

                $('#image_upload').click();

            });

        function show_image(input)
            {
                var image_object = window.URL || window.webkitURL;
                var image_from_file = input.files[0];
                var image = new Image();

                image.src = image_object.createObjectURL(image_from_file);

                image.onload = function ()
                    {

                        if (input.files && image_from_file)
                            {
                                var reader = new FileReader();

                                reader.onload = function (e)
                                    {
                                        var file_data = $('#image_upload').prop('files')[0];
                                        var form_data = new FormData();
                                        form_data.append('file', file_data);
                                        $.ajax({
                                            url: 'image_check.php', // point to server-side PHP script 
                                            dataType: 'json', // what to expect back from the PHP script, if anything
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            data: form_data,
                                            type: 'post',
                                            success: function (response)
                                                {

                                                    if (response.result === "success")
                                                        {
                                                            $('#preview_image').attr('src', e.target.result);
                                                            imageBase64 = e.target.result;
                                                            $("#li").addClass("edit");
                                                        } else
                                                        {
                                                            $alert_box.empty();
                                                            $alert_box.addClass("alert alert-warning text-center ");
                                                            $alert_box.append(response.response);
                                                            $alert_box.fadeOut();
                                                            $alert_box.fadeIn();
                                                            $alert_box.delay(5000).fadeOut();

                                                        }
                                                }
                                        });

                                    }
                                reader.readAsDataURL(image_from_file);

                            }

                    };
                image.onerror = function ()
                    {
                        $alert_box.empty();
                        $alert_box.addClass("alert alert-warning text-center ");
                        $alert_box.append("That is not a valid image!");
                        $alert_box.fadeOut();
                        $alert_box.fadeIn();
                        $alert_box.delay(5000).fadeOut();
                    };



            }

        $('#image_upload').on('change', function (e)
            {
                show_image(this);


            });

        $('#cancel_upload_image').on('click', function ()
            {
                $('#preview_image').attr('src', '');
                imageBase64 = null;
                $(this).closest('#li').removeClass('edit');
            });



        $('#change_upload_image').on('click', function ()
            {
                $('#image_upload').click();
            });

    });