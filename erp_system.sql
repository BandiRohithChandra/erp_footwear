-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: erp_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'transaction','created','App\\Models\\Transaction','created',1,'App\\Models\\User',2,'{\"attributes\":{\"description\":\"tyui\",\"type\":\"expense\",\"category\":\"salary\",\"amount\":\"50000.00\",\"tax_rate\":\"5.00\",\"tax_amount\":\"2500.00\",\"total_amount\":\"52500.00\",\"transaction_date\":\"2025-11-24T00:00:00.000000Z\",\"region\":\"in\",\"status\":\"pending\",\"approved_by\":null,\"approved_at\":null}}',NULL,'2025-11-24 02:45:00','2025-11-24 02:45:00'),(2,'transaction','created','App\\Models\\Transaction','created',2,'App\\Models\\User',2,'{\"attributes\":{\"description\":\"ertyuiosdfghjmk\",\"type\":\"income\",\"category\":\"invoice_payment\",\"amount\":\"6000.00\",\"tax_rate\":\"5.00\",\"tax_amount\":\"300.00\",\"total_amount\":\"6300.00\",\"transaction_date\":\"2025-11-24T00:00:00.000000Z\",\"region\":\"in\",\"status\":\"pending\",\"approved_by\":null,\"approved_at\":null}}',NULL,'2025-11-24 05:30:30','2025-11-24 05:30:30'),(3,'transaction','updated','App\\Models\\Transaction','updated',2,'App\\Models\\User',2,'{\"attributes\":{\"status\":\"approved\",\"approved_by\":2,\"approved_at\":\"2025-11-24T11:29:35.000000Z\"},\"old\":{\"status\":\"pending\",\"approved_by\":null,\"approved_at\":null}}',NULL,'2025-11-24 05:59:35','2025-11-24 05:59:35'),(4,'transaction','created','App\\Models\\Transaction','created',3,'App\\Models\\User',2,'{\"attributes\":{\"description\":\"example\",\"type\":\"expense\",\"category\":\"expense_claim\",\"amount\":\"12000.00\",\"tax_rate\":\"0.00\",\"tax_amount\":\"0.00\",\"total_amount\":\"12000.00\",\"transaction_date\":\"2025-11-25T00:00:00.000000Z\",\"region\":\"in\",\"status\":\"pending\",\"approved_by\":null,\"approved_at\":null}}',NULL,'2025-11-25 04:43:51','2025-11-25 04:43:51');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advance_deductions`
--

DROP TABLE IF EXISTS `advance_deductions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advance_deductions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `salary_advance_id` bigint(20) unsigned NOT NULL,
  `employee_id` bigint(20) unsigned NOT NULL,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `deducted_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advance_deductions_salary_advance_id_foreign` (`salary_advance_id`),
  KEY `advance_deductions_employee_id_foreign` (`employee_id`),
  KEY `advance_deductions_batch_id_foreign` (`batch_id`),
  CONSTRAINT `advance_deductions_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `employee_batch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advance_deductions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advance_deductions_salary_advance_id_foreign` FOREIGN KEY (`salary_advance_id`) REFERENCES `salary_advances` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advance_deductions`
--

