<?php
namespace System\Tests\System\Dot;

use System\Dot\Dot;
use System\Tests\TestCase;

class PullTest extends TestCase
{

    public function test_is_successful()
    {
        $object = $this->factoryObj(['name' => "myName", 'family' => "myLastName"]);

        $response = $object->pull('family');

        $this->assertEquals($response,"myLastName");
        $this->assertEquals(((array) $object)["\x00*\x00items"], [
            'name' => "myName"
        ]);
    }

    public function test_is_successful_if_key_is_null()
    {
        $object = $this->factoryObj(['name' => "myName", 'family' => "myLastName"]);

        $response = $object->pull(null);

        $this->assertEquals($response,[
            'name' => "myName",
            'family' => "myLastName"
        ]);
        $this->assertEquals(((array) $object)["\x00*\x00items"], []);
    }

    public function test_is_successful_with_key_and_defualt()
    {
        $object = $this->factoryObj(['name' => "myName", 'family' => "myLastName"]);

        $response = $object->pull('name', '');

        $this->assertEquals($response, "myName");
        $this->assertEquals(((array) $object)["\x00*\x00items"], [
            'family' => "myLastName",
        ]);
    }

    public function test_with_key_and_default_but_key_value_does_not_exist()
    {
        $object = $this->factoryObj(['name' => "myName", 'family' => "myLastName"]);

        $response = $object->pull('age', 'family');

        $this->assertEquals($response, "family");
        $this->assertEquals(((array) $object)["\x00*\x00items"], [
            'name' => "myName",
            'family' => "myLastName",
        ]);
    }

    public function setUp(): void
    {
        $this->obj = new Dot;
    }
}

