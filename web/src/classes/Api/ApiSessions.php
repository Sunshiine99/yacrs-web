<?php

class ApiSessions
{
    /**
     * Sessions API page
     */
    public static function listSessions() {

        // Required parameters
        $key = Api::checkParameter("key");

        // Check the API Key and get the username of the user
        $username = Api::checkApiKey($key);

        // If invalid api key
        if(!$username) {
            ApiError::invalidApiKey();
        }

        $output["sessions"] = Sessions::getUserOwnedSessions($username);

        Api::output($output);
    }

    // TODO ENSURE USER IS ALLOWED TO VIEW SESSION DETAILS
    public static function details($id) {

        // Required parameters
        $key = Api::checkParameter("key");

        // Check the API Key and get the username of the user
        $username = Api::checkApiKey($key);

        // Convert ID to integer
        $id = (int)$id;

        $session = DatabaseSession::retrieveSession($id);



        if(!$session) {
            $output["error"]["code"]    = "invalidSessionId";
            $output["error"]["message"] = "Invalid Session ID";
            Api::output($output);
            die();
        }

        $output = $session->toArray();

        Api::output($output);
    }
}