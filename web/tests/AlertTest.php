<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers AlertTest
 */
final class AlertTest extends TestCase
{

    public function testCanBeCreatedFromValidArray() {

        $array = [];
        $array["title"]        = "Alert Title";
        $array["message"]      = "Alert Message";
        $array["type"]         = "danger";
        $array["dismissable"]  = true;

        $alert = new Alert($array);

        $this->assertInstanceOf(
            Alert::class,
            $alert
        );

        $this->assertEquals(
            $array,
            $alert->toArray()
        );
    }
}