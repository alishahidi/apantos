<?php

namespace System\Request\Traits;


trait HasRunValidation
{
    protected function errorRedirect()
    {
        if ($this->errorExist == false)
            return $this->request;
        return back();
    }

    private function checkFirstError($name)
    {
        if (!errorExists($name) && !in_array($name, $this->errorVariablesName))
            return true;
        return false;
    }

    protected function checkFieldExist($name)
    {
        return isset($this->request[$name]) && !empty($this->request[$name]);
    }

    protected function checkFileExist($name)
    {
        return isset($this->files[$name]["name"]) && !empty($this->request[$name]["name"]);
    }

    private function setError($name, $errorMessage, $ruleType = null)
    {
        array_push($this->errorVariablesName, $name);
        $errorMessage = isset($this->customErrors[$name][$ruleType]) ? $this->customErrors[$name][$ruleType] : $errorMessage;
        error($name, $errorMessage);
        $this->errorExist = true;
    }
}
