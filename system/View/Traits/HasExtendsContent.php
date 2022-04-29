<?php

namespace System\View\Traits;

trait HasExtendsContent
{
    private $extendsContent;

    private function checkExtendsContent()
    {
        $layoutFilePath = $this->findExtends();
        if (!$layoutFilePath)
            return false;
        $this->extendsContent = $this->viewLoader($layoutFilePath);
        $yieldNameArray = $this->findYieldNames();
        if ($yieldNameArray)
            foreach ($yieldNameArray as $yieldName)
                $this->initialYield($yieldName);
        $this->content = $this->extendsContent;
    }

    private function findYieldNames()
    {
        $yieldsNamesArray = [];
        preg_match_all("/@yield+\('([^)]+)'\)/", d($this->extendsContent), $yieldsNamesArray, PREG_UNMATCHED_AS_NULL);
        return isset($yieldsNamesArray[1]) ? $yieldsNamesArray[1] : false;
    }

    private function findExtends()
    {
        $filePathArray = [];
        preg_match("/s*@extends+\('([^)]+)'\)/", d($this->content), $filePathArray);
        return isset($filePathArray[1]) ? $filePathArray[1] : false;
    }

    private function initialYield($yieldName)
    {
        $string = d($this->content);
        $startWord = "@section('$yieldName')";
        $endWord = "@endsection";
        $startPos = strpos($string, $startWord);
        if (!$startPos)
            return $this->extendsContent = str_replace("@yield('$yieldName')", "", d($this->extendsContent));
        $startPos += strlen($startWord);
        $endPos = strpos($string, $endWord, $startPos);
        if (!$startPos)
            return $this->extendsContent = str_replace("@yield('$yieldName')", "", d($this->extendsContent));
        $length = $endPos - $startPos;
        $sectionContent = substr($string, $startPos, $length);
        return $this->extendsContent = str_replace("@yield('$yieldName')", $sectionContent, d($this->extendsContent));
    }
}
