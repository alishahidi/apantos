<?php

namespace System\Database\Traits;

use System\Database\DBConnection\DBConnection;

trait hasCRUD
{
    protected function createMethod($values)
    {
        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function updateMethod($values)
    {
        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function deleteMethod($id = null)
    {
        $object = $this;
        $this->resetQuery();
        if ($id) {
            $object = $this->findMethod($id);
            $this->resetQuery();
        }

        $object->setSql("DELETE FROM {$object->getTableName()}");
        $object->setWhere("AND", "{$object->getAttributeName($object->primaryKey)} = ?");
        $object->addValue($object->primaryKey, $object->{$object->primaryKey});
        return $object->executeQuery();
    }

    protected function allMethod()
    {
        $this->setSql("SELECT {$this->getTableName()}.* FROM {$this->getTableName()}");
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function findMethod($id)
    {
        $this->setSql("SELECT {$this->getTableName()}.* FROM {$this->getTableName()}");
        $this->setWhere("AND", "{$this->getAttributeName($this->primaryKey)} = ?");
        $this->addValue($this->primaryKey, $id);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        $this->setAllowedMethods(["update", "delete", "save"]);
        if ($data)
            return $this->arrayToAttributes($data);
        return null;
    }

    protected function whereMethod($attribute, $firstValue, $secondValue = null)
    {
        if (!isset($secondValue)) {
            $condition = "{$this->getAttributeName($attribute)} = ?";
            $this->addValue(
                $attribute,
                $firstValue
            );
        } else {
            $condition = "{$this->getAttributeName($attribute)} $firstValue ?";
            $this->addValue(
                $attribute,
                $secondValue
            );
        }
        $operator = "AND";
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(["where", "whereOr", "whereNull", "whereNotNull", "limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function whereOrMethod($attribute, $firstValue, $secondValue = null)
    {
        if (!isset($secondValue)) {
            $condition = "{$this->getAttributeName($attribute)} = ?";
            $this->addValue(
                $attribute,
                $firstValue
            );
        } else {
            $condition = "{$this->getAttributeName($attribute)} $firstValue ?";
            $this->addValue(
                $attribute,
                $secondValue
            );
        }
        $operator = "OR";
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(["where", "whereOr", "whereNull", "whereNotNull", "limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function whereNullMethod($attribute)
    {
        $condition = "{$this->getAttributeName($attribute)} IS NULL ";
        $operator = "AND";
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(["where", "whereOr", "whereNull", "whereNotNull", "limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function whereNotNullMethod($attribute)
    {
        $condition = "{$this->getAttributeName($attribute)} IS NOT NULL ";
        $operator = "AND";
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(["where", "whereOr", "whereNull", "whereNotNull", "limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function whereInMethod($attribute, array $values)
    {
        $valuesArray = [];
        foreach ($values as $value) {
            $this->addValue($attribute, $value);
            array_push($valuesArray, "?");
        }
        $condition = "{$this->getAttributeName($attribute)} IN ({implode(', ', $valuesArray)})";
        $operator = "AND";
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(["where", "whereOr", "whereNull", "whereNotNull", "limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function randomOrderMethod($expression){
        array_push($this->orderBy, "RAND() $expression");
        $this->setAllowedMethods(["limit", "orderBy", "get", "paginate", "count"]);
        return $this;
    }

    protected function orderByMethod($attribute, $expression)
    {
        $this->setOrderBy($attribute, $expression);
        $this->setAllowedMethods(["limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function setLimitMethod($from, $number)
    {
        $this->limit["form"] = (int) $from;
        $this->limit["number"] = (int) $number;
    }

    protected function resetLimitMethod()
    {
        unset($this->limit["from"]);
        unset($this->limit["number"]);
    }

    protected function limitMethod($from, $number)
    {
        $this->setLimit($from, $number);
        $this->setAllowedMethods(["limit", "orderBy", "randomOrder", "get", "paginate", "count"]);
        return $this;
    }

    protected function countMethod(){
        if($this->is_relation)
            return $this->getRelationCount();
        else
            return $this->getCount();
    }

    protected function getMethod($array = [])
    {
        if ($this->sql == "") {
            if (empty($array)) {
                $fields = "{$this->getTableName()}.*";
            } else {
                foreach ($array as $key => $field) {
                    $array[$key] = $this->getAttributeName($field);
                }
                $fields = implode(", ", $array);
            }
            $this->setSql("SELECT $fields FROM {$this->getTableName()}");
        }
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function paginateMethod($perPage)
    {
        if($this->is_relation)
            $totalRows = $this->getRelationCount();
        else
            $totalRows = $this->getCount();
        $currentPage = isset($_GET["_pageid"]) ? (int) $_GET["_pageid"] : 1;
        $totalPages = ceil($totalRows / $perPage);
        $currentPage = max(min($currentPage, $totalPages), 1);
        $currentRow = ($currentPage - 1) * $perPage;
        $this->setLimit($currentRow, $perPage);
        if ($this->sql == "") {
            $this->setSql("SELECT {$this->getTableName()}.* FROM {$this->getTableName()}");
        }
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function saveMethod()
    {
        $fillString = $this->fill();
        if (!isset($this->{$this->primaryKey}))
            $this->setSql("INSERT INTO {$this->getTableName()} SET $fillString, {$this->getAttributeName($this->createdAt)} = now()");
        else {
            $this->setSql("UPDATE {$this->getTableName()} SET $fillString, {$this->getAttributeName($this->updatedAt)} = now()");
            $this->setWhere("AND", "{$this->getAttributeName($this->primaryKey)} = ?");
            $this->addValue($this->primaryKey, $this->{$this->primaryKey});
        }
        $this->executeQuery();
        $this->resetQuery();
        if (!isset($this->{$this->primaryKey})) {
            $insertId = DBConnection::newInsertId();
            $this->insertId = $insertId;
            $object = $this->findMethod($insertId);
            $defaultVars = get_class_vars(get_called_class());
            $allVars = get_object_vars($object);
            $differentVars = array_diff(array_keys($allVars), array_keys($defaultVars));
            foreach ($differentVars as $attribute) {
                $this->inCastAttributes($attribute) === true ? $this->registerAttribute($this, $attribute, $this->castEncodeValue($attribute, $object->$attribute)) : $this->registerAttribute($this, $attribute, $object->$attribute);
            }
        }
        $this->resetQuery();
        $this->setAllowedMethods(["update", "delete", "find"]);
        return $this;
    }

    protected function fill()
    {
        $fillArray = [];
        foreach ($this->fillable as $attribute) {
            if (!isset($this->$attribute))
                continue;
            if ($this->$attribute === "")
                $this->$attribute = null;
            array_push($fillArray, "{$this->getAttributeName($attribute)} = ?");
            $this->inCastAttributes($attribute) ? $this->addValue($attribute, $this->castEncodeValue($attribute, $this->$attribute)) : $this->addValue($attribute, $this->$attribute);
        }
        $fillString = implode(",", $fillArray);
        return $fillString;
    }
}
