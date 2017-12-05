<?php

class ApiLegacy
{

    public static function api() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        // Switch on action
        switch ($_REQUEST["action"]) {

            // Login
            case "login":
                $username = $_REQUEST["uname"];
                $password = $_REQUEST["pwd"];
                ApiLegacyLogin::login($username, $password, $config, $mysqli);
                break;

            default:
                self::sendResponse(null, [], [], $config);
        }
    }

    public static function sendResponse($messageName, $errors, $data, $config) {
        header ("Content-Type:text/xml");
        echo "<?xml version=\"1.0\"?>\n";
        echo "<YACRSResponse version=\"".$config["version"]."\"";
        if($messageName) {
            echo " messageName='$messageName'";
        }
        echo ">\n";
        if(sizeof($errors) == 0)
            echo "<errors/>\n";
        else {
            echo "<errors>\n";
            foreach($errors as $error)
                echo "<error>$error</error>\n";
            echo "</errors>\n";
        }
        if($data === false)
            echo "<data/>\n";
        else {
            echo self::array2XML('data', $data);
        }
        echo "</YACRSResponse>";
    }

    private static function array2XML($name, $data) {
        $out = '';
        if(is_array($data)) {
            if(self::is_assoc($data)) {
                $out .= "<$name";
                if(isset($data['attributes'])) {
                    foreach($data['attributes'] as $k=>$v) {
                        if(is_bool($v))
                            $v2 = $v?'1':'0';
                        else
                            $v2 = htmlentities($v);
                        $out .= " $k=\"{$v2}\"";
                    }
                }
                if((isset($data['attributes']))&&(isset($data[0]))&&(sizeof($data)==2)) {
                    $out .= ">";
                    $out .= htmlentities($data[0]);
                }
                else {
                    $out .= ">\n";
                    foreach($data as $k=>$v) {
                        if($k !== 'attributes') {
                            $out .= self::array2XML($k, $v);
                        }
                    }
                }
                $out .= "</$name>\n";
            }
            else {
                foreach($data as $k=>$v) {
                    $out .= self::array2XML($name, $v);
                }
            }
        }
        else  {
            $out = "<$name>";
            if(is_bool($data))
                $out .= $data?'1':'0';
            else
                $out .= htmlentities($data);
            $out .= "</$name>\n";
        }
        return $out;
    }

    private static function is_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}