LOCK TABLES `advance_deductions` WRITE;
/*!40000 ALTER TABLE `advance_deductions` DISABLE KEYS */;
/*!40000 ALTER TABLE `advance_deductions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_raw_material`
--

DROP TABLE IF EXISTS `article_raw_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_raw_material` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) unsigned NOT NULL,
  `raw_material_id` bigint(20) unsigned NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_article` (`article_id`),
  KEY `fk_raw_material` (`raw_material_id`),
  CONSTRAINT `fk_article` FOREIGN KEY (`article_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_raw_material` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_raw_material`
--

LOCK TABLES `article_raw_material` WRITE;
/*!40000 ALTER TABLE `article_raw_material` DISABLE KEYS */;
INSERT INTO `article_raw_material` VALUES (38,83,94,120.00,'2025-09-29 00:50:25','2025-09-29 00:50:25'),(39,83,100,250.00,'2025-09-29 00:50:25','2025-09-29 00:50:25'),(108,13,3,120.00,'2025-11-07 23:26:01','2025-11-07 23:26:01'),(109,13,4,150.00,'2025-11-07 23:26:01','2025-11-07 23:26:01'),(111,14,6,50.00,'2025-11-08 06:03:41','2025-11-08 06:03:41'),(120,2,1,20.00,'2025-11-11 07:07:34','2025-11-22 06:53:50'),(121,3,2,15.00,'2025-11-11 07:07:34','2025-11-22 06:53:51'),(123,3,3,20.00,'2025-11-12 01:59:41','2025-11-12 01:59:41');
/*!40000 ALTER TABLE `article_raw_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `is_remote` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'absent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_details`
--

DROP TABLE IF EXISTS `bank_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `ifsc_code` varchar(255) NOT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_details`
--

LOCK TABLES `bank_details` WRITE;
/*!40000 ALTER TABLE `bank_details` DISABLE KEYS */;
INSERT INTO `bank_details` VALUES (1,'Bank of Maharashtra','Nagpada, Mumbai','Mohan lal','7799922331122','BOM123456',NULL,'2025-11-10 02:13:48','2025-11-10 02:19:25');
/*!40000 ALTER TABLE `bank_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_client`
--

DROP TABLE IF EXISTS `batch_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_client` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_client_batch_id_foreign` (`batch_id`),
  KEY `batch_client_client_id_foreign` (`client_id`),
  CONSTRAINT `batch_client_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_client_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_client`
--

LOCK TABLES `batch_client` WRITE;
/*!40000 ALTER TABLE `batch_client` DISABLE KEYS */;
INSERT INTO `batch_client` VALUES (87,17,121,NULL,NULL),(88,17,122,NULL,NULL),(89,17,123,NULL,NULL),(90,17,124,NULL,NULL),(91,18,122,NULL,NULL),(92,19,121,NULL,NULL),(93,19,122,NULL,NULL),(94,19,124,NULL,NULL),(95,20,122,NULL,NULL),(96,20,121,NULL,NULL),(145,7,178,NULL,NULL),(146,7,179,NULL,NULL),(147,7,182,NULL,NULL),(148,7,183,NULL,NULL),(149,7,184,NULL,NULL),(150,7,185,NULL,NULL),(151,7,186,NULL,NULL),(152,7,187,NULL,NULL),(153,7,188,NULL,NULL),(154,7,189,NULL,NULL),(155,7,190,NULL,NULL),(156,7,191,NULL,NULL),(157,7,192,NULL,NULL),(158,7,193,NULL,NULL),(159,8,178,NULL,NULL),(160,8,184,NULL,NULL),(161,8,185,NULL,NULL),(162,8,186,NULL,NULL),(163,8,187,NULL,NULL),(164,8,188,NULL,NULL),(165,8,189,NULL,NULL),(166,8,190,NULL,NULL),(167,8,191,NULL,NULL),(168,8,192,NULL,NULL),(169,8,193,NULL,NULL),(170,9,178,NULL,NULL),(171,10,181,NULL,NULL),(172,10,182,NULL,NULL),(173,10,183,NULL,NULL),(174,11,186,NULL,NULL),(175,12,178,NULL,NULL),(176,13,179,NULL,NULL),(177,14,179,NULL,NULL),(178,15,182,NULL,NULL),(179,16,180,NULL,NULL),(180,1,195,NULL,NULL),(181,2,195,NULL,NULL),(182,2,196,NULL,NULL),(183,3,197,NULL,NULL),(184,4,197,NULL,NULL),(185,5,195,NULL,NULL),(186,5,196,NULL,NULL),(187,5,197,NULL,NULL),(188,6,195,NULL,NULL),(189,6,196,NULL,NULL);
/*!40000 ALTER TABLE `batch_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_flows`
--

DROP TABLE IF EXISTS `batch_flows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_flows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `priority` int(11) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_flows_batch_id_foreign` (`batch_id`),
  KEY `batch_flows_quotation_id_foreign` (`quotation_id`),
  KEY `batch_flows_created_by_foreign` (`created_by`),
  CONSTRAINT `batch_flows_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_flows_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_flows_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_flows`
--

LOCK TABLES `batch_flows` WRITE;
/*!40000 ALTER TABLE `batch_flows` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_flows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_labor_usage`
--

DROP TABLE IF EXISTS `batch_labor_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_labor_usage` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `process_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `labor_rate` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_labor_usage`
--

LOCK TABLES `batch_labor_usage` WRITE;
/*!40000 ALTER TABLE `batch_labor_usage` DISABLE KEYS */;
INSERT INTO `batch_labor_usage` VALUES (4,14,63,50,0.00,0.00,'2025-09-20 05:53:16','2025-09-20 05:53:16'),(5,14,64,50,0.00,0.00,'2025-09-20 05:53:16','2025-09-20 05:53:16'),(6,14,65,50,0.00,0.00,'2025-09-20 05:53:16','2025-09-20 05:53:16'),(271,110,414,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(272,110,415,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(273,110,416,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(274,110,417,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(275,110,418,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(276,110,419,30,0.00,0.00,'2025-09-29 00:50:49','2025-09-29 00:50:49'),(295,13,1,15,75.00,1125.00,'2025-09-29 09:40:31','2025-09-29 09:40:31'),(296,13,2,15,75.00,1125.00,'2025-09-29 09:40:31','2025-09-29 09:40:31'),(297,13,3,15,100.00,1500.00,'2025-09-29 09:40:31','2025-09-29 09:40:31'),(298,14,1,6,95.00,570.00,'2025-09-30 09:35:39','2025-09-30 09:35:39'),(299,14,2,6,95.00,570.00,'2025-09-30 09:35:39','2025-09-30 09:35:39'),(300,14,3,6,105.00,630.00,'2025-09-30 09:35:39','2025-09-30 09:35:39'),(310,20,1,10,95.00,950.00,'2025-09-30 22:27:57','2025-09-30 22:27:57'),(311,20,2,10,95.00,950.00,'2025-09-30 22:27:57','2025-09-30 22:27:57'),(312,20,3,10,105.00,1050.00,'2025-09-30 22:27:57','2025-09-30 22:27:57'),(313,21,1,10,95.00,950.00,'2025-09-30 22:30:57','2025-09-30 22:30:57'),(314,21,2,10,95.00,950.00,'2025-09-30 22:30:57','2025-09-30 22:30:57'),(315,21,3,10,105.00,1050.00,'2025-09-30 22:30:57','2025-09-30 22:30:57'),(364,33,1,5,120.00,600.00,'2025-10-06 23:39:04','2025-10-06 23:39:04'),(365,33,2,5,120.00,600.00,'2025-10-06 23:39:04','2025-10-06 23:39:04'),(366,33,3,5,200.00,1000.00,'2025-10-06 23:39:04','2025-10-06 23:39:04'),(375,3,1,2,50.00,100.00,'2025-10-07 04:54:44','2025-10-07 04:54:44'),(376,3,2,2,50.00,100.00,'2025-10-07 04:54:44','2025-10-07 04:54:44'),(377,3,3,2,50.00,100.00,'2025-10-07 04:54:44','2025-10-07 04:54:44'),(378,3,7,2,75.00,150.00,'2025-10-07 04:54:44','2025-10-07 04:54:44'),(379,4,1,1,50.00,50.00,'2025-10-07 05:44:01','2025-10-07 05:44:01'),(380,4,2,1,50.00,50.00,'2025-10-07 05:44:01','2025-10-07 05:44:01'),(381,4,3,1,50.00,50.00,'2025-10-07 05:44:01','2025-10-07 05:44:01'),(382,4,7,1,75.00,75.00,'2025-10-07 05:44:01','2025-10-07 05:44:01'),(387,6,1,1,50.00,50.00,'2025-10-07 06:06:57','2025-10-07 06:06:57'),(388,6,2,1,50.00,50.00,'2025-10-07 06:06:57','2025-10-07 06:06:57'),(389,6,3,1,50.00,50.00,'2025-10-07 06:06:57','2025-10-07 06:06:57'),(390,6,7,1,75.00,75.00,'2025-10-07 06:06:57','2025-10-07 06:06:57'),(391,7,1,1,50.00,50.00,'2025-10-08 00:41:12','2025-10-08 00:41:12'),(392,7,2,1,50.00,50.00,'2025-10-08 00:41:12','2025-10-08 00:41:12'),(393,7,3,1,50.00,50.00,'2025-10-08 00:41:12','2025-10-08 00:41:12'),(394,7,7,1,75.00,75.00,'2025-10-08 00:41:12','2025-10-08 00:41:12'),(410,14,1,10,50.00,500.00,'2025-10-09 06:00:45','2025-10-09 06:00:45'),(411,14,3,10,100.00,1000.00,'2025-10-09 06:00:45','2025-10-09 06:00:45'),(412,14,8,10,65.00,650.00,'2025-10-09 06:00:45','2025-10-09 06:00:45'),(413,15,1,10,50.00,500.00,'2025-10-09 06:06:20','2025-10-09 06:06:20'),(414,15,3,10,100.00,1000.00,'2025-10-09 06:06:20','2025-10-09 06:06:20'),(415,15,8,10,65.00,650.00,'2025-10-09 06:06:20','2025-10-09 06:06:20'),(416,16,1,5,50.00,250.00,'2025-10-09 06:20:37','2025-10-09 06:20:37'),(417,16,3,5,100.00,500.00,'2025-10-09 06:20:37','2025-10-09 06:20:37'),(418,16,8,5,65.00,325.00,'2025-10-09 06:20:37','2025-10-09 06:20:37'),(419,17,1,14,15.00,210.00,'2025-10-11 08:07:37','2025-10-11 08:07:37'),(420,17,2,14,100.00,1400.00,'2025-10-11 08:07:37','2025-10-11 08:07:37'),(421,17,3,14,5.00,70.00,'2025-10-11 08:07:37','2025-10-11 08:07:37'),(422,17,8,14,35.00,490.00,'2025-10-11 08:07:37','2025-10-11 08:07:37'),(431,20,1,570,15.00,8550.00,'2025-10-11 08:35:20','2025-10-11 08:35:20'),(432,20,2,570,100.00,57000.00,'2025-10-11 08:35:20','2025-10-11 08:35:20'),(433,20,3,570,5.00,2850.00,'2025-10-11 08:35:20','2025-10-11 08:35:20'),(434,20,8,570,35.00,19950.00,'2025-10-11 08:35:20','2025-10-11 08:35:20');
/*!40000 ALTER TABLE `batch_labor_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_no` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `materials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`materials`)),
  `liquid_materials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`liquid_materials`)),
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `stock_deducted` tinyint(1) NOT NULL DEFAULT 0,
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `created_by` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `labor_assignments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`labor_assignments`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `batches_batch_no_unique` (`batch_no`),
  KEY `batches_client_id_foreign` (`client_id`),
  CONSTRAINT `batches_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (1,'BATCH-20251122123512','boots','1541521',NULL,NULL,2,100,'pending',1,'normal','2025-11-11',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"sole-one\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"25\\\",\\\"36\\\":\\\"25\\\",\\\"37\\\":\\\"25\\\",\\\"38\\\":\\\"25\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-22 07:05:35','2025-11-22 07:05:36',NULL),(2,'BATCH-20251122125310','boots','78978',NULL,NULL,2,145,'in_progress',1,'normal','2025-11-16',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"kiran\\\",\\\"sole_color\\\":\\\"peach\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"50\\\",\\\"37\\\":\\\"75\\\",\\\"38\\\":\\\"0\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-22 07:24:05','2025-11-24 05:26:11','\"[{\\\"employee_id\\\":2,\\\"process_id\\\":1,\\\"total_quantity\\\":120,\\\"salary_per_day\\\":0,\\\"start_date\\\":\\\"2025-11-23\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":20,\\\"36\\\":50,\\\"37\\\":50,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":6,\\\"process_id\\\":3,\\\"total_quantity\\\":145,\\\"salary_per_day\\\":0,\\\"start_date\\\":\\\"2025-11-25\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":20,\\\"36\\\":50,\\\"37\\\":75,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]}]\"'),(3,'BATCH-20251122131226','boots','1235',NULL,NULL,2,80,'pending',1,'normal','2025-11-18',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"sole-two\\\",\\\"sole_color\\\":\\\"beige\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-22 07:42:56','2025-11-22 07:42:57',NULL),(4,'BATCH-20251122131853','boots','454554',NULL,NULL,2,80,'completed',1,'normal','2025-11-23',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"sole-one\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":{\\\"ordered\\\":20,\\\"available\\\":35,\\\"delivered\\\":15},\\\"36\\\":{\\\"ordered\\\":20,\\\"available\\\":40,\\\"delivered\\\":10},\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-22 07:49:16','2025-11-23 23:46:29','\"[{\\\"employee_id\\\":\\\"2\\\",\\\"process_id\\\":1,\\\"total_quantity\\\":80,\\\"salary_per_day\\\":\\\"55.00\\\",\\\"start_date\\\":\\\"2025-11-23\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":20,\\\"36\\\":20,\\\"37\\\":20,\\\"38\\\":20,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":\\\"3\\\",\\\"process_id\\\":2,\\\"total_quantity\\\":45,\\\"salary_per_day\\\":\\\"45.00\\\",\\\"start_date\\\":\\\"2025-11-23\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":15,\\\"36\\\":15,\\\"37\\\":15,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":1,\\\"process_id\\\":3,\\\"total_quantity\\\":45,\\\"salary_per_day\\\":0,\\\"start_date\\\":\\\"2025-11-18\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":15,\\\"36\\\":15,\\\"37\\\":15,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]}]\"'),(5,'BATCH-20251124052648','Sneakers-premium','78978',NULL,NULL,3,295,'completed',1,'normal','2025-11-25',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"sole-three\\\",\\\"sole_color\\\":\\\"peach\\\",\\\"sizes\\\":{\\\"35\\\":{\\\"ordered\\\":45,\\\"available\\\":50,\\\"delivered\\\":5},\\\"36\\\":{\\\"ordered\\\":50,\\\"available\\\":50,\\\"delivered\\\":5},\\\"37\\\":\\\"50\\\",\\\"38\\\":\\\"50\\\",\\\"39\\\":\\\"50\\\",\\\"40\\\":\\\"50\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-23 23:57:32','2025-11-24 05:24:31','\"[{\\\"employee_id\\\":2,\\\"process_id\\\":1,\\\"total_quantity\\\":100,\\\"salary_per_day\\\":0,\\\"start_date\\\":\\\"2025-11-25\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":25,\\\"36\\\":25,\\\"37\\\":25,\\\"38\\\":25,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":3,\\\"process_id\\\":2,\\\"total_quantity\\\":80,\\\"salary_per_day\\\":0,\\\"start_date\\\":\\\"2025-11-25\\\",\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":20,\\\"36\\\":20,\\\"37\\\":20,\\\"38\\\":20,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":1,\\\"process_id\\\":3,\\\"total_quantity\\\":30,\\\"salary_per_day\\\":0,\\\"start_date\\\":null,\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":10,\\\"36\\\":10,\\\"37\\\":10,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]},{\\\"employee_id\\\":6,\\\"process_id\\\":3,\\\"total_quantity\\\":190,\\\"salary_per_day\\\":0,\\\"start_date\\\":null,\\\"end_date\\\":null,\\\"variations\\\":[{\\\"35\\\":0,\\\"36\\\":0,\\\"37\\\":40,\\\"38\\\":50,\\\"39\\\":50,\\\"40\\\":50,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}]}]\"'),(6,'BATCH-20251125051721','boots','1541521',NULL,NULL,2,450,'pending',1,'normal','2025-11-26',NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"sole_name\\\":\\\"sole-two\\\",\\\"sole_color\\\":\\\"beige\\\",\\\"sizes\\\":{\\\"35\\\":\\\"150\\\",\\\"36\\\":\\\"200\\\",\\\"37\\\":\\\"100\\\",\\\"38\\\":\\\"0\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','Admin',NULL,'2025-11-24 23:48:00','2025-11-24 23:48:01',NULL);
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('erp_system_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:25:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"manage hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:11;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"view hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:11;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"manage sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:6;i:2;i:7;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"manage finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:12;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"view finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:8;i:2;i:9;i:3;i:12;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:9:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"view notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:12:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:20:\"manage notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"view production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:10;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"manage production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"view employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:10;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"access employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:10;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:21:\"access manager portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:20:\"view sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:22:\"manage sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"manage quotations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"process production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"approve transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:12;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"manage payroll\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:12:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"HR Manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:7:\"Manager\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"HR Employee\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Sales Manager\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"Sales Employee\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"Inventory Manager\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"Inventory Employee\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Finance Manager\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"Finance Employee\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}}}',1764073003);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_user_id_foreign` (`user_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sales_rep_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`),
  KEY `fk_clients_salesrep` (`sales_rep_id`),
  CONSTRAINT `fk_clients_salesrep` FOREIGN KEY (`sales_rep_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_batch_counters`
--

DROP TABLE IF EXISTS `daily_batch_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_batch_counters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_date` date NOT NULL,
  `counter` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_batch_counters_batch_date_unique` (`batch_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_batch_counters`
--

LOCK TABLES `daily_batch_counters` WRITE;
/*!40000 ALTER TABLE `daily_batch_counters` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_batch_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_cards`
--

DROP TABLE IF EXISTS `dashboard_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dashboard_cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `count_type` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_cards_admin_id_foreign` (`admin_id`),
  CONSTRAINT `dashboard_cards_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_cards`
--

LOCK TABLES `dashboard_cards` WRITE;
/*!40000 ALTER TABLE `dashboard_cards` DISABLE KEYS */;
INSERT INTO `dashboard_cards` VALUES (1,2,'New Orders','orders_new','/orders','icons/order.png',1,'2025-09-30 09:04:13','2025-09-30 09:04:13'),(2,2,'Pending Orders','orders_pending','/orders/pending','icons/pending.png',2,'2025-09-30 09:04:13','2025-09-30 09:04:13'),(3,2,'Articles','articles','/products','icons/product.png',3,'2025-09-30 09:04:13','2025-09-30 09:04:13'),(4,2,'Total Sales','total_sales','http://127.0.0.1:8000/admin/sales/total','icons/sales.png',4,'2025-09-30 09:04:13','2025-09-30 09:04:13'),(5,2,'Pending Payments','pending_payments','http://127.0.0.1:8000/admin/orders/pending-payments','icons/payments.png',5,'2025-09-30 09:04:13','2025-09-30 09:04:13'),(6,2,'Total Clients','Total_clients','http://127.0.0.1:8000/admin/clients','icons/clients.png',6,'2025-09-30 09:04:13','2025-09-30 09:04:13');
/*!40000 ALTER TABLE `dashboard_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_notes`
--

DROP TABLE IF EXISTS `delivery_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_note_no` varchar(255) NOT NULL,
  `batch_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_qty` int(11) NOT NULL DEFAULT 0,
  `delivery_date` date NOT NULL DEFAULT curdate(),
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `delivery_notes_delivery_note_no_unique` (`delivery_note_no`),
  KEY `delivery_notes_batch_id_foreign` (`batch_id`),
  KEY `delivery_notes_client_id_foreign` (`client_id`),
  CONSTRAINT `delivery_notes_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `delivery_notes_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_notes`
--

LOCK TABLES `delivery_notes` WRITE;
/*!40000 ALTER TABLE `delivery_notes` DISABLE KEYS */;
INSERT INTO `delivery_notes` VALUES (1,'DN-20251105130528',3,NULL,0,'2025-11-05','\"[{\\\"color\\\":\\\"black\\\",\\\"sole_color\\\":\\\"beige\\\",\\\"sizes\\\":{\\\"35\\\":\\\"25\\\",\\\"36\\\":\\\"40\\\",\\\"37\\\":\\\"50\\\",\\\"38\\\":\\\"50\\\",\\\"39\\\":\\\"50\\\",\\\"40\\\":\\\"5\\\",\\\"41\\\":\\\"50\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','2025-11-05 07:35:28','2025-11-05 07:35:28'),(2,'DN-20251105131108',2,NULL,0,'2025-11-05','\"[{\\\"color\\\":\\\"black\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"40\\\",\\\"36\\\":\\\"80\\\",\\\"37\\\":\\\"90\\\",\\\"38\\\":\\\"100\\\",\\\"39\\\":\\\"110\\\",\\\"40\\\":\\\"80\\\",\\\"41\\\":\\\"30\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','2025-11-05 07:41:08','2025-11-05 07:41:08'),(5,'DN-PIFAHV',2,119,125,'2025-11-07','\"[{\\\"color\\\":\\\"ANT\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"25\\\",\\\"36\\\":\\\"30\\\",\\\"37\\\":\\\"25\\\",\\\"38\\\":\\\"30\\\",\\\"41\\\":\\\"15\\\"}}]\"','2025-11-06 22:57:50','2025-11-06 22:57:50'),(6,'DN-UFWQ72',2,119,110,'2025-11-07','\"[{\\\"color\\\":\\\"ANT\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"15\\\",\\\"36\\\":\\\"35\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"20\\\"}}]\"','2025-11-06 22:59:11','2025-11-06 23:06:51'),(7,'DN-HJMDPK',2,120,30,'2025-11-07','\"[{\\\"color\\\":\\\"ANT\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"10\\\",\\\"36\\\":\\\"5\\\",\\\"37\\\":\\\"10\\\",\\\"38\\\":\\\"5\\\"}}]\"','2025-11-06 23:18:44','2025-11-06 23:18:44'),(8,'DN-ODXE9M',3,119,52,'2025-11-07','\"[{\\\"color\\\":\\\"ANT\\\",\\\"sole_color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"15\\\",\\\"36\\\":\\\"17\\\",\\\"37\\\":\\\"18\\\",\\\"38\\\":\\\"2\\\"}}]\"','2025-11-07 01:05:30','2025-11-07 01:05:30'),(9,'DN-HTZ6JO',1,121,33,'2025-11-07','\"[{\\\"color\\\":\\\"GREY\\\",\\\"sole_color\\\":\\\"peach\\\",\\\"sizes\\\":{\\\"36\\\":\\\"15\\\",\\\"37\\\":\\\"18\\\"}}]\"','2025-11-07 03:14:21','2025-11-07 03:14:21'),(10,'DN-HCV7S3',1,121,20,'2025-11-07','\"[{\\\"color\\\":\\\"GREY\\\",\\\"sole_color\\\":\\\"peach\\\",\\\"sizes\\\":{\\\"36\\\":10,\\\"37\\\":10}}]\"','2025-11-07 03:15:47','2025-11-07 03:15:47'),(11,'DN-OJJVTZ',4,122,0,'2025-11-07','\"[]\"','2025-11-07 05:41:50','2025-11-07 05:41:50'),(12,'DN-3J7UYS',4,122,0,'2025-11-07','[]','2025-11-07 05:46:56','2025-11-07 05:46:56'),(13,'DN-Y6XQXE',4,122,0,'2025-11-07','\"[]\"','2025-11-07 05:48:25','2025-11-07 05:48:25'),(14,'DN-ETHYPO',4,122,0,'2025-11-07','\"[]\"','2025-11-07 05:49:13','2025-11-07 05:49:13'),(15,'DN-2F4FR6',4,124,0,'2025-11-07','[]','2025-11-07 05:51:38','2025-11-07 05:51:38'),(16,'DN-WKAPQI',4,124,0,'2025-11-07','[]','2025-11-07 05:57:38','2025-11-07 05:57:38'),(17,'DN-GREMGW',4,122,40,'2025-11-07','[{\"color\":\"beige\",\"sole_color\":\"black\",\"sizes\":{\"37\":40}}]','2025-11-07 05:59:08','2025-11-07 05:59:08'),(18,'DN-WSYQXZ',4,124,0,'2025-11-07','[]','2025-11-07 06:22:10','2025-11-07 06:22:10'),(19,'DN-QVEIHO',4,122,20,'2025-11-07','[{\"color\":\"beige\",\"sole_color\":\"black\",\"sizes\":{\"37\":20}}]','2025-11-07 06:29:40','2025-11-07 06:29:40'),(20,'DN-TWEAM1',4,122,30,'2025-11-07','[{\"color\":\"beige\",\"sole_color\":\"black\",\"sizes\":{\"38\":30}}]','2025-11-07 06:32:31','2025-11-07 06:32:31'),(21,'DN-ASWX3V',4,124,25,'2025-11-07','[{\"color\":\"beige\",\"sole_color\":\"black\",\"sizes\":{\"39\":{\"ordered\":90,\"available\":65,\"delivered\":25}}}]','2025-11-07 06:36:13','2025-11-07 06:36:13'),(22,'DN-BARRM6',4,124,75,'2025-11-07','[{\"color\":\"beige\",\"sole_color\":\"black\",\"sizes\":{\"39\":{\"ordered\":90,\"available\":40,\"delivered\":50},\"40\":{\"ordered\":54,\"available\":4,\"delivered\":50}}}]','2025-11-07 06:51:08','2025-11-07 06:51:08'),(23,'DN-QDJQAO',17,124,0,'2025-11-08','[]','2025-11-08 00:56:33','2025-11-08 00:56:33'),(24,'DN-5KXXDS',17,122,20,'2025-11-08','[{\"color\":\"black\",\"sole_color\":\"beige\",\"sizes\":{\"36\":{\"ordered\":25,\"available\":5,\"delivered\":20}}}]','2025-11-08 01:57:54','2025-11-08 01:57:54'),(25,'DN-SEWDAN',19,121,30,'2025-11-08','[{\"color\":\"white\",\"sole_color\":\"tan\",\"sizes\":{\"35\":{\"ordered\":\"50\",\"available\":30,\"delivered\":20},\"36\":{\"ordered\":\"25\",\"available\":15,\"delivered\":10}}}]','2025-11-08 02:04:56','2025-11-08 02:04:56'),(26,'DN-5XG8DR',19,122,10,'2025-11-08','[{\"color\":\"white\",\"sole_color\":\"tan\",\"sizes\":{\"37\":{\"ordered\":\"25\",\"available\":15,\"delivered\":10}}}]','2025-11-08 02:10:47','2025-11-08 02:10:47'),(27,'DN-2JOWB2',19,122,20,'2025-11-08','[{\"color\":\"white\",\"sole_color\":\"tan\",\"sizes\":{\"35\":{\"ordered\":50,\"available\":20,\"delivered\":30},\"38\":{\"ordered\":\"25\",\"available\":15,\"delivered\":10}}}]','2025-11-08 02:32:09','2025-11-08 02:32:09'),(28,'DN-HVT7OU',19,121,40,'2025-11-08','[{\"color\":\"white\",\"sole_color\":\"tan\",\"sizes\":{\"35\":{\"ordered\":50,\"available\":0,\"delivered\":50},\"36\":{\"ordered\":25,\"available\":0,\"delivered\":25},\"37\":{\"ordered\":25,\"available\":10,\"delivered\":15}}}]','2025-11-08 02:37:27','2025-11-08 02:37:27'),(29,'DN-VFVEPS',20,122,30,'2025-11-08','[{\"color\":\"black\",\"sole_color\":\"tan\",\"sizes\":{\"35\":{\"ordered\":\"155\",\"available\":125,\"delivered\":30}}}]','2025-11-08 02:50:50','2025-11-08 02:50:50'),(30,'DN-HKITO6',1,181,20,'2025-11-15','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"37\":{\"ordered\":20,\"available\":15,\"delivered\":5},\"38\":{\"ordered\":20,\"available\":15,\"delivered\":5},\"39\":{\"ordered\":20,\"available\":15,\"delivered\":5},\"40\":{\"ordered\":20,\"available\":15,\"delivered\":5}}}]','2025-11-15 02:45:47','2025-11-15 02:45:47'),(31,'DN-WCHLNC',1,179,28,'2025-11-15','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"37\":{\"ordered\":20,\"available\":8,\"delivered\":12},\"38\":{\"ordered\":20,\"available\":8,\"delivered\":12},\"39\":{\"ordered\":20,\"available\":8,\"delivered\":12},\"40\":{\"ordered\":20,\"available\":8,\"delivered\":12}}}]','2025-11-15 02:46:33','2025-11-15 02:46:33'),(32,'DN-EUK4H7',3,179,20,'2025-11-15','[{\"color\":\"beige\",\"sole_color\":\"peach\",\"sizes\":{\"36\":{\"ordered\":\"20\",\"available\":0,\"delivered\":20}}}]','2025-11-15 03:36:21','2025-11-15 03:36:21'),(33,'DN-HAVEJJ',3,179,15,'2025-11-15','[{\"color\":\"beige\",\"sole_color\":\"peach\",\"sizes\":{\"37\":{\"ordered\":\"20\",\"available\":5,\"delivered\":15}}}]','2025-11-15 03:36:37','2025-11-15 03:36:37'),(34,'DN-LA7HAN',4,179,21,'2025-11-15','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"37\":{\"ordered\":\"20\",\"available\":13,\"delivered\":7},\"38\":{\"ordered\":\"20\",\"available\":13,\"delivered\":7},\"39\":{\"ordered\":\"20\",\"available\":13,\"delivered\":7}}}]','2025-11-15 03:48:33','2025-11-15 03:48:33'),(35,'DN-QBRXVX',4,181,15,'2025-11-15','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"37\":{\"ordered\":20,\"available\":8,\"delivered\":12},\"38\":{\"ordered\":20,\"available\":8,\"delivered\":12},\"39\":{\"ordered\":20,\"available\":8,\"delivered\":12}}}]','2025-11-15 03:49:28','2025-11-15 03:49:28'),(36,'DN-QXHIFU',8,185,10,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"black\",\"sizes\":{\"35\":{\"ordered\":20,\"available\":10,\"delivered\":10}}}]','2025-11-22 01:50:53','2025-11-22 01:50:53'),(37,'DN-4JUI7E',9,178,50,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":\"54\",\"available\":34,\"delivered\":20},\"36\":{\"ordered\":\"50\",\"available\":20,\"delivered\":30}}}]','2025-11-22 02:51:29','2025-11-22 02:51:29'),(38,'DN-WMGNOQ',9,178,30,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":54,\"available\":24,\"delivered\":30},\"36\":{\"ordered\":50,\"available\":0,\"delivered\":50}}}]','2025-11-22 03:03:04','2025-11-22 03:03:04'),(39,'DN-2NKHIU',9,178,120,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":54,\"available\":0,\"delivered\":75},\"36\":{\"ordered\":50,\"available\":0,\"delivered\":75},\"37\":{\"ordered\":60,\"available\":25,\"delivered\":50}}}]','2025-11-22 03:13:02','2025-11-22 03:13:02'),(40,'DN-R6SIAI',10,181,165,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":100,\"available\":85,\"delivered\":40},\"36\":{\"ordered\":100,\"available\":90,\"delivered\":50},\"37\":{\"ordered\":100,\"available\":50,\"delivered\":75}}}]','2025-11-22 03:54:01','2025-11-22 03:54:01'),(41,'DN-JIHAUT',10,182,170,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":100,\"available\":40,\"delivered\":85},\"36\":{\"ordered\":100,\"available\":15,\"delivered\":125},\"37\":{\"ordered\":100,\"available\":0,\"delivered\":125}}}]','2025-11-22 03:55:57','2025-11-22 03:55:57'),(42,'DN-1JKQOQ',11,186,220,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":120,\"available\":55,\"delivered\":120},\"36\":{\"ordered\":120,\"available\":120,\"delivered\":100}}}]','2025-11-22 04:27:50','2025-11-22 04:27:50'),(43,'DN-IOCHSY',11,186,380,'2025-11-22','[{\"color\":\"brown\",\"sole_color\":\"peach\",\"sizes\":{\"36\":{\"ordered\":120,\"available\":100,\"delivered\":120},\"37\":{\"ordered\":120,\"available\":80,\"delivered\":120},\"38\":{\"ordered\":120,\"available\":0,\"delivered\":90},\"39\":{\"ordered\":150,\"available\":0,\"delivered\":150}},\"source\":\"quotation\"}]','2025-11-22 04:30:47','2025-11-22 04:30:47'),(44,'DN-OPT7ER',4,197,25,'2025-11-24','[{\"color\":\"black\",\"sole_color\":\"black\",\"sizes\":{\"35\":{\"ordered\":20,\"available\":35,\"delivered\":15},\"36\":{\"ordered\":20,\"available\":40,\"delivered\":10}},\"source\":\"quotation\"}]','2025-11-23 23:46:29','2025-11-23 23:46:29'),(45,'DN-FTJPWN',5,196,10,'2025-11-24','[{\"color\":\"black\",\"sole_color\":\"peach\",\"sizes\":{\"35\":{\"ordered\":45,\"available\":50,\"delivered\":5},\"36\":{\"ordered\":50,\"available\":50,\"delivered\":5}},\"source\":\"quotation\"}]','2025-11-24 00:30:01','2025-11-24 00:30:01');
/*!40000 ALTER TABLE `delivery_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_batch`
--

DROP TABLE IF EXISTS `employee_batch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_batch` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `employee_id` bigint(20) unsigned NOT NULL,
  `process_id` bigint(20) unsigned NOT NULL,
  `quantities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quantities`)),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `labor_rate` decimal(8,2) NOT NULL DEFAULT 0.00,
  `labor_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `advance_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_batch_batch_id_employee_id_process_id_unique` (`batch_id`,`employee_id`,`process_id`),
  KEY `employee_batch_employee_id_foreign` (`employee_id`),
  CONSTRAINT `employee_batch_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_batch_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_batch`
--

LOCK TABLES `employee_batch` WRITE;
/*!40000 ALTER TABLE `employee_batch` DISABLE KEYS */;
INSERT INTO `employee_batch` VALUES (1,2,2,1,'[{\"35\":20,\"36\":50,\"37\":50,\"38\":0,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',120,0.00,'in_progress','2025-11-22 07:25:28','2025-11-24 05:25:25','2025-11-23',NULL,0.00,0.00),(2,4,2,1,NULL,80,55.00,'paid','2025-11-22 07:49:39','2025-11-24 02:53:39','2025-11-23',NULL,0.00,4400.00),(3,4,3,2,NULL,45,45.00,'paid','2025-11-22 07:49:39','2025-11-24 02:53:30','2025-11-23',NULL,0.00,2025.00),(4,4,1,3,'[{\"35\":15,\"36\":15,\"37\":15,\"38\":0,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',45,25.00,'paid','2025-11-23 23:34:39','2025-11-24 02:53:18','2025-11-18',NULL,0.00,1125.00),(5,5,2,1,'[{\"35\":25,\"36\":25,\"37\":25,\"38\":25,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',100,0.00,'completed','2025-11-23 23:58:28','2025-11-24 00:17:17','2025-11-25',NULL,0.00,0.00),(6,5,3,2,'[{\"35\":20,\"36\":20,\"37\":20,\"38\":20,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',80,0.00,'completed','2025-11-23 23:58:28','2025-11-24 00:17:17','2025-11-25',NULL,0.00,0.00),(7,5,1,3,'[{\"35\":10,\"36\":10,\"37\":10,\"38\":0,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',30,0.00,'completed','2025-11-24 00:05:39','2025-11-24 00:23:06',NULL,NULL,0.00,0.00),(8,5,6,3,'[{\"35\":0,\"36\":0,\"37\":40,\"38\":50,\"39\":50,\"40\":50,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',190,0.00,'completed','2025-11-24 05:24:11','2025-11-24 05:24:31',NULL,NULL,0.00,0.00),(9,2,6,3,'[{\"35\":20,\"36\":50,\"37\":75,\"38\":0,\"39\":0,\"40\":0,\"41\":0,\"42\":0,\"43\":0,\"44\":0}]',145,25.00,'completed','2025-11-24 05:25:25','2025-11-24 05:26:11','2025-11-25',NULL,0.00,0.00);
/*!40000 ALTER TABLE `employee_batch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `employee_type` varchar(255) DEFAULT NULL,
  `labor_type` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `employee_commission` decimal(5,2) DEFAULT NULL,
  `salary_basis` varchar(255) DEFAULT NULL,
  `labor_amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'SAR',
  `hire_date` date NOT NULL DEFAULT curdate(),
  `phone` varchar(20) DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `igama_national_id` varchar(10) DEFAULT NULL,
  `personal_email` varchar(255) DEFAULT NULL,
  `present_address_line1` varchar(255) DEFAULT NULL,
  `present_address_line2` varchar(255) DEFAULT NULL,
  `present_city` varchar(100) DEFAULT NULL,
  `present_address_arabic_line1` varchar(255) DEFAULT NULL,
  `present_address_arabic_line2` varchar(255) DEFAULT NULL,
  `present_city_arabic` varchar(100) DEFAULT NULL,
  `permanent_address_line1` varchar(255) DEFAULT NULL,
  `permanent_state` varchar(100) DEFAULT NULL,
  `permanent_pin_code` varchar(10) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `personal_documents` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  UNIQUE KEY `employees_email_unique` (`email`),
  UNIQUE KEY `employees_personal_email_unique` (`personal_email`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'EMP-1125-001','SALMAN',NULL,'Finish Man',NULL,'Labor',NULL,'Finish Man',0.00,NULL,NULL,NULL,'SAR','2025-10-31',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(2,'EMP-1125-002','Karim',NULL,'UPPAR MAN',NULL,'Labor',NULL,'UPPAR MAN',0.00,NULL,NULL,NULL,'SAR','2025-10-31',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(3,'EMP-1125-003','RUKHSAD',NULL,'Bottom Man',NULL,'Labor',NULL,'Bottom Man',0.00,NULL,NULL,NULL,'SAR','2025-10-31',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(4,'EMP-1125-004','Suraj',NULL,'Upper Man',NULL,'Employee',NULL,'Upper Man',0.00,NULL,NULL,NULL,'SAR','2025-11-06',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(5,'EMP-1125-005','Khan',NULL,'Bottom Part',NULL,'Labor',NULL,'Bottom Part',0.00,NULL,NULL,NULL,'SAR','2025-11-06',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(6,'EMP-1125-006','Kiran',NULL,'Finish Men',NULL,'Labor',NULL,'Finish Men',0.00,NULL,NULL,NULL,'SAR','2025-11-06',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(7,'EMP-1125-007','ravi',NULL,'worker',NULL,'Labor',NULL,'worker',0.00,NULL,NULL,NULL,'SAR','2025-11-06',NULL,NULL,'2025-11-22 07:25:00','2025-11-22 07:25:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,'cash',NULL,'active'),(8,'EMP-1125-008','Rahul','rahul@gmail.com','head of labors','production','Manager',NULL,NULL,25000.00,NULL,NULL,NULL,'INR','2025-11-24','8877559988','4444445578','2025-11-24 07:11:06','2025-11-24 07:11:06',198,NULL,NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active'),(9,'EMP-1125-009','rohan',NULL,'worker','production','Labor',NULL,NULL,NULL,NULL,'pieces',NULL,'INR','2025-11-24','5555554444','1155447788','2025-11-24 07:26:18','2025-11-24 07:35:50',NULL,'2000-02-02',NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,'cash','[{\"name\":\"Reprint_3e7a566fccd2b582f81314a14dfd936808dabe86d196976daa518a48502ce449.pdf\",\"path\":\"personal_documents\\/1763988978_Reprint_3e7a566fccd2b582f81314a14dfd936808dabe86d196976daa518a48502ce449.pdf\"},{\"name\":\"mob-1.png\",\"path\":\"personal_documents\\/1763989550_mob-1.png\"}]','active'),(10,'EMP-1125-010','kartik',NULL,'worker','production','Labor',NULL,'BOTTOM MAN',NULL,NULL,'pieces',NULL,'INR','2025-11-25',NULL,NULL,'2025-11-25 00:07:32','2025-11-25 00:07:32',NULL,'2001-11-12',NULL,NULL,NULL,NULL,NULL,'India',NULL,NULL,NULL,NULL,NULL,NULL,'cash',NULL,'active');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exit_entry_requests`
--

DROP TABLE IF EXISTS `exit_entry_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exit_entry_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `exit_date` date NOT NULL,
  `re_entry_date` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exit_entry_requests_employee_id_foreign` (`employee_id`),
  CONSTRAINT `exit_entry_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exit_entry_requests`
--

LOCK TABLES `exit_entry_requests` WRITE;
/*!40000 ALTER TABLE `exit_entry_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `exit_entry_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_claims`
--

DROP TABLE IF EXISTS `expense_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `expense_date` date NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_claims_employee_id_foreign` (`employee_id`),
  KEY `expense_claims_manager_id_foreign` (`manager_id`),
  CONSTRAINT `expense_claims_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expense_claims_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_claims`
--

LOCK TABLES `expense_claims` WRITE;
/*!40000 ALTER TABLE `expense_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_id_warehouse_id_unique` (`product_id`,`warehouse_id`),
  KEY `inventory_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `inventory_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_transfers`
--

DROP TABLE IF EXISTS `inventory_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `from_warehouse_id` bigint(20) unsigned NOT NULL,
  `to_warehouse_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `transfer_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_transfers_product_id_foreign` (`product_id`),
  KEY `inventory_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  KEY `inventory_transfers_to_warehouse_id_foreign` (`to_warehouse_id`),
  CONSTRAINT `inventory_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transfers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transfers`
--

LOCK TABLES `inventory_transfers` WRITE;
/*!40000 ALTER TABLE `inventory_transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_product`
--

DROP TABLE IF EXISTS `invoice_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_product_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_product_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_product_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_product`
--

LOCK TABLES `invoice_product` WRITE;
/*!40000 ALTER TABLE `invoice_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'order',
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `payment_type` varchar(255) NOT NULL,
  `grace_period` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  KEY `invoices_quotation_id_foreign` (`quotation_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `production_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,NULL,NULL,'order',1,1,197,6000.00,0.00,'\"[{\\\"product_id\\\":2,\\\"name\\\":\\\"boots\\\",\\\"quantity\\\":50,\\\"unit_price\\\":\\\"120.00\\\",\\\"total\\\":6000,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"sizes\\\\\\\":{\\\\\\\"35\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"36\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"37\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"38\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"39\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"40\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"41\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"42\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"43\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"44\\\\\\\":\\\\\\\"0\\\\\\\"},\\\\\\\"images\\\\\\\":[],\\\\\\\"main_image\\\\\\\":\\\\\\\"products\\\\\\\\\\\\\\/variations\\\\\\\\\\\\\\/1762753314_WhatsApp Image 2025-10-12 at 21.54.08.jpeg\\\\\\\"}]\\\"}]\"','grace',35,'2025-12-27','pending',0,'2025-11-22 06:54:55','2025-11-22 06:54:55'),(2,NULL,'575487','order',3,2,199,7500.00,2500.00,'\"[{\\\"product_id\\\":3,\\\"name\\\":\\\"Sneakers-premium\\\",\\\"quantity\\\":50,\\\"unit_price\\\":\\\"150.00\\\",\\\"total\\\":7500,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"sizes\\\\\\\":{\\\\\\\"35\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"36\\\\\\\":\\\\\\\"2\\\\\\\",\\\\\\\"37\\\\\\\":\\\\\\\"50\\\\\\\",\\\\\\\"38\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"39\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"40\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"41\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"42\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"43\\\\\\\":\\\\\\\"0\\\\\\\",\\\\\\\"44\\\\\\\":\\\\\\\"0\\\\\\\"},\\\\\\\"images\\\\\\\":[],\\\\\\\"main_image\\\\\\\":\\\\\\\"products\\\\\\\\\\\\\\/variations\\\\\\\\\\\\\\/1762753798_WhatsApp Image 2025-09-09 at 11.00.25 (3).jpeg\\\\\\\"}]\\\"}]\"','grace',90,'2026-02-23','partially_paid',0,'2025-11-25 00:24:09','2025-11-25 00:27:46');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_balances`
--

DROP TABLE IF EXISTS `leave_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_balances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `leave_type` varchar(255) NOT NULL DEFAULT 'annual',
  `total_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `used_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `remaining_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `year` year(4) NOT NULL DEFAULT 2025,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_balances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `leave_balances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_balances`
--

LOCK TABLES `leave_balances` WRITE;
/*!40000 ALTER TABLE `leave_balances` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_balances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `leave_type` varchar(255) NOT NULL DEFAULT 'annual',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `manager_comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_employee_id_foreign` (`employee_id`),
  KEY `leave_requests_manager_id_foreign` (`manager_id`),
  CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_requests`
--

LOCK TABLES `leave_requests` WRITE;
/*!40000 ALTER TABLE `leave_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `liquid_materials`
--

DROP TABLE IF EXISTS `liquid_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liquid_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `per_unit_volume` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liquid_materials_product_id_foreign` (`product_id`),
  CONSTRAINT `liquid_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liquid_materials`
--

LOCK TABLES `liquid_materials` WRITE;
/*!40000 ALTER TABLE `liquid_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `liquid_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_24_072107_create_products_table',1),(5,'2025_05_24_075023_create_transactions_table',1),(6,'2025_05_24_084541_create_employees_table',1),(7,'2025_05_24_084615_create_payrolls_table',1),(8,'2025_05_24_091422_add_tax_fields_to_transactions_table',1),(9,'2025_05_24_091500_add_tax_fields_to_payrolls_table',1),(10,'2025_05_24_093343_add_tax_rate_to_transactions_and_payrolls',1),(11,'2025_05_24_095031_add_currency_to_employees',1),(12,'2025_05_24_095511_add_tax_fields_to_products',1),(13,'2025_05_24_101800_create_settings_table',1),(14,'2025_05_24_104921_create_sales_table',1),(15,'2025_05_24_105219_create_attendances_table',1),(16,'2025_05_24_105537_create_warehouses_table',1),(17,'2025_05_24_105616_modify_products_table_for_warehouses',1),(18,'2025_05_24_105645_create_product_warehouse_table',1),(19,'2025_05_24_105739_create_stock_adjustments_table',1),(20,'2025_05_24_105819_create_inventory_transfers_table',1),(21,'2025_05_24_105948_add_warehouse_id_to_sales_table',1),(22,'2025_05_24_112653_add_total_amount_to_products_table',1),(23,'2025_05_24_114347_create_permission_tables',1),(24,'2025_05_24_122513_create_activity_log_table',1),(25,'2025_05_24_122514_add_event_column_to_activity_log_table',1),(26,'2025_05_24_122515_add_batch_uuid_column_to_activity_log_table',1),(27,'2025_05_26_055134_create_notifications_table',1),(28,'2025_05_26_065750_make_position_nullable_in_employees_table',1),(29,'2025_05_26_065826_make_amount_nullable_in_payrolls_table',1),(30,'2025_05_26_071116_add_discount_to_sales_table',1),(31,'2025_05_26_071139_create_inventory_table',1),(32,'2025_05_26_071617_add_unit_price_to_products_table',1),(33,'2025_05_26_090849_add_profile_picture_to_users_table',1),(34,'2025_05_26_092421_create_leave_requests_table',1),(35,'2025_05_26_092440_create_advance_salary_requests_table',1),(36,'2025_05_26_092459_add_manager_id_and_is_remote_to_users_table',1),(37,'2025_05_26_105038_add_manager_id_to_users_table',1),(38,'2025_05_26_110311_add_user_id_to_employees_table',1),(39,'2025_05_26_111929_rename_advance_salary_requests_to_salary_advance_requests',1),(40,'2025_05_26_114640_make_manager_id_nullable_in_leave_requests_and_salary_advance_requests',1),(41,'2025_05_26_121342_add_force_password_change_to_users_table',1),(42,'2025_05_26_125004_create_quotations_table',1),(43,'2025_05_26_125046_create_orders_table',1),(44,'2025_05_26_125121_create_invoices_table',1),(45,'2025_05_29_103243_add_phone_and_address_to_users_table',1),(46,'2025_05_29_103531_add_fields_to_quotations_table',1),(47,'2025_05_29_132610_add_client_id_to_quotations_table',1),(48,'2025_05_29_132820_create_product_quotation_table',1),(49,'2025_05_29_133538_make_salesperson_id_nullable_in_quotations_table',1),(50,'2025_05_29_144311_create_production_orders_table',1),(51,'2025_05_31_064052_add_items_to_invoices_table',1),(52,'2025_05_31_065344_fix_invoices_order_id_foreign_key',1),(53,'2025_05_31_082407_create_warning_letters_table',1),(54,'2025_05_31_083226_add_saudi_fields_to_users_table',1),(55,'2025_05_31_083705_add_status_to_attendances_table',1),(56,'2025_05_31_084444_create_exit_entry_requests_table',1),(57,'2025_06_01_063214_remove_region_from_users_table',1),(58,'2025_06_01_071726_add_country_to_users_table',1),(59,'2025_06_01_093227_update_exit_entry_requests_table_employee_id_foreign_key',1),(60,'2025_06_02_122923_add_status_and_category_to_transactions_table',1),(61,'2025_06_14_050114_add_amount_paid_to_invoices_table',1),(62,'2025_06_14_050141_create_payments_table',1),(63,'2025_06_14_060540_create_leave_balances_table',1),(64,'2025_06_14_060631_add_leave_type_to_leave_requests_table',1),(65,'2025_06_14_060900_create_expense_claims_table',1),(66,'2025_06_14_060943_create_training_requests_table',1),(67,'2025_06_14_061751_create_performance_reviews_table',1),(68,'2025_06_16_081640_add_approval_fields_to_payrolls_table',1),(69,'2025_06_25_071345_add_new_fields_to_employees_table',1),(70,'2025_06_25_071815_drop_address_from_employees_table',1),(71,'2025_06_25_072048_add_phone_and_emergency_contact_to_employees_table',1),(72,'2025_08_14_104517_add_image_to_products_table',1),(73,'2025_08_16_062448_create_raw_materials_table',1),(74,'2025_08_16_062534_add_stage_to_production_orders_table',1),(75,'2025_08_16_063348_add_due_date_to_production_orders_table',1),(76,'2025_08_16_075058_create_production_processes_table',1),(77,'2025_08_17_040349_create_supply_chain_stages_table',1),(78,'2025_08_17_041727_create_processes_table',1),(79,'2025_08_18_054619_add_client_fields_to_users_table',1),(80,'2025_08_18_085926_create_orders_table',1),(81,'2025_08_18_094723_create_clients_table',1),(82,'2025_08_18_095309_create_order_product_table',1),(83,'2025_08_18_123538_add_variations_to_products_table',1),(84,'2025_08_19_095823_make_quotation_id_nullable_in_production_orders_table',1),(85,'2025_08_19_100603_add_client_order_id_to_production_orders_table',1),(86,'2025_08_19_121609_add_company_fields_to_users_table',1),(87,'2025_08_20_062326_create_production_stages_table',1),(88,'2025_08_20_065510_create_workers_table',1),(89,'2025_08_21_043611_add_total_quantity_to_products_table',1),(90,'2025_08_21_045956_add_product_id_to_production_processes_table',1),(91,'2025_08_21_051411_add_unit_to_raw_materials_table',1),(92,'2025_08_21_052520_add_product_id_to_raw_materials_table',1),(93,'2025_08_21_052700_create_liquid_materials_table',1),(94,'2025_08_21_063901_add_unique_constraint_to_batch_no_in_batches_table',1),(95,'2025_08_21_065550_add_priority_to_batches_table',1),(96,'2025_08_21_065650_add_created_by_to_batches_table',1),(97,'2025_08_21_071358_create_batch_employee_table',1),(98,'2025_08_21_115543_add_customer_name_to_orders_table',1),(99,'2025_08_22_063411_add_client_id_to_orders_table',1),(100,'2025_08_23_055504_add_client_fields_to_users_table',1),(101,'2025_08_24_120251_add_transport_fields_to_orders_table',1),(102,'2025_08_24_120417_make_address_nullable_in_orders_table',1),(103,'2025_08_24_131427_add_status_to_users_table',1),(104,'2025_08_25_075810_add_price_and_image_to_cart_items_table',1),(105,'2025_08_25_102211_add_business_fields_to_users_table',1),(106,'2025_08_25_102546_create_dashboard_cards_table',1),(107,'2025_08_25_102851_add_amount_fields_to_orders_table',1),(108,'2025_08_25_103143_add_category_to_products_table',1),(109,'2025_08_25_113604_add_salesperson_id_to_quotations_table',1),(110,'2025_08_25_123838_create_cart_items_table',1),(111,'2025_08_25_124156_add_user_id_to_orders_table',1),(112,'2025_08_25_132214_update_orders_client_id_foreign',1),(113,'2025_08_26_110528_add_company_fields_to_users_table',1),(114,'2025_08_26_125141_create_support_tickets_table',1),(115,'2025_08_28_051032_add_company_fields_to_orders_table',1),(116,'2025_08_29_052433_add_read_at_to_orders_table',1),(117,'2025_08_30_094321_add_paid_amount_to_orders_table',1),(118,'2025_09_01_080504_add_seen_onboarding_to_users_table',1),(119,'2025_09_02_133325_add_transport_phone_to_orders_table',1),(120,'2025_09_04_095905_add_seen_onboarding_to_users_table',2),(121,'2025_09_04_140646_add_sole_columns_to_products_table',2),(122,'2025_09_08_111700_add_po_no_to_orders_table',2),(123,'2025_09_08_121312_add_payment_status_to_orders_table',2),(124,'2025_09_08_130105_add_hsn_code_to_products_table',2),(125,'2025_09_08_131326_add_soles_to_products_table',2),(126,'2025_09_09_050936_add_role_to_users_table',2),(127,'2025_09_09_054452_add_added_by_offline_to_products_table',2),(128,'2025_09_09_132935_create_batches_table',2),(129,'2025_09_09_133037_create_batch_flows_table',2),(130,'2025_09_09_133429_create_daily_batch_counters_table',2),(131,'2025_09_10_044806_add_production_cost_and_profit_to_products_table',2),(132,'2025_09_10_055743_add_labor_fields_to_employees_table',2),(133,'2025_09_10_063529_make_email_nullable_in_employees_table',2),(134,'2025_09_10_063619_make_salary_nullable_in_employees_table',2),(135,'2025_09_10_072618_add_role_to_employees_table',2),(136,'2025_09_10_073411_add_employee_id_to_employees_table',2),(137,'2025_09_10_090616_add_labor_rate_to_production_processes_table',2),(138,'2025_09_10_110402_add_labor_assignments_to_batches_table',2),(139,'2025_09_10_110630_add_fields_to_batches_table',2),(140,'2025_09_10_111800_create_employee_batch_table',2),(141,'2025_09_10_132543_make_batch_id_nullable_in_production_processes_table',3),(142,'2025_09_10_140245_add_labor_status_to_employee_batch_table',3),(143,'2025_09_11_054143_add_start_end_date_to_employee_batch_table',3),(144,'2025_09_11_103918_add_process_order_to_production_processes_table',4),(145,'2025_09_11_115436_create_salary_advances_table',4),(146,'2025_09_12_052354_add_quantities_to_production_processes_table',4),(147,'2025_09_12_052729_add_process_order_to_production_processes_table',4),(148,'2025_09_12_054600_add_price_to_raw_materials_table',4),(149,'2025_09_12_065359_create_soles_table',4),(150,'2025_09_12_080522_add_per_unit_length_to_raw_materials_table',4),(151,'2025_09_12_081624_update_liquid_materials_table',4),(152,'2025_09_12_083540_add_po_no_to_batches_table',4),(153,'2025_09_12_083617_add_variations_to_batches_table',4),(154,'2025_09_12_101203_add_labor_type_to_employees_table',4),(155,'2025_09_12_112802_add_employee_type_fields_to_employees_table',4),(156,'2025_09_12_131933_update_hire_date_default_on_employees_table',4),(157,'2025_09_13_045804_create_sales_commissions_table',4),(158,'2025_09_13_055559_add_order_id_to_sales_commissions_table',5),(159,'2025_09_13_061015_make_quotation_id_nullable_in_orders_table',5),(160,'2025_09_13_104106_add_sizes_qty_to_soles_table',6),(161,'2025_09_15_052638_add_color_to_raw_materials_table',7),(162,'2025_09_15_053157_add_selling_price_to_soles_table',8),(163,'2025_09_15_053241_add_columns_to_liquid_materials_table',9),(164,'2025_09_16_103423_add_sales_rep_id_to_users_table',10),(165,'2025_09_16_124142_make_warehouse_id_nullable_in_quotations_table',11),(166,'2025_09_17_044135_add_commission_to_products_table',12),(167,'2025_09_19_080154_make_product_id_nullable_in_soles_and_raw_materials',13),(168,'2025_09_19_092857_update_status_enum_in_quotations_table',14),(169,'2025_09_19_103323_add_quotation_no_to_quotations_table',15),(170,'2025_09_19_104109_add_variations_to_product_quotation_table',16),(171,'2025_09_19_122132_add_tax_type_to_quotations_table',17),(172,'2025_09_19_122319_add_notes_to_quotations_table',18),(173,'2025_09_19_131321_add_quotation_id_to_invoices_table',19),(174,'2025_09_19_131440_make_order_id_nullable_in_invoices_table',20),(175,'2025_09_19_131455_add_quotation_id_to_invoices_table',20),(176,'2025_09_19_131844_create_invoice_product_table',21),(177,'2025_09_20_071125_add_type_to_invoices_table',22),(178,'2025_09_20_104812_create_stocks_table',23),(179,'2025_09_22_045330_create_stock_movements_table',24),(180,'2025_09_24_050113_add_tax_columns_to_orders_table',25),(181,'2025_09_25_045711_create_product_sole_table',26),(182,'2025_09_25_063622_add_quantity_used_to_product_sole_table',27),(183,'2025_09_26_074214_create_worker_payrolls_table',28),(184,'2025_09_27_055834_add_advance_paid_to_employee_batches_table',29),(185,'2025_09_29_044747_create_advance_deductions_table',30),(186,'2025_09_29_050026_add_used_amount_to_salary_advances_table',31),(187,'2025_09_29_081213_create_product_processes_table',32),(188,'2025_09_29_093800_create_production_order_product_table',33),(189,'2025_09_29_093924_add_price_to_production_order_product_table',34),(190,'2025_09_30_093836_make_email_nullable_in_users_table',35),(191,'2025_09_30_094505_make_email_nullable_in_clients_table',36),(192,'2025_09_30_114214_create_product_liquid_material_table',37),(193,'2025_10_01_041450_add_unit_price_to_production_order_product_table',38),(194,'2025_10_01_041653_add_variations_to_production_order_product_table',39),(195,'2025_10_06_074846_add_process_order_to_product_processes_table',40),(196,'2025_10_06_075244_add_process_order_to_product_processes_table',41),(197,'2025_10_06_075405_add_quantity_to_product_processes_table',42),(198,'2025_10_06_111006_add_dashboard_cards_to_users_table',43),(199,'2025_10_06_113627_add_custom_card_labels_to_users_table',44),(200,'2025_10_07_065757_add_unit_price_to_order_product_table',45),(201,'2025_10_07_065927_add_variations_to_order_product_table',46),(202,'2025_10_08_091553_create_suppliers_table',47),(203,'2025_10_08_091636_add_supplier_id_to_orders_table',48),(204,'2025_10_08_100423_create_supplier_orders_table',49),(205,'2025_10_08_120602_add_business_name_to_suppliers_table',50),(206,'2025_10_09_071455_update_raw_and_liquid_materials_and_stocks',51),(207,'2025_10_11_124157_make_sole_type_nullable_in_soles_table',52),(208,'2025_10_11_141312_add_stock_deducted_to_batches_table',53),(209,'2025_10_12_081325_add_columns_to_stock_arrivals_table',54),(210,'2025_10_12_091301_add_received_at_to_stock_arrivals_table',55),(211,'2025_10_13_045846_add_available_qty_to_soles_table',56),(212,'2025_10_16_132556_add_description_to_stock_movements_table',57),(213,'2025_10_18_072618_add_status_to_employees_table',58),(214,'2025_10_18_075924_add_calculated_fields_to_raw_materials_table',59),(215,'2025_10_21_070606_add_quantities_to_employee_batch_table',59),(216,'2025_10_28_111231_add_deleted_at_to_stocks_table',60),(217,'2025_10_30_052738_create_batch_client_table',61),(218,'2025_10_30_053512_remove_client_id_from_batches_table',62),(219,'2025_10_30_081854_create_quotation_client_table',63),(220,'2025_11_05_125918_create_delivery_notes_table',64),(221,'2025_11_07_034028_add_client_and_assigned_qty_to_delivery_notes_table',65),(222,'2025_11_07_040159_update_delivery_notes_client_fk_to_users_table',66),(223,'2025_11_08_053045_add_po_no_to_invoices_table',67),(224,'2025_11_10_073858_create_bank_details_table',68),(225,'2025_11_11_070315_add_is_synced_to_invoices_table',69),(226,'2025_11_15_102403_add_online_id_to_users_table',70),(227,'2025_11_18_054416_add_brand_name_to_quotations_table',71),(228,'2025_11_18_055810_add_grace_period_to_invoices_table',72),(229,'2025_10_12_000000_create_stock_arrivals_table',73),(230,'2025_11_20_000000_add_real_sizes_qty_to_soles_table',73),(231,'2025_11_20_000001_fix_liquid_materials_column',73),(232,'2025_11_20_000002_rename_per_unit_length_to_per_unit_volume',73),(233,'2025_11_20_131019_add_supplier_order_id_to_stock_arrivals_table',73),(234,'2025_11_20_132051_add_brand_to_quotations_table',73),(235,'2025_11_22_045036_create_supplier_returns_table',73),(236,'2025_11_24_103825_add_reference_to_transactions',74);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(4,'App\\Models\\User',4),(10,'App\\Models\\User',3),(10,'App\\Models\\User',6),(10,'App\\Models\\User',8),(10,'App\\Models\\User',51),(10,'App\\Models\\User',53),(11,'App\\Models\\User',52),(11,'App\\Models\\User',74),(11,'App\\Models\\User',75),(11,'App\\Models\\User',76),(11,'App\\Models\\User',77),(11,'App\\Models\\User',78),(11,'App\\Models\\User',80),(11,'App\\Models\\User',92),(11,'App\\Models\\User',198),(13,'App\\Models\\User',10),(13,'App\\Models\\User',11),(13,'App\\Models\\User',14),(16,'App\\Models\\User',13),(16,'App\\Models\\User',14);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('02688231-cb9a-4159-9508-25c22c42ac54','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:00:31','2025-09-24 04:00:31'),('0309f601-48f3-4f8c-afca-01d0934c48b2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #25 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/25\",\"status\":\"pending\"}',NULL,'2025-09-30 05:00:29','2025-09-30 05:00:29'),('03762aa7-b487-446b-830e-16a3aaa048b8','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #50 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/50\",\"status\":\"pending\"}',NULL,'2025-09-30 07:30:39','2025-09-30 07:30:39'),('03f3a785-26d5-41eb-8e74-d271d02ea55a','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #23 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/23\",\"status\":\"pending\"}',NULL,'2025-09-30 04:51:40','2025-09-30 04:51:40'),('07cae0e2-9396-4f35-98af-273b8a10ac8b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:02:08','2025-09-24 04:02:08'),('0834e06c-ef7c-4f76-a369-53eedfa327ec','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #38 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/38\",\"status\":\"accepted\"}',NULL,'2025-09-26 00:43:18','2025-09-26 00:43:18'),('0871238d-9576-4a08-b34b-2acd8b999ad5','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #9 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/9\",\"status\":\"pending\"}',NULL,'2025-09-29 07:13:51','2025-09-29 07:13:51'),('09f0252e-4cdf-4676-b2b8-4fc9fa03e5c6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #49 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/49\",\"status\":\"pending\"}',NULL,'2025-09-30 07:25:04','2025-09-30 07:25:04'),('0b1ebdc1-9a7e-47c5-acb4-1f4fa9fd9eb3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-10-03 06:02:13','2025-10-03 06:02:13'),('0c886bc2-e7ac-43da-8353-fbb8e3fa6471','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #38 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/38\",\"status\":\"accepted\"}',NULL,'2025-09-25 03:04:08','2025-09-25 03:04:08'),('0d7f36a2-ec39-4898-afb0-d75a0717a14f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',44,'{\"message\":\"Your order #2 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/2\",\"status\":\"pending\"}',NULL,'2025-10-09 06:20:20','2025-10-09 06:20:20'),('107ffa85-17ce-43e9-8b00-5a46be77c98d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #44 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/44\",\"status\":\"pending\"}',NULL,'2025-09-30 06:53:11','2025-09-30 06:53:11'),('129689a8-fdb8-4785-9736-744c2b680e89','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #24 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/24\",\"status\":\"accepted\"}',NULL,'2025-09-24 01:55:25','2025-09-24 01:55:25'),('13c2859b-2eca-4562-ae1e-6cca541adb76','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #2 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/2\",\"status\":\"pending\"}',NULL,'2025-09-29 05:59:09','2025-09-29 05:59:09'),('145a959c-f94c-408e-95d8-0d18d55e593a','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #21 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/21\",\"status\":\"pending\"}',NULL,'2025-09-30 04:41:15','2025-09-30 04:41:15'),('14825dc8-2eec-4549-8489-7dbfcb6ae049','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #7 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/7\",\"status\":\"pending\"}',NULL,'2025-09-29 07:08:31','2025-09-29 07:08:31'),('16527359-0db7-403b-bdbb-b230a7775e69','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #22 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/22\",\"status\":\"accepted\"}',NULL,'2025-09-24 01:42:02','2025-09-24 01:42:02'),('18916a2b-8e69-4aca-ad56-7188937bf4f7','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #28 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/28\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:40:19','2025-09-24 03:40:19'),('19ff38f2-ca3b-4404-abd0-d5193a128017','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #5 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/5\",\"status\":\"pending\"}',NULL,'2025-09-29 07:04:03','2025-09-29 07:04:03'),('1a8c337f-aee3-4814-aae0-151d3973c81c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #5 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/5\",\"status\":\"pending\"}',NULL,'2025-09-29 07:04:12','2025-09-29 07:04:12'),('1d494b91-15ee-4805-b074-e92be237bc84','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #7 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/7\",\"status\":\"pending\"}',NULL,'2025-10-05 23:37:14','2025-10-05 23:37:14'),('1d8a19ee-a644-4baa-bff5-25551337f0e2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-09-29 05:54:49','2025-09-29 05:54:49'),('1e858b39-5b6c-43a8-b866-f2b16a561889','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #8 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/8\",\"status\":\"pending\"}',NULL,'2025-09-29 07:10:48','2025-09-29 07:10:48'),('1ff333f7-611a-46a4-953f-bc4d0ed7fc23','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #68 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/68\",\"status\":\"pending\"}',NULL,'2025-09-30 22:07:41','2025-09-30 22:07:41'),('24785928-e67a-4bea-aeaa-c1d84f942c69','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #32 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/32\",\"status\":\"accepted\"}',NULL,'2025-09-25 00:00:42','2025-09-25 00:00:42'),('2499785e-bf20-4c1c-bb79-3136f0290040','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',20,'{\"message\":\"Your order #35 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/35\",\"status\":\"pending\"}',NULL,'2025-09-30 05:57:43','2025-09-30 05:57:43'),('255baa16-a767-49bd-a45d-848dd9ded25e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:28:03','2025-09-24 02:28:03'),('2577d7aa-048e-4084-904b-f9cb22992935','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:15:11','2025-09-24 04:15:11'),('28deb3e9-3af4-4e23-8498-78c18451c102','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #33 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/33\",\"status\":\"pending\"}',NULL,'2025-09-30 05:54:09','2025-09-30 05:54:09'),('2a418670-62ab-4498-8370-e4a85d913964','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #26 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/26\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:07:13','2025-09-24 02:07:13'),('2b2261c6-2b82-467f-8c9a-f27909d0d646','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #53 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/53\",\"status\":\"pending\"}',NULL,'2025-09-30 07:37:33','2025-09-30 07:37:33'),('2b5eea8e-53d2-4206-82a4-465ff9db0365','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #31 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/31\",\"status\":\"pending\"}',NULL,'2025-09-30 05:36:50','2025-09-30 05:36:50'),('2bce71a6-b5ba-4b83-8f07-4d32250c809e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #6 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/6\",\"status\":\"pending\"}',NULL,'2025-09-29 07:05:39','2025-09-29 07:05:39'),('2be533e5-604a-4313-8310-00bd3bfca0e8','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #59 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/59\",\"status\":\"pending\"}',NULL,'2025-09-30 21:37:29','2025-09-30 21:37:29'),('2d35ed20-cf77-4a05-8f33-3e35a6af2e07','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #40 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/40\",\"status\":\"accepted\"}',NULL,'2025-09-26 00:43:30','2025-09-26 00:43:30'),('2dcb617a-5796-462c-b452-c5a03906a209','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #62 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/62\",\"status\":\"pending\"}',NULL,'2025-09-30 21:44:49','2025-09-30 21:44:49'),('2f6e8ffd-7812-4857-962b-f1fe71840146','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #14 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/14\",\"status\":\"pending\"}',NULL,'2025-10-06 00:43:56','2025-10-06 00:43:56'),('301f4e5a-ec8a-43c2-947a-fd0b3f966e7d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #27 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/27\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:16:21','2025-09-24 02:16:21'),('301f6c83-fe90-401e-a40a-cbfd9223bf5e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #41 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/41\",\"status\":\"accepted\"}',NULL,'2025-09-26 00:49:56','2025-09-26 00:49:56'),('31696393-7812-45fd-af5a-66e4c316a65c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #35 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/35\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:28:44','2025-09-24 04:28:44'),('31815280-7645-48d8-805f-7f0f8f43e8e6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #39 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/39\",\"status\":\"accepted\"}',NULL,'2025-09-26 00:40:47','2025-09-26 00:40:47'),('36f30372-5f46-468b-bb9f-694f77fc4e5b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #25 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/25\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:04:06','2025-09-24 02:04:06'),('370dc69b-b974-4331-a721-d60a57a8a16d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',19,'{\"message\":\"Your order #48 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/48\",\"status\":\"pending\"}',NULL,'2025-09-30 07:16:18','2025-09-30 07:16:18'),('37547a2d-1e4e-4b01-ab52-65743a9c0423','App\\Notifications\\TestNotification','App\\Models\\User',2,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-09-16 02:14:59','2025-09-16 02:14:59'),('39a4b2a7-45b7-4837-9632-80f622800950','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #14 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/14\",\"status\":\"pending\"}',NULL,'2025-09-29 22:42:47','2025-09-29 22:42:47'),('39a5bd04-3eba-4a0c-b0e6-b3bf4ac9096f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #24 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/24\",\"status\":\"pending\"}',NULL,'2025-09-30 04:53:59','2025-09-30 04:53:59'),('3a1b6283-6aa2-4ecf-90fb-3a461593fe48','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #22 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/22\",\"status\":\"accepted\"}',NULL,'2025-09-24 01:41:34','2025-09-24 01:41:34'),('3af3041a-e71b-480a-ab1a-6658a0d5beb2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',99,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-11-04 23:35:37','2025-11-04 23:35:37'),('3d0028ca-ea91-408b-92bc-ca6960190c11','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:13:50','2025-09-24 04:13:50'),('3d9ca347-3960-41d5-85c9-6bcf5c122d38','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #16 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/16\",\"status\":\"accepted\"}',NULL,'2025-09-24 01:42:38','2025-09-24 01:42:38'),('3dabfc4b-9492-4628-af0a-146af1c5c929','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #33 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/33\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:42:46','2025-09-24 03:42:46'),('3dc1071d-8e5b-406b-a2de-bcff23cc9421','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #26 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/26\",\"status\":\"pending\"}',NULL,'2025-09-30 05:05:44','2025-09-30 05:05:44'),('3dc5065d-7e49-4a84-9fd3-8aef7608d7c2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #3 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/3\",\"status\":\"pending\"}',NULL,'2025-10-03 07:08:47','2025-10-03 07:08:47'),('40cd036a-cc8f-4b41-9de2-54e84b2684ed','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #28 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/28\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:20:44','2025-09-24 02:20:44'),('41a455cc-943c-4c13-a017-03bcf5eaf846','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #56 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/56\",\"status\":\"pending\"}',NULL,'2025-09-30 10:02:26','2025-09-30 10:02:26'),('429213f7-f76f-4670-afac-c471eeeb6ee9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',19,'{\"message\":\"Your order #57 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/57\",\"status\":\"pending\"}',NULL,'2025-09-30 21:12:20','2025-09-30 21:12:20'),('43937039-c567-4639-a6f7-9943f6bd6ca3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #8 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/8\",\"status\":\"pending\"}',NULL,'2025-10-05 23:42:58','2025-10-05 23:42:58'),('44a38eff-78cc-4ae2-b45a-555bbd55de79','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #39 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/39\",\"status\":\"pending\"}',NULL,'2025-09-30 06:29:57','2025-09-30 06:29:57'),('46eb145e-b9ed-42fa-9bf8-c24004b08004','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #7 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/7\",\"status\":\"pending\"}',NULL,'2025-09-29 07:07:32','2025-09-29 07:07:32'),('46fc675f-8d84-4b64-a75f-20264df8be63','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #36 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/36\",\"status\":\"accepted\"}',NULL,'2025-09-24 05:34:22','2025-09-24 05:34:22'),('47c34239-85ce-4fdd-8d18-9f6279ed4dc6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #18 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/18\",\"status\":\"pending\"}',NULL,'2025-10-06 01:04:56','2025-10-06 01:04:56'),('47e4de03-c02b-4dab-9eaa-6f2dd1ee9c0c','App\\Notifications\\TestNotification','App\\Models\\User',1,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-09-14 23:39:40','2025-09-14 23:39:40'),('48d80cf5-d3a7-4a6c-8c5c-ea35be2ab2bf','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #4 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/4\",\"status\":\"pending\"}',NULL,'2025-09-29 06:10:04','2025-09-29 06:10:04'),('49fd9ee3-8082-4034-a4ff-074a6e195e7b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',101,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-11-05 01:23:51','2025-11-05 01:23:51'),('4b51d267-6e52-4153-9e41-8e806bac0734','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #26 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/26\",\"status\":\"pending\"}',NULL,'2025-09-30 05:02:27','2025-09-30 05:02:27'),('4c066c65-7679-4e70-962d-e674b58b966c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #19 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/19\",\"status\":\"pending\"}',NULL,'2025-10-06 01:07:19','2025-10-06 01:07:19'),('4f49ede9-379d-4d4d-b4e0-e8e7381888a6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #65 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/65\",\"status\":\"pending\"}',NULL,'2025-09-30 21:51:24','2025-09-30 21:51:24'),('4f77a1f0-f922-4c17-9ba4-9afa84e4eb53','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #28 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/28\",\"status\":\"pending\"}',NULL,'2025-09-30 05:13:54','2025-09-30 05:13:54'),('51c08523-508e-4bb8-833e-04dd6b22bd99','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #37 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/37\",\"status\":\"pending\"}',NULL,'2025-09-30 06:19:31','2025-09-30 06:19:31'),('5268d014-7b93-4674-8570-1ec2dd4bf25c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #30 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/30\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:33:57','2025-09-24 02:33:57'),('531bd197-5772-4e17-9735-e8b7053d13c5','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #45 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/45\",\"status\":\"pending\"}',NULL,'2025-09-30 06:55:37','2025-09-30 06:55:37'),('550d24c9-1ae9-41c0-90ba-920de345ce7d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #15 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/15\",\"status\":\"pending\"}',NULL,'2025-09-29 22:46:45','2025-09-29 22:46:45'),('57e212ef-842f-424f-bf04-05acd43a7f32','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #38 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/38\",\"status\":\"pending\"}',NULL,'2025-09-30 06:27:46','2025-09-30 06:27:46'),('587d1026-87ee-4189-9f3a-716b67cd3246','App\\Notifications\\NewClientRegistered','App\\Models\\User',1,'{\"message\":\"New client bobby has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/10\"}',NULL,'2025-09-16 23:28:06','2025-09-16 23:28:06'),('5896b6de-c9d6-48c5-9eac-8c72512dde6f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',99,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-11-04 23:36:30','2025-11-04 23:36:30'),('58c5465c-e949-42d5-b2c2-88b18d3b145b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #19 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/19\",\"status\":\"pending\"}',NULL,'2025-09-29 22:59:26','2025-09-29 22:59:26'),('596a5c29-a126-40a4-b17b-cbbc9b7de40b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #31 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/31\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:49:19','2025-09-24 02:49:19'),('5b9c0ccd-a1fa-4ce5-977f-d20fe281afee','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":3,\"description\":\"example\",\"message\":\"A new transaction \'example\' is pending approval.\"}',NULL,'2025-11-25 04:43:52','2025-11-25 04:43:52'),('5c851766-2c4e-44d3-97c6-7e26e60a5ae3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #40 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/40\",\"status\":\"pending\"}',NULL,'2025-09-30 06:31:56','2025-09-30 06:31:56'),('5ca7db38-eee9-497b-8c81-2dfeca59918c','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":2,\"description\":\"ertyuiosdfghjmk\",\"message\":\"A new transaction \'ertyuiosdfghjmk\' is pending approval.\"}',NULL,'2025-11-24 05:30:31','2025-11-24 05:30:31'),('5e32b856-40ae-45ac-a577-ad98d15a8d66','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',44,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-10-08 00:40:59','2025-10-08 00:40:59'),('60162f5b-e392-421e-98bd-532587b201ce','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #29 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"pending\"}',NULL,'2025-09-30 05:17:57','2025-09-30 05:17:57'),('616c0338-a62a-40b0-9112-cd9961b315c0','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #11 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/11\",\"status\":\"pending\"}',NULL,'2025-09-29 07:21:15','2025-09-29 07:21:15'),('619f3efa-f3fc-4301-86f1-a4dd43646a05','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #16 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/16\",\"status\":\"pending\"}',NULL,'2025-09-29 22:51:55','2025-09-29 22:51:55'),('639c6b70-5812-47c8-8139-0a0d9cea16a2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',20,'{\"message\":\"Your order #70 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/70\",\"status\":\"pending\"}',NULL,'2025-09-30 22:38:18','2025-09-30 22:38:18'),('67618459-ae57-4d24-8e80-f2a738a8a20c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #15 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/15\",\"status\":\"pending\"}',NULL,'2025-10-06 00:45:22','2025-10-06 00:45:22'),('68e08b7f-648c-437e-8f0e-9129ae3cc2fc','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #34 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/34\",\"status\":\"accepted\"}',NULL,'2025-09-25 00:04:25','2025-09-25 00:04:25'),('6968aa8c-9b9e-428e-b3c7-3e25b90c837c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',18,'{\"message\":\"Your order #34 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/34\",\"status\":\"pending\"}',NULL,'2025-09-30 05:56:50','2025-09-30 05:56:50'),('6a7d43db-d708-445e-845e-aecfdaa3578d','App\\Notifications\\NewClientRegistered','App\\Models\\User',2,'{\"message\":\"New client Kumud Kumar has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/11\"}',NULL,'2025-09-17 05:05:49','2025-09-17 05:05:49'),('6af3fbb2-87c7-412e-8610-2878406807ba','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #23 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/23\",\"status\":\"accepted\"}',NULL,'2025-09-24 01:50:06','2025-09-24 01:50:06'),('6ca6812a-3f52-407b-896e-8fb09eac95e4','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',26,'{\"message\":\"Your order #51 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/51\",\"status\":\"pending\"}',NULL,'2025-09-30 07:32:18','2025-09-30 07:32:18'),('6e3dca2e-5931-4eba-a287-6dc213b51b8f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #37 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/37\",\"status\":\"accepted\"}',NULL,'2025-09-25 00:04:41','2025-09-25 00:04:41'),('6f0cf334-93fa-4600-870e-b1de130555c3','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":1,\"description\":\"tyui\",\"message\":\"A new transaction \'tyui\' is pending approval.\"}',NULL,'2025-11-24 02:45:00','2025-11-24 02:45:00'),('6f1142de-5fb2-4e44-a947-5540785a6057','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #35 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/35\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:36:42','2025-09-24 04:36:42'),('708b2482-3203-4cf6-8111-667e85a4b178','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:14:59','2025-09-24 04:14:59'),('709d9e48-93bb-4dc3-86ad-29fe73dc4ed6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #30 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/30\",\"status\":\"pending\"}',NULL,'2025-09-30 05:27:11','2025-09-30 05:27:11'),('7209a20a-d87d-4708-bd55-f05d05cf7a41','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #9 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/9\",\"status\":\"pending\"}',NULL,'2025-10-05 23:52:19','2025-10-05 23:52:19'),('7443eb70-4017-439c-b44b-a8cae72b8cd9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #36 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/36\",\"status\":\"accepted\"}',NULL,'2025-09-24 05:29:19','2025-09-24 05:29:19'),('7517ab44-26ae-4e95-987f-64db419f50b3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #50 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/50\",\"status\":\"pending\"}',NULL,'2025-09-30 07:28:15','2025-09-30 07:28:15'),('7533bc4b-4443-44e9-b6d9-ea17ed3687c9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #32 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/32\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:37:46','2025-09-24 03:37:46'),('762aca5e-c3bf-4a74-9cad-29b938ee5d6b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #47 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/47\",\"status\":\"pending\"}',NULL,'2025-09-30 07:07:45','2025-09-30 07:07:45'),('777eab03-3355-46df-958e-d7049554d0b8','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',18,'{\"message\":\"Your order #61 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/61\",\"status\":\"pending\"}',NULL,'2025-09-30 21:39:50','2025-09-30 21:39:50'),('77cc5953-b381-46a1-9be3-aa545e89538c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #20 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/20\",\"status\":\"pending\"}',NULL,'2025-09-30 01:41:35','2025-09-30 01:41:35'),('7844f3ae-c68d-495c-8d73-1c018ecbb1b9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #36 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/36\",\"status\":\"accepted\"}',NULL,'2025-09-24 05:29:59','2025-09-24 05:29:59'),('7bd39d99-6ca2-48fb-be04-b099979a965c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #52 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/52\",\"status\":\"pending\"}',NULL,'2025-09-30 07:36:28','2025-09-30 07:36:28'),('7c147256-d764-4643-a371-6b92ac7d7ce6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #3 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/3\",\"status\":\"pending\"}',NULL,'2025-09-29 06:00:18','2025-09-29 06:00:18'),('7ee25845-aa86-45ee-ab70-c771f844b1f4','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #11 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/11\",\"status\":\"pending\"}',NULL,'2025-10-05 23:59:00','2025-10-05 23:59:00'),('80027728-9f1d-49f7-b760-205a3c1f6ac8','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',19,'{\"message\":\"Your order #60 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/60\",\"status\":\"pending\"}',NULL,'2025-09-30 21:38:55','2025-09-30 21:38:55'),('80f8666d-b54a-4391-a0e4-b97a565acb50','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #22 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/22\",\"status\":\"pending\"}',NULL,'2025-10-06 01:21:52','2025-10-06 01:21:52'),('87c8ff16-443f-4e6a-89c0-7c762ac5d851','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #2 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/2\",\"status\":\"pending\"}',NULL,'2025-10-03 07:03:00','2025-10-03 07:03:00'),('883c67a1-3b38-4cec-97f1-b1600a14167e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #30 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/30\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:34:11','2025-09-24 02:34:11'),('8e01ca01-8adc-4d4a-a73e-150a8ddeca9d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #13 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/13\",\"status\":\"pending\"}',NULL,'2025-09-29 07:47:43','2025-09-29 07:47:43'),('90c715a7-a2e0-4b67-99e2-d6e13afd10ba','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #13 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/13\",\"status\":\"pending\"}',NULL,'2025-10-06 00:42:51','2025-10-06 00:42:51'),('91bae0fe-1bf8-4456-a41e-f60ff23d2fe4','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #17 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/17\",\"status\":\"pending\"}',NULL,'2025-09-29 22:55:53','2025-09-29 22:55:53'),('9256bafe-924f-4889-9244-3df9ab6844eb','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',20,'{\"message\":\"Your order #54 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/54\",\"status\":\"pending\"}',NULL,'2025-09-30 09:36:37','2025-09-30 09:36:37'),('93a930e1-87d7-4cd2-98db-6855c5eedb22','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',99,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-11-04 23:36:27','2025-11-04 23:36:27'),('970e0c19-486c-409d-95ce-3ec6682a679f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #5 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/5\",\"status\":\"pending\"}',NULL,'2025-09-29 06:31:17','2025-09-29 06:31:17'),('9c2a46e3-cc65-493f-b3d0-0bed816cee7c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #69 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/69\",\"status\":\"pending\"}',NULL,'2025-09-30 22:27:47','2025-09-30 22:27:47'),('9ee0799a-a5a7-4879-9025-abc0c9e0be47','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #4 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/4\",\"status\":\"pending\"}',NULL,'2025-09-29 06:09:35','2025-09-29 06:09:35'),('a315f0e5-8d49-45e3-9a14-4b068b30e5ff','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #21 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/21\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:15:04','2025-09-24 02:15:04'),('a488e5ad-a4af-437e-923d-3b7c6147b6a4','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #18 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/18\",\"status\":\"pending\"}',NULL,'2025-09-29 22:58:19','2025-09-29 22:58:19'),('a6f471ff-59ef-438e-9e68-59ee134ada7a','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #30 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/30\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:34:59','2025-09-24 02:34:59'),('accfb9bf-5e0c-4c26-9549-1a36168693f3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #23 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/23\",\"status\":\"pending\"}',NULL,'2025-10-06 01:31:25','2025-10-06 01:31:25'),('ae0d210b-92c3-4993-b371-445a2f5b2112','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',19,'{\"message\":\"Your order #58 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/58\",\"status\":\"pending\"}',NULL,'2025-09-30 21:21:11','2025-09-30 21:21:11'),('af129eab-612f-4cea-b81a-8f36e59ac712','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #38 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/38\",\"status\":\"accepted\"}',NULL,'2025-09-25 03:22:53','2025-09-25 03:22:53'),('b06d8663-3f45-4c5e-a75d-27dcfafc6e59','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #12 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/12\",\"status\":\"pending\"}',NULL,'2025-09-29 07:24:19','2025-09-29 07:24:19'),('b07a05ce-87ff-4b21-9517-08b20032ffac','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #4 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/4\",\"status\":\"pending\"}',NULL,'2025-10-03 07:12:17','2025-10-03 07:12:17'),('b140156f-4d38-45e2-bee8-04369927b7a9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #67 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/67\",\"status\":\"pending\"}',NULL,'2025-09-30 22:04:30','2025-09-30 22:04:30'),('b1df81ab-b66d-4109-b71b-9fec5f568551','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',99,'{\"message\":\"Your order #2 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/2\",\"status\":\"pending\"}',NULL,'2025-11-04 23:38:00','2025-11-04 23:38:00'),('b86af638-d04f-4e8f-b228-f3fc183168ed','App\\Notifications\\NewClientRegistered','App\\Models\\User',2,'{\"message\":\"New client bobby has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/10\"}',NULL,'2025-09-16 23:28:06','2025-09-16 23:28:06'),('b91417fc-e50e-4833-9553-04822442d7c6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #33 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/33\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:00:21','2025-09-24 04:00:21'),('ba45e05f-0143-4b32-ac47-d4c0d4ae75a5','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',88,'{\"message\":\"Your order #2 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/2\",\"status\":\"pending\"}',NULL,'2025-10-30 07:08:21','2025-10-30 07:08:21'),('bce7dff1-9c12-487e-93b2-036bb10be2c3','App\\Notifications\\NewClientRegistered','App\\Models\\User',1,'{\"message\":\"New client kiran has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/7\"}',NULL,'2025-09-16 05:04:44','2025-09-16 05:04:44'),('bd69beb9-53df-4f83-b985-4c9c69e9dac6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #26 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/26\",\"status\":\"pending\"}',NULL,'2025-09-30 05:04:11','2025-09-30 05:04:11'),('bdbcffbc-561e-4bc1-ad62-3293fee57f3e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #10 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/10\",\"status\":\"pending\"}',NULL,'2025-09-29 07:17:49','2025-09-29 07:17:49'),('c1969dec-34bd-42af-8dc9-3e5d83180e27','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #21 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/21\",\"status\":\"pending\"}',NULL,'2025-10-06 01:20:04','2025-10-06 01:20:04'),('c1d57b8b-c363-451d-a67a-7b3856b4f902','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #32 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/32\",\"status\":\"accepted\"}',NULL,'2025-09-25 00:02:30','2025-09-25 00:02:30'),('c3802485-4de6-4b11-b015-dae85d65c96d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',61,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-10-13 10:11:18','2025-10-13 10:11:18'),('c4b26a3c-532a-402c-bc0e-bca755408c5f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',18,'{\"message\":\"Your order #42 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/42\",\"status\":\"pending\"}',NULL,'2025-09-30 06:43:28','2025-09-30 06:43:28'),('c6f95819-8e46-4961-895c-050fc3f886c5','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #46 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/46\",\"status\":\"pending\"}',NULL,'2025-09-30 07:02:16','2025-09-30 07:02:16'),('c9324d40-25b4-4432-bda9-e96284d4a61e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #32 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/32\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:26:50','2025-09-24 03:26:50'),('cb1a8839-766b-4b36-8a4f-ea4e14791612','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #66 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/66\",\"status\":\"pending\"}',NULL,'2025-09-30 21:56:38','2025-09-30 21:56:38'),('d3c15020-0947-4d56-84ea-3d74f285749f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #4 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/4\",\"status\":\"pending\"}',NULL,'2025-09-29 06:07:43','2025-09-29 06:07:43'),('d42680f0-302f-4818-abf6-10afe43ee386','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #30 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/30\",\"status\":\"accepted\"}',NULL,'2025-09-24 02:37:28','2025-09-24 02:37:28'),('d43e5c96-9eb7-4628-9012-c4d0b89ddee7','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #20 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/20\",\"status\":\"pending\"}',NULL,'2025-10-06 01:15:31','2025-10-06 01:15:31'),('d5ac75ef-bf53-40d6-93dc-1c6c251008c1','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #55 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/55\",\"status\":\"pending\"}',NULL,'2025-09-30 10:01:38','2025-09-30 10:01:38'),('d5ca1a96-10af-4dcf-9bec-facf94505c54','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #64 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/64\",\"status\":\"pending\"}',NULL,'2025-09-30 21:49:33','2025-09-30 21:49:33'),('d61f18f4-d816-4220-a6e1-eb331513e341','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #63 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/63\",\"status\":\"pending\"}',NULL,'2025-09-30 21:47:11','2025-09-30 21:47:11'),('d9d23f57-36fb-420a-9406-e3e86c012fcf','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #37 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/37\",\"status\":\"accepted\"}',NULL,'2025-09-26 00:42:59','2025-09-26 00:42:59'),('da3346b4-7431-4c6a-8352-30e5267d3877','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',88,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-10-30 07:03:31','2025-10-30 07:03:31'),('dd6143ca-c7ce-4068-a3a0-42b61aaceda6','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #33 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/33\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:51:11','2025-09-24 03:51:11'),('dd97bdb6-ca11-4d07-a33b-f0c86121bf56','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',73,'{\"message\":\"Your order #1 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/1\",\"status\":\"pending\"}',NULL,'2025-10-27 02:07:03','2025-10-27 02:07:03'),('de7b14e4-5984-4ab7-a408-86e6a33a945a','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #10 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/10\",\"status\":\"pending\"}',NULL,'2025-10-05 23:58:05','2025-10-05 23:58:05'),('df41ec1b-b6b9-49f6-b360-c0b8915c3c75','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #32 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/32\",\"status\":\"pending\"}',NULL,'2025-09-30 05:45:43','2025-09-30 05:45:43'),('e0c143b6-81dd-4f73-b1cb-24e95cbd7293','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #5 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/5\",\"status\":\"pending\"}',NULL,'2025-10-03 07:14:46','2025-10-03 07:14:46'),('e301c8b7-93bd-4074-8d3f-e1a7b952f229','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #28 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/28\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:27:29','2025-09-24 03:27:29'),('e466905f-f7bb-4373-bdbe-404419b34cd2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #5 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/5\",\"status\":\"pending\"}',NULL,'2025-09-29 06:58:26','2025-09-29 06:58:26'),('e4dc6879-b01a-41d1-8b67-84c7f5b373c8','App\\Notifications\\NewClientRegistered','App\\Models\\User',1,'{\"message\":\"New client Kumud Kumar has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/11\"}',NULL,'2025-09-17 05:05:49','2025-09-17 05:05:49'),('e6e5aff1-90a7-424d-b5ad-b9ff2fbececf','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #9 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/9\",\"status\":\"pending\"}',NULL,'2025-09-29 07:14:08','2025-09-29 07:14:08'),('e73b9cf6-3bae-42ed-a9b5-63a7a02262b3','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #29 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/29\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:54:59','2025-09-24 03:54:59'),('e8964c47-96b5-427d-a690-b076bfd8b4d1','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #12 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/12\",\"status\":\"pending\"}',NULL,'2025-10-06 00:17:15','2025-10-06 00:17:15'),('eb188401-925d-4a5a-9853-ebb19e0cc3d2','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #36 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/36\",\"status\":\"pending\"}',NULL,'2025-09-30 06:06:32','2025-09-30 06:06:32'),('ebc873a0-1e32-4c2a-ab88-1b3832d9ca24','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',23,'{\"message\":\"Your order #22 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/22\",\"status\":\"pending\"}',NULL,'2025-09-30 04:47:41','2025-09-30 04:47:41'),('ec97dae0-c23d-412d-a753-aeef11dd15ec','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',18,'{\"message\":\"Your order #27 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/27\",\"status\":\"pending\"}',NULL,'2025-09-30 05:08:56','2025-09-30 05:08:56'),('ec9a654c-f99d-4f61-a55d-be865c48898d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #17 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/17\",\"status\":\"pending\"}',NULL,'2025-10-06 00:52:34','2025-10-06 00:52:34'),('f727df39-faed-4269-83c2-4a8b60516c5b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',10,'{\"message\":\"Your order #31 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/31\",\"status\":\"accepted\"}',NULL,'2025-09-24 03:40:32','2025-09-24 03:40:32'),('f8b5e597-f3e2-4efc-b47d-055fafd7563e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',13,'{\"message\":\"Your order #43 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/43\",\"status\":\"pending\"}',NULL,'2025-09-30 06:45:38','2025-09-30 06:45:38'),('f8c49766-be29-41b7-a354-edde5b9d6e52','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',16,'{\"message\":\"Your order #31 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/31\",\"status\":\"pending\"}',NULL,'2025-09-30 05:39:29','2025-09-30 05:39:29'),('f97007a3-3a08-4b55-bd5f-ad052513cd31','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',17,'{\"message\":\"Your order #41 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/41\",\"status\":\"pending\"}',NULL,'2025-09-30 06:34:41','2025-09-30 06:34:41'),('fa6977ee-719c-42e9-b57c-51d987787c15','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',11,'{\"message\":\"Your order #34 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/34\",\"status\":\"accepted\"}',NULL,'2025-09-24 04:15:59','2025-09-24 04:15:59'),('fbab386e-7ae9-4ab3-bd84-a21339262f51','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #16 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/16\",\"status\":\"pending\"}',NULL,'2025-10-06 00:49:39','2025-10-06 00:49:39'),('fbf53f14-6c99-4aec-84d2-fb98cdbcca8f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',43,'{\"message\":\"Your order #6 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/6\",\"status\":\"pending\"}',NULL,'2025-10-05 23:26:37','2025-10-05 23:26:37'),('fe808586-9a82-4ccb-a6d5-284adc0d5dd1','App\\Notifications\\NewClientRegistered','App\\Models\\User',2,'{\"message\":\"New client kiran has registered and is awaiting approval.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/clients\\/7\"}',NULL,'2025-09-16 05:04:44','2025-09-16 05:04:44');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_product`
--

DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_product_order_id_foreign` (`order_id`),
  KEY `order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `online_order_id` bigint(20) DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `po_no` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `cart_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cart_items`)),
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gst` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `transport_name` varchar(255) NOT NULL,
  `transport_address` varchar(255) NOT NULL,
  `transport_id` varchar(255) NOT NULL,
  `transport_phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_gst` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `cgst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sgst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `online_client_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_quotation_id_foreign` (`quotation_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_client_id_foreign` (`client_id`),
  KEY `orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payrolls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(255) NOT NULL DEFAULT 'sa',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `finance_approver_id` bigint(20) unsigned DEFAULT NULL,
  `disbursed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payrolls_employee_id_foreign` (`employee_id`),
  KEY `payrolls_manager_id_foreign` (`manager_id`),
  KEY `payrolls_finance_approver_id_foreign` (`finance_approver_id`),
  CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payrolls_finance_approver_id_foreign` FOREIGN KEY (`finance_approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payrolls_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performance_reviews`
--

DROP TABLE IF EXISTS `performance_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `performance_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `reviewer_id` bigint(20) unsigned DEFAULT NULL,
  `review_date` date NOT NULL,
  `feedback` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `goals` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `performance_reviews_employee_id_foreign` (`employee_id`),
  KEY `performance_reviews_reviewer_id_foreign` (`reviewer_id`),
  CONSTRAINT `performance_reviews_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `performance_reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performance_reviews`
--

LOCK TABLES `performance_reviews` WRITE;
/*!40000 ALTER TABLE `performance_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `performance_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage hr','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(2,'view hr','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(3,'manage sales','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(4,'view sales','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(5,'manage inventory','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(6,'view inventory','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(7,'manage finance','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(8,'view finance','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(9,'manage settings','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(10,'view dashboard','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(11,'view reports','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(12,'manage users','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(13,'view notifications','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(14,'manage notifications','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(15,'view production','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(16,'manage production','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(17,'view employee portal','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(18,'access employee portal','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(19,'access manager portal','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(20,'view sales dashboard','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(21,'manage sales dashboard','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(22,'manage quotations','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(23,'process production','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(24,'approve transactions','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(25,'manage payroll','web','2025-09-14 23:39:19','2025-09-14 23:39:19');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `processes`
--

DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `processes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `progress_percent` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `sequence` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `labor_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `processes_parent_id_foreign` (`parent_id`),
  CONSTRAINT `processes_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` VALUES (1,'Upper Part',NULL,NULL,0,'Pending',0,'2025-09-14 23:44:05','2025-09-14 23:44:05',NULL),(2,'Bottom Part',NULL,NULL,0,'Pending',0,'2025-09-14 23:55:38','2025-09-14 23:55:38',NULL),(3,'Finished Part',NULL,NULL,0,'Pending',0,'2025-09-14 23:55:38','2025-09-14 23:55:38',NULL),(4,'1',NULL,NULL,0,'Pending',0,'2025-09-16 02:59:50','2025-09-16 02:59:50',NULL),(5,'2',NULL,NULL,0,'Pending',0,'2025-09-16 02:59:50','2025-09-16 02:59:50',NULL),(6,'3',NULL,NULL,0,'Pending',0,'2025-09-16 02:59:50','2025-09-16 02:59:50',NULL),(7,'design part',NULL,NULL,0,'Pending',0,'2025-09-27 02:35:47','2025-09-27 02:35:47',NULL),(8,'Lower Part',NULL,NULL,0,'Pending',0,'2025-10-08 23:13:28','2025-10-08 23:13:28',NULL),(9,'Upper Men',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(10,'Bottom Men',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(11,'Finish Men',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(12,'Upper Man',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(13,'Bottom Man',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(14,'Finish Man',NULL,NULL,0,'Pending',0,'2025-11-06 08:44:59','2025-11-06 08:44:59',NULL),(15,'Finish Part',NULL,NULL,0,'Pending',0,'2025-11-06 22:40:29','2025-11-06 22:40:29',NULL);
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_liquid_material`
--

DROP TABLE IF EXISTS `product_liquid_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_liquid_material` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `liquid_material_id` bigint(20) unsigned NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_liquid_material_product_id_foreign` (`product_id`),
  KEY `product_liquid_material_liquid_material_id_foreign` (`liquid_material_id`),
  CONSTRAINT `product_liquid_material_liquid_material_id_foreign` FOREIGN KEY (`liquid_material_id`) REFERENCES `liquid_materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_liquid_material_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_liquid_material`
--

LOCK TABLES `product_liquid_material` WRITE;
/*!40000 ALTER TABLE `product_liquid_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_liquid_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_processes`
--

DROP TABLE IF EXISTS `product_processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_processes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `process_id` bigint(20) unsigned NOT NULL,
  `labor_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `process_order` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_processes_product_id_process_id_unique` (`product_id`,`process_id`),
  KEY `product_processes_process_id_foreign` (`process_id`),
  CONSTRAINT `product_processes_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=375 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_processes`
--

LOCK TABLES `product_processes` WRITE;
/*!40000 ALTER TABLE `product_processes` DISABLE KEYS */;
INSERT INTO `product_processes` VALUES (108,29,1,0.00,'2025-10-11 23:46:19','2025-10-13 01:11:38',0,0),(109,29,8,0.00,'2025-10-11 23:46:19','2025-10-13 01:11:38',0,0),(110,29,3,0.00,'2025-10-11 23:46:19','2025-10-13 01:11:38',0,0),(111,30,1,0.00,'2025-10-11 23:55:49','2025-10-13 01:22:20',0,0),(112,30,8,0.00,'2025-10-11 23:55:49','2025-10-13 01:22:20',0,0),(113,30,3,0.00,'2025-10-11 23:55:49','2025-10-13 01:22:20',0,0),(126,35,1,0.00,'2025-10-12 01:01:07','2025-10-13 01:48:50',0,0),(127,35,8,0.00,'2025-10-12 01:01:07','2025-10-13 01:48:50',0,0),(128,35,3,0.00,'2025-10-12 01:01:07','2025-10-13 01:48:50',0,0),(129,36,1,0.00,'2025-10-12 01:17:52','2025-10-13 01:51:53',0,0),(130,36,8,0.00,'2025-10-12 01:17:52','2025-10-13 01:51:53',0,0),(131,36,3,0.00,'2025-10-12 01:17:52','2025-10-13 01:51:53',0,0),(169,22,1,0.00,'2025-10-12 08:49:27','2025-10-12 08:49:27',0,0),(170,22,8,0.00,'2025-10-12 08:49:27','2025-10-12 08:49:27',0,0),(171,22,3,0.00,'2025-10-12 08:49:27','2025-10-12 08:49:27',0,0),(175,24,1,25.00,'2025-10-12 11:00:06','2025-10-12 11:00:06',0,0),(176,24,8,40.00,'2025-10-12 11:00:06','2025-10-12 11:00:06',0,0),(177,24,3,5.00,'2025-10-12 11:00:06','2025-10-12 11:00:06',0,0),(178,25,1,25.00,'2025-10-12 11:01:48','2025-10-12 11:01:48',0,0),(179,25,8,40.00,'2025-10-12 11:01:49','2025-10-12 11:01:49',0,0),(180,25,3,5.00,'2025-10-12 11:01:49','2025-10-12 11:01:49',0,0),(181,26,1,20.00,'2025-10-12 11:09:14','2025-10-12 11:22:52',0,0),(182,26,8,30.00,'2025-10-12 11:09:14','2025-10-12 11:22:52',0,0),(183,26,3,5.00,'2025-10-12 11:09:14','2025-10-12 11:22:52',0,0),(184,27,1,0.00,'2025-10-12 11:18:14','2025-10-12 11:23:53',0,0),(185,27,8,0.00,'2025-10-12 11:18:14','2025-10-12 11:23:53',0,0),(186,27,3,0.00,'2025-10-12 11:18:14','2025-10-12 11:23:53',0,0),(190,31,1,0.00,'2025-10-13 01:24:35','2025-10-13 01:24:35',0,0),(191,31,8,0.00,'2025-10-13 01:24:35','2025-10-13 01:24:35',0,0),(192,31,3,0.00,'2025-10-13 01:24:35','2025-10-13 01:24:35',0,0),(193,32,1,50.00,'2025-10-13 01:30:33','2025-10-13 01:30:33',0,0),(194,32,8,50.00,'2025-10-13 01:30:33','2025-10-13 01:30:33',0,0),(195,32,3,50.00,'2025-10-13 01:30:33','2025-10-13 01:30:33',0,0),(196,33,1,25.00,'2025-10-13 01:37:00','2025-10-13 01:37:00',0,0),(197,33,8,25.00,'2025-10-13 01:37:00','2025-10-13 01:37:00',0,0),(198,33,3,25.00,'2025-10-13 01:37:00','2025-10-13 01:37:00',0,0),(199,34,1,0.00,'2025-10-13 01:40:43','2025-10-13 01:40:43',0,0),(200,34,8,0.00,'2025-10-13 01:40:43','2025-10-13 01:40:43',0,0),(201,34,3,0.00,'2025-10-13 01:40:43','2025-10-13 01:40:43',0,0),(321,11,1,0.00,'2025-11-07 07:44:29','2025-11-07 07:44:29',0,0),(322,11,2,0.00,'2025-11-07 07:44:29','2025-11-07 07:44:29',0,0),(323,11,3,0.00,'2025-11-07 07:44:29','2025-11-07 07:44:29',0,0),(324,12,1,0.00,'2025-11-07 07:59:43','2025-11-07 07:59:43',0,0),(325,12,2,0.00,'2025-11-07 07:59:43','2025-11-07 07:59:43',0,0),(326,12,3,0.00,'2025-11-07 07:59:43','2025-11-07 07:59:43',0,0),(327,13,1,75.00,'2025-11-07 23:26:01','2025-11-07 23:26:01',0,0),(328,13,2,85.00,'2025-11-07 23:26:01','2025-11-07 23:26:01',0,0),(329,13,3,95.00,'2025-11-07 23:26:01','2025-11-07 23:26:01',0,0),(330,14,1,50.00,'2025-11-08 06:03:41','2025-11-08 06:03:41',0,0),(331,14,2,55.00,'2025-11-08 06:03:41','2025-11-08 06:03:41',0,0),(332,14,3,70.00,'2025-11-08 06:03:41','2025-11-08 06:03:41',0,0),(340,2,1,55.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(341,2,2,45.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(342,2,3,25.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(343,3,1,0.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(344,3,2,0.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(345,3,3,0.00,'2025-11-11 07:07:34','2025-11-22 06:53:51',0,0),(346,1,1,55.00,'2025-11-12 00:14:54','2025-11-25 05:48:29',0,0),(347,1,2,75.00,'2025-11-12 00:14:54','2025-11-25 05:48:29',0,0),(348,1,3,85.00,'2025-11-12 00:14:54','2025-11-25 05:48:29',0,0),(355,4,1,55.00,'2025-11-14 10:19:44','2025-11-15 02:26:54',0,0),(356,4,2,40.00,'2025-11-14 10:19:44','2025-11-15 02:26:54',0,0),(357,4,3,0.00,'2025-11-14 10:19:44','2025-11-14 10:19:44',0,0),(358,6,1,50.00,'2025-11-14 12:15:43','2025-11-15 02:26:54',0,0),(359,6,2,55.00,'2025-11-14 12:15:43','2025-11-15 02:26:54',0,0),(360,6,3,65.00,'2025-11-14 12:15:43','2025-11-15 02:26:54',0,0),(361,2,9,55.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(362,2,10,40.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(363,2,11,5.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(364,3,12,55.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(365,3,13,45.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(366,3,14,5.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(367,4,15,35.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(368,5,1,50.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(369,5,2,55.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(370,5,3,70.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(371,5,7,80.00,'2025-11-15 02:26:54','2025-11-15 02:26:54',0,0),(372,7,1,0.00,'2025-11-15 11:22:56','2025-11-15 11:22:56',0,0),(373,7,2,0.00,'2025-11-15 11:22:56','2025-11-15 11:22:56',0,0),(374,7,3,0.00,'2025-11-15 11:22:56','2025-11-15 11:22:56',0,0);
/*!40000 ALTER TABLE `product_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_quotation`
--

DROP TABLE IF EXISTS `product_quotation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_quotation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_quotation_quotation_id_foreign` (`quotation_id`),
  KEY `product_quotation_product_id_foreign` (`product_id`),
  KEY `product_quotation_quotation_id_created_at_index` (`quotation_id`,`created_at`),
  KEY `product_quotation_product_id_quantity_index` (`product_id`,`quantity`),
  CONSTRAINT `product_quotation_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_quotation_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_quotation`
--

LOCK TABLES `product_quotation` WRITE;
/*!40000 ALTER TABLE `product_quotation` DISABLE KEYS */;
INSERT INTO `product_quotation` VALUES (217,4,6,30,50.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"5\\\",\\\"36\\\":\\\"5\\\",\\\"37\\\":\\\"5\\\",\\\"38\\\":\\\"5\\\",\\\"39\\\":\\\"5\\\",\\\"40\\\":\\\"5\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762427071_WhatsApp Image 2025-10-12 at 16.37.36 (2).jpeg\\\"}]\"','2025-11-15 02:37:07','2025-11-15 02:37:07'),(218,5,6,80,111.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"0\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"20\\\",\\\"40\\\":\\\"20\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762427071_WhatsApp Image 2025-10-12 at 16.37.36 (2).jpeg\\\"}]\"','2025-11-15 02:41:26','2025-11-15 02:41:26'),(219,6,6,35,200.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"0\\\",\\\"37\\\":\\\"7\\\",\\\"38\\\":\\\"7\\\",\\\"39\\\":\\\"7\\\",\\\"40\\\":\\\"7\\\",\\\"41\\\":\\\"7\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762427071_WhatsApp Image 2025-10-12 at 16.37.36 (2).jpeg\\\"}]\"','2025-11-15 02:42:00','2025-11-15 02:42:00'),(220,7,3,174,214.00,'\"[{\\\"color\\\":\\\"beige\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"15\\\",\\\"37\\\":\\\"25\\\",\\\"38\\\":\\\"30\\\",\\\"39\\\":\\\"14\\\",\\\"40\\\":\\\"40\\\",\\\"41\\\":\\\"50\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762090487_006.jpg\\\"}]\"','2025-11-15 03:07:21','2025-11-15 03:07:21'),(221,8,2,60,100.00,'\"[{\\\"color\\\":\\\"GREY\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"0\\\",\\\"37\\\":\\\"0\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"20\\\",\\\"40\\\":\\\"20\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762090424_002.jpg\\\"}]\"','2025-11-16 02:17:28','2025-11-16 02:17:28'),(222,9,6,84,111.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"21\\\",\\\"37\\\":\\\"21\\\",\\\"38\\\":\\\"21\\\",\\\"39\\\":\\\"21\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"}}]\"','2025-11-18 00:18:00','2025-11-18 00:22:14'),(223,10,2,145,200.00,'\"[{\\\"color\\\":\\\"GREY\\\",\\\"sizes\\\":{\\\"35\\\":\\\"15\\\",\\\"36\\\":\\\"15\\\",\\\"37\\\":\\\"15\\\",\\\"38\\\":\\\"25\\\",\\\"39\\\":\\\"20\\\",\\\"40\\\":\\\"25\\\",\\\"41\\\":\\\"30\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762090424_002.jpg\\\"}]\"','2025-11-18 00:33:06','2025-11-18 00:33:06'),(224,11,5,25,100.00,'\"[{\\\"color\\\":\\\"brown-leather\\\",\\\"sizes\\\":{\\\"35\\\":\\\"5\\\",\\\"36\\\":\\\"5\\\",\\\"37\\\":\\\"5\\\",\\\"38\\\":\\\"5\\\",\\\"39\\\":\\\"5\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762321991_WhatsApp Image 2025-09-17 at 14.53.35 (2).jpeg\\\"}]\"','2025-11-21 05:19:29','2025-11-21 05:19:29'),(225,12,4,80,100.00,'\"[{\\\"color\\\":\\\"ANT\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762095602_696.jpg\\\"}]\"','2025-11-21 05:21:16','2025-11-21 05:21:16'),(226,13,5,10,55.00,'\"[{\\\"color\\\":\\\"brown-leather\\\",\\\"sizes\\\":{\\\"35\\\":\\\"2\\\",\\\"36\\\":\\\"2\\\",\\\"37\\\":\\\"2\\\",\\\"38\\\":\\\"2\\\",\\\"39\\\":\\\"2\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762321991_WhatsApp Image 2025-09-17 at 14.53.35 (2).jpeg\\\"}]\"','2025-11-21 06:14:21','2025-11-21 06:14:21'),(227,14,2,90,120.00,'\"[{\\\"color\\\":\\\"GREY\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"25\\\",\\\"38\\\":\\\"25\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762090424_002.jpg\\\"}]\"','2025-11-21 06:27:52','2025-11-21 06:27:52'),(228,15,7,60,120.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"0\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1763225575_WhatsApp Image 2025-10-12 at 16.37.36.jpeg\\\"}]\"','2025-11-21 06:28:16','2025-11-21 06:28:16'),(229,16,7,75,120.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"0\\\",\\\"36\\\":\\\"25\\\",\\\"37\\\":\\\"25\\\",\\\"38\\\":\\\"25\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1763225575_WhatsApp Image 2025-10-12 at 16.37.36.jpeg\\\"}]\"','2025-11-21 06:29:34','2025-11-21 06:29:34'),(230,17,3,222,150.00,'\"[{\\\"color\\\":\\\"beige\\\",\\\"sizes\\\":{\\\"35\\\":\\\"202\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"0\\\",\\\"38\\\":\\\"0\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762090487_006.jpg\\\"}]\"','2025-11-21 06:40:37','2025-11-21 06:40:37'),(231,18,6,980,110.00,'\"[{\\\"color\\\":\\\"brown\\\",\\\"sizes\\\":{\\\"35\\\":\\\"120\\\",\\\"36\\\":\\\"120\\\",\\\"37\\\":\\\"120\\\",\\\"38\\\":\\\"120\\\",\\\"39\\\":\\\"150\\\",\\\"40\\\":\\\"150\\\",\\\"41\\\":\\\"200\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762427071_WhatsApp Image 2025-10-12 at 16.37.36 (2).jpeg\\\"}]\"','2025-11-22 03:58:53','2025-11-22 03:58:53'),(232,1,2,80,120.00,'\"[{\\\"color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"20\\\",\\\"37\\\":\\\"20\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762753314_WhatsApp Image 2025-10-12 at 21.54.08.jpeg\\\"}]\"','2025-11-22 06:54:25','2025-11-22 06:54:25'),(233,2,2,10,120.00,'\"[{\\\"color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"10\\\",\\\"36\\\":\\\"0\\\",\\\"37\\\":\\\"0\\\",\\\"38\\\":\\\"0\\\",\\\"39\\\":\\\"0\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762753314_WhatsApp Image 2025-10-12 at 21.54.08.jpeg\\\"}]\"','2025-11-24 05:58:35','2025-11-24 05:58:35'),(234,3,3,112,150.00,'\"[{\\\"color\\\":\\\"black\\\",\\\"sizes\\\":{\\\"35\\\":\\\"20\\\",\\\"36\\\":\\\"2\\\",\\\"37\\\":\\\"50\\\",\\\"38\\\":\\\"20\\\",\\\"39\\\":\\\"20\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"0\\\",\\\"42\\\":\\\"0\\\",\\\"43\\\":\\\"0\\\",\\\"44\\\":\\\"0\\\"},\\\"images\\\":[],\\\"main_image\\\":\\\"products\\\\\\/variations\\\\\\/1762753798_WhatsApp Image 2025-09-09 at 11.00.25 (3).jpeg\\\"}]\"','2025-11-25 00:23:28','2025-11-25 00:23:28');
/*!40000 ALTER TABLE `product_quotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_sole`
--

DROP TABLE IF EXISTS `product_sole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_sole` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sole_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity_used` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `product_sole_product_id_foreign` (`product_id`),
  KEY `product_sole_sole_id_foreign` (`sole_id`),
  CONSTRAINT `product_sole_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_sole_sole_id_foreign` FOREIGN KEY (`sole_id`) REFERENCES `soles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_sole`
--

LOCK TABLES `product_sole` WRITE;
/*!40000 ALTER TABLE `product_sole` DISABLE KEYS */;
INSERT INTO `product_sole` VALUES (18,83,76,NULL,NULL,1),(59,35,54,'2025-10-12 01:01:07','2025-10-12 01:01:07',1),(60,36,55,'2025-10-12 01:17:52','2025-10-12 01:17:52',1),(93,22,54,'2025-10-12 08:49:27','2025-10-12 08:49:27',1),(94,22,55,'2025-10-12 08:49:27','2025-10-12 08:49:27',1),(97,24,58,'2025-10-12 11:00:06','2025-10-12 11:00:06',1),(98,25,59,'2025-10-12 11:01:49','2025-10-12 11:01:49',1),(99,26,61,'2025-10-12 11:09:14','2025-10-12 11:09:14',1),(100,26,62,'2025-10-12 11:09:14','2025-10-12 11:09:14',1),(101,26,63,'2025-10-12 11:09:14','2025-10-12 11:09:14',1),(102,27,64,'2025-10-12 11:18:14','2025-10-12 11:18:14',1),(103,27,65,'2025-10-12 11:18:14','2025-10-12 11:18:14',1),(106,29,69,'2025-10-13 01:11:38','2025-10-13 01:11:38',1),(107,30,71,'2025-10-13 01:22:20','2025-10-13 01:22:20',1),(108,31,72,'2025-10-13 01:24:35','2025-10-13 01:24:35',1),(109,32,73,'2025-10-13 01:30:33','2025-10-13 01:30:33',1),(110,33,75,'2025-10-13 01:37:00','2025-10-13 01:37:00',1),(111,34,45,'2025-10-13 01:40:43','2025-10-13 01:40:43',1),(112,35,78,'2025-10-13 01:48:50','2025-10-13 01:48:50',1),(113,36,79,'2025-10-13 01:51:53','2025-10-13 01:51:53',1),(206,11,9,'2025-11-07 07:44:29','2025-11-07 07:44:29',1),(207,12,10,'2025-11-07 07:59:43','2025-11-07 07:59:43',1),(208,13,11,'2025-11-07 23:26:01','2025-11-07 23:26:01',1),(209,13,12,'2025-11-07 23:26:01','2025-11-07 23:26:01',1),(210,14,7,'2025-11-08 06:03:41','2025-11-08 06:03:41',1),(237,2,3,'2025-11-11 07:07:34','2025-11-22 04:58:57',1),(238,2,4,'2025-11-11 07:07:34','2025-11-22 06:53:50',1),(239,1,1,'2025-11-12 00:14:54','2025-11-25 05:48:29',1),(240,1,2,'2025-11-12 00:14:54','2025-11-25 05:48:29',1),(241,1,3,'2025-11-12 00:14:54','2025-11-25 05:48:29',1),(242,2,2,'2025-11-12 01:59:41','2025-11-12 01:59:41',1),(243,3,4,'2025-11-12 01:59:41','2025-11-12 01:59:41',1),(244,3,5,'2025-11-12 01:59:41','2025-11-12 01:59:41',1),(245,6,4,'2025-11-15 02:26:54','2025-11-15 02:26:54',1),(246,1,4,'2025-11-22 06:53:50','2025-11-25 05:48:29',1),(247,2,5,'2025-11-22 06:53:50','2025-11-22 06:53:50',1);
/*!40000 ALTER TABLE `product_sole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_warehouse`
--

DROP TABLE IF EXISTS `product_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_warehouse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_warehouse_product_id_foreign` (`product_id`),
  KEY `product_warehouse_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `product_warehouse_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_warehouse`
--

LOCK TABLES `product_warehouse` WRITE;
/*!40000 ALTER TABLE `product_warehouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_order_product`
--

DROP TABLE IF EXISTS `production_order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_order_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `production_order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_order_product_production_order_id_foreign` (`production_order_id`),
  KEY `production_order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `production_order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_order_product_production_order_id_foreign` FOREIGN KEY (`production_order_id`) REFERENCES `production_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_order_product`
--

LOCK TABLES `production_order_product` WRITE;
/*!40000 ALTER TABLE `production_order_product` DISABLE KEYS */;
INSERT INTO `production_order_product` VALUES (119,4,13,275,NULL,NULL,'2025-11-07 23:28:58','2025-11-07 23:28:58',NULL),(120,5,13,611,NULL,NULL,'2025-11-08 02:49:28','2025-11-08 02:49:28',NULL),(123,1,3,82,NULL,NULL,'2025-11-11 07:09:15','2025-11-11 07:09:15',NULL),(125,4,2,42,NULL,NULL,'2025-11-14 06:57:36','2025-11-14 06:57:36',NULL),(126,14,6,80,NULL,NULL,'2025-11-14 23:12:48','2025-11-14 23:12:48',NULL),(127,2,3,192,NULL,NULL,'2025-11-15 02:35:35','2025-11-15 02:35:35',NULL),(128,3,4,32,NULL,NULL,'2025-11-15 02:37:15','2025-11-15 02:37:15',NULL),(129,5,6,80,NULL,NULL,'2025-11-15 02:41:30','2025-11-15 02:41:30',NULL),(130,6,6,35,NULL,NULL,'2025-11-15 02:42:04','2025-11-15 02:42:04',NULL),(131,7,3,174,NULL,NULL,'2025-11-15 03:07:25','2025-11-15 03:07:25',NULL),(132,12,2,60,NULL,NULL,'2025-11-16 02:17:34','2025-11-16 02:17:34',NULL),(133,13,6,84,NULL,NULL,'2025-11-18 00:24:15','2025-11-18 00:24:15',NULL),(134,15,4,80,NULL,NULL,'2025-11-21 06:06:24','2025-11-21 06:06:24',NULL),(135,16,5,25,NULL,NULL,'2025-11-21 06:26:23','2025-11-21 06:26:23',NULL),(136,17,5,10,NULL,NULL,'2025-11-21 06:26:23','2025-11-21 06:26:23',NULL),(137,18,2,90,NULL,NULL,'2025-11-21 06:28:23','2025-11-21 06:28:23',NULL),(138,19,7,60,NULL,NULL,'2025-11-21 06:28:23','2025-11-21 06:28:23',NULL),(139,20,7,75,NULL,NULL,'2025-11-21 06:29:42','2025-11-21 06:29:42',NULL),(140,21,3,222,NULL,NULL,'2025-11-21 06:40:46','2025-11-21 06:40:46',NULL),(141,22,6,980,NULL,NULL,'2025-11-22 03:59:01','2025-11-22 03:59:01',NULL);
/*!40000 ALTER TABLE `production_order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_orders`
--

DROP TABLE IF EXISTS `production_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stage` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `client_order_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `production_orders_quotation_id_foreign` (`quotation_id`),
  KEY `production_orders_client_order_id_foreign` (`client_order_id`),
  CONSTRAINT `production_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_orders`
--

LOCK TABLES `production_orders` WRITE;
/*!40000 ALTER TABLE `production_orders` DISABLE KEYS */;
INSERT INTO `production_orders` VALUES (1,1,1,NULL,'accepted','2025-11-29','2025-11-22 06:54:30','2025-11-22 07:04:36',0),(2,1,3,NULL,'accepted','2025-12-02','2025-11-25 00:23:39','2025-11-25 04:18:17',0);
/*!40000 ALTER TABLE `production_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_processes`
--

DROP TABLE IF EXISTS `production_processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_processes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `process_id` bigint(20) unsigned DEFAULT NULL,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','paid') NOT NULL DEFAULT 'pending',
  `operator` varchar(255) DEFAULT NULL,
  `labor_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assigned_quantity` int(11) NOT NULL DEFAULT 0,
  `completed_quantity` int(11) NOT NULL DEFAULT 0,
  `process_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `batch_process_unique` (`batch_id`,`process_id`),
  UNIQUE KEY `unique_batch_process` (`batch_id`,`process_id`),
  KEY `production_processes_product_id_foreign` (`product_id`),
  KEY `production_processes_process_id_foreign` (`process_id`),
  CONSTRAINT `production_processes_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_processes`
--

LOCK TABLES `production_processes` WRITE;
/*!40000 ALTER TABLE `production_processes` DISABLE KEYS */;
INSERT INTO `production_processes` VALUES (1,2,1,1,'Upper Part','Pending','completed',NULL,55.00,'2025-11-22 07:05:35','2025-11-24 02:53:39',100,0,0),(2,2,2,1,'Bottom Part','Pending','completed',NULL,45.00,'2025-11-22 07:05:35','2025-11-24 02:53:30',100,0,0),(3,2,3,1,'Finished Part','Pending','completed',NULL,25.00,'2025-11-22 07:05:35','2025-11-24 02:53:18',100,0,0),(4,2,9,1,'Upper Men','Pending','pending',NULL,55.00,'2025-11-22 07:05:35','2025-11-22 07:05:35',100,0,0),(5,2,10,1,'Bottom Men','Pending','pending',NULL,40.00,'2025-11-22 07:05:35','2025-11-22 07:05:35',100,0,0),(6,2,11,1,'Finish Men','Pending','pending',NULL,5.00,'2025-11-22 07:05:35','2025-11-22 07:05:35',100,0,0),(7,2,1,2,'Upper Part','Pending','pending',NULL,55.00,'2025-11-22 07:24:05','2025-11-22 07:24:05',145,0,0),(8,2,2,2,'Bottom Part','Pending','pending',NULL,45.00,'2025-11-22 07:24:05','2025-11-22 07:24:05',145,0,0),(9,2,3,2,'Finished Part','Pending','completed',NULL,25.00,'2025-11-22 07:24:05','2025-11-24 05:26:11',145,0,0),(10,2,9,2,'Upper Men','Pending','pending',NULL,55.00,'2025-11-22 07:24:05','2025-11-22 07:24:05',145,0,0),(11,2,10,2,'Bottom Men','Pending','pending',NULL,40.00,'2025-11-22 07:24:05','2025-11-22 07:24:05',145,0,0),(12,2,11,2,'Finish Men','Pending','pending',NULL,5.00,'2025-11-22 07:24:05','2025-11-22 07:24:05',145,0,0),(13,2,1,3,'Upper Part','Pending','pending',NULL,55.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(14,2,2,3,'Bottom Part','Pending','pending',NULL,45.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(15,2,3,3,'Finished Part','Pending','pending',NULL,25.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(16,2,9,3,'Upper Men','Pending','pending',NULL,55.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(17,2,10,3,'Bottom Men','Pending','pending',NULL,40.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(18,2,11,3,'Finish Men','Pending','pending',NULL,5.00,'2025-11-22 07:42:56','2025-11-22 07:42:56',80,0,0),(19,2,1,4,'Upper Part','Pending','completed',NULL,55.00,'2025-11-22 07:49:16','2025-11-23 23:29:15',80,0,0),(20,2,2,4,'Bottom Part','Pending','completed',NULL,45.00,'2025-11-22 07:49:16','2025-11-23 23:30:52',80,0,0),(21,2,3,4,'Finished Part','Pending','completed',NULL,25.00,'2025-11-22 07:49:16','2025-11-23 23:37:48',80,0,0),(22,2,9,4,'Upper Men','Pending','pending',NULL,55.00,'2025-11-22 07:49:16','2025-11-22 07:49:16',80,0,0),(23,2,10,4,'Bottom Men','Pending','pending',NULL,40.00,'2025-11-22 07:49:16','2025-11-22 07:49:16',80,0,0),(24,2,11,4,'Finish Men','Pending','pending',NULL,5.00,'2025-11-22 07:49:16','2025-11-22 07:49:16',80,0,0),(25,3,1,5,'Upper Part','Pending','completed',NULL,0.00,'2025-11-23 23:57:32','2025-11-24 00:17:17',295,0,0),(26,3,2,5,'Bottom Part','Pending','completed',NULL,0.00,'2025-11-23 23:57:32','2025-11-24 00:17:17',295,0,0),(27,3,3,5,'Finished Part','Pending','completed',NULL,0.00,'2025-11-23 23:57:32','2025-11-24 05:24:31',295,0,0),(28,3,12,5,'Upper Man','Pending','pending',NULL,55.00,'2025-11-23 23:57:32','2025-11-23 23:57:32',295,0,0),(29,3,13,5,'Bottom Man','Pending','pending',NULL,45.00,'2025-11-23 23:57:32','2025-11-23 23:57:32',295,0,0),(30,3,14,5,'Finish Man','Pending','pending',NULL,5.00,'2025-11-23 23:57:32','2025-11-23 23:57:32',295,0,0),(31,2,1,6,'Upper Part','Pending','pending',NULL,55.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0),(32,2,2,6,'Bottom Part','Pending','pending',NULL,45.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0),(33,2,3,6,'Finished Part','Pending','pending',NULL,25.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0),(34,2,9,6,'Upper Men','Pending','pending',NULL,55.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0),(35,2,10,6,'Bottom Men','Pending','pending',NULL,40.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0),(36,2,11,6,'Finish Men','Pending','pending',NULL,5.00,'2025-11-24 23:48:00','2025-11-24 23:48:00',450,0,0);
/*!40000 ALTER TABLE `production_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_stages`
--

DROP TABLE IF EXISTS `production_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `production_order_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `progress_percent` int(11) NOT NULL DEFAULT 0,
  `time_taken` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_stages_production_order_id_foreign` (`production_order_id`),
  KEY `production_stages_employee_id_foreign` (`employee_id`),
  CONSTRAINT `production_stages_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `production_stages_production_order_id_foreign` FOREIGN KEY (`production_order_id`) REFERENCES `production_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_stages`
--

LOCK TABLES `production_stages` WRITE;
/*!40000 ALTER TABLE `production_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `production_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `low_stock_threshold` int(10) unsigned NOT NULL DEFAULT 10,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `production_cost` decimal(10,2) DEFAULT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `commission` decimal(5,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sole_name` varchar(255) DEFAULT NULL,
  `sole_color` varchar(255) DEFAULT NULL,
  `article_subtype` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `hsn_code` varchar(255) DEFAULT NULL,
  `sole_info` longtext DEFAULT NULL,
  `added_by_offline` tinyint(1) NOT NULL DEFAULT 0,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'High Heels','0997',NULL,0.00,10,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'fd',NULL,NULL,NULL,'2025-11-22 06:53:50','2025-11-25 05:47:46',NULL,'[{\"color\":\"brown\",\"sizes\":[\"39\",\"40\",\"41\",\"42\",\"43\",\"44\"],\"quantity\":0,\"hsn_code\":\"6402\",\"images\":[\"products\\/variations\\/1762607538_WhatsApp Image 2025-10-12 at 21.54.08.jpeg\"]}]','mens',0,'6402',NULL,1,0),(2,'boots','4560',NULL,0.00,10,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'fddfghjjhg',NULL,NULL,NULL,'2025-11-22 06:53:50','2025-11-22 06:53:50',NULL,'[{\"color\":\"black\",\"sizes\":[\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\"],\"quantity\":0,\"hsn_code\":\"6402\",\"images\":[\"products\\/variations\\/1762753314_WhatsApp Image 2025-10-12 at 21.54.08.jpeg\"]}]','women',0,NULL,NULL,1,0),(3,'Sneakers-premium','2018',NULL,0.00,10,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'fdsdfg',NULL,NULL,NULL,'2025-11-22 06:53:51','2025-11-22 06:53:51',NULL,'[{\"color\":\"black\",\"sizes\":[\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\"],\"quantity\":0,\"hsn_code\":\"6402\",\"images\":[\"products\\/variations\\/1762753798_WhatsApp Image 2025-09-09 at 11.00.25 (3).jpeg\"]}]','women',0,NULL,NULL,1,0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_client`
--

DROP TABLE IF EXISTS `quotation_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation_client` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotation_client_quotation_id_client_id_unique` (`quotation_id`,`client_id`),
  KEY `quotation_client_client_id_foreign` (`client_id`),
  CONSTRAINT `quotation_client_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotation_client_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_client`
--

LOCK TABLES `quotation_client` WRITE;
/*!40000 ALTER TABLE `quotation_client` DISABLE KEYS */;
INSERT INTO `quotation_client` VALUES (1,1,82,'2025-10-30 03:06:29','2025-10-30 03:06:29'),(2,1,84,'2025-10-30 03:06:29','2025-10-30 03:06:29'),(3,1,86,'2025-10-30 03:06:29','2025-10-30 03:06:29');
/*!40000 ALTER TABLE `quotation_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `quotation_no` varchar(255) NOT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `salesperson_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','sent','accepted','rejected','expired') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `tax_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_quotation_no_unique` (`quotation_no`),
  KEY `quotations_warehouse_id_foreign` (`warehouse_id`),
  KEY `quotations_salesperson_id_foreign` (`salesperson_id`),
  KEY `quotations_quotation_no_index` (`quotation_no`),
  KEY `quotations_client_id_foreign` (`client_id`),
  CONSTRAINT `quotations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `quotations_salesperson_id_foreign` FOREIGN KEY (`salesperson_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
INSERT INTO `quotations` VALUES (1,197,NULL,'ice cream','QTN-20251122-000001',NULL,2,'accepted',9600.00,4800.00,14400.00,'cgst',NULL,'2025-11-22 06:54:25','2025-11-22 06:54:30',0),(2,195,NULL,'orange','QTN-20251124-000001',NULL,2,'pending',1200.00,600.00,1800.00,'cgst',NULL,'2025-11-24 05:58:35','2025-11-24 05:58:35',0),(3,199,NULL,'MAX PAYNE','QTN-20251125-000001',NULL,2,'accepted',16800.00,8400.00,25200.00,'cgst',NULL,'2025-11-25 00:23:28','2025-11-25 00:23:39',0);
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `raw_materials`
--

DROP TABLE IF EXISTS `raw_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raw_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `per_unit_length` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raw_materials`
--

LOCK TABLES `raw_materials` WRITE;
/*!40000 ALTER TABLE `raw_materials` DISABLE KEYS */;
INSERT INTO `raw_materials` VALUES (1,'blade','peach','piece','Material',20.00,NULL,'2025-11-22 06:53:50','2025-11-22 06:53:50',0.00,NULL),(2,'new','brown','piece','Material',15.00,NULL,'2025-11-22 06:53:51','2025-11-22 06:53:51',0.00,NULL);
/*!40000 ALTER TABLE `raw_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(1,11),(2,1),(2,2),(2,3),(2,11),(3,1),(3,4),(4,1),(4,4),(4,5),(5,1),(5,6),(6,1),(6,6),(6,7),(7,1),(7,8),(7,12),(8,1),(8,8),(8,9),(8,12),(9,1),(10,1),(10,2),(10,3),(10,4),(10,5),(10,6),(10,7),(10,8),(10,9),(11,1),(11,2),(11,4),(11,6),(11,8),(12,1),(13,1),(13,2),(13,3),(13,4),(13,5),(13,6),(13,7),(13,8),(13,9),(13,10),(13,11),(13,12),(14,1),(14,2),(14,4),(14,6),(14,8),(15,1),(15,4),(15,10),(16,1),(17,1),(17,10),(18,1),(18,10),(19,1),(19,11),(20,1),(21,1),(22,1),(23,1),(23,4),(24,1),(24,8),(24,12),(25,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2025-09-14 23:33:48','2025-09-14 23:33:48'),(2,'HR Manager','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(3,'HR Employee','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(4,'Sales Manager','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(5,'Sales Employee','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(6,'Inventory Manager','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(7,'Inventory Employee','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(8,'Finance Manager','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(9,'Finance Employee','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(10,'Employee','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(11,'Manager','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(12,'Accountant','web','2025-09-14 23:39:19','2025-09-14 23:39:19'),(13,'client','web','2025-09-16 03:01:50','2025-09-16 03:01:50'),(14,'sales','web','2025-09-16 03:44:17','2025-09-16 03:44:17'),(15,'super_admin','web','2025-09-16 07:15:29','2025-09-16 07:15:29'),(16,'retail','web','2025-09-29 10:00:38','2025-09-29 10:00:38'),(17,'wholesale','web','2025-09-29 10:00:38','2025-09-29 10:00:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_advance_requests`
--

DROP TABLE IF EXISTS `salary_advance_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_advance_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `manager_comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advance_salary_requests_employee_id_foreign` (`employee_id`),
  KEY `advance_salary_requests_manager_id_foreign` (`manager_id`),
  CONSTRAINT `advance_salary_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advance_salary_requests_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_advance_requests`
--

LOCK TABLES `salary_advance_requests` WRITE;
/*!40000 ALTER TABLE `salary_advance_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary_advance_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_advances`
--

DROP TABLE IF EXISTS `salary_advances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_advances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `used_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `salary_advances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `salary_advances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_advances`
--

LOCK TABLES `salary_advances` WRITE;
/*!40000 ALTER TABLE `salary_advances` DISABLE KEYS */;
INSERT INTO `salary_advances` VALUES (1,3,15000.00,'2025-11-24','Pending','2025-11-25 01:10:46','2025-11-25 01:10:46',0.00);
/*!40000 ALTER TABLE `salary_advances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` date NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_product_id_foreign` (`product_id`),
  KEY `sales_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_commissions`
--

DROP TABLE IF EXISTS `sales_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `commission_date` date NOT NULL DEFAULT '2025-09-15',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_commissions`
--

LOCK TABLES `sales_commissions` WRITE;
/*!40000 ALTER TABLE `sales_commissions` DISABLE KEYS */;
INSERT INTO `sales_commissions` VALUES (1,7,10,1,0.00,'2025-09-15',NULL,'2025-09-16 23:37:49','2025-09-16 23:37:49'),(2,7,10,2,0.00,'2025-09-15',NULL,'2025-09-16 23:44:04','2025-09-16 23:44:04'),(3,7,10,3,0.00,'2025-09-15',NULL,'2025-09-16 23:46:38','2025-09-16 23:46:38'),(4,7,10,4,0.00,'2025-09-15',NULL,'2025-09-16 23:54:10','2025-09-16 23:54:10'),(5,7,10,5,0.00,'2025-09-15',NULL,'2025-09-16 23:57:11','2025-09-16 23:57:11'),(6,7,10,6,0.00,'2025-09-15',NULL,'2025-09-16 23:59:10','2025-09-16 23:59:10'),(7,7,10,7,0.00,'2025-09-15',NULL,'2025-09-17 00:00:58','2025-09-17 00:00:58'),(8,7,10,8,0.00,'2025-09-15',NULL,'2025-09-17 00:02:16','2025-09-17 00:02:16'),(9,7,10,9,0.00,'2025-09-15',NULL,'2025-09-17 00:04:57','2025-09-17 00:04:57'),(10,7,10,11,105.00,'2025-09-15',NULL,'2025-09-17 00:09:00','2025-09-17 00:09:00'),(11,7,10,12,250.00,'2025-09-15',NULL,'2025-09-17 00:21:02','2025-09-17 00:21:02'),(12,7,10,13,100.00,'2025-09-15',NULL,'2025-09-23 23:10:17','2025-09-23 23:10:17'),(13,7,10,41,0.00,'2025-09-15',NULL,'2025-09-26 00:49:20','2025-09-26 00:49:20');
/*!40000 ALTER TABLE `sales_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('pPgDwcK5UIV1lXmLVKl30uIpBb5lEkqEfPesVNeZ',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiMWYzTFVIMUFNaWl4dHZzSjhBMGs0ZmRablB5M3EyY012N1JlMEh6aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucy91bnJlYWQtY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MTY6ImlzX29mZmxpbmVfYWRtaW4iO2I6MTtzOjE0OiJjbGllbnRfZGV0YWlscyI7YTo0OntzOjQ6Im5hbWUiO3M6NjoicHJhYmh1IjtzOjU6ImVtYWlsIjtOO3M6NToicGhvbmUiO047czo1OiJicmFuZCI7Tjt9czo2OiJsb2NhbGUiO3M6MjoiZW4iO30=',1764071372);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'logo_path','logos/r4Fntdzx1XJrnn2vsdpT8aCnqZvRsemYQG4FtNLI.png','2025-09-14 23:21:46','2025-11-21 08:00:21'),(2,'default_region','in','2025-09-16 07:22:40','2025-09-16 07:22:40'),(3,'default_currency','INR','2025-09-16 07:22:40','2025-09-16 07:22:40'),(4,'company_name','Creative Shoes','2025-11-22 01:41:36','2025-11-25 00:20:48'),(5,'company_address','grd-floor,, room no.5,, municipal chawl no.6,, transit camp road,,\r\nbyculla,mumbai., Mumbai City, Maharashtra, 400011','2025-11-22 01:41:36','2025-11-25 00:20:48'),(6,'company_gst','27AMRPK6699L1ZV','2025-11-22 01:41:36','2025-11-25 00:20:48'),(7,'company_phone','4455778847','2025-11-22 01:41:36','2025-11-22 01:41:36'),(8,'company_email',NULL,'2025-11-22 01:41:36','2025-11-22 01:41:36'),(9,'company_website',NULL,'2025-11-22 01:41:36','2025-11-22 01:41:36'),(10,'company_logo','uploads/settings/company_logo_1763795683.jpeg','2025-11-22 01:44:43','2025-11-22 01:44:43');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soles`
--

DROP TABLE IF EXISTS `soles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `sole_type` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `qty_per_unit` decimal(10,2) DEFAULT 1.00,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sizes_qty` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`sizes_qty`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `available_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `available_qty_per_size` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_qty_per_size`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_default_sole` (`product_id`,`name`,`color`),
  UNIQUE KEY `unique_product_sole` (`product_id`,`name`,`color`),
  KEY `soles_product_id_foreign` (`product_id`),
  CONSTRAINT `soles_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soles`
--

LOCK TABLES `soles` WRITE;
/*!40000 ALTER TABLE `soles` DISABLE KEYS */;
INSERT INTO `soles` VALUES (1,NULL,'rambabu','black',NULL,0,1.00,35.00,'\"{\\\"35\\\":0,\\\"36\\\":0,\\\"37\\\":0,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}\"','2025-11-22 06:23:55','2025-11-25 05:48:29',0.00,NULL),(2,1,'sole-one','black',NULL,40,1.00,50.00,'\"{\\\"35\\\":0,\\\"36\\\":0,\\\"37\\\":0,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}\"','2025-11-22 06:53:50','2025-11-25 05:48:29',0.00,NULL),(3,1,'sole-two','beige',NULL,450,1.00,50.00,'\"{\\\"35\\\":0,\\\"36\\\":0,\\\"37\\\":0,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}\"','2025-11-22 06:53:50','2025-11-25 05:48:29',0.00,NULL),(4,1,'sole-three','peach',NULL,0,1.00,55.00,'\"{\\\"35\\\":0,\\\"36\\\":0,\\\"37\\\":0,\\\"38\\\":0,\\\"39\\\":0,\\\"40\\\":0,\\\"41\\\":0,\\\"42\\\":0,\\\"43\\\":0,\\\"44\\\":0}\"','2025-11-22 06:53:50','2025-11-25 05:48:29',0.00,NULL),(5,2,'kiran','peach',NULL,250,1.00,50.00,'{\"34\":0,\"35\":0,\"36\":0,\"37\":0,\"38\":0,\"39\":0,\"40\":50,\"41\":50,\"42\":50,\"43\":50,\"44\":50}','2025-11-22 06:53:50','2025-11-24 23:28:28',0.00,NULL),(6,NULL,'my name','peach',NULL,0,1.00,65.00,'\"[]\"','2025-11-22 07:54:42','2025-11-22 07:54:42',0.00,NULL),(7,NULL,'reels','brown',NULL,0,1.00,50.00,'\"[]\"','2025-11-24 07:47:51','2025-11-24 07:47:51',0.00,NULL),(8,NULL,'bhoomi','black',NULL,0,1.00,65.00,'\"[]\"','2025-11-24 23:21:32','2025-11-24 23:21:32',0.00,NULL);
/*!40000 ALTER TABLE `soles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reason` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `adjustment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_product_id_foreign` (`product_id`),
  KEY `stock_adjustments_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_adjustments`
--

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_arrivals`
--

DROP TABLE IF EXISTS `stock_arrivals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_arrivals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'sole',
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','received') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `party` varchar(255) DEFAULT NULL,
  `article_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_arrivals`
--

LOCK TABLES `stock_arrivals` WRITE;
/*!40000 ALTER TABLE `stock_arrivals` DISABLE KEYS */;
INSERT INTO `stock_arrivals` VALUES (1,5,'sole',NULL,'36',0.00,'received','2025-11-22 07:07:57','2025-11-22 07:09:32','2025-11-22 07:09:32','Purchase Order',3,NULL,NULL,NULL),(2,5,'sole',NULL,'37',0.00,'received','2025-11-22 07:07:57','2025-11-22 07:09:36','2025-11-22 07:09:36','Purchase Order',3,NULL,NULL,NULL),(3,5,'sole',NULL,'38',0.00,'received','2025-11-22 07:07:57','2025-11-22 07:09:40','2025-11-22 07:09:40','Purchase Order',3,NULL,NULL,NULL),(4,5,'sole',NULL,'39',0.00,'received','2025-11-22 07:07:57','2025-11-22 07:09:43','2025-11-22 07:09:43','Purchase Order',3,NULL,NULL,NULL),(5,5,'sole',NULL,'40',0.00,'received','2025-11-22 07:07:57','2025-11-22 07:09:47','2025-11-22 07:09:47','Purchase Order',3,NULL,NULL,NULL),(6,5,'sole_return',NULL,'36',-30.00,'pending','2025-11-22 07:13:22','2025-11-22 07:13:22','2025-11-22 07:13:22','Return to Supplier',3,NULL,NULL,NULL),(7,5,'sole_return',NULL,'37',-50.00,'pending','2025-11-22 07:13:22','2025-11-22 07:13:22','2025-11-22 07:13:22','Return to Supplier',3,NULL,NULL,NULL),(8,5,'sole',NULL,'36',0.00,'received','2025-11-22 07:17:22','2025-11-22 07:18:16','2025-11-22 07:18:16','Purchase Order',4,NULL,NULL,NULL),(9,5,'sole',NULL,'37',0.00,'received','2025-11-22 07:17:22','2025-11-22 07:18:20','2025-11-22 07:18:20','Purchase Order',4,NULL,NULL,NULL),(10,5,'sole',NULL,'38',0.00,'received','2025-11-22 07:17:22','2025-11-22 07:18:24','2025-11-22 07:18:24','Purchase Order',4,NULL,NULL,NULL),(11,5,'sole',NULL,'39',0.00,'received','2025-11-22 07:17:22','2025-11-22 07:18:27','2025-11-22 07:18:27','Purchase Order',4,NULL,NULL,NULL),(12,5,'sole_return',NULL,'36',-50.00,'pending','2025-11-22 07:18:00','2025-11-22 07:18:00','2025-11-22 07:18:00','Return to Supplier',4,NULL,NULL,NULL),(13,5,'sole_return',NULL,'37',-45.00,'pending','2025-11-22 07:18:00','2025-11-22 07:18:00','2025-11-22 07:18:00','Return to Supplier',4,NULL,NULL,NULL),(14,5,'sole_return',NULL,'38',-50.00,'pending','2025-11-22 07:18:00','2025-11-22 07:18:00','2025-11-22 07:18:00','Return to Supplier',4,NULL,NULL,NULL),(15,1,'sole',NULL,'36',0.00,'received','2025-11-24 02:48:19','2025-11-24 02:48:49','2025-11-24 02:48:49','Purchase Order',5,NULL,NULL,NULL),(16,1,'sole',NULL,'37',0.00,'received','2025-11-24 02:48:19','2025-11-24 02:48:54','2025-11-24 02:48:54','Purchase Order',5,NULL,NULL,NULL),(17,1,'sole',NULL,'38',0.00,'received','2025-11-24 02:48:19','2025-11-24 02:51:36','2025-11-24 02:51:36','Purchase Order',5,NULL,NULL,NULL),(18,1,'sole',NULL,'39',50.00,'pending','2025-11-24 02:48:19','2025-11-24 02:48:19',NULL,'Purchase Order',5,NULL,NULL,NULL),(19,1,'sole',NULL,'40',50.00,'pending','2025-11-24 02:48:19','2025-11-24 02:48:19',NULL,'Purchase Order',5,NULL,NULL,NULL),(20,1,'sole',NULL,'41',65.00,'pending','2025-11-24 02:48:19','2025-11-24 02:48:19',NULL,'Purchase Order',5,NULL,NULL,NULL),(21,6,'sole',NULL,'37',0.00,'received','2025-11-25 02:39:29','2025-11-25 04:55:32','2025-11-25 04:55:32','Purchase Order',1,NULL,NULL,NULL),(22,6,'sole',NULL,'38',0.00,'received','2025-11-25 02:39:29','2025-11-25 04:55:37','2025-11-25 04:55:37','Purchase Order',1,NULL,NULL,NULL),(23,6,'sole',NULL,'39',0.00,'received','2025-11-25 02:39:29','2025-11-25 04:55:41','2025-11-25 04:55:41','Purchase Order',1,NULL,NULL,NULL),(24,6,'sole_return',NULL,'37',-100.00,'pending','2025-11-25 04:54:48','2025-11-25 04:54:48','2025-11-25 04:54:48','Return to Supplier',1,NULL,NULL,NULL),(25,6,'sole_return',NULL,'38',-74.00,'pending','2025-11-25 04:54:48','2025-11-25 04:54:48','2025-11-25 04:54:48','Return to Supplier',1,NULL,NULL,NULL),(26,6,'sole_return',NULL,'39',-45.00,'pending','2025-11-25 04:54:48','2025-11-25 04:54:48','2025-11-25 04:54:48','Return to Supplier',1,NULL,NULL,NULL),(27,8,'sole',NULL,'38',20.00,'pending','2025-11-25 06:14:26','2025-11-25 06:14:26',NULL,'Purchase Order',6,NULL,NULL,NULL),(28,8,'sole',NULL,'39',20.00,'pending','2025-11-25 06:14:26','2025-11-25 06:14:26',NULL,'Purchase Order',6,NULL,NULL,NULL),(29,8,'sole',NULL,'40',20.00,'pending','2025-11-25 06:14:26','2025-11-25 06:14:26',NULL,'Purchase Order',6,NULL,NULL,NULL),(30,8,'sole',NULL,'41',20.00,'pending','2025-11-25 06:14:26','2025-11-25 06:14:26',NULL,'Purchase Order',6,NULL,NULL,NULL),(31,8,'sole',NULL,'42',20.00,'pending','2025-11-25 06:14:26','2025-11-25 06:14:26',NULL,'Purchase Order',6,NULL,NULL,NULL);
/*!40000 ALTER TABLE `stock_arrivals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `qty_after` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (1,1,2,'sole',-25.00,-25.00,'Deducted 25 for batch BATCH-20251122123512','2025-11-22 07:05:35','2025-11-22 07:05:35','35'),(2,1,2,'sole',-25.00,-25.00,'Deducted 25 for batch BATCH-20251122123512','2025-11-22 07:05:35','2025-11-22 07:05:35','36'),(3,1,2,'sole',-25.00,-25.00,'Deducted 25 for batch BATCH-20251122123512','2025-11-22 07:05:35','2025-11-22 07:05:35','37'),(4,1,2,'sole',-25.00,-25.00,'Deducted 25 for batch BATCH-20251122123512','2025-11-22 07:05:35','2025-11-22 07:05:35','38'),(5,NULL,5,'sole',55.00,55.00,'Received 55 for size 36','2025-11-22 07:09:32','2025-11-22 07:09:32','36'),(6,NULL,5,'sole',65.00,65.00,'Received 65 for size 37','2025-11-22 07:09:36','2025-11-22 07:09:36','37'),(7,NULL,5,'sole',75.00,75.00,'Received 75 for size 38','2025-11-22 07:09:40','2025-11-22 07:09:40','38'),(8,NULL,5,'sole',85.00,85.00,'Received 85 for size 39','2025-11-22 07:09:43','2025-11-22 07:09:43','39'),(9,NULL,5,'sole',95.00,95.00,'Received 95 for size 40','2025-11-22 07:09:47','2025-11-22 07:09:47','40'),(10,NULL,5,'sole',100.00,155.00,'Received 100 for size 36','2025-11-22 07:18:16','2025-11-22 07:18:16','36'),(11,NULL,5,'sole',55.00,120.00,'Received 55 for size 37','2025-11-22 07:18:20','2025-11-22 07:18:20','37'),(12,NULL,5,'sole',75.00,150.00,'Received 75 for size 38','2025-11-22 07:18:24','2025-11-22 07:18:24','38'),(13,NULL,5,'sole',50.00,135.00,'Received 50 for size 39','2025-11-22 07:18:27','2025-11-22 07:18:27','39'),(14,NULL,5,'sole',-50.00,105.00,'Returned 50 – deducted from stock','2025-11-22 07:19:18','2025-11-22 07:19:18','36'),(15,NULL,5,'sole',-45.00,75.00,'Returned 45 – deducted from stock','2025-11-22 07:19:18','2025-11-22 07:19:18','37'),(16,NULL,5,'sole',-50.00,100.00,'Returned 50 – deducted from stock','2025-11-22 07:19:18','2025-11-22 07:19:18','38'),(17,2,5,'sole',-20.00,-20.00,'Deducted 20 for batch BATCH-20251122125310','2025-11-22 07:24:05','2025-11-22 07:24:05','35'),(18,2,5,'sole',-50.00,55.00,'Deducted 50 for batch BATCH-20251122125310','2025-11-22 07:24:05','2025-11-22 07:24:05','36'),(19,2,5,'sole',-75.00,0.00,'Deducted 75 for batch BATCH-20251122125310','2025-11-22 07:24:05','2025-11-22 07:24:05','37'),(20,3,3,'sole',-20.00,-20.00,'Deducted 20 for batch BATCH-20251122131226','2025-11-22 07:42:56','2025-11-22 07:42:56','35'),(21,3,3,'sole',-20.00,-20.00,'Deducted 20 for batch BATCH-20251122131226','2025-11-22 07:42:56','2025-11-22 07:42:56','36'),(22,3,3,'sole',-20.00,-20.00,'Deducted 20 for batch BATCH-20251122131226','2025-11-22 07:42:56','2025-11-22 07:42:56','37'),(23,3,3,'sole',-20.00,-20.00,'Deducted 20 for batch BATCH-20251122131226','2025-11-22 07:42:56','2025-11-22 07:42:56','38'),(24,4,2,'sole',-20.00,-45.00,'Deducted 20 for batch BATCH-20251122131853','2025-11-22 07:49:16','2025-11-22 07:49:16','35'),(25,4,2,'sole',-20.00,-45.00,'Deducted 20 for batch BATCH-20251122131853','2025-11-22 07:49:16','2025-11-22 07:49:16','36'),(26,4,2,'sole',-20.00,-45.00,'Deducted 20 for batch BATCH-20251122131853','2025-11-22 07:49:16','2025-11-22 07:49:16','37'),(27,4,2,'sole',-20.00,-45.00,'Deducted 20 for batch BATCH-20251122131853','2025-11-22 07:49:16','2025-11-22 07:49:16','38'),(28,5,4,'sole',-45.00,-45.00,'Deducted 45 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','35'),(29,5,4,'sole',-50.00,-50.00,'Deducted 50 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','36'),(30,5,4,'sole',-50.00,-50.00,'Deducted 50 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','37'),(31,5,4,'sole',-50.00,-50.00,'Deducted 50 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','38'),(32,5,4,'sole',-50.00,-50.00,'Deducted 50 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','39'),(33,5,4,'sole',-50.00,-50.00,'Deducted 50 for batch BATCH-20251124052648','2025-11-23 23:57:32','2025-11-23 23:57:32','40'),(34,NULL,1,'sole',50.00,50.00,'Received 50 for size 36','2025-11-24 02:48:49','2025-11-24 02:48:49','36'),(35,NULL,1,'sole',50.00,50.00,'Received 50 for size 37','2025-11-24 02:48:54','2025-11-24 02:48:54','37'),(36,NULL,1,'sole',50.00,50.00,'Received 50 for size 38','2025-11-24 02:51:36','2025-11-24 02:51:36','38'),(37,6,3,'sole',-150.00,-150.00,'Deducted 150 for batch BATCH-20251125051721','2025-11-24 23:48:00','2025-11-24 23:48:00','35'),(38,6,3,'sole',-200.00,-200.00,'Deducted 200 for batch BATCH-20251125051721','2025-11-24 23:48:00','2025-11-24 23:48:00','36'),(39,6,3,'sole',-100.00,-100.00,'Deducted 100 for batch BATCH-20251125051721','2025-11-24 23:48:00','2025-11-24 23:48:00','37'),(40,NULL,6,'sole',100.00,100.00,'Received 100 for size 37','2025-11-25 04:55:32','2025-11-25 04:55:32','37'),(41,NULL,6,'sole',74.00,74.00,'Received 74 for size 38','2025-11-25 04:55:37','2025-11-25 04:55:37','38'),(42,NULL,6,'sole',45.00,45.00,'Received 45 for size 39','2025-11-25 04:55:41','2025-11-25 04:55:41','39'),(43,NULL,6,'sole',-100.00,0.00,'Returned 100 – deducted from stock','2025-11-25 04:56:17','2025-11-25 04:56:17','37'),(44,NULL,6,'sole',-74.00,0.00,'Returned 74 – deducted from stock','2025-11-25 04:56:17','2025-11-25 04:56:17','38'),(45,NULL,6,'sole',-45.00,0.00,'Returned 45 – deducted from stock','2025-11-25 04:56:17','2025-11-25 04:56:17','39');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `qty_available` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `in_transit_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_material_stock` (`item_id`,`type`,`size`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (1,2,'sole',0.00,'2025-11-22 07:05:35','2025-11-24 23:32:28','35',0.00,NULL),(2,2,'sole',0.00,'2025-11-22 07:05:35','2025-11-24 23:32:28','36',0.00,NULL),(3,2,'sole',0.00,'2025-11-22 07:05:35','2025-11-24 23:32:28','37',0.00,NULL),(4,2,'sole',20.00,'2025-11-22 07:05:35','2025-11-24 23:32:28','38',0.00,NULL),(5,5,'sole',0.00,'2025-11-22 07:09:32','2025-11-24 23:28:28','36',0.00,NULL),(6,5,'sole',0.00,'2025-11-22 07:09:36','2025-11-22 07:24:05','37',0.00,NULL),(7,5,'sole',0.00,'2025-11-22 07:09:40','2025-11-24 23:28:28','38',0.00,NULL),(8,5,'sole',0.00,'2025-11-22 07:09:43','2025-11-24 23:28:28','39',0.00,NULL),(9,5,'sole',50.00,'2025-11-22 07:09:47','2025-11-24 23:28:28','40',0.00,NULL),(10,5,'sole',0.00,'2025-11-22 07:24:05','2025-11-24 23:28:28','35',0.00,NULL),(11,3,'sole',-150.00,'2025-11-22 07:42:56','2025-11-24 23:48:00','35',0.00,NULL),(12,3,'sole',-200.00,'2025-11-22 07:42:56','2025-11-24 23:48:00','36',0.00,NULL),(13,3,'sole',-100.00,'2025-11-22 07:42:56','2025-11-24 23:48:00','37',0.00,NULL),(14,3,'sole',0.00,'2025-11-22 07:42:56','2025-11-24 23:34:52','38',0.00,NULL),(15,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','35',0.00,NULL),(16,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','36',0.00,NULL),(17,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','37',0.00,NULL),(18,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','38',0.00,NULL),(19,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','39',0.00,NULL),(20,4,'sole',0.00,'2025-11-23 23:57:32','2025-11-24 23:35:02','40',0.00,NULL),(21,1,'sole',50.00,'2025-11-24 02:48:49','2025-11-24 02:48:49','36',0.00,NULL),(22,1,'sole',50.00,'2025-11-24 02:48:54','2025-11-24 02:48:54','37',0.00,NULL),(23,1,'sole',50.00,'2025-11-24 02:51:36','2025-11-24 02:51:36','38',0.00,NULL),(24,5,'sole',50.00,'2025-11-24 23:28:28','2025-11-24 23:28:28','41',0.00,NULL),(25,5,'sole',50.00,'2025-11-24 23:28:28','2025-11-24 23:28:28','42',0.00,NULL),(26,5,'sole',50.00,'2025-11-24 23:28:28','2025-11-24 23:28:28','43',0.00,NULL),(27,5,'sole',50.00,'2025-11-24 23:28:28','2025-11-24 23:28:28','44',0.00,NULL),(28,2,'sole',20.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','39',0.00,NULL),(29,2,'sole',0.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','40',0.00,NULL),(30,2,'sole',0.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','41',0.00,NULL),(31,2,'sole',0.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','42',0.00,NULL),(32,2,'sole',0.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','43',0.00,NULL),(33,2,'sole',0.00,'2025-11-24 23:32:28','2025-11-24 23:32:28','44',0.00,NULL),(34,3,'sole',0.00,'2025-11-24 23:34:52','2025-11-24 23:34:52','39',0.00,NULL),(35,3,'sole',40.00,'2025-11-24 23:34:52','2025-11-24 23:41:59','40',0.00,NULL),(36,3,'sole',40.00,'2025-11-24 23:34:52','2025-11-24 23:41:59','41',0.00,NULL),(37,3,'sole',40.00,'2025-11-24 23:34:52','2025-11-24 23:41:59','42',0.00,NULL),(38,3,'sole',40.00,'2025-11-24 23:34:52','2025-11-24 23:41:59','43',0.00,NULL),(39,3,'sole',40.00,'2025-11-24 23:34:52','2025-11-24 23:41:59','44',0.00,NULL),(40,4,'sole',0.00,'2025-11-24 23:35:02','2025-11-24 23:35:02','41',0.00,NULL),(41,4,'sole',0.00,'2025-11-24 23:35:02','2025-11-24 23:35:02','42',0.00,NULL),(42,4,'sole',0.00,'2025-11-24 23:35:02','2025-11-24 23:35:02','43',0.00,NULL),(43,4,'sole',0.00,'2025-11-24 23:35:02','2025-11-24 23:35:02','44',0.00,NULL),(44,6,'sole',0.00,'2025-11-25 04:55:32','2025-11-25 04:56:17','37',0.00,NULL),(45,6,'sole',0.00,'2025-11-25 04:55:37','2025-11-25 04:56:17','38',0.00,NULL),(46,6,'sole',0.00,'2025-11-25 04:55:41','2025-11-25 04:56:17','39',0.00,NULL);
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_orders`
--

DROP TABLE IF EXISTS `supplier_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `order_date` date DEFAULT NULL,
  `expected_delivery` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supplier_orders_po_number_unique` (`po_number`),
  KEY `supplier_orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `supplier_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_orders`
--

LOCK TABLES `supplier_orders` WRITE;
/*!40000 ALTER TABLE `supplier_orders` DISABLE KEYS */;
INSERT INTO `supplier_orders` VALUES (1,3,'PO-FHDN5Q','[{\"type\":\"sole\",\"id\":\"5\",\"sizes_qty\":{\"35\":null,\"36\":\"55\",\"37\":\"65\",\"38\":\"75\",\"39\":\"85\",\"40\":\"95\",\"41\":null,\"42\":null,\"43\":null,\"44\":null},\"price\":\"120\",\"article_id\":null}]',45000.00,0.00,'pending','pending','2025-11-22',NULL,'2025-11-22 07:07:57','2025-11-22 07:07:57'),(2,4,'PO-CAN953','[{\"type\":\"sole\",\"id\":\"5\",\"sizes_qty\":{\"35\":null,\"36\":\"100\",\"37\":\"55\",\"38\":\"75\",\"39\":\"50\",\"40\":null,\"41\":null,\"42\":null,\"43\":null,\"44\":null},\"price\":\"100\",\"article_id\":null}]',28000.00,0.00,'pending','pending','2025-11-22',NULL,'2025-11-22 07:17:22','2025-11-22 07:17:22'),(3,5,'PO-CFLLS8','[{\"type\":\"sole\",\"id\":\"1\",\"sizes_qty\":{\"35\":null,\"36\":\"50\",\"37\":\"50\",\"38\":\"50\",\"39\":\"50\",\"40\":\"50\",\"41\":\"65\",\"42\":null,\"43\":null,\"44\":null},\"price\":\"100\",\"article_id\":null}]',31500.00,0.00,'pending','pending','2025-11-24',NULL,'2025-11-24 02:48:19','2025-11-24 02:48:19'),(4,1,'PO-SBVNJV','[{\"type\":\"sole\",\"id\":\"6\",\"sizes_qty\":{\"35\":null,\"36\":null,\"37\":\"100\",\"38\":\"74\",\"39\":\"45\",\"40\":null,\"41\":null,\"42\":null,\"43\":null,\"44\":null},\"price\":\"65\",\"article_id\":null}]',14235.00,14235.00,'paid','delivered','2025-11-25',NULL,'2025-11-25 02:39:29','2025-11-25 02:40:45'),(5,6,'PO-87TOX0','[{\"type\":\"sole\",\"id\":\"8\",\"sizes_qty\":{\"35\":null,\"36\":null,\"37\":null,\"38\":\"20\",\"39\":\"20\",\"40\":\"20\",\"41\":\"20\",\"42\":\"20\",\"43\":null,\"44\":null},\"price\":\"65\",\"article_id\":null}]',6500.00,6825.00,'paid','pending','2025-11-25',NULL,'2025-11-25 06:14:26','2025-11-25 06:14:26');
/*!40000 ALTER TABLE `supplier_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_returns`
--

DROP TABLE IF EXISTS `supplier_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_returns_supplier_id_foreign` (`supplier_id`),
  KEY `supplier_returns_order_id_foreign` (`order_id`),
  CONSTRAINT `supplier_returns_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `supplier_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `supplier_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_returns`
--

LOCK TABLES `supplier_returns` WRITE;
/*!40000 ALTER TABLE `supplier_returns` DISABLE KEYS */;
INSERT INTO `supplier_returns` VALUES (1,3,1,'{\"5_36\":{\"qty\":\"30\",\"sole_id\":\"5\",\"size\":\"36\",\"reason\":\"Defective\",\"other_reason\":null},\"5_37\":{\"qty\":\"50\",\"sole_id\":\"5\",\"size\":\"37\",\"reason\":\"Wrong Color\",\"other_reason\":null}}','completed',NULL,'2025-11-22 07:13:22','2025-11-22 07:14:04'),(2,4,2,'{\"5_36\":{\"qty\":\"50\",\"sole_id\":\"5\",\"size\":\"36\",\"reason\":\"Defective\",\"other_reason\":null},\"5_37\":{\"qty\":\"45\",\"sole_id\":\"5\",\"size\":\"37\",\"reason\":\"Wrong Color\",\"other_reason\":null},\"5_38\":{\"qty\":\"50\",\"sole_id\":\"5\",\"size\":\"38\",\"reason\":\"Damaged\",\"other_reason\":null}}','completed',NULL,'2025-11-22 07:18:00','2025-11-22 07:19:18'),(3,1,4,'{\"6_37\":{\"qty\":\"100\",\"sole_id\":\"6\",\"size\":\"37\",\"reason\":\"Damaged\",\"other_reason\":null},\"6_38\":{\"qty\":\"74\",\"sole_id\":\"6\",\"size\":\"38\",\"reason\":\"Wrong Size\",\"other_reason\":null},\"6_39\":{\"qty\":\"45\",\"sole_id\":\"6\",\"size\":\"39\",\"reason\":\"Damaged\",\"other_reason\":null}}','completed',NULL,'2025-11-25 04:54:47','2025-11-25 04:56:17');
/*!40000 ALTER TABLE `supplier_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `material_types` text DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Mohammed Abdul Qadeer','traders','','','','','06AADCN3227C1ZZ','2025-11-22 06:58:22','2025-11-22 06:58:22'),(2,'Appeal','APPEAL FOOTWEAR Pvt Ltd','','','','','','2025-11-22 06:58:22','2025-11-22 06:58:22'),(3,'Bishal Special','Kiran Hero','','','','','','2025-11-22 06:58:22','2025-11-22 06:58:22'),(4,'Capstan','CAPSTAN RUBBER INDIA','','','','','','2025-11-22 06:58:22','2025-11-22 06:58:22'),(5,'mithun','Prudent','','','','Leather, Rubber, Cork soles','27AAACM4754E1ZL','2025-11-22 06:58:22','2025-11-22 06:58:22'),(6,'bhushan','Cosmopolitan','bhusan@gmail.com','5511442288','Near metro station pillar no 1123 , Lakdikapool, , Hyderabad, telangana','Leather, Rubber, Cork soles , Glue, White rubber, Blue buckle etc','36ABCDE1234F1Z5','2025-11-25 05:55:07','2025-11-25 05:55:07');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supply_chain_stages`
--

DROP TABLE IF EXISTS `supply_chain_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_chain_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply_chain_stages`
--

LOCK TABLES `supply_chain_stages` WRITE;
/*!40000 ALTER TABLE `supply_chain_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `supply_chain_stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_requests`
--

DROP TABLE IF EXISTS `training_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `training_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `proposed_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_requests_employee_id_foreign` (`employee_id`),
  KEY `training_requests_manager_id_foreign` (`manager_id`),
  CONSTRAINT `training_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `training_requests_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_requests`
--

LOCK TABLES `training_requests` WRITE;
/*!40000 ALTER TABLE `training_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(255) NOT NULL DEFAULT 'sa',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_approved_by_foreign` (`approved_by`),
  KEY `txn_ref_idx` (`reference_type`,`reference_id`),
  CONSTRAINT `transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'tyui','expense','salary',NULL,NULL,'pending',NULL,NULL,50000.00,'2025-11-24','2025-11-24 02:45:00','2025-11-24 02:45:00','in',5.00,2500.00,52500.00),(2,'ertyuiosdfghjmk','income','invoice_payment',NULL,NULL,'approved',2,'2025-11-24 05:59:35',6000.00,'2025-11-24','2025-11-24 05:30:30','2025-11-24 05:59:35','in',5.00,300.00,6300.00),(3,'example','expense','expense_claim',NULL,NULL,'pending',NULL,NULL,12000.00,'2025-11-25','2025-11-25 04:43:51','2025-11-25 04:43:51','in',0.00,0.00,12000.00);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `online_id` int(11) DEFAULT NULL,
  `offline_id` int(11) DEFAULT NULL,
  `sales_rep_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `iqama_number` varchar(255) DEFAULT NULL,
  `iqama_expiry_date` date DEFAULT NULL,
  `health_card_number` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `dashboard_cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dashboard_cards`)),
  `custom_card_labels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_card_labels`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `is_remote` tinyint(1) NOT NULL DEFAULT 0,
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `business_name` varchar(255) DEFAULT NULL,
  `company_document` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `gst_certificate` varchar(255) DEFAULT NULL,
  `electricity_certificate` varchar(255) DEFAULT NULL,
  `category` enum('wholesale','retail') DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `aadhar_certificate` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `contact_person` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `alt_email` varchar(255) DEFAULT NULL,
  `alt_phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `seen_onboarding` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'Admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_manager_id_foreign` (`manager_id`),
  KEY `users_sales_rep_id_foreign` (`sales_rep_id`),
  CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_sales_rep_id_foreign` FOREIGN KEY (`sales_rep_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,NULL,NULL,NULL,'Admin','offline_admin@example.com','IN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$VwIA8a7H/gh/zWASJQDs8.FusVvNYuFDa5uaBDDL1CxgkwOzfbw6y','sejNsk0MR5KcP7gwLzffwTKFjsrlT4MKxH4wBNQApIATpj4nEKeKpLqnPsRA','\"[\\\"finance_summary\\\",\\\"hr_summary\\\",\\\"production_kpis\\\",\\\"low_stock_alerts\\\",\\\"charts_section\\\",\\\"total_labors\\\",\\\"total_invoices\\\"]\"','\"{\\\"total_labors\\\":{\\\"label\\\":\\\"total labors\\\",\\\"url\\\":null,\\\"color\\\":\\\"gray\\\"},\\\"total_invoices\\\":{\\\"label\\\":\\\"Total Invoices\\\",\\\"url\\\":null,\\\"color\\\":\\\"teal\\\"}}\"','2025-09-14 23:34:36','2025-11-18 01:07:23',0,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(194,NULL,NULL,NULL,'Metro','metro_690caa3a1d488@example.com',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'$2y$12$1n3ZYjicR8UjLba6TpD0d.aIiYcLIfhl2HBQvz1q2Hwc/7CYch2Du',NULL,NULL,NULL,'2025-11-22 06:52:52','2025-11-22 06:52:52',0,NULL,0,0,'Metro',NULL,'178625391827356',NULL,NULL,'retail',NULL,NULL,'approved',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(195,NULL,NULL,NULL,'Creative','creative_690caa3a632fa@example.com',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'$2y$12$IBJs6yAxM8AUk1USjXyFzeSmj7ODRqN1JWEHN1mFpCS6W5Vm/5YTq',NULL,NULL,NULL,'2025-11-22 06:52:52','2025-11-22 06:52:52',0,NULL,0,0,'Creative',NULL,'17825637',NULL,NULL,'retail',NULL,NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(196,NULL,NULL,NULL,'Metro','metro_690c4d72903ce@example.com',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'$2y$12$O6/bTsKeugm1K31Loa23lOctF.8EkUnqIHUaAZX8HvrStGUcRRj8G',NULL,NULL,NULL,'2025-11-22 06:52:53','2025-11-22 06:52:53',0,NULL,0,0,'Metro',NULL,'78601237612093',NULL,NULL,'retail',NULL,NULL,'approved',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(197,NULL,NULL,NULL,'KARIM','karim_690c4d72d690d@example.com',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'$2y$12$oyeNtcS7nfoQRpO5Aqu83.zYesHxHx3GJUgTMtOarwgOw0voMBerK',NULL,NULL,NULL,'2025-11-22 06:52:53','2025-11-22 06:52:53',0,NULL,0,0,'PADYATRY',NULL,'27AMRPL6699L1ZV',NULL,NULL,'retail',NULL,NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(198,NULL,NULL,NULL,'Rahul','rahul@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$LspDc07veW3pSDYlVASM1eXj1ihoLVaeHtruUFqqg5OguSpuqA30K',NULL,NULL,NULL,'2025-11-24 07:11:06','2025-11-24 07:11:06',0,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin'),(199,NULL,NULL,NULL,'prabhu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$xjFNUWl/oFeZ6Nm4v6VKoeX.Xfz1xvmIDJJHijLCne7UGWcMG9T/m',NULL,NULL,NULL,'2025-11-25 00:22:49','2025-11-25 00:22:49',0,NULL,0,0,'mexico',NULL,'29ABCDE1234F1Z6','client_documents/GgQ2SXJ5y5Ue0WOlvCdNyZhaQsON8vETVx8lV4kl.png',NULL,'wholesale',NULL,NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'Admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warning_letters`
--

DROP TABLE IF EXISTS `warning_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warning_letters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `issue_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'issued',
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warning_letters_employee_id_foreign` (`employee_id`),
  CONSTRAINT `warning_letters_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warning_letters`
--

LOCK TABLES `warning_letters` WRITE;
/*!40000 ALTER TABLE `warning_letters` DISABLE KEYS */;
/*!40000 ALTER TABLE `warning_letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `worker_payrolls`
--

DROP TABLE IF EXISTS `worker_payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `worker_payrolls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `process_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `finance_approver_id` bigint(20) unsigned DEFAULT NULL,
  `disbursed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `worker_payrolls_employee_id_foreign` (`employee_id`),
  KEY `worker_payrolls_batch_id_foreign` (`batch_id`),
  KEY `worker_payrolls_process_id_foreign` (`process_id`),
  KEY `worker_payrolls_manager_id_foreign` (`manager_id`),
  KEY `worker_payrolls_finance_approver_id_foreign` (`finance_approver_id`),
  CONSTRAINT `worker_payrolls_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worker_payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `worker_payrolls_finance_approver_id_foreign` FOREIGN KEY (`finance_approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worker_payrolls_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `worker_payrolls_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `production_processes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `worker_payrolls`
--

LOCK TABLES `worker_payrolls` WRITE;
/*!40000 ALTER TABLE `worker_payrolls` DISABLE KEYS */;
INSERT INTO `worker_payrolls` VALUES (1,1,NULL,NULL,1125.00,NULL,NULL,1125.00,'2025-11-24','paid',NULL,NULL,NULL,'2025-11-24 02:53:18','2025-11-24 02:53:18'),(2,3,NULL,NULL,2025.00,NULL,NULL,2025.00,'2025-11-24','paid',NULL,NULL,NULL,'2025-11-24 02:53:30','2025-11-24 02:53:30'),(3,2,NULL,NULL,4400.00,NULL,NULL,4400.00,'2025-11-24','paid',NULL,NULL,NULL,'2025-11-24 02:53:39','2025-11-24 02:53:39');
/*!40000 ALTER TABLE `worker_payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workers`
--

DROP TABLE IF EXISTS `workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workers`
--

LOCK TABLES `workers` WRITE;
/*!40000 ALTER TABLE `workers` DISABLE KEYS */;
/*!40000 ALTER TABLE `workers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-25 17:20:02
