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

final class Version20191217075815 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX IDX_5C4F3331989A8203 ON sylius_refund_credit_memo');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo CHANGE orderNumber order_number VARCHAR(255) NOT NULL, CHANGE currencyCode currency_code VARCHAR(255) NOT NULL, CHANGE localeCode locale_code VARCHAR(255) NOT NULL');
            $this->addSql('CREATE INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo (order_number)');
            $this->addSql('ALTER TABLE sylius_refund_refund CHANGE orderNumber order_number VARCHAR(255) NOT NULL');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX IDX_5C4F3331989A8203');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN orderNumber TO order_number');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN currencyCode TO currency_code');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN localeCode TO locale_code');

            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN order_number TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN order_number SET NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN currency_code TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN currency_code SET NOT NULL');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN locale_code TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN locale_code SET NOT NULL');

            $this->addSql('CREATE INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo (order_number)');
            $this->addSql('ALTER TABLE sylius_refund_refund RENAME COLUMN orderNumber TO order_number');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN order_number TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN order_number SET NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        $databasePlatform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf($databasePlatform !== 'mysql' && $databasePlatform !== 'postgresql', 'Migration can only be executed safely on \'mysql\' or \'postgres\'.');

        if ($databasePlatform === 'mysql') {
            $this->addSql('DROP INDEX IDX_5C4F3331551F0F81 ON sylius_refund_credit_memo');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo CHANGE order_number orderNumber VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE currency_code currencyCode VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE locale_code localeCode VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
            $this->addSql('CREATE INDEX IDX_5C4F3331989A8203 ON sylius_refund_credit_memo (orderNumber)');
            $this->addSql('ALTER TABLE sylius_refund_refund CHANGE order_number orderNumber VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        } elseif ($databasePlatform === 'postgresql') {
            $this->addSql('DROP INDEX IF EXISTS IDX_5C4F3331551F0F81');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN order_number TO orderNumber');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN currency_code TO "currencyCode"');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo RENAME COLUMN locale_code TO "localeCode"');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN orderNumber TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN "currencyCode" TYPE VARCHAR(255)');
            $this->addSql('ALTER TABLE sylius_refund_credit_memo ALTER COLUMN "localeCode" TYPE VARCHAR(255)');
            $this->addSql('CREATE INDEX IDX_5C4F3331989A8203 ON sylius_refund_credit_memo (orderNumber)');
            $this->addSql('ALTER TABLE sylius_refund_refund RENAME COLUMN order_number TO orderNumber');
            $this->addSql('ALTER TABLE sylius_refund_refund ALTER COLUMN orderNumber TYPE VARCHAR(255)');
        }
    }
}
