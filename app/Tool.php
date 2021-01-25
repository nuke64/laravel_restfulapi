<?php


namespace App;


class Tool
{
    public static function trimData($var) : string{
        $var = trim($var);
        $var = stripslashes($var);
        $var = htmlspecialchars($var);
        return $var;
    }
}
