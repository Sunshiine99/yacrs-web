<?php

class PageSessionEditProperties
{
    public static function properties($sessionIdentifier) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load session details
        $session = DatabaseSessionIdentifier::loadSession($sessionIdentifier, $mysqli);

        // If the session is invalid or the user cannot edit this page, forward home
        if($session === null || !$session->checkIfUserCanEdit($user)) {
            header("Location: "  . $config["baseUrl"]);
            die();
        }
        $arr = [];
        $users = $session->getAdditionalUsers();
        foreach ($users as $u){
            array_push($arr, DatabaseUser::loadDetailsFromUsername($u, $mysqli));
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem(($session->getTitle() ? $session->getTitle() : "Session") . " (#$sessionIdentifier)"  . " Edit", $config["baseUrl"]."session/$sessionIdentifier/edit");
        $breadcrumbs->addItem("Properties");

        //$data = array_merge($data, $session->toArray());

        $data["session"] = $session;
        $data["additionalUsersCsv"] = $session->getAdditionalUsersCsv();
        $data["user"] = $user;
        $data["additionalUsers"] = $arr;
        $data["breadcrumbs"] = $breadcrumbs;

        echo $templates->render("session/edit/properties", $data);
    }

    public static function submit() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Ensure user is allowed to create sessions
        Page::ensureUserIsSessionCreator($user, $config);

        // Setup session from submitted data
        $session = new Session($_POST);
        $session->setOwner($user->getId());

        // Load new users
        foreach ($_POST as $key => $value) {

            preg_match("/(user-)(\w*[0-9]\w*)/", $key, $matches);

            if($matches) {

                // Get the user index from the regex matches
                $index = $matches[2];

                // If there is an index associated with this user, store it
                if(isset($_POST["user-" . $index])) {
                    $username = $_POST["user-" . $index];
                    //If user does not exist output error
                    if(!DatabaseUser::checkUserExists($username, $mysqli) and $username != ""){
                        PageError::generic("Additional user does not exist", "One of the additional users you have typed does not exist");
                        die();
                    }
                    // Else add the new user
                    $session->addAdditionalUser($username);
                }

            }
        }

        DatabaseSession::update($session, $mysqli);

        header("Location: "  . $config["baseUrl"] . "session/" . $session->getSessionIdentifier() . "/edit/");
        die();
    }
}