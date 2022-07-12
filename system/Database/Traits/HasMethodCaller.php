<?php

namespace System\Database\Traits;

trait HasMethodCaller
{
    private $allMethods = ["create", "update", "delete", "find", "all", "save", "where", "whereOr", "whereIn", "whereNull", "whereNotNull", "whereBetween", "limit", "orderBy", "randomOrder", "get", "paginate", "count"];

    private $allowedMethods = ["create", "update", "delete", "find", "all", "save", "where", "whereOr", "whereIn", "whereNull", "whereNotNull", "whereBetween", "limit", "orderBy", "randomOrder", "get", "paginate", "count"];

    public function __call($method, $argvs)
    {
        return $this->methodCaller($this, $method, $argvs);
    }

    public static function __callStatic($method, $argvs)
    {
        $className = get_called_class();
        $instance = new $className;
        return $instance->methodCaller($instance, $method, $argvs);
    }

    private function methodCaller($object, $method, $argvs)
    {
        $suffix = "Method";
        $methodName = "$method{$suffix}";
        if (in_array($method, $this->allowedMethods)) {
            return call_user_func_array([$object, $methodName], $argvs);
        }
    }

    protected function setAllowedMethods($array)
    {
        $this->allowedMethods = $array;
    }
}
