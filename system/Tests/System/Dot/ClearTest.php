<?php 

namespace System\Dot;

use System\Tests\TestCase;

class ClearTest extends TestCase
{
    public $method;

    public function test_was_passed_successfully()
    {
        $this->method->clear('name');

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], [
            '' => 'string',
            'name' => [],
            0 => 'value',
            'friends' => [
                0 => 'friend1',
                1 => 'friend2'
            ]
        ]);
    }

    public function test_to_check_that_if_the_key_is_null_items_will_be_empty()
    {
        $this->method->clear(null);

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], []);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->obj = new Dot;
        $this->method = $this->factoryObj([
            null => 'string',            
            'name' => 'myName',
             0 => 'value',
            'friends' => [
                'friend1',
                'friend2'
            ]
    ]);
    }
}

