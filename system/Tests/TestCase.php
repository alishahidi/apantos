<?php
namespace System\Tests;

use PHPUnit\Framework\TestCase as TestCaseUnit;

class TestCase extends TestCaseUnit
{
    public object $obj;

    /**
     * Create factory object 
     * @param mixed $objName object name
     * @param mixed $params paramaters for object
     * @return void
     */
    public function factoryObj($params=null): object
    {
        return  ($params) ? new $this->obj($params) : new $this->obj;
    }
}