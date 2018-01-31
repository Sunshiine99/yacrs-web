<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionTest
 */
final class DatabaseSessionTest extends TestCase
{
    public function testInsert()
    {

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["ownerID"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);
        //$this->assertNotNull($sessionIdentifier);
    }

    public function testLoad(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["title"] = "name";
        $array["ownerID"] = $user->getId();
        $session = new Session($array);

        $sessionIdentifier = DatabaseSession::insert($session, $mysqli);

        /*$this->assertNotNull(DatabaseSession::loadSession
        (DatabaseSessionIdentifier::loadSessionID
        ($sessionIdentifier, $mysqli), $mysqli));*/
    }

}