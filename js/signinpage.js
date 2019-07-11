$(document).ready(function ()
    {
        var $sign_in_alert_div = $("#sign_in_alerts");
        var $sign_up_alert_div = $("#sign_up_alerts");
        var $sign_in_div = $('#sign_in_div');
        var $sign_up_div = $('#sign_up_div');
        var $sign_in_button = $('#sign_in_button');
        var $sign_up_button = $('#sign_up_button');
        var $sign_in_modal_button = $('#sign_in_modal_button');
        var $sign_up_modal_button = $('#sign_up_modal_button');
        var $loading = $('.loading');
        var imageBase64;

        $('#upload_image').on('click', function ()
            {

                $('#image_upload').click();

            });


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
                                                            imageBase64 = e.target.result;
                                                            $("#li").addClass("edit");
                                                        } else
                                                        {
                                                            $sign_up_alert_div.empty();
                                                            $sign_up_alert_div.append(response.response);
                                                        }
                                                },
                                            error: function ()
                                                {
                                                    $sign_in_alert_div.empty();
                                                    $sign_in_alert_div.css("color", "red");
                                                    $sign_in_alert_div.append("There is a problem connecting with the server...");
                                                },
                                            complete: function ()
                                                {
                                                    $sign_up_alert_div.fadeOut();
                                                    $sign_up_alert_div.fadeIn();
                                                }
                                        });
                                    };
                                reader.readAsDataURL(image_from_file);
                            }

                    };
                image.onerror = function (response)
                    {
                        $sign_up_alert_div.empty();
                        $sign_up_alert_div.append("This is not a valid image file.");
                        $sign_up_alert_div.fadeOut();
                        $sign_up_alert_div.fadeIn();
                    };

            }

        $sign_up_modal_button.on("click", function ()
            {
                $sign_in_div.fadeOut(1000);
                $sign_up_div.fadeIn(1000);
            });

        $sign_in_modal_button.on("click", function ()
            {
                $sign_up_div.fadeOut(1000);
                $sign_in_div.fadeIn(1000);
            });

        $sign_in_button.on("click", function ()
            {
                var search_input = {email: $('#email_sign_in').val(), password: $('#password_sign_in').val()};
                $.ajax({
                    type: 'POST',
                    url: 'web_login.php',
                    data: search_input,
                    dataType: 'json',
                    success: function (response)
                        {
                            if (response.result === "input_error")
                                {
                                    $sign_in_alert_div.empty();
                                    $sign_in_alert_div.append(response.response);

                                } else if (response.result === "technical_error")
                                {

                                    $sign_in_alert_div.empty();
                                    $sign_in_alert_div.css("color", "red");
                                    $sign_in_alert_div.append("There is a problem connecting with the server...");
                                    ;

                                } else if (response.result === "success")
                                {
                                    $sign_in_alert_div.empty();
                                    $sign_in_alert_div.css("color", "green");
                                    $sign_in_alert_div.append("Correct! Signing in...");
                                    window.location.href = "home.php";
                                }
                        },
                    error: function ()
                        {
                            $sign_in_alert_div.empty();
                            $sign_in_alert_div.css("color", "red");
                            $sign_in_alert_div.append("There is a problem connecting with the server...");
                        },
                    complete: function ()
                        {
                            $sign_in_alert_div.fadeOut();
                            $sign_in_alert_div.fadeIn();
                            $loading.fadeOut(1000);
                            $sign_in_alert_div.css("color", "black");
                            $("#sign_up_modal_button, #sign_in_button").removeClass("disabled");
                        }

                });
                $loading.fadeIn(100);
                $sign_in_button.addClass("disabled");
                $sign_up_modal_button.addClass("disabled");

            });

        $sign_up_button.on("click", function ()
            {
                
                
                if (imageBase64 !== "")
                {
                    var split = imageBase64.split(",");
                    imageBase64 = split[1];
                    
                }
                
                var search_input = {
                    username: $('#username_sign_up').val(),
                    email: $('#email_sign_up').val(),
                    password: $('#password_sign_up').val(),
                    password_check: $('#password_check_sign_up').val(),
                    image: imageBase64
                };
                $.ajax({
                    type: 'POST',
                    url: 'web_register.php',
                    data: search_input,
                    dataType: 'json',
                    success: function (response)
                        {
                            if (response.result === "input_error")
                                {
                                    $sign_up_alert_div.empty();
                                    $sign_up_alert_div.append(response.response);
                                } else if (response.result === "technical_error")
                                {
                                    $sign_up_alert_div.empty();
                                    $sign_up_alert_div.css("color", "red");
                                    $sign_up_alert_div.append("There is a problem with the server...");
                                } else if (response.result === "success")
                                {
                                    $sign_up_alert_div.empty();
                                    $sign_up_alert_div.css("color", "green");
                                    $sign_up_alert_div.append("You're set! Feel free to sign in.");
                                }


                        },
                    error: function ()
                        {
                            $sign_up_alert_div.empty();
                            $sign_up_alert_div.css("color", "red");
                            $sign_up_alert_div.append("There is a problem with the server...");

                            $loading.fadeOut(1000);
                        },
                    complete: function ()
                        {
                            $sign_up_alert_div.fadeOut();
                            $sign_up_alert_div.fadeIn();
                            $sign_in_modal_button.removeClass("disabled");
                            $sign_up_button.removeClass("disabled");
                            $sign_up_alert_div.css("color", "black");
                            $loading.fadeOut(1000);
                        }

                });
                $loading.fadeIn(100);
                $sign_up_button.addClass("disabled");
                $sign_in_modal_button.addClass("disabled");
            });
    });
    