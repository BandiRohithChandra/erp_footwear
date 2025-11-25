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
INSERT INTO `cache` VALUES ('erp_system_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:25:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"manage hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:11;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"view hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:11;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"manage sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:6;i:2;i:7;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"manage finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:12;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"view finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:8;i:2;i:9;i:3;i:12;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:9:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"view notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:12:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:20:\"manage notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"view production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:10;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"manage production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"view employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:10;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"access employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:10;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:21:\"access manager portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:20:\"view sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:22:\"manage sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"manage quotations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"process production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"approve transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:12;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"manage payroll\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:12:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"HR Manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:7:\"Manager\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"HR Employee\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Sales Manager\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"Sales Employee\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"Inventory Manager\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"Inventory Employee\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Finance Manager\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"Accountant\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"Finance Employee\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}}}',1756627589);
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`)
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_cards`
--

LOCK TABLES `dashboard_cards` WRITE;
/*!40000 ALTER TABLE `dashboard_cards` DISABLE KEYS */;
INSERT INTO `dashboard_cards` VALUES (11,11,'New Orders','orders_new','/orders','icons/order.png',1,'2025-08-25 06:00:21','2025-08-25 06:00:21'),(12,11,'Pending Orders','orders_pending','/orders/pending','icons/pending.png',2,'2025-08-25 06:00:21','2025-08-25 06:00:21'),(13,11,'Products','articles','/products','icons/product.png',3,'2025-08-25 06:00:21','2025-08-25 06:00:21'),(14,11,'Total Sales','total_sales','/orders/completed','icons/sales.png',4,'2025-08-25 06:00:21','2025-08-25 06:00:21'),(15,11,'Pending Payments','pending_payments','/orders/pending','icons/payments.png',5,'2025-08-25 06:00:21','2025-08-25 06:00:21');
/*!40000 ALTER TABLE `dashboard_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'SAR',
  `hire_date` date NOT NULL,
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
  `personal_documents` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_email_unique` (`email`),
  UNIQUE KEY `employees_personal_email_unique` (`personal_email`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
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
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `payment_type` varchar(255) NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (9,54,15,5750.00,0.00,'\"[{\\\"product_id\\\":11,\\\"name\\\":\\\"Shoes\\\",\\\"quantity\\\":50,\\\"price\\\":\\\"115.00\\\",\\\"total\\\":\\\"5750.00\\\"}]\"','cod',NULL,'pending','2025-08-30 02:35:27','2025-08-30 02:35:27'),(10,57,14,6000.00,0.00,'\"[{\\\"product_id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"quantity\\\":\\\"40\\\",\\\"price\\\":\\\"150.00\\\",\\\"total\\\":\\\"6000.00\\\"}]\"','cash',NULL,'pending','2025-08-30 02:44:45','2025-08-30 02:44:45'),(11,63,15,4600.00,0.00,'\"[{\\\"product_id\\\":11,\\\"name\\\":\\\"Shoes\\\",\\\"quantity\\\":40,\\\"price\\\":\\\"115.00\\\",\\\"total\\\":\\\"4600.00\\\"}]\"','cod',NULL,'pending','2025-08-30 04:51:30','2025-08-30 04:51:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"641ba284-ef83-4c10-b21c-645a4ee620fe\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"a800ab03-d4a3-4293-970a-5babe57fe81c\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-25 10:51:16\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1756119076,\"delay\":null}',0,NULL,1756119076,1756119076),(2,'default','{\"uuid\":\"8837e451-f684-45e2-abbe-a2fdcd46e869\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"c79227ef-1e97-48dc-bb4c-36e983136320\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-25 11:20:08\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1756120808,\"delay\":null}',0,NULL,1756120808,1756120808),(3,'default','{\"uuid\":\"516ea20e-e55e-4623-badd-64cb6ff6d87a\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"permissions\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"35c4b829-6e5c-4def-b320-73e63467fea6\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-26 06:07:08\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1756188428,\"delay\":null}',0,NULL,1756188428,1756188428),(4,'default','{\"uuid\":\"22ee8190-0fa9-4ac2-b3db-f5dda21ab847\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"permissions\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"dc79c69f-361e-4fd6-b265-48271d5dfcd0\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-26 06:51:14\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1756191074,\"delay\":null}',0,NULL,1756191074,1756191074),(5,'default','{\"uuid\":\"c8cae0d5-8282-45c2-ac7b-ad9bb62cf119\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"5cafcce3-c6c4-4296-9963-28da035d5789\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-26 07:42:49\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1756194169,\"delay\":null}',0,NULL,1756194169,1756194169);
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
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liquid_materials_product_id_foreign` (`product_id`),
  CONSTRAINT `liquid_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liquid_materials`
--

LOCK TABLES `liquid_materials` WRITE;
/*!40000 ALTER TABLE `liquid_materials` DISABLE KEYS */;
INSERT INTO `liquid_materials` VALUES (7,11,'Psu','115','2025-08-25 23:18:46','2025-08-25 23:18:46'),(8,13,'Gum','115','2025-08-28 04:30:05','2025-08-28 04:30:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_24_072107_create_products_table',1),(5,'2025_05_24_075023_create_transactions_table',1),(6,'2025_05_24_084541_create_employees_table',1),(7,'2025_05_24_084615_create_payrolls_table',1),(8,'2025_05_24_091422_add_tax_fields_to_transactions_table',1),(9,'2025_05_24_091500_add_tax_fields_to_payrolls_table',1),(10,'2025_05_24_093343_add_tax_rate_to_transactions_and_payrolls',1),(11,'2025_05_24_095031_add_currency_to_employees',1),(12,'2025_05_24_095511_add_tax_fields_to_products',1),(13,'2025_05_24_101800_create_settings_table',1),(14,'2025_05_24_104921_create_sales_table',1),(15,'2025_05_24_105219_create_attendances_table',1),(16,'2025_05_24_105537_create_warehouses_table',1),(17,'2025_05_24_105616_modify_products_table_for_warehouses',1),(18,'2025_05_24_105645_create_product_warehouse_table',1),(19,'2025_05_24_105739_create_stock_adjustments_table',1),(20,'2025_05_24_105819_create_inventory_transfers_table',1),(21,'2025_05_24_105948_add_warehouse_id_to_sales_table',1),(22,'2025_05_24_112653_add_total_amount_to_products_table',1),(23,'2025_05_24_114347_create_permission_tables',1),(24,'2025_05_24_122513_create_activity_log_table',1),(25,'2025_05_24_122514_add_event_column_to_activity_log_table',1),(26,'2025_05_24_122515_add_batch_uuid_column_to_activity_log_table',1),(27,'2025_05_26_055134_create_notifications_table',1),(28,'2025_05_26_065750_make_position_nullable_in_employees_table',1),(29,'2025_05_26_065826_make_amount_nullable_in_payrolls_table',1),(30,'2025_05_26_071116_add_discount_to_sales_table',1),(31,'2025_05_26_071139_create_inventory_table',1),(32,'2025_05_26_071617_add_unit_price_to_products_table',1),(33,'2025_05_26_090849_add_profile_picture_to_users_table',1),(34,'2025_05_26_092421_create_leave_requests_table',1),(35,'2025_05_26_092440_create_advance_salary_requests_table',1),(36,'2025_05_26_092459_add_manager_id_and_is_remote_to_users_table',1),(37,'2025_05_26_105038_add_manager_id_to_users_table',1),(38,'2025_05_26_110311_add_user_id_to_employees_table',1),(39,'2025_05_26_111929_rename_advance_salary_requests_to_salary_advance_requests',1),(40,'2025_05_26_114640_make_manager_id_nullable_in_leave_requests_and_salary_advance_requests',1),(41,'2025_05_26_121342_add_force_password_change_to_users_table',1),(42,'2025_05_26_125004_create_quotations_table',1),(43,'2025_05_26_125046_create_orders_table',1),(44,'2025_05_26_125121_create_invoices_table',1),(45,'2025_05_29_103243_add_phone_and_address_to_users_table',1),(46,'2025_05_29_103531_add_fields_to_quotations_table',1),(47,'2025_05_29_132610_add_client_id_to_quotations_table',1),(48,'2025_05_29_132820_create_product_quotation_table',1),(49,'2025_05_29_133538_make_salesperson_id_nullable_in_quotations_table',1),(50,'2025_05_29_144311_create_production_orders_table',1),(51,'2025_05_31_064052_add_items_to_invoices_table',1),(52,'2025_05_31_065344_fix_invoices_order_id_foreign_key',1),(53,'2025_05_31_082407_create_warning_letters_table',1),(54,'2025_05_31_083226_add_saudi_fields_to_users_table',1),(55,'2025_05_31_083705_add_status_to_attendances_table',1),(56,'2025_05_31_084444_create_exit_entry_requests_table',1),(57,'2025_06_01_063214_remove_region_from_users_table',1),(58,'2025_06_01_071726_add_country_to_users_table',1),(59,'2025_06_01_093227_update_exit_entry_requests_table_employee_id_foreign_key',1),(60,'2025_06_02_122923_add_status_and_category_to_transactions_table',1),(61,'2025_06_14_050114_add_amount_paid_to_invoices_table',1),(62,'2025_06_14_050141_create_payments_table',1),(63,'2025_06_14_060540_create_leave_balances_table',1),(64,'2025_06_14_060631_add_leave_type_to_leave_requests_table',1),(65,'2025_06_14_060900_create_expense_claims_table',1),(66,'2025_06_14_060943_create_training_requests_table',1),(67,'2025_06_14_061751_create_performance_reviews_table',1),(68,'2025_06_16_081640_add_approval_fields_to_payrolls_table',1),(69,'2025_06_25_071345_add_new_fields_to_employees_table',1),(70,'2025_06_25_071815_drop_address_from_employees_table',1),(71,'2025_06_25_072048_add_phone_and_emergency_contact_to_employees_table',1),(72,'2025_08_14_104517_add_image_to_products_table',1),(73,'2025_08_16_062448_create_raw_materials_table',1),(74,'2025_08_16_062534_add_stage_to_production_orders_table',1),(75,'2025_08_16_063348_add_due_date_to_production_orders_table',1),(76,'2025_08_16_075058_create_production_processes_table',1),(77,'2025_08_17_040349_create_supply_chain_stages_table',1),(78,'2025_08_17_041727_create_processes_table',1),(79,'2025_08_18_054619_add_client_fields_to_users_table',1),(80,'2025_08_18_085926_create_orders_table',1),(81,'2025_08_18_094723_create_clients_table',1),(82,'2025_08_18_095309_create_order_product_table',1),(83,'2025_08_18_123538_add_variations_to_products_table',1),(84,'2025_08_19_095823_make_quotation_id_nullable_in_production_orders_table',1),(85,'2025_08_19_100603_add_client_order_id_to_production_orders_table',1),(86,'2025_08_19_121609_add_company_fields_to_users_table',1),(87,'2025_08_20_062326_create_production_stages_table',1),(88,'2025_08_20_065510_create_workers_table',1),(89,'2025_08_21_043611_add_total_quantity_to_products_table',1),(90,'2025_08_21_045956_add_product_id_to_production_processes_table',1),(91,'2025_08_21_051411_add_unit_to_raw_materials_table',1),(92,'2025_08_21_052520_add_product_id_to_raw_materials_table',1),(93,'2025_08_21_052700_create_liquid_materials_table',1),(94,'2025_08_21_063901_add_unique_constraint_to_batch_no_in_batches_table',1),(95,'2025_08_21_065550_add_priority_to_batches_table',1),(96,'2025_08_21_065650_add_created_by_to_batches_table',1),(97,'2025_08_21_071358_create_batch_employee_table',1),(98,'2025_08_21_115543_add_customer_name_to_orders_table',1),(99,'2025_08_22_063411_add_client_id_to_orders_table',1),(100,'2025_08_23_055504_add_client_fields_to_users_table',1),(101,'2025_08_24_120251_add_transport_fields_to_orders_table',1),(102,'2025_08_24_120417_make_address_nullable_in_orders_table',1),(103,'2025_08_24_131427_add_status_to_users_table',1),(104,'2025_08_25_075810_add_price_and_image_to_cart_items_table',1),(105,'2025_08_25_102211_add_business_fields_to_users_table',2),(107,'2025_08_25_102546_create_dashboard_cards_table',3),(108,'2025_08_25_102851_add_amount_fields_to_orders_table',4),(109,'2025_08_25_103143_add_category_to_products_table',5),(110,'2025_08_25_113604_add_salesperson_id_to_quotations_table',6),(112,'2025_08_25_123838_create_cart_items_table',7),(113,'2025_08_25_124156_add_user_id_to_orders_table',8),(114,'2025_08_25_132214_update_orders_client_id_foreign',9),(115,'2025_08_26_110528_add_company_fields_to_users_table',10),(116,'2025_08_26_125141_create_support_tickets_table',11),(117,'2025_08_28_051032_add_company_fields_to_orders_table',12),(118,'2025_08_29_052433_add_read_at_to_orders_table',13),(119,'2025_08_30_094321_add_paid_amount_to_orders_table',14);
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
INSERT INTO `model_has_permissions` VALUES (1,'App\\Models\\User',4),(1,'App\\Models\\User',10),(1,'App\\Models\\User',11),(1,'App\\Models\\User',12),(2,'App\\Models\\User',4),(2,'App\\Models\\User',10),(2,'App\\Models\\User',11),(2,'App\\Models\\User',12),(3,'App\\Models\\User',4),(3,'App\\Models\\User',10),(3,'App\\Models\\User',11),(3,'App\\Models\\User',12),(4,'App\\Models\\User',4),(4,'App\\Models\\User',10),(4,'App\\Models\\User',11),(4,'App\\Models\\User',12),(5,'App\\Models\\User',4),(5,'App\\Models\\User',10),(5,'App\\Models\\User',11),(5,'App\\Models\\User',12),(6,'App\\Models\\User',4),(6,'App\\Models\\User',10),(6,'App\\Models\\User',11),(6,'App\\Models\\User',12),(7,'App\\Models\\User',4),(7,'App\\Models\\User',10),(7,'App\\Models\\User',11),(7,'App\\Models\\User',12),(8,'App\\Models\\User',4),(8,'App\\Models\\User',10),(8,'App\\Models\\User',11),(8,'App\\Models\\User',12),(9,'App\\Models\\User',4),(9,'App\\Models\\User',10),(9,'App\\Models\\User',11),(9,'App\\Models\\User',12),(10,'App\\Models\\User',4),(10,'App\\Models\\User',10),(10,'App\\Models\\User',11),(10,'App\\Models\\User',12),(11,'App\\Models\\User',4),(11,'App\\Models\\User',10),(11,'App\\Models\\User',11),(11,'App\\Models\\User',12),(12,'App\\Models\\User',4),(12,'App\\Models\\User',10),(12,'App\\Models\\User',11),(12,'App\\Models\\User',12),(13,'App\\Models\\User',4),(13,'App\\Models\\User',10),(13,'App\\Models\\User',11),(13,'App\\Models\\User',12),(14,'App\\Models\\User',4),(14,'App\\Models\\User',10),(14,'App\\Models\\User',11),(14,'App\\Models\\User',12),(15,'App\\Models\\User',4),(15,'App\\Models\\User',10),(15,'App\\Models\\User',11),(15,'App\\Models\\User',12),(16,'App\\Models\\User',4),(16,'App\\Models\\User',10),(16,'App\\Models\\User',11),(16,'App\\Models\\User',12),(17,'App\\Models\\User',4),(17,'App\\Models\\User',10),(17,'App\\Models\\User',11),(17,'App\\Models\\User',12),(18,'App\\Models\\User',4),(18,'App\\Models\\User',10),(18,'App\\Models\\User',11),(18,'App\\Models\\User',12),(19,'App\\Models\\User',4),(19,'App\\Models\\User',10),(19,'App\\Models\\User',11),(19,'App\\Models\\User',12),(20,'App\\Models\\User',4),(20,'App\\Models\\User',10),(20,'App\\Models\\User',11),(20,'App\\Models\\User',12),(21,'App\\Models\\User',4),(21,'App\\Models\\User',10),(21,'App\\Models\\User',11),(21,'App\\Models\\User',12),(22,'App\\Models\\User',4),(22,'App\\Models\\User',10),(22,'App\\Models\\User',11),(22,'App\\Models\\User',12),(23,'App\\Models\\User',4),(23,'App\\Models\\User',10),(23,'App\\Models\\User',11),(23,'App\\Models\\User',12),(24,'App\\Models\\User',4),(24,'App\\Models\\User',10),(24,'App\\Models\\User',11),(24,'App\\Models\\User',12),(25,'App\\Models\\User',4),(25,'App\\Models\\User',10),(25,'App\\Models\\User',11),(25,'App\\Models\\User',12);
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',3),(1,'App\\Models\\User',4),(1,'App\\Models\\User',10),(1,'App\\Models\\User',11),(1,'App\\Models\\User',12),(4,'App\\Models\\User',13),(15,'App\\Models\\User',15);
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
INSERT INTO `notifications` VALUES ('0530e0a3-1b2c-4b5f-91c7-1090bc8ad3f9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #54 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/54\",\"status\":\"accepted\"}',NULL,'2025-08-30 02:33:33','2025-08-30 02:33:33'),('0bf66dc4-7b81-4449-ac23-35294eae2447','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #50 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/50\",\"status\":\"accepted\"}',NULL,'2025-08-29 06:17:36','2025-08-29 06:17:36'),('10fc89b0-2751-42c7-a081-b43d6dbfa900','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #22 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/localhost\\/client\\/orders\\/22\"}',NULL,'2025-08-29 00:01:16','2025-08-29 00:01:16'),('1511c521-970d-44e5-bd96-8ea35a4ab423','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #45 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/45\"}',NULL,'2025-08-29 05:26:54','2025-08-29 05:26:54'),('17ad9581-48f1-40f9-987a-19101549121c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #21 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/localhost\\/client\\/orders\\/21\"}',NULL,'2025-08-29 00:01:16','2025-08-29 00:01:16'),('1c180b32-f593-43b2-9bca-1349795b4cdc','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #40 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/40\",\"status\":\"accepted\"}',NULL,'2025-08-29 05:28:07','2025-08-29 05:28:07'),('1c80407d-0121-41cc-9b67-c57ef1604f0f','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #16 placed by rahul.\",\"url\":\"http:\\/\\/localhost\\/admin\\/orders\\/16\"}','2025-08-28 23:12:13','2025-08-28 23:11:16','2025-08-28 23:12:13'),('2cc106f7-ba56-4ac3-ad49-029877689415','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #58 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/58\"}',NULL,'2025-08-30 03:40:37','2025-08-30 03:40:37'),('2e48b47f-a5da-4e6e-a85b-391814ac101b','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #60 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/60\"}',NULL,'2025-08-30 04:09:15','2025-08-30 04:09:15'),('31085dbd-a9ed-4f78-8b54-36266b4443fc','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #48 status has been updated to \'processing\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/48\",\"status\":\"processing\"}',NULL,'2025-08-29 06:06:08','2025-08-29 06:06:08'),('31c11228-0b7c-4f15-89a4-006447881c18','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #61 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/61\"}',NULL,'2025-08-30 04:18:56','2025-08-30 04:18:56'),('34588ea4-a7de-4ba8-afad-ed0277eb0a09','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #47 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/47\"}',NULL,'2025-08-29 05:43:40','2025-08-29 05:43:40'),('35c4b829-6e5c-4def-b320-73e63467fea6','App\\Notifications\\TestNotification','App\\Models\\User',12,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-26 00:37:08','2025-08-26 00:37:08'),('35cd91cb-04fc-47ff-8e0d-ca6ca6cf601c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #52 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/52\",\"status\":\"accepted\"}',NULL,'2025-08-29 08:06:18','2025-08-29 08:06:18'),('38c36b4c-1f4b-4f2e-8088-0aae43836c81','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #46 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/46\",\"status\":\"accepted\"}',NULL,'2025-08-29 05:39:03','2025-08-29 05:39:03'),('407e6e45-3840-4d01-a285-5489902a6def','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #44 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/44\",\"status\":\"rejected\"}',NULL,'2025-08-29 05:27:45','2025-08-29 05:27:45'),('43c8e53b-87a0-4ff7-8612-dce1ad0b7d38','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #63 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/63\",\"status\":\"accepted\"}',NULL,'2025-08-30 04:51:30','2025-08-30 04:51:30'),('47415696-cc05-47f0-9672-938d19bace4e','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #44 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/44\"}',NULL,'2025-08-29 05:18:05','2025-08-29 05:18:05'),('484bdaab-e2c0-4e5e-ab0e-100ba59f135f','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #62 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/62\"}','2025-08-30 05:13:45','2025-08-30 04:23:45','2025-08-30 05:13:45'),('4997f04a-593e-4056-9eab-45629534eebe','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #53 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/53\"}',NULL,'2025-08-29 23:07:56','2025-08-29 23:07:56'),('4a9de1c8-26c2-473c-a8d0-8e1aecc5d770','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #41 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/41\",\"status\":\"accepted\"}',NULL,'2025-08-29 05:33:33','2025-08-29 05:33:33'),('4c9e0dec-7a2a-4f9b-9f76-0e82fcdaafde','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #49 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/49\"}',NULL,'2025-08-29 06:08:23','2025-08-29 06:08:23'),('523efabd-094c-4dc4-a429-cf85afcb33ed','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #53 status has been updated to \'processing\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/53\",\"status\":\"processing\"}',NULL,'2025-08-30 02:35:34','2025-08-30 02:35:34'),('527b0efd-5417-407a-9638-65ab700ee8e9','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #52 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/52\",\"status\":\"accepted\"}',NULL,'2025-08-29 08:11:27','2025-08-29 08:11:27'),('55ab4fdd-6ff3-40a7-85d6-3689753aee7d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #50 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/50\",\"status\":\"rejected\"}',NULL,'2025-08-29 07:25:26','2025-08-29 07:25:26'),('58cd4b46-6fee-4538-bdbc-f08f31f7be99','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #58 status has been updated to \'delivered\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/58\",\"status\":\"delivered\"}',NULL,'2025-08-30 03:57:08','2025-08-30 03:57:08'),('5afd1c55-b631-4be1-a8c1-a13102365114','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #64 status has been updated to \'delivered\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/64\",\"status\":\"delivered\"}',NULL,'2025-08-30 04:57:13','2025-08-30 04:57:13'),('5cafcce3-c6c4-4296-9963-28da035d5789','App\\Notifications\\TestNotification','App\\Models\\User',13,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-26 02:12:49','2025-08-26 02:12:49'),('5e383d40-6de2-4e75-b0f2-f7da96023e0d','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #54 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/54\"}',NULL,'2025-08-29 23:08:22','2025-08-29 23:08:22'),('6509f873-1276-469b-b607-259de72b3a8a','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #57 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/57\",\"status\":\"accepted\"}',NULL,'2025-08-30 02:44:45','2025-08-30 02:44:45'),('65cd4296-cc2c-462e-9231-32c61101f336','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #42 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/42\"}',NULL,'2025-08-29 01:32:28','2025-08-29 01:32:28'),('67b3595d-59ef-4eae-80dd-71c25183df35','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #52 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/52\",\"status\":\"accepted\"}',NULL,'2025-08-29 08:08:11','2025-08-29 08:08:11'),('6b71a4b3-6af8-4eb6-ae0e-fe6f329db877','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #39 status has been updated to \'shipping\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/39\",\"status\":\"shipping\"}',NULL,'2025-08-29 01:28:39','2025-08-29 01:28:39'),('6e3ed011-8ec0-439f-b1f4-83816cb527cf','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #21 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/localhost\\/client\\/orders\\/21\"}',NULL,'2025-08-29 00:00:18','2025-08-29 00:00:18'),('6fd6d271-a2d6-428e-a61e-43ccf57f8225','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #46 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/46\"}',NULL,'2025-08-29 05:38:36','2025-08-29 05:38:36'),('72d0842d-c249-4baf-9364-58c6acb7c192','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #47 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/47\",\"status\":\"rejected\"}',NULL,'2025-08-29 05:47:00','2025-08-29 05:47:00'),('76db983d-332f-41d6-9a7d-67c2a896fa8d','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #48 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/48\"}',NULL,'2025-08-29 06:05:32','2025-08-29 06:05:32'),('7954f745-7771-48bc-9c6e-9813780069dc','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #43 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/43\",\"status\":\"accepted\"}',NULL,'2025-08-29 04:56:20','2025-08-29 04:56:20'),('8b2c8e2f-4b6b-423f-9496-59ac2e218a9e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #39 status has been updated to \'shipping\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/39\",\"status\":\"shipping\"}',NULL,'2025-08-29 05:05:11','2025-08-29 05:05:11'),('8eb1dcf0-24ab-4567-8072-3e5fa1ed8752','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #49 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/49\",\"status\":\"accepted\"}',NULL,'2025-08-29 06:14:07','2025-08-29 06:14:07'),('8feb5afd-6b51-4e21-b547-54aba07c939b','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #45 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/45\",\"status\":\"accepted\"}',NULL,'2025-08-29 05:27:23','2025-08-29 05:27:23'),('9392d2bf-9313-4908-a629-d201a37dd8f0','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #21 placed by rahul.\",\"url\":\"http:\\/\\/localhost\\/admin\\/orders\\/21\"}','2025-08-30 05:13:30','2025-08-28 23:38:07','2025-08-30 05:13:30'),('93e1c40f-4125-4b32-b2c2-84cd0015a5f6','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #52 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/52\"}',NULL,'2025-08-29 08:05:58','2025-08-29 08:05:58'),('9a7a9aab-9f5d-4e6a-9a19-502b2136ff81','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #58 status has been updated to \'pending\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/58\",\"status\":\"pending\"}',NULL,'2025-08-30 03:41:02','2025-08-30 03:41:02'),('9ae8879a-166f-48de-892e-51b15b12d8af','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #56 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/56\",\"status\":\"accepted\"}',NULL,'2025-08-30 01:30:41','2025-08-30 01:30:41'),('a44c7f82-2517-4492-b4f4-cf1f60b9785e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #55 status has been updated to \'delivered\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/55\",\"status\":\"delivered\"}',NULL,'2025-08-30 01:32:52','2025-08-30 01:32:52'),('a64becd4-2ce7-4720-aea8-3e780e8784eb','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #59 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/59\"}',NULL,'2025-08-30 04:07:17','2025-08-30 04:07:17'),('a6c14006-8ab6-4243-a275-b485cfb7431b','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #16 placed by rahul.\",\"url\":\"http:\\/\\/localhost\\/admin\\/orders\\/16\"}','2025-08-28 23:35:53','2025-08-28 23:26:27','2025-08-28 23:35:53'),('a76f96e7-4154-40c3-b6a5-f7e1bab7bd23','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #52 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/52\",\"status\":\"accepted\"}',NULL,'2025-08-29 08:09:36','2025-08-29 08:09:36'),('a800ab03-d4a3-4293-970a-5babe57fe81c','App\\Notifications\\TestNotification','App\\Models\\User',6,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-25 05:21:16','2025-08-25 05:21:16'),('b96aa762-e7b9-4571-be7a-efe069706905','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #22 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/localhost\\/client\\/orders\\/22\"}',NULL,'2025-08-29 00:00:18','2025-08-29 00:00:18'),('bfd77bdc-66c4-43c2-8c11-eab911ec4e1c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #35 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/35\",\"status\":\"accepted\"}',NULL,'2025-08-29 01:17:03','2025-08-29 01:17:03'),('c0453923-a358-436e-824c-77b6df454026','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #42 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/42\",\"status\":\"rejected\"}',NULL,'2025-08-29 04:59:27','2025-08-29 04:59:27'),('c4c07a0a-e83c-4e72-a8da-03f3b240127e','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',14,'{\"message\":\"Your order #56 status has been updated to \'delivered\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/56\",\"status\":\"delivered\"}',NULL,'2025-08-30 01:54:48','2025-08-30 01:54:48'),('c5fc6f12-2791-4666-951c-e721e1695c60','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #63 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/63\"}','2025-08-30 05:13:41','2025-08-30 04:50:54','2025-08-30 05:13:41'),('c79227ef-1e97-48dc-bb4c-36e983136320','App\\Notifications\\TestNotification','App\\Models\\User',8,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-25 05:50:08','2025-08-25 05:50:08'),('c87aa04a-5453-4330-98ef-a57373fa6e2c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #54 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/54\",\"status\":\"accepted\"}',NULL,'2025-08-30 02:35:27','2025-08-30 02:35:27'),('d0d20a65-c0fb-4a0e-b8d6-66830ca1c589','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #64 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/64\"}','2025-08-30 05:13:37','2025-08-30 04:52:34','2025-08-30 05:13:37'),('d478dfa7-d1c0-4652-80b1-c98dbd1bf62d','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #51 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/51\",\"status\":\"accepted\"}',NULL,'2025-08-29 07:26:53','2025-08-29 07:26:53'),('d4cd4283-2a08-4abe-a70b-a7ef3049616c','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #49 status has been updated to \'processing\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/49\",\"status\":\"processing\"}',NULL,'2025-08-29 07:05:33','2025-08-29 07:05:33'),('daa160af-2b62-4958-aa74-8e4b63d7832f','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #35 status has been updated to \'accepted\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/35\",\"status\":\"accepted\"}',NULL,'2025-08-29 05:35:34','2025-08-29 05:35:34'),('dc79c69f-361e-4fd6-b265-48271d5dfcd0','App\\Notifications\\TestNotification','App\\Models\\User',11,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}','2025-08-28 06:32:19','2025-08-26 01:21:14','2025-08-28 06:32:19'),('e5f2e0d6-0c41-4537-a502-24d8fc1e3665','App\\Notifications\\OrderStatusUpdated','App\\Models\\User',15,'{\"message\":\"Your order #50 status has been updated to \'rejected\'.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/client\\/orders\\/50\",\"status\":\"rejected\"}',NULL,'2025-08-29 07:04:24','2025-08-29 07:04:24'),('e634411a-ac28-42fe-bd59-66fb54a4cc95','App\\Notifications\\OrderPlaced','App\\Models\\User',12,'{\"message\":\"New order #21 placed by rahul.\",\"url\":\"http:\\/\\/localhost\\/admin\\/orders\\/21\"}',NULL,'2025-08-28 23:38:07','2025-08-28 23:38:07'),('fdf14e43-bfc5-43ba-9fc3-aefa730d26a9','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #51 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/51\"}',NULL,'2025-08-29 07:26:31','2025-08-29 07:26:31'),('fff44582-b73f-4930-87f7-bd623565b66e','App\\Notifications\\OrderPlaced','App\\Models\\User',11,'{\"message\":\"New order #50 placed by rahul.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/orders\\/50\"}',NULL,'2025-08-29 06:17:19','2025-08-29 06:17:19');
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
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_product_order_id_foreign` (`order_id`),
  KEY `order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `transport_name` varchar(255) NOT NULL,
  `transport_address` varchar(255) NOT NULL,
  `transport_id` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_gst` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_quotation_id_foreign` (`quotation_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_client_id_foreign` (`client_id`),
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (46,15,NULL,'accepted','2025-08-29 05:38:36','2025-08-29 05:39:03','rahul','\"[{\\\"id\\\":38,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":9,\\\"quantity\\\":20,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":39,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":11,\\\"quantity\\\":40,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":40,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"cream\\\",\\\"size\\\":10,\\\"quantity\\\":50,\\\"price\\\":\\\"45.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:07:50.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',16500.00,2970.00,19470.00,0.00,0.00,'cod',15,'Rishi Transport','Sarrornagar, Hyderabad','46691255',NULL,NULL,NULL,NULL,NULL),(47,15,NULL,'rejected','2025-08-29 05:43:40','2025-08-29 05:47:00','rahul','\"[{\\\"id\\\":41,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":9,\\\"quantity\\\":50,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:13:25.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:13:25.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',7500.00,1350.00,8850.00,0.00,0.00,'cod',15,'Rishsi','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691255',NULL,NULL,NULL,NULL,NULL),(48,15,NULL,'processing','2025-08-29 06:05:32','2025-08-29 06:06:08','rahul','\"[{\\\"id\\\":42,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":10,\\\"quantity\\\":20,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:35:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:35:18.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',3000.00,540.00,3540.00,0.00,0.00,'cod',15,'Kiran Pvt','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691255',NULL,NULL,NULL,NULL,NULL),(49,15,NULL,'processing','2025-08-29 06:08:23','2025-08-29 07:05:33','rahul','\"[{\\\"id\\\":43,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":12,\\\"quantity\\\":2,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:38:11.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:38:11.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',300.00,54.00,354.00,0.00,0.00,'cod',15,'Kiran Pvt','2-54/1\nkondapur, Ghatkesar','46691255',NULL,NULL,NULL,NULL,NULL),(50,15,NULL,'rejected','2025-08-29 06:17:19','2025-08-29 07:04:23','rahul','\"[{\\\"id\\\":44,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":9,\\\"quantity\\\":50,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T11:47:07.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T11:47:07.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',7500.00,1350.00,8850.00,0.00,0.00,'cod',15,'Sharath Transport PVT LTD','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691258',NULL,NULL,NULL,NULL,NULL),(51,15,NULL,'accepted','2025-08-29 07:26:31','2025-08-29 07:26:53','rahul','\"[{\\\"id\\\":45,\\\"user_id\\\":15,\\\"product_id\\\":12,\\\"color\\\":\\\"Red\\\",\\\"size\\\":9,\\\"quantity\\\":20,\\\"price\\\":\\\"120.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/V3h5outedN3KV9xTtpaxtm2DPyslfRFmWbGmIar8.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T12:56:09.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T12:56:09.000000Z\\\",\\\"product\\\":{\\\"id\\\":12,\\\"name\\\":\\\"High Heels\\\",\\\"sku\\\":\\\"456\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"1500.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-26T07:19:35.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-26T07:19:35.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"size\\\":\\\"35\\\\\\/7\\\",\\\"color\\\":\\\"Red\\\",\\\"price\\\":\\\"120\\\",\\\"unit_price\\\":\\\"1500\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/V3h5outedN3KV9xTtpaxtm2DPyslfRFmWbGmIar8.jpg\\\",\\\"products\\\\\\/variations\\\\\\/RxzxYtqEldkc8lPWHDoEbPtf09FQtBSGB1I7BxBZ.jpg\\\",\\\"products\\\\\\/variations\\\\\\/BHiqYPW3Txfo3la6CEHUMgfA2YJ9cN5DG0dxwlbu.jpg\\\"]},{\\\"size\\\":\\\"7\\\",\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"115\\\",\\\"unit_price\\\":\\\"1299\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/1p32Xso9jg1WCTvaET1ofDfx7Sytjl8zGhNqIedf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/juwwW6RzxTOZhYAVTxlxcXez1TbEj9HtUWVOxxyN.jpg\\\"]}],\\\"category\\\":\\\"Womens\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',2400.00,432.00,2832.00,0.00,0.00,'cod',15,'Rishi Transport','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691258',NULL,NULL,NULL,NULL,NULL),(52,15,NULL,'accepted','2025-08-29 08:05:58','2025-08-29 08:06:18','rahul','\"[{\\\"id\\\":46,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":10,\\\"quantity\\\":20,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-29T13:35:40.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-29T13:35:40.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',3000.00,540.00,3540.00,0.00,0.00,'cod',15,'Ravi Transport','2-54/1\nkondapur, Ghatkesar','46691256',NULL,NULL,NULL,NULL,NULL),(53,15,NULL,'processing','2025-08-29 23:07:56','2025-08-30 02:35:34','rahul','\"[{\\\"id\\\":47,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":9,\\\"quantity\\\":20,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T04:37:43.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T04:37:43.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',3000.00,540.00,3540.00,0.00,0.00,'cod',15,'Sharath Transport PVT LTD','2-54/1\nkondapur, Ghatkesar','46691255',NULL,NULL,NULL,NULL,NULL),(54,15,NULL,'accepted','2025-08-29 23:08:22','2025-08-30 02:33:33','rahul','\"[{\\\"id\\\":48,\\\"user_id\\\":15,\\\"product_id\\\":11,\\\"color\\\":\\\"Black\\\",\\\"size\\\":10,\\\"quantity\\\":50,\\\"price\\\":\\\"115.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/0Z1tC0uBhZMXf4ToVjSkxN3Gn0Z4d63b02Pyhcsc.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T04:38:09.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T04:38:09.000000Z\\\",\\\"product\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Shoes\\\",\\\"sku\\\":\\\"522\\\",\\\"price\\\":\\\"115.00\\\",\\\"unit_price\\\":\\\"500.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"20.70\\\",\\\"total_amount\\\":\\\"135.70\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-26T04:48:46.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-26T06:36:27.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"8\\\",\\\"price\\\":\\\"115\\\",\\\"unit_price\\\":\\\"500\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/0Z1tC0uBhZMXf4ToVjSkxN3Gn0Z4d63b02Pyhcsc.jpg\\\",\\\"products\\\\\\/variations\\\\\\/cm17CzBIhSKnBqf3NyBOcNWfSE8HuXWkQBUUKk0Z.jpg\\\",\\\"products\\\\\\/variations\\\\\\/lfX27cyPQyjh6wyJ7h78kLC4idH2ZcglQdqSdRTg.jpg\\\",\\\"products\\\\\\/variations\\\\\\/7baLditKTUW5Bctp9sml1JwncCcMRCefWXcbLK6z.jpg\\\",\\\"products\\\\\\/variations\\\\\\/M3gA9Vz28M0CGDPXwUbMB6xy4GWQvTuhbl6ouYYr.jpg\\\"]},{\\\"color\\\":\\\"White\\\",\\\"size\\\":\\\"9\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"675\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"images\\\":[]}],\\\"category\\\":\\\"Mens Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',5750.00,1035.00,6785.00,0.00,0.00,'cod',15,'Kiran Pvt','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691256',NULL,NULL,NULL,NULL,NULL),(55,13,NULL,'delivered','2025-08-29 23:09:26','2025-08-30 01:32:52',NULL,'\"[{\\\"product_id\\\":12,\\\"name\\\":\\\"High Heels\\\",\\\"quantity\\\":\\\"50\\\",\\\"price\\\":\\\"120\\\",\\\"color\\\":\\\"Red\\\",\\\"size\\\":\\\"9\\\"},{\\\"product_id\\\":12,\\\"name\\\":\\\"High Heels\\\",\\\"quantity\\\":\\\"90\\\",\\\"price\\\":\\\"115\\\",\\\"color\\\":\\\"black\\\",\\\"size\\\":\\\"11\\\"}]\"',16350.00,2943.00,19293.00,0.00,0.00,'cash',14,'Not Assigned','Not Assigned','0','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri',NULL,NULL,NULL,NULL),(56,13,NULL,'delivered','2025-08-29 23:09:55','2025-08-30 01:54:48',NULL,'\"[{\\\"product_id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"quantity\\\":\\\"20\\\",\\\"price\\\":\\\"150\\\",\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"9\\\"}]\"',3000.00,540.00,3540.00,0.00,0.00,'cash',14,'Not Assigned','Not Assigned','0','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri',NULL,NULL,NULL,NULL),(57,13,NULL,'accepted','2025-08-30 02:40:29','2025-08-30 02:44:45',NULL,'\"[{\\\"product_id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"quantity\\\":\\\"40\\\",\\\"price\\\":\\\"150\\\",\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"10\\\"}]\"',6000.00,1080.00,7080.00,0.00,0.00,'cash',14,'Not Assigned','Not Assigned','0','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri',NULL,NULL,NULL,NULL),(58,15,NULL,'delivered','2025-08-30 03:40:37','2025-08-30 03:57:08','rahul','\"[{\\\"id\\\":49,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":10,\\\"quantity\\\":100,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T07:22:55.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T07:22:55.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":50,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"cream\\\",\\\"size\\\":11,\\\"quantity\\\":100,\\\"price\\\":\\\"45.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T09:10:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T09:10:18.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',30000.00,5400.00,35400.00,0.00,0.00,'cod',15,'Kiran Pvt','2-54/1\nkondapur, Ghatkesar','46691258',NULL,NULL,NULL,NULL,NULL),(59,15,NULL,'Partially Paid','2025-08-30 04:07:17','2025-08-30 04:07:17','rahul','\"[{\\\"id\\\":51,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":11,\\\"quantity\\\":20,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T09:36:42.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T09:36:42.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',3000.00,540.00,3540.00,0.00,0.00,'cod',15,'Ravi Transport','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri','46691258',NULL,NULL,NULL,NULL,NULL),(60,15,NULL,'Partially Paid','2025-08-30 04:09:15','2025-08-30 04:09:15','rahul','\"[{\\\"id\\\":52,\\\"user_id\\\":15,\\\"product_id\\\":12,\\\"color\\\":\\\"Red\\\",\\\"size\\\":12,\\\"quantity\\\":200,\\\"price\\\":\\\"120.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/V3h5outedN3KV9xTtpaxtm2DPyslfRFmWbGmIar8.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T09:38:55.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T09:38:55.000000Z\\\",\\\"product\\\":{\\\"id\\\":12,\\\"name\\\":\\\"High Heels\\\",\\\"sku\\\":\\\"456\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"1500.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-26T07:19:35.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-26T07:19:35.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"size\\\":\\\"35\\\\\\/7\\\",\\\"color\\\":\\\"Red\\\",\\\"price\\\":\\\"120\\\",\\\"unit_price\\\":\\\"1500\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/V3h5outedN3KV9xTtpaxtm2DPyslfRFmWbGmIar8.jpg\\\",\\\"products\\\\\\/variations\\\\\\/RxzxYtqEldkc8lPWHDoEbPtf09FQtBSGB1I7BxBZ.jpg\\\",\\\"products\\\\\\/variations\\\\\\/BHiqYPW3Txfo3la6CEHUMgfA2YJ9cN5DG0dxwlbu.jpg\\\"]},{\\\"size\\\":\\\"7\\\",\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"115\\\",\\\"unit_price\\\":\\\"1299\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/1p32Xso9jg1WCTvaET1ofDfx7Sytjl8zGhNqIedf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/juwwW6RzxTOZhYAVTxlxcXez1TbEj9HtUWVOxxyN.jpg\\\"]}],\\\"category\\\":\\\"Womens\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',24000.00,4320.00,28320.00,0.00,0.00,'cod',15,'Kiran Pvt','2-54/1\nkondapur, Ghatkesar','46691256',NULL,NULL,NULL,NULL,NULL),(61,15,NULL,'Partially Paid','2025-08-30 04:18:56','2025-08-30 04:18:56','rahul','\"[{\\\"id\\\":53,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":11,\\\"quantity\\\":14,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T09:48:24.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T09:48:24.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',2100.00,378.00,2478.00,0.00,0.00,'cod',15,'Sharath Transport PVT LTD','2-54/1\nkondapur, Ghatkesar','46691258',NULL,NULL,NULL,NULL,NULL),(62,15,NULL,'Partially Paid','2025-08-30 04:23:44','2025-08-30 04:23:44','rahul','\"[{\\\"id\\\":54,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":12,\\\"quantity\\\":10,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T09:53:27.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T09:53:27.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',1500.00,270.00,1770.00,1050.00,0.00,'cod',15,'Sharath Transport PVT LTD','2-54/1\nkondapur, Ghatkesar','46691258',NULL,NULL,NULL,NULL,NULL),(63,15,NULL,'accepted','2025-08-30 04:50:54','2025-08-30 04:51:30','rahul','\"[{\\\"id\\\":55,\\\"user_id\\\":15,\\\"product_id\\\":11,\\\"color\\\":\\\"Black\\\",\\\"size\\\":11,\\\"quantity\\\":40,\\\"price\\\":\\\"115.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/0Z1tC0uBhZMXf4ToVjSkxN3Gn0Z4d63b02Pyhcsc.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T10:20:39.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T10:20:39.000000Z\\\",\\\"product\\\":{\\\"id\\\":11,\\\"name\\\":\\\"Shoes\\\",\\\"sku\\\":\\\"522\\\",\\\"price\\\":\\\"115.00\\\",\\\"unit_price\\\":\\\"500.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"20.70\\\",\\\"total_amount\\\":\\\"135.70\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-26T04:48:46.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-26T06:36:27.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"8\\\",\\\"price\\\":\\\"115\\\",\\\"unit_price\\\":\\\"500\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/0Z1tC0uBhZMXf4ToVjSkxN3Gn0Z4d63b02Pyhcsc.jpg\\\",\\\"products\\\\\\/variations\\\\\\/cm17CzBIhSKnBqf3NyBOcNWfSE8HuXWkQBUUKk0Z.jpg\\\",\\\"products\\\\\\/variations\\\\\\/lfX27cyPQyjh6wyJ7h78kLC4idH2ZcglQdqSdRTg.jpg\\\",\\\"products\\\\\\/variations\\\\\\/7baLditKTUW5Bctp9sml1JwncCcMRCefWXcbLK6z.jpg\\\",\\\"products\\\\\\/variations\\\\\\/M3gA9Vz28M0CGDPXwUbMB6xy4GWQvTuhbl6ouYYr.jpg\\\"]},{\\\"color\\\":\\\"White\\\",\\\"size\\\":\\\"9\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"675\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"images\\\":[]}],\\\"category\\\":\\\"Mens Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',4600.00,828.00,5428.00,5428.00,0.00,'cod',15,'Ravi Transport','2-54/1\nkondapur, Ghatkesar','46691256',NULL,NULL,NULL,NULL,NULL),(64,15,NULL,'delivered','2025-08-30 04:52:34','2025-08-30 04:57:13','rahul','\"[{\\\"id\\\":56,\\\"user_id\\\":15,\\\"product_id\\\":13,\\\"color\\\":\\\"Black\\\",\\\"size\\\":11,\\\"quantity\\\":10,\\\"price\\\":\\\"150.00\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"created_at\\\":\\\"2025-08-30T10:22:21.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-30T10:22:21.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"Sandals\\\",\\\"sku\\\":\\\"5114\\\",\\\"price\\\":\\\"150.00\\\",\\\"unit_price\\\":\\\"50.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"27.00\\\",\\\"total_amount\\\":\\\"177.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-28T10:00:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-28T10:02:48.000000Z\\\",\\\"image\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"Black\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"150\\\",\\\"unit_price\\\":\\\"50\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"50\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\\\",\\\"products\\\\\\/variations\\\\\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\\\",\\\"products\\\\\\/variations\\\\\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\\\"]},{\\\"color\\\":\\\"cream\\\",\\\"size\\\":\\\"37\\\",\\\"price\\\":\\\"45\\\",\\\"unit_price\\\":\\\"125\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"images\\\":[\\\"products\\\\\\/variations\\\\\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\\\",\\\"products\\\\\\/variations\\\\\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\\\",\\\"products\\\\\\/variations\\\\\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\\\"]}],\\\"category\\\":\\\"Ladies Footwear\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',1500.00,270.00,1770.00,1770.00,0.00,'cod',15,'Ravi Transport','2-54/1\nkondapur, Ghatkesar','46691258',NULL,NULL,NULL,NULL,NULL);
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
INSERT INTO `permissions` VALUES (1,'manage hr','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(2,'view hr','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(3,'manage sales','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(4,'view sales','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(5,'manage inventory','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(6,'view inventory','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(7,'manage finance','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(8,'view finance','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(9,'manage settings','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(10,'view dashboard','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(11,'view reports','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(12,'manage users','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(13,'view notifications','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(14,'manage notifications','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(15,'view production','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(16,'manage production','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(17,'view employee portal','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(18,'access employee portal','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(19,'access manager portal','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(20,'view sales dashboard','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(21,'manage sales dashboard','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(22,'manage quotations','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(23,'process production','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(24,'approve transactions','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(25,'manage payroll','web','2025-08-25 04:48:21','2025-08-25 04:48:21');
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
  PRIMARY KEY (`id`),
  KEY `processes_parent_id_foreign` (`parent_id`),
  CONSTRAINT `processes_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` VALUES (1,'Designing',NULL,NULL,0,'Pending',0,'2025-08-25 05:06:23','2025-08-25 05:06:23'),(2,'Finished Article',NULL,NULL,0,'Pending',0,'2025-08-25 23:18:46','2025-08-25 23:18:46');
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_quotation_quotation_id_foreign` (`quotation_id`),
  KEY `product_quotation_product_id_foreign` (`product_id`),
  CONSTRAINT `product_quotation_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_quotation_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_quotation`
--

LOCK TABLES `product_quotation` WRITE;
/*!40000 ALTER TABLE `product_quotation` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_quotation` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `production_orders_quotation_id_foreign` (`quotation_id`),
  KEY `production_orders_client_order_id_foreign` (`client_order_id`),
  CONSTRAINT `production_orders_client_order_id_foreign` FOREIGN KEY (`client_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `production_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_orders`
--

LOCK TABLES `production_orders` WRITE;
/*!40000 ALTER TABLE `production_orders` DISABLE KEYS */;
INSERT INTO `production_orders` VALUES (1,1,NULL,NULL,'pending','2025-09-01','2025-08-25 07:14:51','2025-08-25 07:14:51'),(2,1,NULL,NULL,'pending','2025-09-02','2025-08-26 01:53:00','2025-08-26 01:53:00'),(3,1,NULL,NULL,'pending','2025-09-02','2025-08-26 02:49:26','2025-08-26 02:49:26'),(4,1,NULL,NULL,'pending','2025-09-02','2025-08-26 03:00:03','2025-08-26 03:00:03'),(5,1,NULL,NULL,'pending','2025-09-04','2025-08-28 06:31:48','2025-08-28 06:31:48'),(6,1,NULL,NULL,'pending','2025-09-05','2025-08-28 23:13:26','2025-08-28 23:13:26'),(7,1,NULL,NULL,'pending','2025-09-05','2025-08-28 23:21:36','2025-08-28 23:21:36'),(8,1,NULL,NULL,'pending','2025-09-05','2025-08-28 23:36:32','2025-08-28 23:36:32'),(9,1,NULL,NULL,'pending','2025-09-05','2025-08-28 23:41:16','2025-08-28 23:41:16'),(10,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:12:20','2025-08-29 00:12:20'),(11,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:16:53','2025-08-29 00:16:53'),(12,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:19:27','2025-08-29 00:19:27'),(13,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:41:22','2025-08-29 00:41:22'),(14,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:43:48','2025-08-29 00:43:48'),(15,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:45:53','2025-08-29 00:45:53'),(16,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:48:05','2025-08-29 00:48:05'),(17,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:50:46','2025-08-29 00:50:46'),(18,1,NULL,NULL,'pending','2025-09-05','2025-08-29 00:57:46','2025-08-29 00:57:46'),(19,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:02:11','2025-08-29 01:02:11'),(20,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:07:32','2025-08-29 01:07:32'),(21,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:13:04','2025-08-29 01:13:04'),(22,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:19:51','2025-08-29 01:19:51'),(23,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:23:45','2025-08-29 01:23:45'),(24,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:27:45','2025-08-29 01:27:45'),(25,1,NULL,NULL,'pending','2025-09-05','2025-08-29 01:32:28','2025-08-29 01:32:28'),(26,1,NULL,NULL,'pending','2025-09-05','2025-08-29 05:18:05','2025-08-29 05:18:05'),(27,1,NULL,NULL,'pending','2025-09-05','2025-08-29 05:26:54','2025-08-29 05:26:54'),(28,1,NULL,46,'pending','2025-09-05','2025-08-29 05:38:36','2025-08-29 05:38:36'),(29,1,NULL,47,'pending','2025-09-05','2025-08-29 05:43:40','2025-08-29 05:43:40'),(30,1,NULL,48,'pending','2025-09-05','2025-08-29 06:05:32','2025-08-29 06:05:32'),(31,1,NULL,49,'pending','2025-09-05','2025-08-29 06:08:23','2025-08-29 06:08:23'),(32,1,NULL,50,'pending','2025-09-05','2025-08-29 06:17:19','2025-08-29 06:17:19'),(33,1,NULL,51,'pending','2025-09-05','2025-08-29 07:26:31','2025-08-29 07:26:31'),(34,1,NULL,52,'pending','2025-09-05','2025-08-29 08:05:58','2025-08-29 08:05:58'),(35,1,NULL,53,'pending','2025-09-06','2025-08-29 23:07:56','2025-08-29 23:07:56'),(36,1,NULL,54,'pending','2025-09-06','2025-08-29 23:08:22','2025-08-29 23:08:22'),(37,1,NULL,58,'pending','2025-09-06','2025-08-30 03:40:37','2025-08-30 03:40:37'),(38,1,NULL,59,'pending','2025-09-06','2025-08-30 04:07:17','2025-08-30 04:07:17'),(39,1,NULL,60,'pending','2025-09-06','2025-08-30 04:09:15','2025-08-30 04:09:15'),(40,1,NULL,61,'pending','2025-09-06','2025-08-30 04:18:56','2025-08-30 04:18:56'),(41,1,NULL,62,'pending','2025-09-06','2025-08-30 04:23:45','2025-08-30 04:23:45'),(42,1,NULL,63,'pending','2025-09-06','2025-08-30 04:50:54','2025-08-30 04:50:54'),(43,1,NULL,64,'pending','2025-09-06','2025-08-30 04:52:34','2025-08-30 04:52:34');
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
  `name` varchar(255) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `operator` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assigned_quantity` int(11) NOT NULL DEFAULT 0,
  `completed_quantity` int(11) NOT NULL DEFAULT 0,
  `process_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `production_processes_product_id_foreign` (`product_id`),
  CONSTRAINT `production_processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_processes`
--

LOCK TABLES `production_processes` WRITE;
/*!40000 ALTER TABLE `production_processes` DISABLE KEYS */;
INSERT INTO `production_processes` VALUES (7,11,'Designing','Pending','Pending',NULL,NULL,NULL,0,0,1),(8,11,'Finished Article','Pending','Pending',NULL,NULL,NULL,0,0,2),(9,12,'Finished Article','Pending','Pending',NULL,NULL,NULL,0,0,2),(10,13,'Finished Article','Pending','Pending',NULL,NULL,NULL,0,0,2);
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
  `price` decimal(8,2) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `low_stock_threshold` int(10) unsigned NOT NULL DEFAULT 10,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `category` varchar(50) NOT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (11,'Shoes','522',115.00,500.00,10,18.00,20.70,135.70,NULL,NULL,'2025-08-25 23:18:46','2025-08-26 01:06:27',NULL,'[{\"color\":\"Black\",\"size\":\"8\",\"price\":\"115\",\"unit_price\":\"500\",\"gst\":\"18\",\"quantity\":\"50\",\"images\":[\"products\\/variations\\/0Z1tC0uBhZMXf4ToVjSkxN3Gn0Z4d63b02Pyhcsc.jpg\",\"products\\/variations\\/cm17CzBIhSKnBqf3NyBOcNWfSE8HuXWkQBUUKk0Z.jpg\",\"products\\/variations\\/lfX27cyPQyjh6wyJ7h78kLC4idH2ZcglQdqSdRTg.jpg\",\"products\\/variations\\/7baLditKTUW5Bctp9sml1JwncCcMRCefWXcbLK6z.jpg\",\"products\\/variations\\/M3gA9Vz28M0CGDPXwUbMB6xy4GWQvTuhbl6ouYYr.jpg\"]},{\"color\":\"White\",\"size\":\"9\",\"price\":\"150\",\"unit_price\":\"675\",\"gst\":\"18\",\"quantity\":\"100\",\"images\":[]}]','Mens Footwear',50),(12,'High Heels','456',120.00,1500.00,10,18.00,21.60,141.60,NULL,NULL,'2025-08-26 01:49:35','2025-08-26 01:49:35',NULL,'[{\"size\":\"35\\/7\",\"color\":\"Red\",\"price\":\"120\",\"unit_price\":\"1500\",\"gst\":\"18\",\"quantity\":\"50\",\"images\":[\"products\\/variations\\/V3h5outedN3KV9xTtpaxtm2DPyslfRFmWbGmIar8.jpg\",\"products\\/variations\\/RxzxYtqEldkc8lPWHDoEbPtf09FQtBSGB1I7BxBZ.jpg\",\"products\\/variations\\/BHiqYPW3Txfo3la6CEHUMgfA2YJ9cN5DG0dxwlbu.jpg\"]},{\"size\":\"7\",\"color\":\"black\",\"price\":\"115\",\"unit_price\":\"1299\",\"gst\":\"18\",\"quantity\":\"40\",\"images\":[\"products\\/variations\\/1p32Xso9jg1WCTvaET1ofDfx7Sytjl8zGhNqIedf.jpg\",\"products\\/variations\\/juwwW6RzxTOZhYAVTxlxcXez1TbEj9HtUWVOxxyN.jpg\"]}]','Womens',90),(13,'Sandals','5114',150.00,50.00,10,18.00,27.00,177.00,NULL,NULL,'2025-08-28 04:30:05','2025-08-28 04:32:48',NULL,'[{\"color\":\"Black\",\"size\":\"37\",\"price\":\"150\",\"unit_price\":\"50\",\"gst\":\"18\",\"quantity\":\"50\",\"images\":[\"products\\/variations\\/G419YZfHdEmrAn1vPLLtcFL1lKmhMAW7v2KhYeqf.jpg\",\"products\\/variations\\/PDxSNPLpDlZDIuGfqby37Ot5CJjIfuzMBexjn9AU.jpg\",\"products\\/variations\\/q1MnlrZEI1mhagvkm27eMUk0XU8jh3CVl0g6i6QG.jpg\"]},{\"color\":\"cream\",\"size\":\"37\",\"price\":\"45\",\"unit_price\":\"125\",\"gst\":\"18\",\"quantity\":\"40\",\"images\":[\"products\\/variations\\/S4yvDG7pE1z5qPOmwmVBFO8gAvR3cm2sggzCrn45.jpg\",\"products\\/variations\\/FDXMJVTsgOgey0cufFD51AY0r8tvOrAfkhiQ6Uy4.jpg\",\"products\\/variations\\/vXIDEDpxkVM1k0WP0UlLSzxxearxOO52A6KbZPqM.jpg\"]}]','Ladies Footwear',90);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `salesperson_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_client_id_foreign` (`client_id`),
  KEY `quotations_warehouse_id_foreign` (`warehouse_id`),
  KEY `quotations_salesperson_id_foreign` (`salesperson_id`),
  CONSTRAINT `quotations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_salesperson_id_foreign` FOREIGN KEY (`salesperson_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
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
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_materials_product_id_foreign` (`product_id`),
  CONSTRAINT `raw_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raw_materials`
--

LOCK TABLES `raw_materials` WRITE;
/*!40000 ALTER TABLE `raw_materials` DISABLE KEYS */;
INSERT INTO `raw_materials` VALUES (7,11,'Leather','112',NULL,0,'2025-08-25 23:18:46','2025-08-25 23:18:46'),(8,13,'Leather','120',NULL,0,'2025-08-28 04:30:05','2025-08-28 04:30:05'),(9,13,'Leather','125',NULL,0,'2025-08-28 04:30:05','2025-08-28 04:30:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2025-08-25 04:36:44','2025-08-25 04:36:44'),(2,'HR Manager','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(3,'HR Employee','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(4,'Sales Manager','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(5,'Sales Employee','web','2025-08-25 04:48:21','2025-08-25 04:48:21'),(6,'Inventory Manager','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(7,'Inventory Employee','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(8,'Finance Manager','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(9,'Finance Employee','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(10,'Employee','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(11,'Manager','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(12,'Accountant','web','2025-08-25 04:48:22','2025-08-25 04:48:22'),(15,'client','web','2025-08-25 06:21:57','2025-08-25 06:21:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (10,11,2,500.00,50.00,18.00,162.00,1112.00,'2025-08-25','Ravi Kumar','ravi.k@example.com','First time customer','2025-08-30 04:53:45','2025-08-30 04:53:45',1),(11,12,1,1500.00,100.00,18.00,252.00,1652.00,'2025-08-26','Anita Sharma','anita.s@example.com','Repeat customer','2025-08-30 04:53:45','2025-08-30 04:53:45',2),(12,13,5,50.00,25.00,18.00,45.00,270.00,'2025-08-27','Vijay Patel','vijay.p@example.com','Bulk discount applied','2025-08-30 04:53:45','2025-08-30 04:53:45',1),(13,11,1,500.00,0.00,18.00,90.00,590.00,'2025-08-28','Priya Nair','priya.n@example.com','Urgent delivery','2025-08-30 04:53:45','2025-08-30 04:53:45',3);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('KFAOUUEEEWFVnTUa3o7yxx04OLN0nz1RsyUolLyh',11,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoieG9ZQ1NKM2FqMkxNTnpSdVlJQjY4QXBqZ0lMblRWa05BVzl0VGs3ZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucy91bnJlYWQtY291bnQiO31zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkL2NhcmQtY291bnRzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7czoxNjoiaXNfb2ZmbGluZV9hZG1pbiI7YjowO30=',1756551288),('y0i0E06Z4RTydfiljqDkxDxrjXM6deXHipHNJ7no',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzlWZ2hRdlNmRTFGVzZlQXpReUZneHVXWmZpcHVCaTFsSkF0eGVOWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbm90aWZpY2F0aW9ucy91bnJlYWQtY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1756733678);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'logo_path',NULL,'2025-08-25 04:32:01','2025-08-25 04:32:01');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
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
-- Table structure for table `supply_chain_stages`
--

DROP TABLE IF EXISTS `supply_chain_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_chain_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  CONSTRAINT `transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
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
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_manager_id_foreign` (`manager_id`),
  CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'Admin User','admin@example.com',NULL,NULL,NULL,NULL,'08639919989','PLOT NO 13, GRD , 1ST FLOOR, HIRABAI COMPOUND, GHODAPDEO CROSS ROAD,\r\n MAZGAON, MUMBAI, Mumbai City, Maharashtra, 400010',NULL,NULL,'$2y$12$5N4rVmA7sxq5LXqPu3c0feiB.4ipIhxDG2bWgnyO0W9jrgOYzOzuW',NULL,'2025-08-25 06:00:01','2025-08-30 01:45:59',NULL,1,0,'CREATIVE SHOES','company_docs/47QeobhqAwsT500WuHTbROATFhdQ6eT08x4YXsqd.pdf','27AMRPK6699L1ZV',NULL,NULL,'wholesale',NULL,NULL,'approved','SIRATULLAH JAMIRULLAH KHAN','CTO','http://www.sap.com','creativeshoes@gmail.com','creative1@gmail.com',NULL,NULL,NULL),(12,'Offline Admin','offlineadmin@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$DzeNgEpSIz9WMPZipo80dubPnWc6f4k5.57tgBG9htjIA9vGoCreS',NULL,'2025-08-25 06:01:05','2025-08-25 06:01:05',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,'Sales Manager User','sales_manager@example.com',NULL,NULL,NULL,NULL,'5555555548','PLOT NO 13, GRD , 1ST FLOOR, HIRABAI COMPOUND, GHODAPDEO CROSS ROAD,\r\n MAZGAON, MUMBAI, Mumbai City, Maharashtra, 400010',NULL,NULL,'$2y$12$qpAubOwIqB2HPSsrlxe6N.cS8jQskg5j9Iv9MNEd.nMKGhbpjwaxK',NULL,'2025-08-25 06:03:55','2025-08-28 00:11:33',NULL,0,0,'CREATIVE SHOES','company_docs/GNQOwJaGLWCIuWODMQbne130KGnrUxsy7iYI6i61.pdf','27AMRPK6699L1ZV',NULL,NULL,'wholesale',NULL,NULL,'pending','SIRATULLAH JAMIRULLAH KHAN','Manager','http://www.dexcom.com','creativeshoes@gmail.com','5544112233',NULL,NULL,NULL),(14,'rahul','mannan@gmail.com',NULL,NULL,NULL,NULL,'9550488354','2-54/1 kondapur Ghatkesar Medchal-Malkajgiri',NULL,NULL,'$2y$12$PMIe6EoCf5TqEZi2r2bwIOzgQeHj6MARLEFePRS8xVURmkFWAqFc2',NULL,'2025-08-25 06:20:45','2025-08-25 06:20:45',NULL,0,0,'Macroman','documents/company/Hbp4D7hiY96ekOUn8n2qWkk7jAMmAyimR2Y8nSG3.jpg','27ABCDE1234F1Z7','documents/gst/MbT0TllI7iGQYrkZArUtZVrzua2590CQmk6F7SjK.jpg','documents/electricity/5Y2MBBOYoJtFgvVkZJ2qcEp0ynbFEfe4QAFymYdW.jpg','wholesale','775308575685','documents/aadhar/4sRDk71lmEnGDhnCJpiOQ3XquQd5I8DrawpgIIS0.png','pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'rahul','kiran@gmail.com',NULL,NULL,NULL,NULL,'9550488354','Ground Floor, Room No. 5, Municipal Chawl No. 6, Transit Camp Road, Byculla',NULL,NULL,'$2y$12$O9jpIvPU2Vno2CvMAVSsqeNauCD9R5uee/.9Dcj0YTbDszlpS3UbC',NULL,'2025-08-25 06:22:58','2025-08-28 01:17:29',NULL,0,0,'CREATIVE SHOES','documents/company/3XCU5axuBGMZEaWLroXwf4Gdr0a8nmRKm4AhBlB2.png','27AMRPK6699L1ZV','documents/gst/5nCjxeA7Ns45YJDgj7jUzvi8sWlxxuXQ3mUkHxdq.jpg','documents/electricity/GEDPQ4EZCiPmLP6nFBVfqCxiA1LFgEdpcp0uoCXv.png','wholesale','775308575685','documents/aadhar/rNQAew6drS2vgIN5x4KqeN9uMSieP9pbnWyVPn6S.png','approved','SIRATULLAH JAMIRULLAH KHAN',NULL,'https://www.microsoft.com',NULL,NULL,'Mumbai','Maharashtra','400011');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,'Main Warehouse','Hyderabad',NULL,'2025-08-30 04:53:40','2025-08-30 04:53:40'),(2,'Secondary Warehouse','Mumbai',NULL,'2025-08-30 04:53:40','2025-08-30 04:53:40'),(3,'Regional Warehouse','Delhi',NULL,'2025-08-30 04:53:40','2025-08-30 04:53:40');
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

-- Dump completed on 2025-09-01 19:05:17
