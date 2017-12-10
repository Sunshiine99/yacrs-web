<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers QuestionTest
 */
final class QuestionTest extends TestCase
{

    public function testQuestionToArray(){
        $questionArr = [];
        $questionArr["sessionQuestionID"] = 3;
        $questionArr["type"] = "mcq";
        $questionArr["question"] = "is this a test";
        $questionArr["created"] = 2017;
        $questionArr["lastUpdate"] = 2017;
        $questionArr["active"] = true;
        $question = new Question($questionArr);
        $arr = $question->toArray();

        $this->assertEquals(
            $arr,
            $questionArr
        );
    }
}