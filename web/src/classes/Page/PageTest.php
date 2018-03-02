<?php

class PageTest
{

    public static function screenshot() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $user = Login::checkUserLoggedIn();
        $data["user"] = $user;
        echo $templates->render("test/screenshot", $data);
    }
}