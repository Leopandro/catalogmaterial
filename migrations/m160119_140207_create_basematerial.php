<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_140207_create_basematerial extends Migration
{
    public function up()
    {

        if($this->db->schema->getTableSchema('base_material', true) !== null) {
            $this->dropTable('base_material');
        }


        $this->createTable('base_material', [
            'id' => 'pk',
            'name' => 'VARCHAR(45) NULL DEFAULT NULL',
            'date_verify' => 'DATETIME NULL DEFAULT NULL',
            'source' => 'VARCHAR(45) NULL DEFAULT NULL',
            'code' => 'VARCHAR(45) NULL DEFAULT NULL',
            'note' => 'VARCHAR(45) NULL DEFAULT NULL'
        ]);
    }

    public function down()
    {
        if($this->db->schema->getTableSchema('base_material', true) !== null) {
            $this->dropTable('base_material');
        }
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
