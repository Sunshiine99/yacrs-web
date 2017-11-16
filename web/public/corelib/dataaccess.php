<?php

class dataConnection
{
    public static function connect() {
        DatabaseAccess::connect();
    }

    public static function runQuery($query) {
        return DatabaseAccess::runQuery($query);
    }

    public static function close() {
        DatabaseAccess::close();
    }

    public static function safe($in)
    {
        return DatabaseAccess::safe($in);
	}

	public static function db2date($in) {
        return DatabaseAccess::db2date($in);
	}

	public static function date2db($in) {
        return DatabaseAccess::date2db($in);
	}

	public static function db2time($in) {
        return DatabaseAccess::db2time($in);
	}

	public static function time2db($in) {
        return DatabaseAccess::time2db($in);
	}

};
