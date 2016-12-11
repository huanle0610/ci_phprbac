<?php
function fineTableName($str)
{
    if(strpos($str, '_') !== false)
    {
        $arr = explode('_', $str);
        $arr = array_map('ucfirst', $arr);

        return implode(' ', $arr);
    }

    return $str;
}