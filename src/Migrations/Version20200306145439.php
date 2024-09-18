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

final class Version20200306145439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Makes CreditMemo number unique and issued_at non nullable';
    }

    public function up(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo CHANGE issued_at issued_at DATETIME NOT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5C4F333196901F54 ON sylius_refund_credit_memo (number)');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN issued_at SET NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN issued_at TYPE TIMESTAMP WITHOUT TIME ZONE');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_5C4F333196901F54 ON sylius_refund_credit_memo (number)');
        }
    }

    public function down(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX UNIQ_5C4F333196901F54 ON sylius_refund_credit_memo');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo CHANGE issued_at issued_at DATETIME DEFAULT NULL');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX IF EXISTS UNIQ_5C4F333196901F54');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo
               ALTER COLUMN issued_at DROP NOT NULL,
               ALTER COLUMN issued_at TYPE TIMESTAMP WITHOUT TIME ZONE');
        }
    }
}
