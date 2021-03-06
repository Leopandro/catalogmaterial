<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_175517_delete_name_table_from_char_group extends Migration
{
    public function up()
    {

        $table = Yii::$app->db->schema->getTableSchema('characteristic_group');
        if(isset($table->columns['name_table'])) {
            $this->dropColumn('characteristic_group', 'name_table');
        }


    }

    public function down()
    {
        $table = Yii::$app->db->schema->getTableSchema('characteristic_group');
        if(!isset($table->columns['name_table'])) {
            $this->addColumn('characteristic_group', 'name_table', Schema::TYPE_STRING. ' NULL');
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
