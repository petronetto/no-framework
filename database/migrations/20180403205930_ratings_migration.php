<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\PostgresAdapter;

class RatingsMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
        $ratings = $this->table('ratings');
        $ratings->addColumn('rating', 'integer', ['limit' => PostgresAdapter::INT_SMALL])
            ->addColumn('recipe_id', 'integer')
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('recipe_id', 'recipes', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->save();
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTable('ratings');
    }
}
