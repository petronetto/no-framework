<?php

declare(strict_types=1);

use Phinx\Db\Adapter\PostgresAdapter;
use Phinx\Migration\AbstractMigration;

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
                ->addColumn('description', 'text')
                ->addColumn('prep_time', 'integer', ['limit' => PostgresAdapter::INT_SMALL])
                ->addColumn('difficulty', 'integer', ['limit' => PostgresAdapter::INT_SMALL])
                ->addColumn('vegetarian', 'boolean', ['default' => false, 'null' => true])
                ->addColumn('ratings', 'json', ['null' => true])
                ->addColumn('created_at', 'datetime', ['null' => true])
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->save();

        $this->execute('ALTER TABLE recipes ADD COLUMN searchtext TSVECTOR');
        $this->execute("UPDATE recipes SET searchtext = to_tsvector('english', name || '' || description)");
        $this->execute('CREATE INDEX searchtext_gin ON recipes USING GIN(searchtext)');
        $this->execute("CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON recipes FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.english', 'name', 'description')");
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
