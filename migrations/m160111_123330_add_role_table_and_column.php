<?php

use yii\db\Schema;
use yii\db\Migration;

class m160111_123330_add_role_table_and_column extends Migration
{
    public function up()
    {
        $this->createTable('role', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING.'(64) NOT NULL',
            'title' => Schema::TYPE_STRING.'(64) NOT NULL'
        ]);
        $this->insert('role', [
            'id' => 0,
            'name' => 'admin',
            'title' => 'Администратор',
        ]);
        $this->addColumn('user', 'role_id', Schema::TYPE_INTEGER . ' NOT NULL default 1');
        $this->addForeignKey('fk_user_role-id', 'user', 'role_id', 'role', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('role');
        $this->dropColumn('user', 'role_id');
        $this->dropForeignKey('fk_user_role-id', 'user');
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
