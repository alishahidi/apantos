<?php

namespace System\Request\Traits;

use DateTime;
use System\Database\DBConnection\DBConnection;
use System\Security\Security;

trait HasValidationRules
{
    public function normalValidaiton($name, $ruleArray)
    {
        foreach ($ruleArray as $rule) {
            if ($rule == "required")
                $this->required($name);
            elseif (strpos($rule, "max:") === 0) {
                $rule = str_replace("max:", "", $rule);
                $this->maxStr($name, $rule);
            } elseif (strpos($rule, "min:") === 0) {
                $rule = str_replace("min:", "", $rule);
                $this->minStr($name, $rule);
            } elseif (strpos($rule, "exists:") === 0) {
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(",", $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $key);
            } elseif (strpos($rule, "unique:") === 0) {
                $rule = str_replace("unique:", "", $rule);
                $rule = explode(",", $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->unique($name, $rule[0], $key);
            } elseif ($rule == "email")
                $this->email($name);
            elseif ($rule == "confirmed")
                $this->confirm($name);
            elseif ($rule == "captcha")
                $this->captcha($name);
            elseif ($rule == "date")
                $this->date($name);
        }
    }

    public function numberValidaiton($name, $ruleArray)
    {
        foreach ($ruleArray as $rule) {
            if ($rule == "required")
                $this->required($name);
            elseif (strpos($rule, "max:") == 0) {
                $rule = str_replace("max:", "", $rule);
                $this->maxNumber($name, $rule);
            } elseif (strpos($rule, "min:") == 0) {
                $rule = str_replace("min:", "", $rule);
                $this->minNumber($name, $rule);
            } elseif (strpos($rule, "exists:") == 0) {
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(",", $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $key);
            } elseif ($rule == "number")
                $this->number($name);
        }
    }

    protected function maxStr($name, $count)
    {
        if ($this->checkFieldExist($name))
            if (strlen($this->request[$name]) >= $count && $this->checkFirstError($name))
                $this->setError($name, "max length equal or lower than $count character", "max");
    }

    protected function minStr($name, $count)
    {
        if ($this->checkFieldExist($name))
            if (strlen($this->request[$name]) < $count && $this->checkFirstError($name))
                $this->setError($name, "min length equal or upper than $count character", "min");
    }

    protected function maxNumber($name, $count)
    {
        if ($this->checkFieldExist($name))
            if ((float) $this->request[$name] >= $count && $this->checkFirstError($name))
                $this->setError($name, "max number equal or lower than $count character", "max");
    }

    protected function minNumber($name, $count)
    {
        if ($this->checkFieldExist($name))
            if ((float) $this->request[$name] >= $count && $this->checkFirstError($name))
                $this->setError($name, "min number equal or upper than $count character", "min");
    }

    protected function required($name)
    {
        if ((!isset($this->request[$name]) || $this->request[$name] == "") && $this->checkFirstError($name))
            $this->setError($name, "$name is required", "required");
    }

    protected function number($name)
    {
        if ($this->checkFieldExist($name))
            if (!is_numeric($this->request[$name]) && $this->checkFirstError($name))
                $this->setError($name, "$name must be number", "number");
    }

    private function validateDate($date, $format)
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    protected function date($name)
    {
        if ($this->checkFieldExist($name)) {
            $date = $this->request[$name];
            $isValid1 = $this->validateDate($date, "Y-m-d");
            $isValid2 = $this->validateDate($date, "Y/m/d");
            $isValid3 = $this->validateDate($date, "Y-m-d H:i:s");
            $isValid4 = $this->validateDate($date, "Y/m/d H:i:s");
            $isValid5 = $this->validateDate($date, "Y-m-d H:i");
            $isValid6 = $this->validateDate($date, "Y/m/d H:i");
            if (!($isValid1 || $isValid2 || $isValid3 || $isValid4 || $isValid5 || $isValid6) && $this->checkFirstError($name))
                $this->setError($name, "$name must be date", "date");
        }
    }

    protected function email($name)
    {
        if ($this->checkFieldExist($name))
            if (!filter_var($this->request[$name], FILTER_VALIDATE_EMAIL) && $this->checkFirstError($name))
                $this->setError($name, "$name must be email", "email");
    }

    protected function existsIn($name, $table, $field = "id")
    {
        if ($this->checkFieldExist($name)) {
            if ($this->checkFirstError($name)) {
                $value = $this->$name;
                $sql = "SELECT COUNT(*) FROM `$table` WHERE `$field` = ?";
                $statement = DBConnection::getDBConnectionInstance()->prepare($sql);
                $statement->execute([$value]);
                $result = $statement->fetchColumn();
                if ($result == 0 || $result === false)
                    $this->setError($name, "$name not already exist", "exists");
            }
        }
    }

    protected function unique($name, $table, $field = "id")
    {
        if ($this->checkFieldExist($name)) {
            if ($this->checkFirstError($name)) {
                $value = $this->$name;
                $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
                $statement = DBConnection::getDBConnectionInstance()->prepare($sql);
                $statement->execute([$value]);
                $result = $statement->fetchColumn();
                if ($result != 0)
                    $this->setError($name, "$name most be unique", "unique");
            }
        }
    }

    protected function confirm($name)
    {
        if ($this->checkFieldExist($name)) {
            $fieldName = "confirm_{$name}";
            if (!isset($this->$fieldName)) {
                $this->setError($name, "{$fieldName} not exist");
            } elseif ($this->$name !== $this->$fieldName) {
                $this->setError($name, "{$name} confirmation does not match", "confirmed");
            }
        }
    }

    protected function captcha($name)
    {
        if ($this->checkFieldExist($name)) {
            if (!Security::verifyCaptcha($this->$name)) {
                $this->setError($name, "{$name} captcha not match", "captcha");
            }
        }
    }
}
