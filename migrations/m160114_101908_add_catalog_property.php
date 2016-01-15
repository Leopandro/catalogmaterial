<?php

use yii\db\Schema;
use yii\db\Migration;

class m160114_101908_add_catalog_property extends Migration
{
    public function up()
    {
        $this->addColumn('catalog', 'node_type', Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('catalog', 'node_type');
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
