<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 14/02/2018
 * Time: 11:39
 */

class PageHelp
{

    public static function help(){
        $templates = Flight::get("templates");
        $data = Flight::get("data");

        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);



        $data["user"] = $user;
        echo $templates->render("help", $data);

    }

}