<?php
namespace System\Tests\System\Dot;

use System\Dot\Dot;
use System\Tests\TestCase;

class IsEmptyTest extends TestCase
{

    public $method;

    public function test_it_check_is_empty_string_parameter()
    {
        $this->assertTrue($this->method->isEmpty('name'));
    }

    public function test_it_check_is_empty_number_parameter()
    {
        $this->method = $this->factoryObj('');

        $this->assertTrue($this->method->isEmpty(0));
    }

    public function test_it_check_is_empty_array_number_parameter()
    {
        $this->method = $this->factoryObj(['','']);

        $this->assertTrue($this->method->isEmpty([0,1]));
    }

    public function test_it_check_is_empty_array_parameter()
    {
        $this->assertTrue($this->method->isEmpty('',''));
    }

    public function test_it_check_is_not_empty_string_parameter()
    {
        $this->method = $this->factoryObj(['name' => 'myName', 'family' => 'myLastName']);

        $this->assertFalse($this->method->isEmpty(['name', 'family']));
    }

    public function test_it_check_is_not_empty_number_parameter()
    {
        $this->method = $this->factoryObj(['myName']);

        $this->assertFalse($this->method->isEmpty([0]));
    }

    public function test_it_check_is_not_empty_array_parameter()
    {
        $this->method = $this->factoryObj(['name' => 'myName', 'family' => 'myLastName']);

        $this->assertFalse($this->method->isEmpty(['name', 'family']));
    }

    public function test_it_check_is_not_empty_array_number_parameter()
    {
        $this->method = $this->factoryObj(['name', 'family']);

        $this->assertFalse($this->method->isEmpty([0, 1]));
    }

    public function setUp(): void
    {
        parent::setUp();
        
        $this->obj = new Dot;

        $this->method = $this->factoryObj(['name' => '', 'family' => '']);
    } 
}

