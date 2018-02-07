<?php

class Demo
{

    public static function home() {
        $templates = Flight::get("templates");
        $config = Flight::get("config");

        echo $templates->render("home",
            [
                "config" => $config,
                "title" => "Home"
            ]
        );
    }

    public static function login() {
        $templates = Flight::get("templates");
        $config = Flight::get("config");

        echo $templates->render("login/login",
            [
                "config" => $config,
                "title" => "Login"
            ]
        );
    }

    public static function sessionView() {
        $templates = Flight::get("templates");
        $config = Flight::get("config");

        echo $templates->render("session/view",
            [
                "config" => $config,
                "title" => "Login"
            ]
        );
    }

    public static function sessionNew() {
        $templates = Flight::get("templates");
        $config = Flight::get("config");

        echo $templates->render("session/new",
            [
                "config" => $config,
                "title" => "New Session"
            ]
        );
    }

    public static function sessionRun() {
        $templates = Flight::get("templates");
        $config = Flight::get("config");

        echo $templates->render("session/edit",
            [
                "config" => $config,
                "title" => "Run Session"
            ]
        );
    }
}