<?php

namespace System\View\Traits;

trait HasIncludeContent
{
    private $extendsContent;

    private function checkIncludesContent()
    {
        while (true) {
            $includeNamesArray = $this->findIncludeNames();
            if (empty($includeNamesArray)) {
                break;
            }
            foreach ($includeNamesArray as $includeName) {
                $this->initialInclude($includeName);
            }
        }
    }

    private function findIncludeNames()
    {
        $includeNamesArray = [];
        preg_match_all("/s*@include+\('([^)]+)'\)/", d($this->content), $includeNamesArray, PREG_UNMATCHED_AS_NULL);

        return isset($includeNamesArray[1]) ? $includeNamesArray[1] : false;
    }

    private function initialInclude($includeName)
    {
        return $this->content = str_replace("@include('$includeName')", $this->viewLoader($includeName), d($this->content));
    }
}
