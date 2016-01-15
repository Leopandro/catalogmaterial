<?php

use yii\db\Schema;
use yii\db\Migration;

class m160115_142854_add_role_user_default extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'role_id', Schema::TYPE_INTEGER . ' NOT NULL default 2');
    }

    public function down()
    {
        $this->alterColumn('user', 'role_id', Schema::TYPE_INTEGER . ' NOT NULL default 1');
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
