<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117222151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE animal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bid_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bid_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_seller_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE animal (id INT NOT NULL, name VARCHAR(255) NOT NULL, scientific_name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, capture_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN animal.capture_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE bid (id INT NOT NULL, animal_id INT NOT NULL, seller_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, initial_price INT NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT DEFAULT NULL, current_price INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4AF2B3F38E962C16 ON bid (animal_id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F38DE820D9 ON bid (seller_id)');
        $this->addSql('COMMENT ON COLUMN bid.start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN bid.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE bid_log (id INT NOT NULL, bid_id INT NOT NULL, bidder_id INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_211CA38E4D9866B8 ON bid_log (bid_id)');
        $this->addSql('CREATE INDEX IDX_211CA38EBE40AFAE ON bid_log (bidder_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, bid_id INT NOT NULL, commentator_id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C4D9866B8 ON comment (bid_id)');
        $this->addSql('CREATE INDEX IDX_9474526C506AFCC0 ON comment (commentator_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, address VARCHAR(255) NOT NULL, capacity INT DEFAULT NULL, registered_users INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA761220EA6 ON event (creator_id)');
        $this->addSql('COMMENT ON COLUMN event.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, event_id INT NOT NULL, holder_id INT NOT NULL, reference VARCHAR(255) NOT NULL, expire_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA371F7E88B ON ticket (event_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3DEEE62D0 ON ticket (holder_id)');
        $this->addSql('COMMENT ON COLUMN ticket.expire_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, status INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON "user" (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_seller (id INT NOT NULL, seller_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9E48C8C18DE820D9 ON user_seller (seller_id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F38DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bid_log ADD CONSTRAINT FK_211CA38E4D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bid_log ADD CONSTRAINT FK_211CA38EBE40AFAE FOREIGN KEY (bidder_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C506AFCC0 FOREIGN KEY (commentator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3DEEE62D0 FOREIGN KEY (holder_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_seller ADD CONSTRAINT FK_9E48C8C18DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE animal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bid_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bid_log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_seller_id_seq CASCADE');
        $this->addSql('ALTER TABLE bid DROP CONSTRAINT FK_4AF2B3F38E962C16');
        $this->addSql('ALTER TABLE bid DROP CONSTRAINT FK_4AF2B3F38DE820D9');
        $this->addSql('ALTER TABLE bid_log DROP CONSTRAINT FK_211CA38E4D9866B8');
        $this->addSql('ALTER TABLE bid_log DROP CONSTRAINT FK_211CA38EBE40AFAE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4D9866B8');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C506AFCC0');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3DEEE62D0');
        $this->addSql('ALTER TABLE user_seller DROP CONSTRAINT FK_9E48C8C18DE820D9');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE bid_log');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_seller');
    }
}
