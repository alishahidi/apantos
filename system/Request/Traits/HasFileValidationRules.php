<?php

namespace System\Request\Traits;

trait HasFileValidationRules
{
    private function toByte($size)
    {
        return $size * 1024;
    }

    protected function fileValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule) {
            if ($rule == 'required') {
                $this->fileRequired($name);
            } elseif (strpos($rule, 'mimes:') === 0) {
                $rule = str_replace('mimes:', '', $rule);
                $typesArray = explode(',', $rule);
                $this->fileType($name, $typesArray);
            } elseif (strpos($rule, 'max:') === 0) {
                $rule = str_replace('max:', '', $rule);
                $this->maxFile($name, $rule);
            } elseif (strpos($rule, 'min:') === 0) {
                $rule = str_replace('min:', '', $rule);
                $this->minFile($name, $rule);
            }
        }
    }

    protected function fileRequired($name)
    {
        if ((! isset($this->files[$name]['name']) || empty($this->files[$name]['name'])) && $this->checkFirstError($name)) {
            $this->setError($name, "$name is required", 'required');
        }
    }

    protected function fileType($name, $typesArray)
    {
        if ($this->checkFirstError($name) && $this->checkFileExist($name)) {
            $currentFileType = pathinfo($this->files[$name]['name'], PATHINFO_EXTENSION);
            if (! in_array($currentFileType, $typesArray)) {
                $this->setError($name, "$name type must be {implode(', ', $typesArray)}", 'mimes');
            }
        }
    }

    protected function maxFile($name, $size)
    {
        $byteSize = $this->toByte($size);
        if ($this->checkFirstError($name) && $this->checkFileExist($name)) {
            if ($this->files[$name]['size'] > $byteSize) {
                $this->setError($name, "$name size must be equal or lower than $size", 'max');
            }
        }
    }

    protected function minFile($name, $size)
    {
        $byteSize = $this->toByte($size);
        if ($this->checkFirstError($name) && $this->checkFileExist($name)) {
            if ($this->files[$name]['size'] < $byteSize) {
                $this->setError($name, "$name size must be equal or upper than $size", 'min');
            }
        }
    }
}
