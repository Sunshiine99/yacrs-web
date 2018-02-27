<?php

class PageAdmin
{
    public static function admin() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // If the user is not an admin, 403
        if(!$user->isAdmin()) {
            PageError::error403();
        }

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Load all sessions
        $sessions = DatabaseSessionIdentifier::loadAllSessions($mysqli);

        // Load all users
        $users = DatabaseUser::loadAllUsers($mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Admin");

        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["sessions"] = $sessions;
        $data["users"] = $users;
        echo $templates->render("admin/admin", $data);
    }
}