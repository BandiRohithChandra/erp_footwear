SET FOREIGN_KEY_CHECKS = 0;
-- MySQL dump 10.13  Distrib 9.3.0, for macos15.2 (arm64)
--
-- Host: localhost    Database: erp_system
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `is_remote` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,2,'2025-06-02','05:10:38','05:10:41',NULL,NULL,0,'present','2025-06-01 23:40:38','2025-06-01 23:40:41'),(2,4,'2025-06-02','05:36:16','05:36:20',NULL,NULL,0,'present','2025-06-02 00:06:16','2025-06-02 00:06:20');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('erp_system_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:30:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"manage hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:15;i:4;i:16;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"view hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:15;i:4;i:16;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"manage sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:11;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:11;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"manage finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:14;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"view finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:9;i:2;i:14;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:9:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"view notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:10:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:5:\"sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"view production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:10;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"manage production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"view employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"access employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:10;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"manage productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:16:\"view productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:20:\"manage notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"manage roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:21:\"access manager portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:16;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:15:\"sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"view sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:22:\"manage sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:17:\"manage quotations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:10:\"production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:10;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:18:\"process production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:14:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"HR Manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Sales Manager\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:2:\"hr\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"HR Employee\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"salesperson\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"Sales Employee\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"Inventory Manager\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"Inventory Employee\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Finance Manager\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:10:\"accountant\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"Finance Employee\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}}}',1749198155);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
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
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SAR',
  `hire_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_email_unique` (`email`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Mohammed Shoebuddin','shoeb@titlysolutions.com','Director','Management',10000.00,'SAR','2025-01-15','2025-05-29 03:56:55','2025-05-29 03:56:55',13),(2,'Admin','admin@example.com','Employee','Default Department',0.00,'SAR','2025-06-02','2025-06-01 23:34:41','2025-06-01 23:34:41',2),(3,'Hr manager','hr_manager@example.com','Employee','Default Department',0.00,'SAR','2025-06-02','2025-06-01 23:34:41','2025-06-01 23:34:41',3),(4,'Sales manager','sales_manager@example.com','Employee','Default Department',0.00,'SAR','2025-06-02','2025-06-01 23:34:41','2025-06-01 23:34:41',5);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exit_entry_requests`
--

DROP TABLE IF EXISTS `exit_entry_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exit_entry_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `exit_date` date NOT NULL,
  `re_entry_date` date NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_transfers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `from_warehouse_id` bigint unsigned NOT NULL,
  `to_warehouse_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `transfer_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `items` json DEFAULT NULL,
  `payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `production_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (4,1,14,57.50,'[{\"name\": \"Red Rose\", \"total\": 50, \"quantity\": 1, \"product_id\": 1, \"unit_price\": \"50.00\"}]','grace','2025-06-12','paid','2025-05-31 01:25:15','2025-05-31 01:33:26'),(5,2,14,115.00,'[{\"name\": \"Blue Rose\", \"total\": 100, \"quantity\": 1, \"product_id\": 2, \"unit_price\": \"100.00\"}]','grace','2025-06-01','paid','2025-05-31 01:34:21','2025-05-31 01:34:30'),(6,3,14,172.50,'[{\"name\": \"Red Rose\", \"total\": 50, \"quantity\": 1, \"product_id\": 1, \"unit_price\": \"50.00\"}, {\"name\": \"Blue Rose\", \"total\": 100, \"quantity\": 1, \"product_id\": 2, \"unit_price\": \"100.00\"}]','grace','2025-06-01','paid','2025-05-31 01:57:41','2025-05-31 01:58:11');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"0b01fafa-ac1f-4f38-8f75-aff03b2ccae1\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"cdae1c25-4134-4fe8-96b8-26c8c34eaf09\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-27 06:35:34\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748327734,\"delay\":null}',0,NULL,1748327734,1748327734),(2,'default','{\"uuid\":\"2016003e-b91b-43d7-8dc4-64fc8d9e80a8\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"f27a248e-d087-4e67-bdef-35749c42aecf\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-27 07:44:13\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748331853,\"delay\":null}',0,NULL,1748331853,1748331853),(3,'default','{\"uuid\":\"384936f2-9089-4460-9abc-07f9c6074369\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"7a2e331c-c1c5-4563-9519-789d96def6e2\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-31 09:04:12\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748682252,\"delay\":null}',0,NULL,1748682252,1748682252);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `manager_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_24_072107_create_products_table',1),(5,'2025_05_24_075023_create_transactions_table',1),(6,'2025_05_24_084541_create_employees_table',1),(7,'2025_05_24_084615_create_payrolls_table',1),(8,'2025_05_24_091422_add_tax_fields_to_transactions_table',1),(9,'2025_05_24_091500_add_tax_fields_to_payrolls_table',1),(10,'2025_05_24_093343_add_tax_rate_to_transactions_and_payrolls',1),(11,'2025_05_24_095031_add_currency_to_employees',1),(12,'2025_05_24_095511_add_tax_fields_to_products',1),(13,'2025_05_24_101800_create_settings_table',1),(14,'2025_05_24_104921_create_sales_table',1),(15,'2025_05_24_105219_create_attendances_table',1),(16,'2025_05_24_105537_create_warehouses_table',1),(17,'2025_05_24_105616_modify_products_table_for_warehouses',1),(18,'2025_05_24_105645_create_product_warehouse_table',1),(19,'2025_05_24_105739_create_stock_adjustments_table',1),(20,'2025_05_24_105819_create_inventory_transfers_table',1),(21,'2025_05_24_105948_add_warehouse_id_to_sales_table',1),(22,'2025_05_24_112653_add_total_amount_to_products_table',1),(23,'2025_05_24_114347_create_permission_tables',1),(24,'2025_05_24_122513_create_activity_log_table',1),(25,'2025_05_24_122514_add_event_column_to_activity_log_table',1),(26,'2025_05_24_122515_add_batch_uuid_column_to_activity_log_table',1),(27,'2025_05_26_055134_create_notifications_table',1),(28,'2025_05_26_065750_make_position_nullable_in_employees_table',1),(29,'2025_05_26_065826_make_amount_nullable_in_payrolls_table',1),(30,'2025_05_26_071116_add_discount_to_sales_table',1),(31,'2025_05_26_071139_create_inventory_table',1),(32,'2025_05_26_071617_add_unit_price_to_products_table',1),(33,'2025_05_26_090849_add_profile_picture_to_users_table',1),(34,'2025_05_26_092421_create_leave_requests_table',1),(35,'2025_05_26_092440_create_advance_salary_requests_table',1),(36,'2025_05_26_092459_add_manager_id_and_is_remote_to_users_table',1),(37,'2025_05_26_105038_add_manager_id_to_users_table',1),(38,'2025_05_26_110311_add_user_id_to_employees_table',1),(39,'2025_05_26_111929_rename_advance_salary_requests_to_salary_advance_requests',1),(40,'2025_05_26_114640_make_manager_id_nullable_in_leave_requests_and_salary_advance_requests',1),(41,'2025_05_26_121342_add_force_password_change_to_users_table',1),(42,'2025_05_26_125004_create_quotations_table',1),(43,'2025_05_26_125046_create_orders_table',1),(45,'2025_05_26_125121_create_invoices_table',2),(46,'2025_05_29_103243_add_phone_and_address_to_users_table',3),(47,'2025_05_29_103531_add_fields_to_quotations_table',4),(48,'2025_05_29_132610_add_client_id_to_quotations_table',5),(49,'2025_05_29_132820_create_product_quotation_table',6),(50,'2025_05_29_133538_make_salesperson_id_nullable_in_quotations_table',7),(51,'2025_05_29_135056_drop_total_amount_from_quotations_table',8),(52,'2025_05_29_144311_create_production_orders_table',9),(53,'2025_05_31_064052_add_items_to_invoices_table',10),(54,'2025_05_31_065344_fix_invoices_order_id_foreign_key',11),(55,'2025_05_31_082407_create_warning_letters_table',12),(56,'2025_05_31_083226_add_saudi_fields_to_users_table',13),(57,'2025_05_31_083705_add_status_to_attendances_table',14),(58,'2025_05_31_084444_create_exit_entry_requests_table',15),(59,'2025_06_01_063214_remove_region_from_users_table',16),(60,'2025_06_01_071726_add_country_to_users_table',17),(61,'2025_06_01_093227_update_exit_entry_requests_table_employee_id_foreign_key',18),(62,'2025_06_02_122923_add_status_and_category_to_transactions_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',2),(2,'App\\Models\\User',3),(3,'App\\Models\\User',4),(4,'App\\Models\\User',5),(5,'App\\Models\\User',6),(6,'App\\Models\\User',7),(7,'App\\Models\\User',8),(8,'App\\Models\\User',9),(9,'App\\Models\\User',10),(10,'App\\Models\\User',13),(12,'App\\Models\\User',14);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `notifications` VALUES ('04dbafca-cba0-467c-b658-e50954e0164c','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"message\":\"You have been issued a warning letter: 1\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\\/warning-letters\\/5\"}','2025-06-02 05:41:52','2025-06-01 23:49:47','2025-06-02 05:41:52'),('11ce9644-2d5d-4af8-a1c2-86ce04892573','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"message\":\"You have been issued a warning letter: asd\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\\/warning-letters\\/6\"}','2025-06-02 05:41:51','2025-06-02 02:54:20','2025-06-02 05:41:51'),('717c882f-9017-4432-8b95-891773b8bc93','App\\Notifications\\WarningLetterIssued','App\\Models\\User',5,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: Absent\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}',NULL,'2025-06-01 04:25:16','2025-06-01 04:25:16'),('7a2e331c-c1c5-4563-9519-789d96def6e2','App\\Notifications\\TestNotification','App\\Models\\User',3,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-05-31 03:34:12','2025-05-31 03:34:12'),('c14bad1e-8901-4467-a729-f6a59439b6d2','App\\Notifications\\WarningLetterIssued','App\\Models\\User',3,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: xyz\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}',NULL,'2025-06-01 04:24:37','2025-06-01 04:24:37'),('cdae1c25-4134-4fe8-96b8-26c8c34eaf09','App\\Notifications\\TestNotification','App\\Models\\User',2,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}','2025-06-02 05:41:53','2025-05-27 01:05:34','2025-06-02 05:41:53'),('d114ebb7-d08d-4205-bd26-544f90ee4f5c','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: 1\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}','2025-06-01 04:07:22','2025-06-01 01:35:21','2025-06-01 04:07:22'),('f27a248e-d087-4e67-bdef-35749c42aecf','App\\Notifications\\TestNotification','App\\Models\\User',5,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-05-27 02:14:13','2025-05-27 02:14:13');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_quotation_id_foreign` (`quotation_id`),
  CONSTRAINT `orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payrolls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sa',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payrolls_employee_id_foreign` (`employee_id`),
  CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
INSERT INTO `payrolls` VALUES (1,1,10000.00,'2025-05-05','April','2025-05-29 04:15:23','2025-05-29 04:15:23','sa',0.00,0.00,10000.00);
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage hr','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(2,'view hr','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(3,'manage sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(4,'view sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(5,'manage inventory','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(6,'view inventory','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(7,'manage finance','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(8,'view finance','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(9,'manage settings','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(10,'view dashboard','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(11,'view reports','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(12,'manage users','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(13,'view notifications','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(14,'sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(15,'view production','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(16,'manage production','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(17,'view employee portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(18,'access employee portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(19,'manage productions','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(20,'view productions','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(21,'manage notifications','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(22,'manage roles','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(23,'view roles','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(24,'access manager portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(25,'sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(26,'view sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(27,'manage sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(28,'manage quotations','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(29,'production','web','2025-05-31 00:42:12','2025-05-31 00:42:12'),(30,'process production','web','2025-05-31 00:42:43','2025-05-31 00:42:43');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_quotation`
--

DROP TABLE IF EXISTS `product_quotation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_quotation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_quotation_quotation_id_foreign` (`quotation_id`),
  KEY `product_quotation_product_id_foreign` (`product_id`),
  CONSTRAINT `product_quotation_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_quotation_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_quotation`
--

LOCK TABLES `product_quotation` WRITE;
/*!40000 ALTER TABLE `product_quotation` DISABLE KEYS */;
INSERT INTO `product_quotation` VALUES (1,1,1,1,50.00,NULL,NULL),(4,3,1,1,50.00,NULL,NULL),(5,2,1,1,50.00,NULL,NULL),(6,2,2,1,100.00,NULL,NULL),(7,4,2,1,100.00,NULL,NULL),(8,5,2,1,100.00,NULL,NULL),(9,5,1,1,50.00,NULL,NULL);
/*!40000 ALTER TABLE `product_quotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_warehouse`
--

DROP TABLE IF EXISTS `product_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_warehouse` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_warehouse_product_id_foreign` (`product_id`),
  KEY `product_warehouse_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `product_warehouse_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_warehouse`
--

LOCK TABLES `product_warehouse` WRITE;
/*!40000 ALTER TABLE `product_warehouse` DISABLE KEYS */;
INSERT INTO `product_warehouse` VALUES (1,1,1,90,'2025-05-27 01:29:02','2025-05-27 01:41:47'),(2,1,2,200,'2025-05-27 01:29:02','2025-05-27 01:29:02'),(3,2,1,0,'2025-05-27 01:29:18','2025-05-29 04:13:23'),(4,2,2,100,'2025-05-27 01:29:18','2025-05-27 01:29:18');
/*!40000 ALTER TABLE `product_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_orders`
--

DROP TABLE IF EXISTS `production_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_orders_quotation_id_foreign` (`quotation_id`),
  CONSTRAINT `production_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_orders`
--

LOCK TABLES `production_orders` WRITE;
/*!40000 ALTER TABLE `production_orders` DISABLE KEYS */;
INSERT INTO `production_orders` VALUES (1,3,'delivered','2025-05-29 09:25:01','2025-05-31 00:56:14'),(2,4,'delivered','2025-05-31 01:34:03','2025-05-31 01:34:10'),(3,5,'delivered','2025-05-31 01:55:33','2025-05-31 01:56:29');
/*!40000 ALTER TABLE `production_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `low_stock_threshold` int unsigned NOT NULL DEFAULT '10',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Red Rose','2001',50.00,0.00,10,0.00,0.00,50.00,NULL,'Red','2025-05-27 01:29:02','2025-05-27 01:29:02'),(2,'Blue Rose','2002',100.00,0.00,10,0.00,0.00,100.00,NULL,'Blue','2025-05-27 01:29:18','2025-05-27 01:29:18');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `salesperson_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_salesperson_id_foreign` (`salesperson_id`),
  KEY `quotations_warehouse_id_foreign` (`warehouse_id`),
  KEY `quotations_client_id_foreign` (`client_id`),
  CONSTRAINT `quotations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_salesperson_id_foreign` FOREIGN KEY (`salesperson_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
INSERT INTO `quotations` VALUES (1,14,2,'cancelled',50.00,7.50,57.50,'2025-05-29 08:33:43','2025-05-29 09:07:51',1),(2,14,2,'approved',150.00,22.50,172.50,'2025-05-29 09:06:09','2025-05-29 09:06:19',1),(3,14,2,'approved',50.00,7.50,57.50,'2025-05-29 09:24:57','2025-05-29 09:25:01',1),(4,14,2,'approved',100.00,15.00,115.00,'2025-05-31 01:34:00','2025-05-31 01:34:03',2),(5,14,2,'approved',150.00,22.50,172.50,'2025-05-31 01:55:16','2025-05-31 01:55:33',1);
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
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
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(1,2),(2,2),(10,2),(11,2),(13,2),(17,2),(18,2),(21,2),(2,3),(10,3),(13,3),(1,4),(3,4),(10,4),(11,4),(13,4),(15,4),(18,4),(29,4),(4,5),(10,5),(13,5),(5,6),(10,6),(11,6),(13,6),(6,7),(10,7),(13,7),(7,8),(10,8),(11,8),(13,8),(8,9),(10,9),(13,9),(13,10),(15,10),(18,10),(29,10),(3,11),(4,11),(7,14),(8,14),(1,15),(2,15),(1,16),(2,16),(24,16);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(2,'HR Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(3,'HR Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(4,'Sales Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(5,'Sales Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(6,'Inventory Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(7,'Inventory Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(8,'Finance Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(9,'Finance Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(10,'Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(11,'salesperson','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(12,'client','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(14,'accountant','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(15,'hr','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(16,'manager','web','2025-05-29 04:49:38','2025-05-29 04:49:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_advance_requests`
--

DROP TABLE IF EXISTS `salary_advance_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salary_advance_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `manager_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` date NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_product_id_foreign` (`product_id`),
  KEY `sales_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,10,100.00,0.00,0.00,0.00,1000.00,'2025-05-27','King Florist','King@Florist.com','red','2025-05-27 01:41:47','2025-05-27 01:41:47',1),(2,2,100,2000.00,0.00,0.00,0.00,200000.00,'2025-05-29','kya kare','King@Florist.com','lkzcjh','2025-05-29 04:13:14','2025-05-29 04:13:23',1);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('Am0XnaLqzJb5Gbxp7s0BUhAMqnwZc64KkGFLhNlL',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaGtzWEtHVTB1T3JkcXBMS1VySTNDdGRGNHhCaXMzaEJrSXA0MzZ1ZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucy91bnJlYWQtY291bnQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6NjoibG9jYWxlIjtzOjI6ImFyIjt9',1749111877);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'default_region','in','2025-05-27 01:05:14','2025-06-02 06:42:58'),(2,'default_currency','INR','2025-05-27 01:05:14','2025-06-02 06:42:58'),(3,'logo_path','logos/2IPG1AMAI57wrCMIIBOr2CtSRPMUElV4UWv1uomk.png','2025-06-02 00:56:00','2025-06-02 05:55:55');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sa',
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_approved_by_foreign` (`approved_by`),
  CONSTRAINT `transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'Sale of Red Rose to King Florist','income','Sales','pending',NULL,NULL,1000.00,'2025-05-27','2025-05-27 01:41:47','2025-05-27 01:41:47','sa',NULL,0.00,1000.00),(2,'Sale of Blue Rose to kya kare','income','Sales','pending',NULL,NULL,200000.00,'2025-05-29','2025-05-29 04:13:14','2025-05-29 04:13:23','in',NULL,0.00,200000.00),(3,'Rent','expense','Rent','pending',NULL,NULL,20500.00,'2025-02-05','2025-05-29 04:13:58','2025-05-29 04:13:58','sa',0.00,0.00,20500.00),(4,'Rent','expense','Rent','pending',NULL,NULL,20500.00,'2025-03-05','2025-05-29 04:14:14','2025-05-29 04:14:14','sa',0.00,0.00,20500.00),(5,'Rent','expense','Rent','pending',NULL,NULL,20500.00,'2025-04-05','2025-05-29 04:14:31','2025-05-29 04:14:31','sa',0.00,0.00,20500.00);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iqama_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iqama_expiry_date` date DEFAULT NULL,
  `health_card_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `is_remote` tinyint(1) NOT NULL DEFAULT '0',
  `force_password_change` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_manager_id_foreign` (`manager_id`),
  CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$LgbRy/Es8tfIc.GMyPQbn.zNt2fFh1x84PJ7Sf2/y6MvHAmKa2gIK','njcU3Q43rR','2025-05-27 01:05:15','2025-05-27 01:05:15',NULL,0,0),(2,'Admin','admin@example.com','IN',NULL,NULL,NULL,NULL,NULL,'profile_pictures/Q1nf4kZHLGrJKqWAExvCbtSgzWaDMdnxh9U2SEhh.png','2025-05-27 01:05:15','$2y$12$ML0RvUIN2QVQxRoxjenSDuYtUVKpcvlrQ82dAd0Wxf/2p7TQgjXFO',NULL,'2025-05-27 01:05:15','2025-06-02 05:43:56',NULL,0,0),(3,'Hr manager','hr_manager@example.com','SA',NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$.BT4ZYvX29mC9e4I6qGoFuJJqFGsMCXjXJcBDfmAFpeGhTa/6ie7a',NULL,'2025-05-27 01:05:15','2025-06-01 03:47:20',NULL,0,0),(4,'Hr employee','hr_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$YWVpGZrJv2YnBj8GxouKa.qUAFkQ3W1TruSK3Z9nyD86fQrxA4yeS',NULL,'2025-05-27 01:05:15','2025-05-27 01:05:15',NULL,0,0),(5,'Sales manager','sales_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$fQbCryYnbMC5bQGHlY8YtuHPMHoZwYFxXtReVHvSn4Tn4mbnUmI4m',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0),(6,'Sales employee','sales_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$.n8jAqEN5MnvgrsE.EgDKuAbec9MUsp.p63J9I8CcTjHhTHskXU.i',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0),(7,'Inventory manager','inventory_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$niayWw0ocjwGNesNoRf//enhckknlMIQzM6DnN.dqlGPqywgWsKeK',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0),(8,'Inventory employee','inventory_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$Pz/BtovBzmzWVm5SlEKF8ebxJn72kkqpemwUBnBc/zJ33IBNXcEf.',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0),(9,'Finance manager','finance_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$bYLprqq0duv85TR7uhq18OhneoOqY5pH.bcLg25Iwk/L0ANvNDGI6',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0),(10,'Finance employee','finance_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:17','$2y$12$Jt5c5sJV6OiBA0jiY/ZY3uFZjwnwVpeOvGaB/eVDZsB8MwVZIBsXC',NULL,'2025-05-27 01:05:17','2025-05-27 01:05:17',NULL,0,0),(13,'Mohammed Shoebuddin','shoeb@titlysolutions.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$s8inz5GEUhRdmILSfGGA4ecf0VSFl0VkCdgleRfIv3ZnBq8nAaVES',NULL,'2025-05-29 03:56:55','2025-05-31 03:20:12',NULL,0,0),(14,'Kings Florist','king@florist.com',NULL,NULL,NULL,NULL,'7867867860','jambag',NULL,NULL,'$2y$12$k60.4SxhqWNgkdiEusBSH.PblFVTFepbGPFwuWe.mH..Xmc80FrSC',NULL,'2025-05-29 05:51:45','2025-05-29 05:51:45',NULL,0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,'Mannan','Hyderabad','New','2025-05-27 01:28:28','2025-05-27 01:28:28'),(2,'Sultan','Bangalore','New','2025-05-27 01:28:36','2025-05-27 01:28:36');
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warning_letters`
--

DROP TABLE IF EXISTS `warning_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warning_letters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `issuer_id` bigint unsigned DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'issued',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warning_letters_employee_id_foreign` (`employee_id`),
  KEY `warning_letters_issuer_id_foreign` (`issuer_id`),
  CONSTRAINT `warning_letters_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warning_letters_issuer_id_foreign` FOREIGN KEY (`issuer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warning_letters`
--

LOCK TABLES `warning_letters` WRITE;
/*!40000 ALTER TABLE `warning_letters` DISABLE KEYS */;
INSERT INTO `warning_letters` VALUES (1,13,NULL,'Absent','Baigan','2025-05-31','issued',NULL,'2025-05-31 03:19:44','2025-05-31 03:19:44'),(2,2,NULL,'1','sc','2025-06-01','issued',NULL,'2025-06-01 01:35:21','2025-06-01 01:35:21'),(3,3,NULL,'xyz','asd','2025-06-01','issued',NULL,'2025-06-01 04:24:37','2025-06-01 04:24:37'),(4,5,NULL,'Absent','asd','2025-06-01','issued',NULL,'2025-06-01 04:25:16','2025-06-01 04:25:16'),(5,2,NULL,'1','asx','2025-06-02','issued',NULL,'2025-06-01 23:49:47','2025-06-01 23:49:47'),(6,2,2,'asd','asdad','2025-06-02','issued',NULL,'2025-06-02 02:54:20','2025-06-02 02:54:20');
/*!40000 ALTER TABLE `warning_letters` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-05 13:54:49
SET FOREIGN_KEY_CHECKS = 1;