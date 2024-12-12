<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212173345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tables creation';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('login', 'string', ['notnull'=>true, 'length'=>200]);
        $table->addColumn('password', 'string', ['notnull'=>true]);
        $table->addColumn('mail', 'string', ['notnull'=>true]);
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('role');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull'=>true, 'length'=>100]);
        $table->addColumn('code', 'string', ['notnull'=>true, 'length'=>200]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('user_role');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
        $table->addColumn('role_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('role_hierarchy');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('parent_id', 'integer', ['notnull'=>true]);
        $table->addColumn('child_id', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('auth_token');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
        $table->addColumn('auth_token', 'string', ['notnull'=>true, 'length'=>250]);
        $table->addColumn('creation_date', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $table = $schema->createTable('refresh_token');
        $table->addColumn('id', 'integer', ['unsigned'=>true, 'autoincrement'=>true]);
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
        $table->addColumn('refresh_token', 'string', ['notnull'=>true, 'length'=>250]);
        $table->addColumn('creation_date', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
        $schema->dropTable('role');
        $schema->dropTable('user_role');
        $schema->dropTable('role_hierarchy');
        $schema->dropTable('auth_token');
        $schema->dropTable('refresh_token');
    }
}
