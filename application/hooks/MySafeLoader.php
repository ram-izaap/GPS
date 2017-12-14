<?php


class MySafeLoader {

	public function index()
	{
		//echo "I am HOOK";
	}
}


//To include files safely
function safe_include($path, $load=true)
{
    if (is_file(APPPATH.$path)) {
        $fpath = APPPATH.$path;
    } else if (is_file(COREPATH.$path)) {
        $fpath = COREPATH.$path;
    } else if(is_file(BASEPATH.$path)) {
        $fpath = BASEPATH.$path;
    }
    if (!isset($fpath)) {
        return false;
    }
    if ($load) {
        include_once $fpath;
        return true;
    }
    return $fpath;
}