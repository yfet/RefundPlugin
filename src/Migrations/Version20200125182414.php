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

final class Version20200125182414 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_line_items (credit_memo_id VARCHAR(255) NOT NULL, line_item_id INT NOT NULL, INDEX IDX_1453B90E8E574316 (credit_memo_id), UNIQUE INDEX UNIQ_1453B90EA7CBD339 (line_item_id), PRIMARY KEY(credit_memo_id, line_item_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE sylius_refund_line_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit_net_price INT NOT NULL, unit_gross_price INT NOT NULL, net_value INT NOT NULL, gross_value INT NOT NULL, tax_amount INT NOT NULL, tax_rate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90E8E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90EA7CBD339 FOREIGN KEY (line_item_id) REFERENCES sylius_refund_line_item (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP units');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('CREATE TABLE sylius_refund_credit_memo_line_items (
    credit_memo_id VARCHAR(255) NOT NULL,
    line_item_id INT NOT NULL,
    PRIMARY KEY(credit_memo_id, line_item_id)
)');

            $this->addSql('CREATE INDEX IDX_1453B90E8E574316 ON sylius_refund_credit_memo_line_items (credit_memo_id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_1453B90EA7CBD339 ON sylius_refund_credit_memo_line_items (line_item_id)');

            $this->addSql('CREATE TABLE sylius_refund_line_item (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                unit_net_price INT NOT NULL,
                unit_gross_price INT NOT NULL,
                net_value INT NOT NULL,
                gross_value INT NOT NULL,
                tax_amount INT NOT NULL,
                tax_rate VARCHAR(255) DEFAULT NULL
            )');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90E8E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90EA7CBD339 FOREIGN KEY (line_item_id) REFERENCES sylius_refund_line_item (id)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP COLUMN units');
        }
    }

    public function down(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP FOREIGN KEY FK_1453B90EA7CBD339');
            $this->addSql('DROP TABLE sylius_refund_credit_memo_line_items');
            $this->addSql('DROP TABLE sylius_refund_line_item');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD units LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json)\'');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP CONSTRAINT IF EXISTS FK_1453B90EA7CBD339');
            $this->addSql('DROP TABLE IF EXISTS sylius_refund_credit_memo_line_items');
            $this->addSql('DROP TABLE IF EXISTS sylius_refund_line_item');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD COLUMN units JSONB NOT NULL');
            $this->addSql('COMMENT ON COLUMN sylius_refund_credit_memo.units IS \'(DC2Type:json)\'');
        }
    }
}
