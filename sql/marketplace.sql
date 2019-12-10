-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: marketplace
-- ------------------------------------------------------
-- Server version	5.6.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `cartId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `customerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `amount` double NOT NULL DEFAULT '0',
  `committed` int(11) NOT NULL,
  PRIMARY KEY (`cartId`),
  KEY `FK_CARTMER_ID` (`customerId`),
  CONSTRAINT `FK_CART_CUSTOMER_ID` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_item` (
  `cartItemId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `cartId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `sellerProductId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `amount` double NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cartItemId`),
  KEY `FK_CARTITEM_CARTID` (`cartId`),
  KEY `FK_CARTITEM_SELLERPRODUCTID` (`sellerProductId`),
  CONSTRAINT `FK_CARTITEM_CARTID` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CARTITEM_SELLERPRODUCTID` FOREIGN KEY (`sellerProductId`) REFERENCES `seller_product` (`sellerProductId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_item`
--

LOCK TABLES `cart_item` WRITE;
/*!40000 ALTER TABLE `cart_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `customerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`customerId`),
  UNIQUE KEY `UNIQ_81398E09E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES ('58952145-1a8f-11ea-b51d-0242ac140002','toni.fernandez@gmx.com');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `productId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`productId`),
  UNIQUE KEY `UNIQ_D34A04ADAEA34913` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES ('19458641-4221-4d07-83a2-250b05d196dd','La Roche-Posay Effaclar gel purificante 400ml','XL463PP0799'),('2d101d4f-995e-4e67-9cd8-4494cd12b192','Crema de culito','XSXSXSXX5454'),('3e9b76e3-7804-4a3d-8327-8c35530fc04b','Fotoprotector ISDIN® Fusion Water SPF 50+ 50ml','SL683PK08ESW455'),('47f2ad60-e1f1-4189-84b9-b95158448127','Armolipid Plus 20comp','XS263462799'),('5178cee9-9bb9-45da-aee9-056ebbe74657','Hansaplast Spiral Heat 3 Parches Lumbar/cuello','XS16345279'),('6419de58-4c5c-4e6c-8d9b-530c9e812257','La Roche-Posay Effaclar Duo 40ml','XL773PP0799'),('6ad9d2dd-dbb3-4e72-b55f-a3ff2ccf88c9','Crema regeneradora','XS123456'),('7b29daf1-f65b-490e-8949-4c70733207ea','ISDIN® protector labial SPF15+ 4g','CL683PK088899'),('809a0de8-2733-4b75-ae0e-24be1dced27c','Bioderma Atoderm crema 500ml','XS463465799'),('843c898a-f99f-4ff9-8498-44859b2d6a87','Fotoprotector ISDIN® Fusion Water SPF 50+ 50ml','CL683PK08868'),('8fda0e93-e4d4-4060-a7b4-7e718f03e9af','Fluocaril® Pack Bi-Fluoré 250- pasta dentífrica','XS163452799'),('935e7f53-5a7a-4632-8c76-d6716b11f071','Germisdin® Higiene Corporal 1l','SL683PK088855'),('95bae675-2bfd-47ee-be10-1438b45b4a9a','Colágeno con magnesio','XS16345678'),('99c6898e-a84f-4038-86d1-3416e92aa42a','contorno ojos 2x2ml','XS1634567'),('9a545a3d-4263-40e4-a494-96da61cf5fff','Bioderma Sébium Global 30ml','XL773PP08764'),('a0703cbe-afaa-4462-9514-6d686837ab41','NeoStrata® SaliZinc 50ml','XL773PP08768'),('a70159c9-d770-4f93-ba56-f91386ec2021','Retincare 30ml','XL683PK08768'),('b6f47ff1-6f3a-4d1e-807d-53960ff27c4a','La Roche-Posay Lipikar Syndet Ap+ 400ml','XL463420799'),('cfcbf1cc-e91e-4455-88b1-ec7bf12981dc','Crema despigmentante 50ml','XS1234567'),('d827e4fc-e706-409e-a24c-3549a41ec56c','Avène compacto SPF50+ dorado 10g','XS163452789'),('fb56deb4-7085-4f73-b4df-18713ad7cd64','Voltatermic parches de calor semicirculares 4 unidades','XS16345679');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller`
--

DROP TABLE IF EXISTS `seller`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller` (
  `sellerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`sellerId`),
  UNIQUE KEY `UNIQ_FB1AD3FCE7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller`
--

LOCK TABLES `seller` WRITE;
/*!40000 ALTER TABLE `seller` DISABLE KEYS */;
INSERT INTO `seller` VALUES ('3af755ae-d599-4890-a24d-335725d0a251','Farmacia Albertos','maria.albertos@farmacalbertos.es'),('7919e868-6927-4672-a839-8d108f78ae71','Farmacia Blanco','pedro@farmaciablanco.es'),('86e9c78d-d955-4aaf-89cd-7192c044c8cd','Farmacia Carreras','juan@farmacicarreras.es');
/*!40000 ALTER TABLE `seller` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller_product`
--

DROP TABLE IF EXISTS `seller_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller_product` (
  `sellerProductId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `productId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `sellerId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `stock` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`sellerProductId`),
  KEY `FK_SELLER_PRODUCT_PRODUCTID` (`productId`),
  KEY `FK_SELLER_PRODUCT_SELLERID` (`sellerId`),
  CONSTRAINT `FK_SELLER_PRODUCT_PRODUCTID` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_SELLER_PRODUCT_SELLERID` FOREIGN KEY (`sellerId`) REFERENCES `seller` (`sellerId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller_product`
--

LOCK TABLES `seller_product` WRITE;
/*!40000 ALTER TABLE `seller_product` DISABLE KEYS */;
INSERT INTO `seller_product` VALUES ('396c6fdf-c75e-4ceb-bae1-815b50de064b','7b29daf1-f65b-490e-8949-4c70733207ea','3af755ae-d599-4890-a24d-335725d0a251',6,20),('550b2bbe-be46-453e-8ffc-004d2aa053f8','47f2ad60-e1f1-4189-84b9-b95158448127','7919e868-6927-4672-a839-8d108f78ae71',8,15.75),('5f871f2d-2a2a-40b4-a901-d8bf884e2b11','5178cee9-9bb9-45da-aee9-056ebbe74657','3af755ae-d599-4890-a24d-335725d0a251',10,28),('8c975b73-213b-466e-97c9-5dc938ed1d40','7b29daf1-f65b-490e-8949-4c70733207ea','7919e868-6927-4672-a839-8d108f78ae71',10,25),('c28235f1-9f9d-4139-aec7-b23e77c56a3b','843c898a-f99f-4ff9-8498-44859b2d6a87','3af755ae-d599-4890-a24d-335725d0a251',10,20);
/*!40000 ALTER TABLE `seller_product` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-09 20:54:22
