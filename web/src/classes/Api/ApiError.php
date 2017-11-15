<?php

class ApiError
{

    public static function handler() {
        Api::output();
    }

    public static function notFound() {
        $output = [];
        $output["error"]["code"]    = "404";
        $output["error"]["message"] = "Command not found";
        Api::output($output);
    }
}