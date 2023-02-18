<?php 

namespace System\Dot;

use System\Tests\TestCase;

class AddTest extends TestCase
{
    public $method;

    public function test_x()
    {
        $this->method->add('age', 18);

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], [
            'age' => 18,
        ]);
    }

    public function test_x3()
    {
        $this->method->add(null, "string");

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], []);
    }

    public function test_x4()
    {
        $this->method->add(18, "string");

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], [
            18 => "string"
        ]);
    }

    public function test_x5()
    {
        $this->method->add(18, [
            'name' => 'myName',
            'friends' => [
                'friend1',
                1 => 'friend2',
                'friend3' => 'friend3',
                null => 'friend4',
            ]
        ]);

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], [
            18 => [
            'name' => 'myName',
            'friends' => [
                0 => 'friend1',
                1 => 'friend2',
                'friend3' => 'friend3',
                "" => 'friend4',
            ]]    
        ]);
    }

    public function test_x2()
    {
        $this->method->add(['age', 'fatherName', 'matherName']);

        $this->assertEquals(((array)$this->method)["\x00*\x00items"], [
            0 => 'age',
            1 => 'fatherName',
            2 => 'matherName',
        ]);
    }


    public function setUp(): void
    {
        parent::setUp();

        $this->obj = new Dot;
        $this->method = $this->factoryObj();
    }
}

