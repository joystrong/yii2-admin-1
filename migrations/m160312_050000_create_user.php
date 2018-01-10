<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m160312_050000_create_user extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();

        // Check if the table exists
        if ($db->schema->getTableSchema($userTable, true) === null) {
            $this->createTable($userTable, [
                'id' => $this->primaryKey(),
                'username' => $this->string(32)->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);
            //add default admin user. account：admin  password：admin123456
            $time = time();
            $this->insert($userTable,[
                'username'=>'admin',
                'auth_key'=>'I7zE7NX1x5XhTBeb_TiUGcccHDdGO3mQ',
                'password_hash'=>'$2y$13$LENFu.1SdAsO.WoWgTq.LurGHzHXTf.Q7zGhPJ4lpEw1e6WjHUeQ.',
                'password_reset_token'=>'',
                'email'=>'admin@admin.com',
                'status'=>10,
                'created_at'=>$time,
                'updated_at'=>$time
            ]);
        }
    }

    public function down()
    {
        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();
        if ($db->schema->getTableSchema($userTable, true) !== null) {
            $this->dropTable($userTable);
        }
    }
}
