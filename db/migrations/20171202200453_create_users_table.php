<?php


use Phinx\Db\Table\Index;
use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        /** @var \Phinx\Db\Table */
        $table = $this->table('users');

        $table->addColumn('username', 'string', ['limit' => 50])
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('photo', 'string')
            ->addIndex('username', ['unique' => Index::UNIQUE])
            ->addIndex('email', ['unique' => Index::UNIQUE])
            ->addTimestamps()
            ->create();
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
