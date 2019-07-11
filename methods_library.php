<?php
 require_once 'includes/configuration.php';

function is_less_than($string, $string_min_length)
{
    $string_size = strlen($string);
    if ($string_size < $string_min_length)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function is_greater_than($string, $string_max_length)
{
    $string_size = strlen($string);
    if ($string_size > $string_max_length)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function string_equals_case_sensitive($str1, $str2)
{
    if (strcmp($str1, $str2) === 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>