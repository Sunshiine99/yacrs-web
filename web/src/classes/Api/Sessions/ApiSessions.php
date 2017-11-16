<?php

class ApiSessions
{

    public static function listSessions() {

        // Required parameters
        $key = Api::checkParameter("key");

        // If invalid api key, output error
        if(!Api::checkApiKey($key))
            ApiError::invalidApiKey();

        $output = [];
        $output["list"] = "list";
        Api::output($output);
    }
}