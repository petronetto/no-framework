<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\PostgresAdapter;

class RecipesMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
        $recipes = $this->table('recipes');
        $recipes->addColumn('name', 'string', ['limit' => 30])
                ->addColumn('prep_time', 'integer', ['limit' => PostgresAdapter::INT_SMALL])
                ->addColumn('difficulty', 'integer', ['limit' => PostgresAdapter::INT_SMALL])
                ->addColumn('vegetarian', 'boolean', ['default' => false, 'null' => true])
                ->addColumn('created_at', 'datetime', ['null' => true])
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->save();
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTable('recipes');
    }
}
