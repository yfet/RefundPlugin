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

final class Version20200131082149 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_tax_items (credit_memo_id VARCHAR(255) NOT NULL, tax_item_id INT NOT NULL, INDEX IDX_9BBDFBE28E574316 (credit_memo_id), UNIQUE INDEX UNIQ_9BBDFBE25327F254 (tax_item_id), PRIMARY KEY(credit_memo_id, tax_item_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE sylius_refund_tax_item (id INT AUTO_INCREMENT NOT NULL, `label` VARCHAR(255) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE28E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE25327F254 FOREIGN KEY (tax_item_id) REFERENCES sylius_refund_tax_item (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP tax_items');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_tax_items (
    credit_memo_id VARCHAR(255) NOT NULL,
    tax_item_id INT NOT NULL,
    PRIMARY KEY(credit_memo_id, tax_item_id)
)');

            $this->addSql('CREATE INDEX IDX_9BBDFBE28E574316 ON sylius_refund_credit_memo_tax_items (credit_memo_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_9BBDFBE25327F254 ON sylius_refund_credit_memo_tax_items (tax_item_id)');

            $this->addSql('CREATE TABLE sylius_refund_tax_item (
    id SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    amount INT NOT NULL
)');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items
    ADD CONSTRAINT FK_9BBDFBE28E574316
    FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items
    ADD CONSTRAINT FK_9BBDFBE25327F254
    FOREIGN KEY (tax_item_id) REFERENCES sylius_refund_tax_item (id)');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP COLUMN IF EXISTS tax_items');
        }
    }

    public function down(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP FOREIGN KEY FK_9BBDFBE25327F254');
            $this->addSql('DROP TABLE sylius_refund_credit_memo_tax_items');
            $this->addSql('DROP TABLE sylius_refund_tax_item');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD tax_items LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json)\'');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP CONSTRAINT IF EXISTS FK_9BBDFBE25327F254');

            $this->addSql('DROP TABLE IF EXISTS sylius_refund_credit_memo_tax_items');

            $this->addSql('DROP TABLE IF EXISTS sylius_refund_tax_item');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD COLUMN tax_items JSONB DEFAULT NULL');

            $this->addSql('COMMENT ON COLUMN sylius_refund_credit_memo.tax_items IS \'(DC2Type:json)\'');
        }
    }
}
