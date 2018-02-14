<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseSessionIdentifierTest
 */
final class DatabaseSessionIdentifierTest extends TestCase{

    public function testInvalidSessionID(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $this->assertNull(DatabaseSessionIdentifier::loadSession(156734345, $mysqli));
        $this->assertNull(DatabaseSessionIdentifier::loadSession("23", $mysqli));
    }
}