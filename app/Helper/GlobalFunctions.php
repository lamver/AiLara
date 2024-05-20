<?php

if(!function_exists('__strTrans')) {

    /**
     * @param string $prefix
     * @param string $msg
     * @param string $replace
     * @return string
     */
    function __strTrans(string $msg, string $prefix = "", string $replace = "_"): string
    {
        return  $prefix.".".str_replace($replace, ' ', ucfirst($msg)) ;
    }

}
