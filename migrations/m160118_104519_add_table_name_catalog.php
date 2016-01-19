<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_104519_add_table_name_catalog extends Migration
{
    public function up()
    {
        $this->addColumn('catalog', 'table_name', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('catalog', 'table_name');
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
