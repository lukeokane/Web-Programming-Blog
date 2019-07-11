<?php

function valid_email($email)
{
    if (preg_match(EMAIL_FULL_REGEX, $email))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function valid_password($password)
{
    if (preg_match(PASSWORD_REGEX, $password))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function valid_username($username)
{
    if (preg_match(USERNAME_REGEX, $username))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>