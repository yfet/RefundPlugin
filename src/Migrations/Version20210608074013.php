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

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608074013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_refund ADD order_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE sylius_refund_refund ADD CONSTRAINT FK_DEF86A0E8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
            $this->addSql('CREATE INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund (order_id)');

            $this->addSql(
                'UPDATE sylius_refund_refund
            SET sylius_refund_refund.order_id = (
                SELECT sylius_order.id FROM sylius_order WHERE sylius_order.number = sylius_refund_refund.order_number
            );',
            );

            $this->addSql('ALTER TABLE sylius_refund_refund DROP order_number');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_refund ADD COLUMN order_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE sylius_refund_refund ADD CONSTRAINT FK_DEF86A0E8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
            $this->addSql('CREATE INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund (order_id)');
            $this->addSql(' UPDATE sylius_refund_refund SET order_id = sylius_order.id FROM sylius_order WHERE sylius_order.number = sylius_refund_refund.order_number ');
            $this->addSql('ALTER TABLE sylius_refund_refund DROP COLUMN order_number');
        }

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_refund DROP FOREIGN KEY FK_DEF86A0E8D9F6D38');
            $this->addSql('DROP INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund');
            $this->addSql('ALTER TABLE sylius_refund_refund ADD order_number VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');

            $this->addSql(
                'UPDATE sylius_refund_refund
                SET sylius_refund_refund.order_number = (
                    SELECT sylius_order.number FROM sylius_order WHERE sylius_order.id = sylius_refund_refund.order_id
                );',
            );

            $this->addSql('ALTER TABLE sylius_refund_refund DROP order_id');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_refund DROP CONSTRAINT IF EXISTS FK_DEF86A0E8D9F6D38');
            $this->addSql('DROP INDEX IF EXISTS IDX_DEF86A0E8D9F6D38');
            $this->addSql('ALTER TABLE sylius_refund_refund ADD COLUMN order_number VARCHAR(255)');
            $this->addSql(' UPDATE sylius_refund_refund SET order_number = sylius_order.number FROM sylius_order WHERE sylius_order.id = sylius_refund_refund.order_id ');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN order_number SET NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_refund DROP COLUMN order_id');
        }
    }
}
