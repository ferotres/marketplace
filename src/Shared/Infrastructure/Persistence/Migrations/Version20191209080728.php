<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209080728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE product (productId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(100) NOT NULL, reference VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADAEA34913 (reference), PRIMARY KEY(productId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE seller (sellerId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_FB1AD3FCE7927C74 (email), PRIMARY KEY(sellerId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE seller_product (sellerProductId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', productId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', sellerId CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', stock INT NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(sellerProductId),  KEY `FK_SELLER_PRODUCT_PRODUCTID` (`productId`),KEY `FK_SELLER_PRODUCT_SELLERID` (`sellerId`),CONSTRAINT `FK_SELLER_PRODUCT_PRODUCTID` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION, CONSTRAINT `FK_SELLER_PRODUCT_SELLERID` FOREIGN KEY (`sellerId`) REFERENCES `seller` (`sellerId`) ON DELETE NO ACTION ON UPDATE NO ACTION) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE `customer` (`customerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,PRIMARY KEY (`customerId`),UNIQUE KEY `UNIQ_81398E09E7927C74` (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        $this->addSql(
            'CREATE TABLE `cart` (`cartId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`customerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`amount` double NOT NULL DEFAULT \'0\',`committed` int(11) NOT NULL,PRIMARY KEY (`cartId`),KEY `FK_CARTMER_ID` (`customerId`),CONSTRAINT `FK_CART_COSTUMERID` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        $this->addSql('
            CREATE TABLE `cart_item` (`cartItemId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`cartId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`sellerProductId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:guid)\',`amount` double NOT NULL DEFAULT \'0\',`quantity` int(11) NOT NULL DEFAULT \'0\',PRIMARY KEY (`cartItemId`),KEY `FK_CARTITEM_CARTID` (`cartId`),KEY `FK_CARTITEM_SELLERPRODUCTID` (`sellerProductId`),CONSTRAINT `FK_CARTITEM_CARTID` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`) ON DELETE NO ACTION ON UPDATE NO ACTION,CONSTRAINT `FK_CARTITEM_SELLERPRODUCTID` FOREIGN KEY (`sellerProductId`) REFERENCES `seller_product` (`sellerProductId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE seller');
        $this->addSql('DROP TABLE seller_product');
        $this->addSql('DROP TABLE seller');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE cart');
    }
}
