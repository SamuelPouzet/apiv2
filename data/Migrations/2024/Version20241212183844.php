<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212183844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Constraints and indexes';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('user');
        $table->addIndex(['login'], 'user_login_index');
        $table->addIndex(['date_created'], 'user_datecreation_index');

        $table = $schema->getTable('auth_token');
        $table->addIndex(['auth_token'], 'auth_token_index');

        $table = $schema->getTable('refresh_token');
        $table->addIndex(['refresh_token'], 'refresh_token_index');

        $table = $schema->getTable('user_role');
        $table->addIndex(['user_id', 'role_id'], 'user_role_index');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('user');
        $table->dropIndex('user_login_index');
        $table->dropIndex('user_datecreation_index');

        $table = $schema->getTable('auth_token');
        $table->dropIndex('auth_token_index');

        $table = $schema->getTable('refresh_token');
        $table->dropIndex('refresh_token_index');

        $table = $schema->getTable('user_role');
        $table->dropIndex('user_role_index');
    }
}
