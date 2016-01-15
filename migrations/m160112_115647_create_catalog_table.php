<?php

use yii\db\Schema;
use yii\db\Migration;

class m160112_115647_create_catalog_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%catalog}}', [
            'id' => Schema::TYPE_PK,
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%catalog}}');
    }
}
