<?php

namespace System\Database\DBBuilder;

use System\Config\Config;
use System\Database\DBConnection\DBConnection;

class DBBuilder
{
    private $priorty = null;

    private $migrations = [];

    public function __construct($priorty = null)
    {
        $this->priorty = $priorty;
        $this->createMigrationTable();
        $this->makeMigrations();
        $this->createTables();
        exit('migrations run successfully');
    }

    private function createMigrationTable()
    {
        $dirSep = DIRECTORY_SEPARATOR;
        $migrations = require __DIR__."{$dirSep}defaults{$dirSep}migrations.php";
        $pdoInstance = DBConnection::getDBConnectionInstance();
        foreach ($migrations as $migration) {
            $statement = $pdoInstance->prepare($migration);
            $statement->execute();
        }

        return true;
    }

    private function makeMigrations()
    {
        $oldMigrationsArray = $this->getOldMigration();
        $baseDir = Config::get('app.BASE_DIR');
        $dirSep = DIRECTORY_SEPARATOR;
        $migrationsDirectory = "{$baseDir}{$dirSep}database{$dirSep}migrations{$dirSep}";
        $allMigrationsArray = glob($migrationsDirectory.'*.php');
        $oldDiffArray = [];
        foreach ($oldMigrationsArray as $oldMigration) {
            array_push($oldDiffArray, $migrationsDirectory.$oldMigration['name'].'.php');
        }
        $newMigrationsArray = array_diff($allMigrationsArray, $oldDiffArray);
        $migrations = [];
        if (isset($this->priorty)) {
            $priortyArray = [];
            foreach ($this->priorty as $priorty) {
                array_push($priortyArray, "{$baseDir}{$dirSep}database{$dirSep}migrations{$dirSep}{$priorty}.php");
            }
            $newPriortyArray = array_diff($priortyArray, $oldDiffArray);
            foreach ($newPriortyArray as $priorty) {
                array_push($migrations, $newMigrationsArray[array_search($priorty, $newMigrationsArray)]);
            }
        } else {
            $migrations = $newMigrationsArray;
        }
        $sqlCodeArray = [];
        foreach ($migrations as $migration) {
            $sqlCode = require $migration;
            foreach ($sqlCode as $sql) {
                array_push($sqlCodeArray, $sql);
            }
        }
        $dbNewMigrationNames = [];
        foreach ($migrations as $migration) {
            array_push($dbNewMigrationNames, str_replace([$migrationsDirectory, '.php'], '', $migration));
        }
        $this->setOldMigration($dbNewMigrationNames);
        $this->migrations = $sqlCodeArray;
    }

    private function getOldMigration()
    {
        $sql = 'SELECT name FROM `migrations`';
        $pdoInstance = DBConnection::getDBConnectionInstance();
        $statement = $pdoInstance->prepare($sql);
        $statement->execute();
        $migrations = $statement->fetchAll();

        return $migrations;
    }

    private function setOldMigration($migrations)
    {
        $pdoInstance = DBConnection::getDBConnectionInstance();
        foreach ($migrations as $migration) {
            $sql = 'INSERT INTO `migrations` (`name`) VALUES(?)';
            $statement = $pdoInstance->prepare($sql);
            $statement->execute([$migration]);
        }
    }

    private function createTables()
    {
        $migrations = $this->migrations;
        $pdoInstance = DBConnection::getDBConnectionInstance();
        foreach ($migrations as $migration) {
            $statement = $pdoInstance->prepare($migration);
            $statement->execute();
        }

        return true;
    }
}
