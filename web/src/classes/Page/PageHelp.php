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


        echo $templates->render("help", $data);

    }

}