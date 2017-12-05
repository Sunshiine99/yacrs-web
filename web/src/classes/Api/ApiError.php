<?php

class ApiError
{

    public static function handler() {
        Api::output();
    }

    public static function notFound() {
        $output = [];
        $output["error"]["code"]    = "notFound";
        $output["error"]["message"] = "Command not found";
        Api::output($output);
        die();
    }

    public static function invalidApiKey() {
        $output = [];
        $output["error"]["code"]    = "invalidApiKey";
        $output["error"]["message"] = "Invalid API Key";
        Api::output($output);
        die();
    }

    public static function permissionDenied() {
        $output = [];
        $output["error"]["code"]    = "permissionDenied";
        $output["error"]["message"] = "You do not have permission to view this page";
        Api::output($output);
        die();
    }
}