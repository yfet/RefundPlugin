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

final class Version20191230121402 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD order_id INT DEFAULT NULL');

            $this->addSql('
                UPDATE sylius_refund_credit_memo AS cm
                INNER JOIN sylius_order o
                ON cm.order_number = o.number
                SET cm.order_id = o.id
                WHERE cm.order_number IS NOT NULL
            ');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP order_number');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F33318D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
            $this->addSql('CREATE INDEX IDX_5C4F33318D9F6D38 ON sylius_refund_credit_memo (order_id)');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX IF EXISTS IDX_5C4F3331551F0F81');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD COLUMN order_id INT DEFAULT NULL');

            $this->addSql('
                UPDATE sylius_refund_credit_memo AS cm
                SET order_id = o.id
                FROM sylius_order o
                WHERE cm.order_number = o.number
                AND cm.order_number IS NOT NULL
            ');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP COLUMN order_number');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F33318D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
            $this->addSql('CREATE INDEX IDX_5C4F33318D9F6D38 ON sylius_refund_credit_memo (order_id)');
        }

    }

    public function down(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP FOREIGN KEY FK_5C4F33318D9F6D38');
            $this->addSql('DROP INDEX IDX_5C4F33318D9F6D38 ON sylius_refund_credit_memo');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD order_number VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');

            $this->addSql('
                UPDATE sylius_refund_credit_memo AS cm
                INNER JOIN sylius_order o
                ON cm.order_id = o.id
                SET cm.order_number = o.number
                WHERE cm.order_id IS NOT NULL
            ');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP order_id');
            $this->addSql('CREATE INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo (order_number)');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP CONSTRAINT IF EXISTS FK_5C4F33318D9F6D38');
            $this->addSql('DROP INDEX IF EXISTS IDX_5C4F33318D9F6D38');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD COLUMN order_number VARCHAR(255)');

            $this->addSql('
                UPDATE sylius_refund_credit_memo AS cm
                SET order_number = o.number
                FROM sylius_order o
                WHERE cm.order_id = o.id
                AND cm.order_id IS NOT NULL
            ');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN order_number SET NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP COLUMN order_id');
            $this->addSql('CREATE INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo (order_number)');
        }

    }
}
