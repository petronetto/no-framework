<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string', ['limit' => 20])
              ->addColumn('password', 'string')
              ->addColumn('email', 'string', ['limit' => 100])
              ->addColumn('first_name', 'string', ['limit' => 30])
              ->addColumn('last_name', 'string', ['limit' => 30])
              ->addIndex(['username', 'email'], ['unique' => true])
            //   ->addTimestamps()
              ->save();
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
