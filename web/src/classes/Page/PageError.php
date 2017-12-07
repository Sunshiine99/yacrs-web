<?php

class PageError
{

    public static function error404() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();
        echo $templates->render("error/error404", $data);
    }

    public static function error500() {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $data["user"] = Login::checkUserLoggedIn();
        echo $templates->render("error/error500", $data);
    }
}