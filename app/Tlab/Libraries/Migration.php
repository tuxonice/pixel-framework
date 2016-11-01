<?php
namespace Tlab\Libraries;


class Migration {

    const _NEW_MIGRATION_BLUEPRINT = 'new_migration.txt';

    public function createMigration($migrationName){

        $className = $this->prepareMigrationClassName($migrationName);

        $filename = _CONFIG_BLUEPRINT_FOLDER._DS.self::_NEW_MIGRATION_BLUEPRINT;
        $blueprint = file_get_contents($filename);

        $blueprint = str_replace('{{CLASS_NAME}}',$className, $blueprint);
        $newMigrationFileName = _CONFIG_MIGRATIONS_PATH._DS.sprintf("%s_%s.php",date("YmdHis"),$className);

        file_put_contents($newMigrationFileName, $blueprint);

        return $className;
    }


    public function migrateMigration(){

        $migrationList = $this->getMigrationFileList();

        foreach($migrationList as $item){
            include(_CONFIG_MIGRATIONS_PATH._DS.$item['fileName']);
            $className = 'Tlab\\Migrations\\'.$item['className'];
            $obj = new $className;
            $obj->up();

        }

    }

    public function rollbackMigration(){

        $migrationList = $this->getMigrationFileList();

        foreach($migrationList as $item){
            include(_CONFIG_MIGRATIONS_PATH._DS.$item['fileName']);
            $className = 'Tlab\\Migrations\\'.$item['className'];
            $obj = new $className;
            $obj->down();

        }

    }



    protected function prepareMigrationClassName($migrationName){


        $className = str_replace('_',' ',$migrationName);
        $className = ucwords($className);
        $className = str_replace(' ','',$className);

        return $className;
    }


    protected function getMigrationFileList(){


        $list = glob(_CONFIG_MIGRATIONS_PATH._DS."[0-9]*_*.php");

        $fileList = array();
        foreach($list as $file){

            $fileList[] = $this->getClassName(basename($file));

        }


        return $fileList;

    }


    protected function getClassName($fileName){

        list($timestamp,$className) = explode('_',$fileName);


        return array('timestamp'=>$timestamp,'className'=>str_replace('.php','',$className),'fileName'=>$fileName);
    }


}