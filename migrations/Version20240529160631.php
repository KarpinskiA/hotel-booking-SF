<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529160631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED97E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3535ED97E3C61F9 ON hotel (owner_id)');
        $this->addSql('ALTER TABLE reservation ADD is_about_id INT DEFAULT NULL, ADD reserved_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955452EAD5D FOREIGN KEY (is_about_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BCDB4AF4 FOREIGN KEY (reserved_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C84955452EAD5D ON reservation (is_about_id)');
        $this->addSql('CREATE INDEX IDX_42C84955BCDB4AF4 ON reservation (reserved_by_id)');
        $this->addSql('ALTER TABLE room ADD is_room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B422BF65B FOREIGN KEY (is_room_id) REFERENCES hotel (id)');
        $this->addSql('CREATE INDEX IDX_729F519B422BF65B ON room (is_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B422BF65B');
        $this->addSql('DROP INDEX IDX_729F519B422BF65B ON room');
        $this->addSql('ALTER TABLE room DROP is_room_id');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED97E3C61F9');
        $this->addSql('DROP INDEX UNIQ_3535ED97E3C61F9 ON hotel');
        $this->addSql('ALTER TABLE hotel DROP owner_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955452EAD5D');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BCDB4AF4');
        $this->addSql('DROP INDEX IDX_42C84955452EAD5D ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955BCDB4AF4 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP is_about_id, DROP reserved_by_id');
    }
}
