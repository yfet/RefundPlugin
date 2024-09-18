<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\RefundPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190207125150 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_sequence (id INT AUTO_INCREMENT NOT NULL, idx INT NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE sylius_refund_credit_memo (id VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, orderNumber VARCHAR(255) NOT NULL, total INT NOT NULL, units LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', currencyCode VARCHAR(255) NOT NULL, localeCode VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, issued_at DATETIME DEFAULT NULL, channel_code VARCHAR(255) NOT NULL, channel_name VARCHAR(255) NOT NULL, channel_color VARCHAR(255) NOT NULL, INDEX IDX_5C4F3331989A8203 (orderNumber), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_sequence ( id SERIAL PRIMARY KEY, idx INT NOT NULL, version INT DEFAULT 1 NOT NULL )');
            $this->addSql('CREATE TABLE sylius_refund_credit_memo ( id VARCHAR(255) PRIMARY KEY, number VARCHAR(255) NOT NULL, orderNumber VARCHAR(255) NOT NULL, total INT NOT NULL, units JSONB NOT NULL, currencyCode VARCHAR(255) NOT NULL, localeCode VARCHAR(255) NOT NULL, comment TEXT NOT NULL, issued_at TIMESTAMP DEFAULT NULL, channel_code VARCHAR(255) NOT NULL, channel_name VARCHAR(255) NOT NULL, channel_color VARCHAR(255) NOT NULL )');
            $this->addSql('CREATE INDEX IDX_5C4F3331989A8203 ON sylius_refund_credit_memo (orderNumber)');
            $this->addSql('COMMENT ON COLUMN sylius_refund_credit_memo.units IS \'(DC2Type:json)\'');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP TABLE sylius_refund_credit_memo_sequence');
            $this->addSql('DROP TABLE sylius_refund_credit_memo');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP TABLE sylius_refund_credit_memo_sequence');
            $this->addSql('DROP TABLE sylius_refund_credit_memo');
        }

    }
}
