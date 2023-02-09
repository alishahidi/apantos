<?php

namespace System\Tests\System\Dot;

use System\Dot\Dot;
use System\Tests\TestCase;

class HasTest extends TestCase
{

    public $method;

    public function test_it_has_true_with_string_parameter()
    {
        $this->assertTrue($this->method->Has('name'));
    }

    public function test_it_has_true_with_null_parameter()
    {
        $this->assertEmpty($this->method->Has(null));
    }

    public function test_it_has_true_with_number_parameter()
    {
        $this->method = $this->factoryObj('myName');

        $this->assertTrue($this->method->Has(0));
    }

    public function test_it_has_true_with_array_number_parameter()
    {
        $this->method = $this->factoryObj(['name','family']);

        $this->assertTrue($this->method->Has([0,1]));
    }

    public function test_it_has_true_with_array_parameter()
    {
        $this->assertTrue($this->method->Has(['name','family']));
    }

    public function test_it_has_false_with_string_parameter()
    {
        $this->method = $this->factoryObj('myName');

        $this->assertFalse($this->method->Has(1));
    }

    public function test_it_has_false_with_number_parameter()
    {
        $this->assertFalse($this->method->Has('age'));
    }

    public function test_it_has_false_with_array_parameter()
    {
        $this->assertFalse($this->method->Has(['age', 1]));
    }

    public function test_it_has_false_with_array_key_value_parameter()
    {
        $this->assertFalse($this->method->Has(['name' => 'myName']));
    }

    public function setUp(): void
    {
        parent::setUp();
        
        $this->obj = new Dot;

        $this->method = $this->factoryObj(['name' => 'myName', 'family' => 'myLastName']);
    }
}