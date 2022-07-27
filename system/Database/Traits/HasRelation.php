<?php

namespace System\Database\Traits;

trait HasRelation
{
    protected function hasOne($model, $foreignKey, $localKey)
    {
        if ($this->{$this->primaryKey}) {
            $modelObject = new $model();

            return $modelObject->getHasOneRelation($this->table, $foreignKey, $localKey, $this->$localKey);
        }
    }

    public function getHasOneRelation($table, $foreignKey, $otherKey, $otherKeyValue)
    {
        $this->is_relation = true;
        $this->setSql("SELECT `b`.* FROM `$table` AS `a` JOIN {$this->getTableName()} AS `b` ON `a`.`{$otherKey}` = `b`.`{$foreignKey}` ");
        $this->setWhere('AND', "`a`.`{$otherKey}` = ?");
        $this->table = 'a';
        $this->addValue($otherKey, $otherKeyValue);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        if ($data) {
            return $this->arrayToAttributes($data);
        }

        return null;
    }

    protected function hasMany($model, $foreignKey, $otherKey)
    {
        if ($this->{$this->primaryKey}) {
            $modelObject = new $model();

            return $modelObject->getHasManyRelation($this->table, $foreignKey, $otherKey, $this->$otherKey);
        }
    }

    public function getHasManyRelation($table, $foreignKey, $otherKey, $otherKeyValue)
    {
        $this->is_relation = true;
        $this->setSql("SELECT `b`.* FROM `$table` AS `a` JOIN {$this->getTableName()} AS `b` ON `a`.`{$otherKey}` = `b`.`{$foreignKey}` ");
        $this->setWhere('AND', "`a`.`{$otherKey}` = ?");
        $this->table = 'b';
        $this->addValue($otherKey, $otherKeyValue);

        return $this;
    }

    protected function belongsTo($model, $foreignKey, $localKey)
    {
        if ($this->{$this->primaryKey}) {
            $modelObject = new $model();

            return $modelObject->getBelongsToRelation($this->table, $foreignKey, $localKey, $this->$foreignKey);
        }
    }

    public function getBelongsToRelation($table, $foreignKey, $otherKey, $foreignKeyValue)
    {
        $this->is_relation = true;
        $this->setSql("SELECT `b`.* FROM `$table` AS `a` JOIN {$this->getTableName()} AS `b` ON `a`.`{$foreignKey}` = `b`.`{$otherKey}` ");
        $this->setWhere('AND', "`a`.`{$foreignKey}` = ?");
        $this->table = 'b';
        $this->addValue($foreignKey, $foreignKeyValue);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        if ($data) {
            return $this->arrayToAttributes($data);
        }

        return null;
    }

    protected function belongsToMany($model, $commonTable, $localKey, $middleForeignKey, $middleRelation, $foreignKey)
    {
        if ($this->{$this->primaryKey}) {
            $modelObject = new $model();

            return $modelObject->getBelongsToManyRelation($this->table, $commonTable, $localKey, $this->$localKey, $middleForeignKey, $middleRelation, $foreignKey);
        }
    }

    public function getBelongsToManyRelation($table, $commonTable, $localKey, $localKeyValue, $middleForeignKey, $middleRelation, $foreignKey)
    {
        $this->is_relation = true;
        $this->setSql("SELECT `b`.* FROM ( SELECT `c`.* FROM `{$table}` AS `a` JOIN `{$commonTable}` AS `c` ON `a`.`{$localKey}` = `c`.`{$middleForeignKey}` WHERE `a`.`{$localKey}` = ? ) AS relation JOIN {$this->getTableName()} AS `b` ON `relation`.`{$middleRelation}` = `b`.`{$foreignKey}` ");
        $this->addValue("`{$table}`.`{$localKey}`", $localKeyValue);
        $this->table = 'b';

        return $this;
    }
}
