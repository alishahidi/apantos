<?php
namespace System\Tests\System\Dot;

use System\Dot\Dot;
use System\Tests\TestCase;

class GetTest extends TestCase
{

    public function test_it_check_success_get()
    {
        $object = (object) $this->factoryObj(['name' => 'myName', 'family' => 'myLastName']);

        $this->assertEquals($object->get(), [
            'name' => 'myName',
            'family' => 'myLastName',
        ]);
    }

    public function test_it_check_success_get_with_key_and_defualt()
    {
        $object = (object) $this->factoryObj(['name' => 'myName']);

        $this->assertEquals($object->get('name', 'Default'), 'myName');
    }

    public function test_it_check_success_get_defualt()
    {
        $object = (object) $this->factoryObj(['name' => 'myName']);

        $this->assertEquals($object->get('family', 'Default'), 'Default');
    }

    public function test_it_check_success_get_with_boolean_items()
    {
        $object = (object) $this->factoryObj(false);

        $this->assertEquals($object->get(false), false);
    }
    
    public function test_it_check_success_without_array_key_items()
    {
        $object = (object) $this->factoryObj(['name', 'family', 'age']);

        $this->assertEquals($object->get('age'), null);
    }

    public function setUp(): void
    {
        $this->obj = new Dot;
    }
}

