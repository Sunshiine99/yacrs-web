<?php

require_once(dirname(__FILE__) . "/../TestHelper.php");
use PHPUnit\Framework\TestCase;

/**
 * @covers DatabaseResponseMcqTest
 */
final class DatabaseResponseMcqTest extends TestCase
{
    public function testInsert(){

        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        // Create a new user
        $user = TestHelper::userSessionCreator($config, $mysqli);

        $array = [];
        $array["question"] = "name";
        $array["type"] = "mcq";
        $question = new QuestionMcq($array);
        $question->addChoice("A", false, 1);
        $question->addChoice("B", false, 2);

        DatabaseQuestion::insert($question, $mysqli);

        $result = DatabaseResponseMcq::insert($question->getSessionQuestionID(),
            $user->getId(),
            1,
            $mysqli);

        $this->assertNotNull($result);
    }

    public function testInsertNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMcq::insert(null,
                null, null, null, $mysqli));

        $this->assertNull(
            DatabaseResponseMcq::insert(0,
                0, null, new QuestionMrq(), $mysqli));
    }

    public function testUpdateNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMcq::update(null,
                null, null, null, $mysqli));

        $this->assertNull(
            DatabaseResponseMcq::insert(0,
                0, null, new QuestionMrq(), $mysqli));
    }

    public function testLoadUserResponsesOnNull(){
        global $config;

        // Connect to the database
        $mysqli = TestHelper::databaseConnect($config);

        $this->assertNull(
            DatabaseResponseMcq::loadUserResponses(0, 0, $mysqli)
        );

        $this->assertNull(
            DatabaseResponseMcq::loadUserResponses(null, null, $mysqli)
        );
    }
}