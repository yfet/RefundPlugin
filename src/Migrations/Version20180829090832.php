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

final class Version20180829090832 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX UNIQ_DEF86A0EE8F826668CDE5729 ON sylius_refund_refund');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX UNIQ_DEF86A0EE8F826668CDE5729');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DEF86A0EE8F826668CDE5729 ON sylius_refund_refund (refunded_unit_id, type)');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('CREATE UNIQUE INDEX UNIQ_DEF86A0EE8F826668CDE5729 ON sylius_refund_refund (refunded_unit_id, type)');
        }
    }
}
