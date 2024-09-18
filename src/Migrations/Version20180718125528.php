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

final class Version20180718125528 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');


        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_refund ADD type VARCHAR(255) NOT NULL, CHANGE refundedunitid refunded_unit_id INT DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DEF86A0EE8F826668CDE5729 ON sylius_refund_refund (refunded_unit_id, type)');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_refund ADD COLUMN type VARCHAR(255) NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_refund RENAME COLUMN refundedunitid TO refunded_unit_id');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN refunded_unit_id DROP NOT NULL');
            $this->addSql('CREATE UNIQUE INDEX uniq_def86a0ee8f826668cde5729 ON sylius_refund_refund (refunded_unit_id, type)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX UNIQ_DEF86A0EE8F826668CDE5729 ON sylius_refund_refund');
            $this->addSql('ALTER TABLE sylius_refund_refund DROP type, CHANGE refunded_unit_id refundedUnitId INT DEFAULT NULL');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX uniq_def86a0ee8f826668cde5729');
            $this->addSql('ALTER TABLE sylius_refund_refund DROP COLUMN type');
            $this->addSql('ALTER TABLE sylius_refund_refund RENAME COLUMN refunded_unit_id TO refundedunitid');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN refundedunitid DROP NOT NULL');
        }
    }
}
