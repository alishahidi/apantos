<?php 
namespace System\Tests\System\Dot;

use System\Tests\TestCase;
use System\Dot\Dot;

class FlattenTest extends TestCase
{
    public function test_check_it_success_flatten_with_array_value()
    {
        $object = (object) $this->factoryObj(['name' => ['myName'], 'family' => ['myLastName']]);

        $this->assertEquals($object->flatten('**',null,''), [
            'name**0' => 'myName',
            'family**0' => 'myLastName',
        ]);
    }

    public function test_check_it_success_flatten_with_string_value()
    {
        $object = (object) $this->factoryObj();
        
        $this->assertEquals($object->flatten('...',['name' => 'myName','family' => 'myLastName'],''), [
            'name' => 'myName',
            'family' => 'myLastName',
        ]);
    }

    public function test_check_it_success_flatten_without_array_key()
    {
        $object = (object) $this->factoryObj();

        $this->assertEquals($object->flatten('',['name','ahmad','mamad']), [
            'name', 'ahmad', 'mamad'
        ]);
    }

    public function test_check_it_success_flatten_with_prepend()
    {
        $object = (object) $this->factoryObj();

        $this->assertEquals(
            $object->flatten('.',['name' => 'myName', 'family' => 'myLastName'], 'Prepend'), [
                'Prependname' => 'myName',
                'Prependfamily' => 'myLastName',
            ]
        );
    }

    public function test_check_it_success_flatten_with_prepend_and_array_value()
    {
        $object = (object) $this->factoryObj();

        $this->assertEquals(
            $object->flatten('.',[
                'name' => ['myName'],
                'family' => ['myLastName'],
            ], 'Prepend.'), [
                'Prepend.name.0' => 'myName',
                'Prepend.family.0' => 'myLastName',
            ]
        );
    }

    public function setUp(): void
    {
        $this->obj = new Dot;
    }
}

