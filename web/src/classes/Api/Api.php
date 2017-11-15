<?php

class Api
{

    public static function output($output = []) {
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    /**
     * Checks whether a parameter was passed
     * @param null $parameter
     * @return bool True if parameter exists
     */
    public static function checkParameter($parameter=null) {

        // Check if parameter has not been given
        if (!$_REQUEST[$parameter]) {
            $output = [];

            $output["error"]["code"] = "parameterNotGiven";
            $output["error"]["message"] = "A required parameter '$parameter' was not given";

            Api::output($output);
            die();
        }

        return $_REQUEST[$parameter];
    }
}