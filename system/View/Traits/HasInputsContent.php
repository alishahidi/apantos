<?php

namespace System\View\Traits;

use System\Config\Config;
use System\Security\Security;

trait HasInputsContent
{
    private function checkInputsContent()
    {
        $methodArray = $this->findMethods();
        Security::startRandomIpToken();
        if ($methodArray)
            foreach ($methodArray as $method)
                $this->initialMethod($method);
        $tokenArray = $this->findTokens();
        if ($tokenArray)
            foreach ($tokenArray as $token)
                $this->initialToken();
    }

    private function findTokens()
    {
        $inputArray = [];
        preg_match_all("/s*@token/", d($this->content), $inputArray, PREG_UNMATCHED_AS_NULL);
        return isset($inputArray[0]) ? $inputArray[0] : false;
    }

    private function findMethods()
    {
        $methodArray = [];
        preg_match_all("/@method+\('([^)]+)'\)/", d($this->content), $methodArray, PREG_UNMATCHED_AS_NULL);
        return isset($methodArray[1]) ? $methodArray[1] : false;
    }

    private function initialToken()
    {
        $token = get_start_random_ip_token();
        return $this->content = str_replace("@token", "<input type='hidden' name='_token' value='$token'>", d($this->content));
    }

    private function initialMethod($methodName)
    {
        $methodValue = strtolower($methodName);
        return $this->content = str_replace("@method('$methodName')", "<input type='hidden' name='_method' value='$methodValue'>", d($this->content));
    }
}
