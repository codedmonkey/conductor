<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241008191512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add package type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE package ADD type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE package DROP type');
    }
}