<?php

trait QuickToString
{
    function __toString() 
    {    
        $valueArray = array();
        foreach($this as $name => $value)
        {
            array_push($valueArray, $value);     
        }       
        return get_class() . "[" . implode(", ", $valueArray) . "]";
    }
}
