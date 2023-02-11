<?php
namespace System\Tests\System\Dot;

use System\Dot\Dot;
use System\Tests\TestCase;

class MergeTest extends TestCase
{
    public $method;

    public function test_is_successful()
    {
        $this->method->merge('age',18);

        $this->assertEquals(((array) $this->method)["\x00*\x00items"], [
            'name' => 'myName',
            'age' => [
                0 => 18,
            ]
        ]);
    }

    public function test_is_successful_if_value_is_a_array()
    {
        $this->method->merge('types',[18, 'string', null, false]);

        $this->assertEquals(((array) $this->method)["\x00*\x00items"], [
            'name' => 'myName',
            'types' => [
                0 => 18,
                1 => 'string',
                2 => null,
                3 => false,
            ]
        ]);
    }

    public function test_is_successful_if_value_is_a_associative_array()
    {
        $data = [
            'number' => 18,
            'string' => 'str',
            'null'  => null,
            'boolean' => false
        ];

        $this->method
        ->merge('typesKey' ,$data);

        $this->assertEquals(((array) $this->method)["\x00*\x00items"], [
            'name' => 'myName',
            'typesKey' => $data
        ]);
    }

    public function test_succeeds_if_value_is_an_array_with_all_types()
    {
        $data = [
            'num',
            'age' => 18, 
            'data' => [
                18,
                'string',
                null,
                false
            ],
            'dataKey' => [
                'number' => 18,
                'string' => 'str',
                'null'  => null,
                'boolean' => false
            ]
        ];

        $this->method->merge($data, 'value');

        $this->assertEquals(((array) $this->method)["\x00*\x00items"], 
        [   
            'num',
            'name' => 'myName',
            'age' => $data['age'],
            'data'=> $data['data'],
            'dataKey' => $data['dataKey'],
        ]);
    }

    public function test_succeeds_if_key_is_an_instance_of_the_class_object()
    {
        $this->method->merge($this->factoryObj(['family' => 'myLastName']));

        $this->assertEquals(((array) $this->method)["\x00*\x00items"], 
        [   
            'name' => 'myName',
            'family' => 'myLastName',
        ]);
    }

    public function setUp(): void
    {
        $this->obj = new Dot;

        $this->method = $this->factoryObj(['name' => 'myName']);
    }
}

