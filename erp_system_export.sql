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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'transaction','updated','App\\Models\\Transaction','updated',5,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"approved_at\": \"2025-06-13T09:53:46.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 04:23:46','2025-06-13 04:23:46'),(2,'transaction','updated','App\\Models\\Transaction','updated',4,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"approved_at\": \"2025-06-13T09:53:55.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 04:23:55','2025-06-13 04:23:55'),(3,'transaction','updated','App\\Models\\Transaction','updated',3,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"approved_at\": \"2025-06-13T09:53:57.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 04:23:57','2025-06-13 04:23:57'),(4,'transaction','updated','App\\Models\\Transaction','updated',2,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"rejected\", \"approved_at\": \"2025-06-13T09:53:58.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 04:23:58','2025-06-13 04:23:58'),(5,'transaction','deleted','App\\Models\\Transaction','deleted',1,'App\\Models\\User',2,'{\"old\": {\"type\": \"income\", \"amount\": \"1000.00\", \"region\": \"sa\", \"status\": \"pending\", \"category\": \"Sales\", \"tax_rate\": null, \"tax_amount\": \"0.00\", \"approved_at\": null, \"approved_by\": null, \"description\": \"Sale of Red Rose to King Florist\", \"total_amount\": \"1000.00\", \"transaction_date\": \"2025-05-27T00:00:00.000000Z\"}}',NULL,'2025-06-13 04:24:03','2025-06-13 04:24:03'),(6,'transaction','updated','App\\Models\\Transaction','updated',2,'App\\Models\\User',2,'{\"old\": {\"status\": \"rejected\", \"category\": \"Sales\", \"tax_rate\": null, \"approved_at\": \"2025-06-13T09:53:58.000000Z\", \"approved_by\": 2}, \"attributes\": {\"status\": \"pending\", \"category\": null, \"tax_rate\": \"0.00\", \"approved_at\": null, \"approved_by\": null}}',NULL,'2025-06-13 04:24:12','2025-06-13 04:24:12'),(7,'transaction','updated','App\\Models\\Transaction','updated',2,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"approved_at\": \"2025-06-13T09:54:24.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 04:24:24','2025-06-13 04:24:24'),(8,'transaction','updated','App\\Models\\Transaction','updated',5,'App\\Models\\User',9,'{\"old\": {\"region\": \"sa\", \"status\": \"approved\", \"category\": \"Rent\", \"approved_at\": \"2025-06-13T09:53:46.000000Z\", \"approved_by\": 2}, \"attributes\": {\"region\": \"in\", \"status\": \"pending\", \"category\": null, \"approved_at\": null, \"approved_by\": null}}',NULL,'2025-06-13 04:24:43','2025-06-13 04:24:43'),(9,'transaction','updated','App\\Models\\Transaction','updated',5,'App\\Models\\User',9,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"approved\", \"approved_at\": \"2025-06-13T09:55:38.000000Z\", \"approved_by\": 9}}',NULL,'2025-06-13 04:25:38','2025-06-13 04:25:38'),(10,'transaction','created','App\\Models\\Transaction','created',6,'App\\Models\\User',2,'{\"attributes\": {\"type\": \"expense\", \"amount\": \"20500.00\", \"region\": \"in\", \"status\": \"pending\", \"category\": \"other\", \"tax_rate\": \"0.00\", \"tax_amount\": \"0.00\", \"approved_at\": null, \"approved_by\": null, \"description\": \"Rent\", \"total_amount\": \"20500.00\", \"transaction_date\": \"2025-06-13T00:00:00.000000Z\"}}',NULL,'2025-06-13 07:56:15','2025-06-13 07:56:15'),(11,'transaction','updated','App\\Models\\Transaction','updated',6,'App\\Models\\User',2,'{\"old\": {\"status\": \"pending\", \"approved_at\": null, \"approved_by\": null}, \"attributes\": {\"status\": \"rejected\", \"approved_at\": \"2025-06-13T13:26:23.000000Z\", \"approved_by\": 2}}',NULL,'2025-06-13 07:56:23','2025-06-13 07:56:23');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,2,'2025-06-02','05:10:38','05:10:41',NULL,NULL,0,'absent','2025-06-01 23:40:38','2025-06-01 23:40:41'),(2,4,'2025-06-02','05:36:16','05:36:20',NULL,NULL,0,'absent','2025-06-02 00:06:16','2025-06-02 00:06:20'),(3,2,'2025-06-16','07:44:05','07:44:11',NULL,NULL,0,'present','2025-06-16 02:14:05','2025-06-16 02:14:11');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_employee`
--

DROP TABLE IF EXISTS `batch_employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_employee` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `employee_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_employee_batch_id_foreign` (`batch_id`),
  KEY `batch_employee_employee_id_foreign` (`employee_id`),
  CONSTRAINT `batch_employee_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_employee_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_employee`
--

LOCK TABLES `batch_employee` WRITE;
/*!40000 ALTER TABLE `batch_employee` DISABLE KEYS */;
INSERT INTO `batch_employee` VALUES (1,13,5,NULL,NULL),(2,15,5,NULL,NULL),(3,17,5,NULL,NULL),(4,18,5,NULL,NULL);
/*!40000 ALTER TABLE `batch_employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_flow_assignments`
--

DROP TABLE IF EXISTS `batch_flow_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_flow_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_flow_id` bigint(20) unsigned NOT NULL,
  `process_id` bigint(20) unsigned NOT NULL,
  `worker_id` bigint(20) unsigned NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `batch_flow_id` (`batch_flow_id`),
  KEY `process_id` (`process_id`),
  KEY `worker_id` (`worker_id`),
  CONSTRAINT `batch_flow_assignments_ibfk_1` FOREIGN KEY (`batch_flow_id`) REFERENCES `batch_flows` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_flow_assignments_ibfk_2` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_flow_assignments`
--

LOCK TABLES `batch_flow_assignments` WRITE;
/*!40000 ALTER TABLE `batch_flow_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_flow_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_flows`
--

DROP TABLE IF EXISTS `batch_flows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_flows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','in_progress','completed','on_hold') DEFAULT 'pending',
  `quantity` int(11) DEFAULT 0,
  `priority` enum('low','normal','high') DEFAULT 'normal',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`),
  KEY `quotation_id` (`quotation_id`),
  CONSTRAINT `batch_flows_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_flows_ibfk_2` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_flows`
--

LOCK TABLES `batch_flows` WRITE;
/*!40000 ALTER TABLE `batch_flows` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_flows` ENABLE KEYS */;
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
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `status` enum('pending','in_progress','completed','on_hold') DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `created_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `batches_batch_no_unique` (`batch_no`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (2,'BATCH-20250821-002','Batch B',NULL,0,'pending',NULL,NULL,'2025-08-16 07:39:24','2025-08-16 07:39:24','normal',NULL),(3,'BATCH-20250821-003','Batch C',NULL,0,'pending',NULL,NULL,'2025-08-16 07:39:24','2025-08-16 07:39:24','normal',NULL),(13,'BATCH-20250821-001','Upper part',6,120,'pending','2025-08-22','2025-08-22','2025-08-21 01:46:22','2025-08-21 01:46:22','normal','System'),(15,'BATCH-20250822-001','bata',9,120,'pending','2025-08-22','2025-08-26','2025-08-21 01:51:34','2025-08-21 01:51:34','normal','Super Admin'),(17,'BATCH-20250821-004','Sun Flower',3,100,'pending','2025-08-21','2025-08-23','2025-08-21 02:21:52','2025-08-21 02:21:52','normal','Super Admin'),(18,'BATCH-20250822-002','red sneakers',15,100,'pending','2025-08-22','2025-08-26','2025-08-21 02:22:12','2025-08-21 02:22:12','normal','Super Admin');
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
INSERT INTO `cache` VALUES ('erp_system_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:34:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"manage hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:15;i:3;i:16;i:4;i:17;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"view hr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:15;i:4;i:16;i:5;i:17;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"manage sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:11;i:3;i:17;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:4;i:2;i:5;i:3;i:11;i:4;i:17;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:6;i:2;i:17;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:6;i:2;i:7;i:3;i:17;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"manage finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:8;i:2;i:14;i:3;i:17;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"view finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:8;i:2;i:9;i:3;i:14;i:4;i:17;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:11:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:12;i:10;i:17;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;i:5;i:17;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"view notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:13:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:14;i:11;i:16;i:12;i:17;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:5:\"sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:15:\"view production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:10;i:3;i:17;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"manage production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"view employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:10;i:2;i:17;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"access employee portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:10;i:2;i:17;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"manage productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:16:\"view productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:20:\"manage notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;i:4;i:8;i:5;i:17;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"manage roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:21:\"access manager portal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:16;i:2;i:17;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:15:\"sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"view sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:17;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:22:\"manage sales dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:17:\"manage quotations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:10:\"production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:18:\"process production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:17;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:14:\"manage tenants\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:20:\"approve transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:8;i:2;i:14;i:3;i:17;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:14:\"manage payroll\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:17;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:13:\"client.orders\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:17;}}}s:5:\"roles\";a:16:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"HR Manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:2:\"hr\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"HR Employee\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"Sales Manager\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"salesperson\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"Sales Employee\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"Inventory Manager\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"Inventory Employee\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Finance Manager\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:10:\"accountant\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"Finance Employee\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:6:\"client\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}}}',1755869150);
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
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (22,5,14,'black','8',100,'2025-08-21 07:58:47','2025-08-21 07:58:47'),(23,5,11,'black','35/7',200,'2025-08-21 08:00:17','2025-08-21 08:00:17'),(24,5,11,'black','35/4',200,'2025-08-21 08:00:17','2025-08-21 08:00:17'),(25,19,10,'white','7',10,'2025-08-21 08:01:01','2025-08-21 08:01:01');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'John Doe','john@example.com','1234567890','123 Street, City','2025-08-20 07:42:42','2025-08-20 07:42:42'),(2,'Jane Smith','jane@example.com','9876543210','456 Avenue, City','2025-08-20 07:42:42','2025-08-20 07:42:42');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_batch_counters`
--

DROP TABLE IF EXISTS `daily_batch_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_batch_counters` (
  `batch_date` date NOT NULL,
  `last_number` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`batch_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_batch_counters`
--

LOCK TABLES `daily_batch_counters` WRITE;
/*!40000 ALTER TABLE `daily_batch_counters` DISABLE KEYS */;
INSERT INTO `daily_batch_counters` VALUES ('2025-08-21',4,NULL,NULL),('2025-08-22',2,NULL,NULL);
/*!40000 ALTER TABLE `daily_batch_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
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
  `skill` varchar(100) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Mohammed Shoebuddin','shoeb@titlysolutions.com','Director','Management',NULL,10000.00,'SAR','2025-01-15',NULL,NULL,'2025-05-29 03:56:55','2025-05-29 03:56:55',13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'Admin','admin@example.com','Employee','Default Department',NULL,10000.00,'SAR','2025-06-02',NULL,NULL,'2025-06-01 23:34:41','2025-06-16 07:28:23',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'Hr manager','hr_manager@example.com','Employee','Default Department',NULL,10000.00,'SAR','2025-06-02',NULL,NULL,'2025-06-01 23:34:41','2025-06-16 07:28:31',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'Sales manager','sales_manager@example.com','Employee','Default Department',NULL,20000.00,'SAR','2025-06-02',NULL,NULL,'2025-06-01 23:34:41','2025-06-16 07:28:37',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'John Doe','johndoe@example.com','worker',NULL,NULL,5000.00,'SAR','2025-08-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `order_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `payment_type` varchar(255) NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (4,0.00,0.00,1,14,57.50,'[{\"name\": \"Red Rose\", \"total\": 50, \"quantity\": 1, \"product_id\": 1, \"unit_price\": \"50.00\"}]','grace','2025-06-12','paid','2025-05-31 01:25:15','2025-05-31 01:33:26'),(5,0.00,0.00,2,14,115.00,'[{\"name\": \"Blue Rose\", \"total\": 100, \"quantity\": 1, \"product_id\": 2, \"unit_price\": \"100.00\"}]','grace','2025-06-01','paid','2025-05-31 01:34:21','2025-05-31 01:34:30'),(6,0.00,0.00,3,14,172.50,'[{\"name\": \"Red Rose\", \"total\": 50, \"quantity\": 1, \"product_id\": 1, \"unit_price\": \"50.00\"}, {\"name\": \"Blue Rose\", \"total\": 100, \"quantity\": 1, \"product_id\": 2, \"unit_price\": \"100.00\"}]','grace','2025-06-01','paid','2025-05-31 01:57:41','2025-05-31 01:58:11'),(7,0.00,157000.00,4,14,207000.00,'[{\"name\": \"Sun Flower\", \"total\": 180000, \"quantity\": 200, \"product_id\": 3, \"unit_price\": \"900.00\"}]','grace','2025-06-23','partially_paid','2025-06-13 07:46:28','2025-08-19 03:53:40'),(8,0.00,9200.00,5,14,9200.00,'[{\"name\": \"Blue Rose\", \"total\": 8000, \"quantity\": 80, \"product_id\": 2, \"unit_price\": \"100.00\"}]','grace','2025-06-23','paid','2025-06-13 07:58:24','2025-06-13 23:43:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"0b01fafa-ac1f-4f38-8f75-aff03b2ccae1\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"cdae1c25-4134-4fe8-96b8-26c8c34eaf09\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-27 06:35:34\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748327734,\"delay\":null}',0,NULL,1748327734,1748327734),(2,'default','{\"uuid\":\"2016003e-b91b-43d7-8dc4-64fc8d9e80a8\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"f27a248e-d087-4e67-bdef-35749c42aecf\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-27 07:44:13\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748331853,\"delay\":null}',0,NULL,1748331853,1748331853),(3,'default','{\"uuid\":\"384936f2-9089-4460-9abc-07f9c6074369\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"7a2e331c-c1c5-4563-9519-789d96def6e2\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-05-31 09:04:12\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1748682252,\"delay\":null}',0,NULL,1748682252,1748682252),(4,'default','{\"uuid\":\"4b761bc9-edaa-4129-a45c-2694be00110b\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"0d0bf1ad-c919-4ac3-8ab3-875744cf5be7\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/localhost:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-06-13 09:30:30\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749807031,\"delay\":null}',0,NULL,1749807031,1749807031),(5,'default','{\"uuid\":\"58bb2177-8fbe-44f3-9c7a-8373bbbe14ef\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:49:\\\"App\\\\Notifications\\\\TransactionApprovalNotification\\\":2:{s:14:\\\"\\u0000*\\u0000transaction\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\Transaction\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"7745e897-1679-48c0-a913-e9486f7ac378\\\";}s:4:\\\"data\\\";a:3:{s:14:\\\"transaction_id\\\";i:2;s:11:\\\"description\\\";s:29:\\\"Sale of Blue Rose to kya kare\\\";s:7:\\\"message\\\";s:70:\\\"A new transaction \'Sale of Blue Rose to kya kare\' is pending approval.\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749808452,\"delay\":null}',0,NULL,1749808452,1749808452),(6,'default','{\"uuid\":\"f40e073d-f921-46a6-8de3-6945154cf6e7\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:49:\\\"App\\\\Notifications\\\\TransactionApprovalNotification\\\":2:{s:14:\\\"\\u0000*\\u0000transaction\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\Transaction\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"484a8ba1-9137-453c-8414-7ab890464067\\\";}s:4:\\\"data\\\";a:3:{s:14:\\\"transaction_id\\\";i:5;s:11:\\\"description\\\";s:4:\\\"Rent\\\";s:7:\\\"message\\\";s:45:\\\"A new transaction \'Rent\' is pending approval.\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749808483,\"delay\":null}',0,NULL,1749808483,1749808483),(7,'default','{\"uuid\":\"cd9792a8-36e0-4aa5-a0d7-b3cdc36587d5\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:49:\\\"App\\\\Notifications\\\\TransactionApprovalNotification\\\":2:{s:14:\\\"\\u0000*\\u0000transaction\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\Transaction\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"22cf76f2-7cd5-419a-8f2e-5c6b4ded55b5\\\";}s:4:\\\"data\\\";a:3:{s:14:\\\"transaction_id\\\";i:6;s:11:\\\"description\\\";s:4:\\\"Rent\\\";s:7:\\\"message\\\";s:45:\\\"A new transaction \'Rent\' is pending approval.\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749821175,\"delay\":null}',0,NULL,1749821175,1749821175),(8,'default','{\"uuid\":\"70dac970-336b-4994-adc4-caac7d38ad2a\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:49:\\\"App\\\\Notifications\\\\TransactionApprovalNotification\\\":2:{s:14:\\\"\\u0000*\\u0000transaction\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\Transaction\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"9e596eb7-e8d2-4755-b36b-890b151d3b92\\\";}s:4:\\\"data\\\";a:3:{s:14:\\\"transaction_id\\\";i:6;s:11:\\\"description\\\";s:4:\\\"Rent\\\";s:7:\\\"message\\\";s:45:\\\"A new transaction \'Rent\' is pending approval.\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749821175,\"delay\":null}',0,NULL,1749821175,1749821175),(9,'default','{\"uuid\":\"e5f77855-2f31-42db-80ee-b2d79168e028\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"43aef43d-52d4-4bbb-a4d0-8a170461d3df\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/localhost:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-06-14 07:07:50\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1749884870,\"delay\":null}',0,NULL,1749884870,1749884870),(10,'default','{\"uuid\":\"1af0a07a-500d-4616-919c-18c90ea7e7fa\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:19;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"7c2fb3b8-d92a-4e06-a746-0b64d886d531\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-18 06:08:54\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1755497334,\"delay\":null}',0,NULL,1755497334,1755497334),(11,'default','{\"uuid\":\"0a90ad8f-9d91-44ca-ba4a-b0f86d9b6bd7\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:21;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"0159a709-9fdc-46fd-bee4-794b66838b18\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-19 11:32:56\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1755603176,\"delay\":null}',0,NULL,1755603176,1755603176),(12,'default','{\"uuid\":\"168b6aea-e247-48e5-83b5-71481110259e\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"c950f06f-f547-451f-a08b-f2849618c738\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-20 10:33:57\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1755686037,\"delay\":null}',0,NULL,1755686037,1755686037),(13,'default','{\"uuid\":\"5487b5de-24c7-4824-87f3-fe3cf2d5ed9c\",\"displayName\":\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:60:\\\"Illuminate\\\\Notifications\\\\Events\\\\BroadcastNotificationCreated\\\":3:{s:10:\\\"notifiable\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:26;s:9:\\\"relations\\\";a:2:{i:0;s:11:\\\"permissions\\\";i:1;s:5:\\\"roles\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:34:\\\"App\\\\Notifications\\\\TestNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"642f708b-3665-4755-b429-a986a871e462\\\";}s:4:\\\"data\\\";a:3:{s:7:\\\"message\\\";s:28:\\\"This is a test notification!\\\";s:3:\\\"url\\\";s:35:\\\"http:\\/\\/127.0.0.1:8000\\/notifications\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-08-20 11:29:24\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1755689364,\"delay\":null}',0,NULL,1755689364,1755689364);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_balances`
--

LOCK TABLES `leave_balances` WRITE;
/*!40000 ALTER TABLE `leave_balances` DISABLE KEYS */;
INSERT INTO `leave_balances` VALUES (1,2,'annual',20.00,0.00,20.00,2025,'2025-06-14 01:00:35','2025-06-14 01:00:35'),(2,2,'sick',10.00,0.00,10.00,2025,'2025-06-14 01:00:35','2025-06-14 01:00:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liquid_materials`
--

LOCK TABLES `liquid_materials` WRITE;
/*!40000 ALTER TABLE `liquid_materials` DISABLE KEYS */;
INSERT INTO `liquid_materials` VALUES (1,20,'gum','100','2025-08-20 23:58:30','2025-08-20 23:58:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_24_072107_create_products_table',1),(5,'2025_05_24_075023_create_transactions_table',1),(6,'2025_05_24_084541_create_employees_table',1),(7,'2025_05_24_084615_create_payrolls_table',1),(8,'2025_05_24_091422_add_tax_fields_to_transactions_table',1),(9,'2025_05_24_091500_add_tax_fields_to_payrolls_table',1),(10,'2025_05_24_093343_add_tax_rate_to_transactions_and_payrolls',1),(11,'2025_05_24_095031_add_currency_to_employees',1),(12,'2025_05_24_095511_add_tax_fields_to_products',1),(13,'2025_05_24_101800_create_settings_table',1),(14,'2025_05_24_104921_create_sales_table',1),(15,'2025_05_24_105219_create_attendances_table',1),(16,'2025_05_24_105537_create_warehouses_table',1),(17,'2025_05_24_105616_modify_products_table_for_warehouses',1),(18,'2025_05_24_105645_create_product_warehouse_table',1),(19,'2025_05_24_105739_create_stock_adjustments_table',1),(20,'2025_05_24_105819_create_inventory_transfers_table',1),(21,'2025_05_24_105948_add_warehouse_id_to_sales_table',1),(22,'2025_05_24_112653_add_total_amount_to_products_table',1),(23,'2025_05_24_114347_create_permission_tables',1),(24,'2025_05_24_122513_create_activity_log_table',1),(25,'2025_05_24_122514_add_event_column_to_activity_log_table',1),(26,'2025_05_24_122515_add_batch_uuid_column_to_activity_log_table',1),(27,'2025_05_26_055134_create_notifications_table',1),(28,'2025_05_26_065750_make_position_nullable_in_employees_table',1),(29,'2025_05_26_065826_make_amount_nullable_in_payrolls_table',1),(30,'2025_05_26_071116_add_discount_to_sales_table',1),(31,'2025_05_26_071139_create_inventory_table',1),(32,'2025_05_26_071617_add_unit_price_to_products_table',1),(33,'2025_05_26_090849_add_profile_picture_to_users_table',1),(34,'2025_05_26_092421_create_leave_requests_table',1),(35,'2025_05_26_092440_create_advance_salary_requests_table',1),(36,'2025_05_26_092459_add_manager_id_and_is_remote_to_users_table',1),(37,'2025_05_26_105038_add_manager_id_to_users_table',1),(38,'2025_05_26_110311_add_user_id_to_employees_table',1),(39,'2025_05_26_111929_rename_advance_salary_requests_to_salary_advance_requests',1),(40,'2025_05_26_114640_make_manager_id_nullable_in_leave_requests_and_salary_advance_requests',1),(41,'2025_05_26_121342_add_force_password_change_to_users_table',1),(42,'2025_05_26_125004_create_quotations_table',1),(43,'2025_05_26_125046_create_orders_table',1),(45,'2025_05_26_125121_create_invoices_table',2),(46,'2025_05_29_103243_add_phone_and_address_to_users_table',3),(47,'2025_05_29_103531_add_fields_to_quotations_table',4),(48,'2025_05_29_132610_add_client_id_to_quotations_table',5),(49,'2025_05_29_132820_create_product_quotation_table',6),(50,'2025_05_29_133538_make_salesperson_id_nullable_in_quotations_table',7),(51,'2025_05_29_135056_drop_total_amount_from_quotations_table',8),(52,'2025_05_29_144311_create_production_orders_table',9),(53,'2025_05_31_064052_add_items_to_invoices_table',10),(54,'2025_05_31_065344_fix_invoices_order_id_foreign_key',11),(64,'2025_05_29_103531_add_fields_to_quotations_table',1),(68,'2019_09_15_000010_create_tenants_table',1),(70,'2019_09_15_000020_create_domains_table',1),(71,'2025_05_31_082407_create_warning_letters_table',12),(72,'2025_05_31_083226_add_saudi_fields_to_users_table',12),(73,'2025_05_31_083705_add_status_to_attendances_table',12),(74,'2025_05_31_084444_create_exit_entry_requests_table',12),(75,'2025_06_01_063214_remove_region_from_users_table',12),(76,'2025_06_01_071726_add_country_to_users_table',12),(77,'2025_06_01_093227_update_exit_entry_requests_table_employee_id_foreign_key',12),(78,'2025_06_02_122923_add_status_and_category_to_transactions_table',12),(79,'2025_06_10_115526_create_tenants_table',12),(80,'2025_06_10_130918_create_password_reset_tokens_table',12),(81,'2025_06_10_132327_add_fields_to_quotations_table',12),(82,'2025_06_11_120640_add_tenant_id_to_users_table_if_missing',12),(83,'2025_06_14_050114_add_amount_paid_to_invoices_table',12),(84,'2025_06_14_050141_create_payments_table',13),(85,'2025_06_14_060540_create_leave_balances_table',14),(86,'2025_06_14_060631_add_leave_type_to_leave_requests_table',14),(87,'2025_06_14_060900_create_expense_claims_table',14),(88,'2025_06_14_060943_create_training_requests_table',14),(89,'2025_06_14_061751_create_performance_reviews_table',14),(90,'2025_06_16_081640_add_approval_fields_to_payrolls_table',15),(91,'2025_06_25_071345_add_new_fields_to_employees_table',16),(92,'2025_06_25_071815_drop_address_from_employees_table',16),(93,'2025_06_25_072048_add_phone_and_emergency_contact_to_employees_table',16),(94,'2025_08_14_104517_add_image_to_products_table',16),(95,'2025_08_16_062448_create_raw_materials_table',17),(96,'2025_08_16_062534_add_stage_to_production_orders_table',18),(97,'2025_08_16_063348_add_due_date_to_production_orders_table',19),(98,'2025_08_16_075058_create_production_processes_table',20),(99,'2025_08_17_040349_create_supply_chain_stages_table',21),(100,'2025_08_17_041727_create_processes_table',22),(101,'2025_08_18_054619_add_client_fields_to_users_table',23),(103,'2025_08_18_085926_create_orders_table',24),(104,'2025_08_18_094723_create_clients_table',25),(105,'2025_08_18_095309_create_order_product_table',26),(106,'2025_08_18_123538_add_variations_to_products_table',27),(107,'2025_08_19_095823_make_quotation_id_nullable_in_production_orders_table',28),(108,'2025_08_19_100603_add_client_order_id_to_production_orders_table',29),(109,'2025_08_19_121609_add_company_fields_to_users_table',30),(110,'2025_08_20_062326_create_production_stages_table',30),(111,'2025_08_20_065510_create_workers_table',31),(112,'2025_08_21_043611_add_total_quantity_to_products_table',31),(113,'2025_08_21_045956_add_product_id_to_production_processes_table',32),(114,'2025_08_21_051411_add_unit_to_raw_materials_table',33),(115,'2025_08_21_052520_add_product_id_to_raw_materials_table',34),(116,'2025_08_21_052700_create_liquid_materials_table',35),(117,'2025_08_21_063901_add_unique_constraint_to_batch_no_in_batches_table',36),(118,'2025_08_21_065550_add_priority_to_batches_table',37),(119,'2025_08_21_065650_add_created_by_to_batches_table',38),(120,'2025_08_21_071358_create_batch_employee_table',39),(121,'2025_08_21_115543_add_customer_name_to_orders_table',40);
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
INSERT INTO `model_has_permissions` VALUES (1,'App\\Models\\User',2),(33,'App\\Models\\User',2);
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',2),(2,'App\\Models\\User',3),(3,'App\\Models\\User',4),(4,'App\\Models\\User',5),(5,'App\\Models\\User',6),(5,'App\\Models\\User',21),(6,'App\\Models\\User',7),(7,'App\\Models\\User',8),(8,'App\\Models\\User',9),(9,'App\\Models\\User',10),(10,'App\\Models\\User',13),(12,'App\\Models\\User',14),(12,'App\\Models\\User',19),(12,'App\\Models\\User',22),(12,'App\\Models\\User',23),(12,'App\\Models\\User',24),(12,'App\\Models\\User',26),(12,'App\\Models\\User',27),(12,'App\\Models\\User',28),(12,'App\\Models\\User',29),(17,'App\\Models\\User',25);
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
INSERT INTO `notifications` VALUES ('0159a709-9fdc-46fd-bee4-794b66838b18','App\\Notifications\\TestNotification','App\\Models\\User',21,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-19 06:02:56','2025-08-19 06:02:56'),('04dbafca-cba0-467c-b658-e50954e0164c','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"message\":\"You have been issued a warning letter: 1\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\\/warning-letters\\/5\"}','2025-06-02 05:41:52','2025-06-01 23:49:47','2025-06-02 05:41:52'),('0d0bf1ad-c919-4ac3-8ab3-875744cf5be7','App\\Notifications\\TestNotification','App\\Models\\User',9,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/localhost:8000\\/notifications\"}','2025-06-13 04:24:53','2025-06-13 04:00:30','2025-06-13 04:24:53'),('11ce9644-2d5d-4af8-a1c2-86ce04892573','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"message\":\"You have been issued a warning letter: asd\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\\/warning-letters\\/6\"}','2025-06-02 05:41:51','2025-06-02 02:54:20','2025-06-02 05:41:51'),('22cf76f2-7cd5-419a-8f2e-5c6b4ded55b5','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":6,\"description\":\"Rent\",\"message\":\"A new transaction \'Rent\' is pending approval.\"}','2025-06-14 01:32:08','2025-06-13 07:56:15','2025-06-14 01:32:08'),('43aef43d-52d4-4bbb-a4d0-8a170461d3df','App\\Notifications\\TestNotification','App\\Models\\User',10,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/localhost:8000\\/notifications\"}',NULL,'2025-06-14 01:37:50','2025-06-14 01:37:50'),('484a8ba1-9137-453c-8414-7ab890464067','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":5,\"description\":\"Rent\",\"message\":\"A new transaction \'Rent\' is pending approval.\"}','2025-06-13 04:28:50','2025-06-13 04:24:43','2025-06-13 04:28:50'),('642f708b-3665-4755-b429-a986a871e462','App\\Notifications\\TestNotification','App\\Models\\User',26,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-20 05:59:24','2025-08-20 05:59:24'),('717c882f-9017-4432-8b95-891773b8bc93','App\\Notifications\\WarningLetterIssued','App\\Models\\User',5,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: Absent\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}',NULL,'2025-06-01 04:25:16','2025-06-01 04:25:16'),('7745e897-1679-48c0-a913-e9486f7ac378','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',2,'{\"transaction_id\":2,\"description\":\"Sale of Blue Rose to kya kare\",\"message\":\"A new transaction \'Sale of Blue Rose to kya kare\' is pending approval.\"}','2025-06-13 04:24:21','2025-06-13 04:24:12','2025-06-13 04:24:21'),('7a2e331c-c1c5-4563-9519-789d96def6e2','App\\Notifications\\TestNotification','App\\Models\\User',3,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-05-31 03:34:12','2025-05-31 03:34:12'),('7c2fb3b8-d92a-4e06-a746-0b64d886d531','App\\Notifications\\TestNotification','App\\Models\\User',19,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-18 00:38:54','2025-08-18 00:38:54'),('9e596eb7-e8d2-4755-b36b-890b151d3b92','App\\Notifications\\TransactionApprovalNotification','App\\Models\\User',9,'{\"transaction_id\":6,\"description\":\"Rent\",\"message\":\"A new transaction \'Rent\' is pending approval.\"}',NULL,'2025-06-13 07:56:15','2025-06-13 07:56:15'),('c14bad1e-8901-4467-a729-f6a59439b6d2','App\\Notifications\\WarningLetterIssued','App\\Models\\User',3,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: xyz\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}',NULL,'2025-06-01 04:24:37','2025-06-01 04:24:37'),('c950f06f-f547-451f-a08b-f2849618c738','App\\Notifications\\TestNotification','App\\Models\\User',25,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-08-20 05:03:57','2025-08-20 05:03:57'),('cdae1c25-4134-4fe8-96b8-26c8c34eaf09','App\\Notifications\\TestNotification','App\\Models\\User',2,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}','2025-06-02 05:41:53','2025-05-27 01:05:34','2025-06-02 05:41:53'),('d114ebb7-d08d-4205-bd26-544f90ee4f5c','App\\Notifications\\WarningLetterIssued','App\\Models\\User',2,'{\"title\":\"Warning Letter Issued\",\"message\":\"A warning letter has been issued to you on  for: 1\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employee-portal\"}','2025-06-01 04:07:22','2025-06-01 01:35:21','2025-06-01 04:07:22'),('f27a248e-d087-4e67-bdef-35749c42aecf','App\\Notifications\\TestNotification','App\\Models\\User',5,'{\"message\":\"This is a test notification!\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/notifications\"}',NULL,'2025-05-27 02:14:13','2025-05-27 02:14:13');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
INSERT INTO `order_product` VALUES (3,1,5,2,560.00,'2025-08-18 10:31:37','2025-08-18 10:31:37');
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
  `customer_name` varchar(255) DEFAULT NULL,
  `cart_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cart_items`)),
  `subtotal` decimal(10,2) NOT NULL,
  `gst` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,19,NULL,'\"{\\\"1-red-M\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Red Rose\\\",\\\"price\\\":\\\"50.00\\\",\\\"image\\\":\\\"products\\\\\\/S9IU09X7nhCTSdqP7YHCMLNV4oZWiTnZwBlwnxe1.png\\\",\\\"color\\\":\\\"red\\\",\\\"size\\\":\\\"M\\\",\\\"quantity\\\":12},\\\"1-red-XL\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Red Rose\\\",\\\"price\\\":\\\"50.00\\\",\\\"image\\\":\\\"products\\\\\\/S9IU09X7nhCTSdqP7YHCMLNV4oZWiTnZwBlwnxe1.png\\\",\\\"color\\\":\\\"red\\\",\\\"size\\\":\\\"XL\\\",\\\"quantity\\\":1},\\\"1-red-L\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Red Rose\\\",\\\"price\\\":\\\"50.00\\\",\\\"image\\\":\\\"products\\\\\\/S9IU09X7nhCTSdqP7YHCMLNV4oZWiTnZwBlwnxe1.png\\\",\\\"color\\\":\\\"red\\\",\\\"size\\\":\\\"L\\\",\\\"quantity\\\":6}}\"',950.00,171.00,1121.00,'cod','placed','Malakpet, Hyderabad','2025-08-18 03:37:12','2025-08-18 03:37:12'),(2,19,NULL,'\"{\\\"1-red-L\\\":{\\\"id\\\":1,\\\"name\\\":\\\"Red Rose\\\",\\\"price\\\":\\\"50.00\\\",\\\"image\\\":\\\"products\\\\\\/S9IU09X7nhCTSdqP7YHCMLNV4oZWiTnZwBlwnxe1.png\\\",\\\"color\\\":\\\"red\\\",\\\"size\\\":\\\"L\\\",\\\"quantity\\\":5}}\"',250.00,45.00,295.00,'cod','placed','Malakpet, Hyderabad','2025-08-18 04:45:25','2025-08-18 04:45:25'),(3,19,NULL,'\"{\\\"6-#111111-\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Upper part\\\",\\\"price\\\":\\\"1200.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":null,\\\"quantity\\\":0},\\\"2-#111111-6\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":4},\\\"2-#111111-7\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":7,\\\"quantity\\\":1}}\"',500.00,90.00,590.00,'cod','placed','Malakpet, Hyderabad','2025-08-18 06:39:46','2025-08-18 06:39:46'),(4,19,NULL,'\"{\\\"2-default-default\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":0,\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"color\\\":\\\"default\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":9},\\\"5-default-default\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"default\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":1},\\\"5-#111111-6\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":1},\\\"5-#111111-7\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":7,\\\"quantity\\\":2},\\\"5-#111111-8\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":8,\\\"quantity\\\":3},\\\"5-#111111-9\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":9,\\\"quantity\\\":4},\\\"5-#111111-10\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":10,\\\"quantity\\\":5},\\\"5-#111111-11\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":11,\\\"quantity\\\":6},\\\"5-#111111-12\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":12,\\\"quantity\\\":7}}\"',3480000.00,626400.00,4106400.00,'cod','placed','Malakpet, Hyderabad','2025-08-19 01:07:21','2025-08-19 01:07:21'),(5,5,NULL,'\"[{\\\"product_id\\\":\\\"8\\\",\\\"quantity\\\":\\\"1\\\"}]\"',15.00,2.70,17.70,'cash','pending','Malakpet','2025-08-19 03:21:51','2025-08-19 03:21:51'),(6,5,NULL,'\"[{\\\"product_id\\\":\\\"8\\\",\\\"quantity\\\":\\\"1\\\"}]\"',15.00,2.70,17.70,'cash','pending','hyd','2025-08-19 03:26:34','2025-08-19 03:26:34'),(7,5,NULL,'\"[{\\\"product_id\\\":\\\"8\\\",\\\"quantity\\\":\\\"1\\\"}]\"',15.00,2.70,17.70,'card','pending','jbhj','2025-08-19 03:39:17','2025-08-19 03:39:17'),(8,19,NULL,'\"{\\\"9-#111111-6\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":10},\\\"9-#111111-7\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":7,\\\"quantity\\\":10},\\\"9-#111111-9\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":9,\\\"quantity\\\":10}}\"',360.00,64.80,424.80,'cod','placed','Malakpet, Hyderabad','2025-08-19 04:17:07','2025-08-19 04:17:07'),(9,19,NULL,'\"{\\\"9-#111111-6\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":1},\\\"9-#111111-7\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":7,\\\"quantity\\\":10}}\"',132.00,23.76,155.76,'cod','placed','Malakpet, Hyderabad','2025-08-19 04:23:58','2025-08-19 04:23:58'),(10,19,NULL,'\"{\\\"8-#111111-8\\\":{\\\"product_id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"price\\\":\\\"15.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":8,\\\"quantity\\\":5},\\\"8-#111111-10\\\":{\\\"product_id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"price\\\":\\\"15.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":10,\\\"quantity\\\":10}}\"',225.00,40.50,265.50,'cod','placed','Malakpet, Hyderabad','2025-08-19 04:35:15','2025-08-19 04:35:15'),(11,19,NULL,'\"{\\\"8-#111111-8\\\":{\\\"product_id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"price\\\":\\\"15.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":8,\\\"quantity\\\":0},\\\"8-#111111-10\\\":{\\\"product_id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"price\\\":\\\"15.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":10,\\\"quantity\\\":0}}\"',225.00,40.50,265.50,'cod','placed','Malakpet, Hyderabad','2025-08-19 04:36:26','2025-08-19 05:18:42'),(12,19,NULL,'\"{\\\"2-#111111-6\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":0},\\\"2-#111111-7\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"color\\\":\\\"#111111\\\",\\\"size\\\":7,\\\"quantity\\\":0},\\\"2-#111111-8\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"color\\\":\\\"#111111\\\",\\\"size\\\":8,\\\"quantity\\\":0},\\\"2-#111111-11\\\":{\\\"product_id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"price\\\":\\\"100.00\\\",\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"color\\\":\\\"#111111\\\",\\\"size\\\":11,\\\"quantity\\\":0}}\"',2800.00,504.00,3304.00,'cod','placed','Malakpet, Hyderabad','2025-08-19 05:14:29','2025-08-19 05:16:51'),(13,19,NULL,'\"{\\\"9-#111111-6\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":6,\\\"quantity\\\":5},\\\"9-#111111-9\\\":{\\\"product_id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"price\\\":\\\"12.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":9,\\\"quantity\\\":15}}\"',360.00,64.80,424.80,'cod','placed','Malakpet, Hyderabad','2025-08-19 05:24:32','2025-08-19 05:25:08'),(14,19,NULL,'\"{\\\"5-#111111-9\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":9,\\\"quantity\\\":10},\\\"5-#111111-11\\\":{\\\"product_id\\\":5,\\\"name\\\":\\\"upper part footwear\\\",\\\"price\\\":\\\"120000.00\\\",\\\"image\\\":null,\\\"color\\\":\\\"#111111\\\",\\\"size\\\":11,\\\"quantity\\\":20}}\"',3600000.00,648000.00,4248000.00,'cod','placed','Malakpet, Hyderabad','2025-08-19 05:30:28','2025-08-19 05:30:28'),(15,19,NULL,'\"[{\\\"id\\\":1,\\\"user_id\\\":19,\\\"product_id\\\":6,\\\"color\\\":\\\"default\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":2,\\\"created_at\\\":\\\"2025-08-20T09:41:42.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T09:46:22.000000Z\\\",\\\"product\\\":{\\\"id\\\":6,\\\"name\\\":\\\"Upper part\\\",\\\"sku\\\":\\\"2005\\\",\\\"price\\\":\\\"1200.00\\\",\\\"unit_price\\\":\\\"0.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"0.00\\\",\\\"tax_amount\\\":\\\"0.00\\\",\\\"total_amount\\\":\\\"1200.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-18T11:43:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:21:39.000000Z\\\",\\\"image\\\":\\\"products\\\\\\/IoGD6q4CKmGi6XzV0v5XtbGUi08Axrf7z3ul6NBF.jpg\\\",\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":null}},{\\\"id\\\":2,\\\"user_id\\\":19,\\\"product_id\\\":7,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":1,\\\"created_at\\\":\\\"2025-08-20T09:46:40.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T09:46:40.000000Z\\\",\\\"product\\\":{\\\"id\\\":7,\\\"name\\\":\\\"Finished Product\\\",\\\"sku\\\":\\\"2015\\\",\\\"price\\\":\\\"45.00\\\",\\\"unit_price\\\":\\\"145.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"8.10\\\",\\\"total_amount\\\":\\\"53.10\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"xs\\\",\\\"created_at\\\":\\\"2025-08-18T12:40:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:49:10.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":[{\\\"color\\\":\\\"white\\\",\\\"price\\\":\\\"12\\\",\\\"unit_price\\\":\\\"111\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"45\\\"}]}}]\"',2445.00,440.10,2885.10,'cod','placed','Malakpet, Hyderabad','2025-08-20 04:18:13','2025-08-20 04:18:13'),(16,19,NULL,'\"[{\\\"id\\\":3,\\\"user_id\\\":19,\\\"product_id\\\":2,\\\"color\\\":\\\"default\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":1,\\\"created_at\\\":\\\"2025-08-20T09:53:56.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T09:53:56.000000Z\\\",\\\"product\\\":{\\\"id\\\":2,\\\"name\\\":\\\"Blue Rose\\\",\\\"sku\\\":\\\"2002\\\",\\\"category\\\":null,\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"0.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"0.00\\\",\\\"tax_amount\\\":\\\"0.00\\\",\\\"total_amount\\\":\\\"100.00\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"Blue\\\",\\\"created_at\\\":\\\"2025-05-27T06:59:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:21:24.000000Z\\\",\\\"image\\\":\\\"products\\\\\\/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg\\\",\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":null,\\\"total_quantity\\\":100,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":2,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-05-27T06:59:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-29T09:43:23.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":2,\\\"warehouse_id\\\":2,\\\"quantity\\\":100,\\\"created_at\\\":\\\"2025-05-27T06:59:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:59:18.000000Z\\\"}}]}},{\\\"id\\\":4,\\\"user_id\\\":19,\\\"product_id\\\":10,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"default\\\",\\\"quantity\\\":2,\\\"created_at\\\":\\\"2025-08-20T10:19:24.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:19:59.000000Z\\\",\\\"product\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Black chappals\\\",\\\"sku\\\":\\\"2019\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"wdxrv\\\",\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":20,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":1,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":2,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}}]}},{\\\"id\\\":5,\\\"user_id\\\":19,\\\"product_id\\\":10,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:22:29.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:22:29.000000Z\\\",\\\"product\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Black chappals\\\",\\\"sku\\\":\\\"2019\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"wdxrv\\\",\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":20,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":1,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":2,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}}]}},{\\\"id\\\":6,\\\"user_id\\\":19,\\\"product_id\\\":10,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"9\\\",\\\"quantity\\\":20,\\\"created_at\\\":\\\"2025-08-20T10:22:29.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:22:29.000000Z\\\",\\\"product\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Black chappals\\\",\\\"sku\\\":\\\"2019\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"wdxrv\\\",\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":20,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":1,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":2,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}}]}},{\\\"id\\\":7,\\\"user_id\\\":19,\\\"product_id\\\":9,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":40,\\\"created_at\\\":\\\"2025-08-21T08:51:17.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T08:51:17.000000Z\\\",\\\"product\\\":{\\\"id\\\":9,\\\"name\\\":\\\"bata\\\",\\\"sku\\\":\\\"2018\\\",\\\"category\\\":null,\\\"price\\\":\\\"12.00\\\",\\\"unit_price\\\":\\\"120.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.16\\\",\\\"total_amount\\\":\\\"14.16\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"cefcr\\\",\\\"created_at\\\":\\\"2025-08-18T13:11:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T13:51:12.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"12\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"40\\\\\\\",\\\\\\\"image\\\\\\\":null},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"8\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"20\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":9,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T13:11:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T13:51:12.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":9,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T13:11:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T13:51:12.000000Z\\\"}}]}}]\"',4420.00,795.60,5215.60,'cod','placed','Malakpet, Hyderabad','2025-08-21 03:21:29','2025-08-21 03:21:29'),(17,19,NULL,'\"[{\\\"id\\\":8,\\\"user_id\\\":19,\\\"product_id\\\":10,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-21T08:53:14.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T08:53:14.000000Z\\\",\\\"product\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Black chappals\\\",\\\"sku\\\":\\\"2019\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"wdxrv\\\",\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":20,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":1,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":2,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}}]}}]\"',1200.00,216.00,1416.00,'cod','placed','Malakpet, Hyderabad','2025-08-21 03:24:50','2025-08-21 03:24:50'),(18,5,NULL,'\"[{\\\"id\\\":9,\\\"user_id\\\":5,\\\"product_id\\\":8,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"5\\\",\\\"quantity\\\":12,\\\"created_at\\\":\\\"2025-08-21T09:48:55.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T09:48:55.000000Z\\\",\\\"product\\\":{\\\"id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"sku\\\":\\\"2014\\\",\\\"category\\\":null,\\\"price\\\":\\\"15.00\\\",\\\"unit_price\\\":\\\"150.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.70\\\",\\\"total_amount\\\":\\\"17.70\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"sdrfv\\\",\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"150\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"12\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"20\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"13\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"102\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"22\\\\\\\"}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}}]}},{\\\"id\\\":10,\\\"user_id\\\":5,\\\"product_id\\\":8,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"8\\\",\\\"quantity\\\":20,\\\"created_at\\\":\\\"2025-08-21T09:48:55.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T09:48:55.000000Z\\\",\\\"product\\\":{\\\"id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"sku\\\":\\\"2014\\\",\\\"category\\\":null,\\\"price\\\":\\\"15.00\\\",\\\"unit_price\\\":\\\"150.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.70\\\",\\\"total_amount\\\":\\\"17.70\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"sdrfv\\\",\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"150\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"12\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"20\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"13\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"102\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"22\\\\\\\"}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}}]}},{\\\"id\\\":11,\\\"user_id\\\":5,\\\"product_id\\\":13,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"9\\\",\\\"quantity\\\":200,\\\"created_at\\\":\\\"2025-08-21T09:51:17.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T09:51:17.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"black sneakers\\\",\\\"sku\\\":\\\"701\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"200.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T04:51:50.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T04:51:50.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"9\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}}]\"',20480.00,3686.40,24166.40,'cod','placed','2-54/1\nkondapur, Ghatkesar','2025-08-21 04:21:25','2025-08-21 04:21:25'),(19,5,NULL,'\"{\\\"9\\\":{\\\"product_id\\\":\\\"9\\\",\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"12.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"},\\\"15\\\":{\\\"product_id\\\":\\\"15\\\",\\\"quantity\\\":\\\"2\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"},\\\"16_N\\\\\\/A_N\\\\\\/A\\\":{\\\"product_id\\\":\\\"16_N\\\\\\/A_N\\\\\\/A\\\",\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"120.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}}\"',332.00,59.76,391.76,'cash','pending','Customer Address','2025-08-21 04:55:24','2025-08-21 04:55:24'),(20,5,NULL,'\"[{\\\"product_id\\\":13,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:27:28','2025-08-21 05:27:28'),(21,5,NULL,'\"[{\\\"product_id\\\":13,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:29:52','2025-08-21 05:29:52'),(22,5,NULL,'\"[{\\\"product_id\\\":13,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:30:54','2025-08-21 05:30:54'),(23,5,NULL,'\"[{\\\"product_id\\\":13,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:33:29','2025-08-21 05:33:29'),(24,5,NULL,'\"[{\\\"product_id\\\":6,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"1200.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',1200.00,216.00,1416.00,'cash','pending','Customer Address Here','2025-08-21 05:34:33','2025-08-21 05:34:33'),(25,5,NULL,'\"[{\\\"product_id\\\":6,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"1200.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',1200.00,216.00,1416.00,'cash','pending','Customer Address Here','2025-08-21 05:41:25','2025-08-21 05:41:25'),(26,5,NULL,'\"[{\\\"product_id\\\":6,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"1200.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',1200.00,216.00,1416.00,'cash','pending','Customer Address Here','2025-08-21 05:42:44','2025-08-21 05:42:44'),(27,5,NULL,'\"[{\\\"product_id\\\":6,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"1200.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',1200.00,216.00,1416.00,'cash','pending','wxef','2025-08-21 05:46:20','2025-08-21 05:46:20'),(28,5,NULL,'\"[{\\\"product_id\\\":11,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"},{\\\"product_id\\\":18,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',200.00,36.00,236.00,'cash','pending','Customer Address Here','2025-08-21 05:48:29','2025-08-21 05:48:29'),(29,5,NULL,'\"[{\\\"product_id\\\":11,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','wdf','2025-08-21 05:55:32','2025-08-21 05:55:32'),(30,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:56:20','2025-08-21 05:56:20'),(31,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:56:29','2025-08-21 05:56:29'),(32,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 05:58:51','2025-08-21 05:58:51'),(33,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','xwe','2025-08-21 05:59:01','2025-08-21 05:59:01'),(34,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','wdxe','2025-08-21 06:03:27','2025-08-21 06:03:27'),(35,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','wdxe','2025-08-21 06:05:24','2025-08-21 06:05:24'),(36,5,NULL,'\"[{\\\"product_id\\\":20,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','wd','2025-08-21 06:06:13','2025-08-21 06:06:13'),(37,5,NULL,'\"[{\\\"product_id\\\":17,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 06:06:49','2025-08-21 06:06:49'),(38,5,NULL,'\"[{\\\"product_id\\\":17,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 06:07:58','2025-08-21 06:07:58'),(39,5,NULL,'\"[{\\\"product_id\\\":17,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 06:08:31','2025-08-21 06:08:31'),(40,5,NULL,'\"[{\\\"product_id\\\":17,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','qswdf','2025-08-21 06:08:41','2025-08-21 06:08:41'),(41,5,NULL,'\"[{\\\"product_id\\\":14,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 06:13:23','2025-08-21 06:13:23'),(42,5,NULL,'\"[{\\\"product_id\\\":14,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Malakpet Hyderabad','2025-08-21 06:13:37','2025-08-21 06:13:37'),(43,5,NULL,'\"[{\\\"product_id\\\":16,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"120.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',120.00,21.60,141.60,'cash','pending','Customer Address Here','2025-08-21 06:21:28','2025-08-21 06:21:28'),(44,5,NULL,'\"[{\\\"product_id\\\":16,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"120.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',120.00,21.60,141.60,'cash','pending','Malakpet','2025-08-21 06:21:40','2025-08-21 06:21:40'),(45,5,NULL,'\"[{\\\"product_id\\\":14,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Customer Address Here','2025-08-21 06:27:42','2025-08-21 06:27:42'),(46,5,NULL,'\"[{\\\"product_id\\\":14,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:27:55','2025-08-21 06:27:55'),(47,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:30:20','2025-08-21 06:30:20'),(48,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:31:31','2025-08-21 06:31:31'),(49,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":100,\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:32:17','2025-08-21 06:32:17'),(50,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":100,\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:32:50','2025-08-21 06:32:50'),(51,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":100,\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:33:41','2025-08-21 06:33:41'),(52,5,NULL,'\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":100,\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:34:53','2025-08-21 06:34:53'),(53,5,'Rohith Chandra','\"[{\\\"product_id\\\":2,\\\"quantity\\\":1,\\\"price\\\":100,\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',100.00,18.00,118.00,'cash','pending','Balanagar','2025-08-21 06:35:23','2025-08-21 06:35:23'),(54,5,'Nanditha','\"[{\\\"product_id\\\":8,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"15.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"},{\\\"product_id\\\":15,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"100.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"},{\\\"product_id\\\":7,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"45.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',160.00,28.80,188.80,'cash','pending','Customer Address Here','2025-08-21 07:37:40','2025-08-21 07:37:40'),(55,5,NULL,'\"[{\\\"product_id\\\":10,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"120.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',120.00,21.60,141.60,'cash','pending','Customer Address Here','2025-08-21 07:38:57','2025-08-21 07:38:57'),(56,5,'Nanditha','\"[{\\\"product_id\\\":10,\\\"quantity\\\":\\\"1\\\",\\\"price\\\":\\\"120.00\\\",\\\"color\\\":\\\"N\\\\\\/A\\\",\\\"size\\\":\\\"N\\\\\\/A\\\"}]\"',120.00,21.60,141.60,'cash','pending','Hyderabad','2025-08-21 07:39:08','2025-08-21 07:39:08'),(57,5,NULL,'\"[{\\\"id\\\":12,\\\"user_id\\\":5,\\\"product_id\\\":14,\\\"color\\\":\\\"black\\\",\\\"size\\\":\\\"8\\\",\\\"quantity\\\":300,\\\"created_at\\\":\\\"2025-08-21T10:05:01.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T13:11:19.000000Z\\\",\\\"product\\\":{\\\"id\\\":14,\\\"name\\\":\\\"black sneakers\\\",\\\"sku\\\":\\\"708\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"180.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T04:58:56.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T04:58:56.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"8\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"180\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":13,\\\"user_id\\\":5,\\\"product_id\\\":10,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-21T10:05:14.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:05:14.000000Z\\\",\\\"product\\\":{\\\"id\\\":10,\\\"name\\\":\\\"Black chappals\\\",\\\"sku\\\":\\\"2019\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"120.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"21.60\\\",\\\"total_amount\\\":\\\"141.60\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"wdxrv\\\",\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"size\\\\\\\":\\\\\\\"7\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"120\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":20,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":1,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":10,\\\"warehouse_id\\\":2,\\\"quantity\\\":10,\\\"created_at\\\":\\\"2025-08-20T10:02:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-20T10:08:30.000000Z\\\"}}]}},{\\\"id\\\":14,\\\"user_id\\\":5,\\\"product_id\\\":13,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"9\\\",\\\"quantity\\\":200,\\\"created_at\\\":\\\"2025-08-21T10:07:05.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:07:05.000000Z\\\",\\\"product\\\":{\\\"id\\\":13,\\\"name\\\":\\\"black sneakers\\\",\\\"sku\\\":\\\"701\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"200.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T04:51:50.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T04:51:50.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"9\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":15,\\\"user_id\\\":5,\\\"product_id\\\":15,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"8\\\",\\\"quantity\\\":100,\\\"created_at\\\":\\\"2025-08-21T10:13:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:13:26.000000Z\\\",\\\"product\\\":{\\\"id\\\":15,\\\"name\\\":\\\"red sneakers\\\",\\\"sku\\\":\\\"710\\\",\\\"category\\\":\\\"Mens Footwear\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"199.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T05:03:12.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T05:03:12.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"8\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"199\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":16,\\\"user_id\\\":5,\\\"product_id\\\":17,\\\"color\\\":\\\"Red\\\",\\\"size\\\":\\\"M\\\",\\\"quantity\\\":50,\\\"created_at\\\":\\\"2025-08-21T10:16:21.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:16:21.000000Z\\\",\\\"product\\\":{\\\"id\\\":17,\\\"name\\\":\\\"Test Product\\\",\\\"sku\\\":\\\"SKU12345\\\",\\\"category\\\":\\\"Electronics\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"90.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"Sample test product\\\",\\\"created_at\\\":\\\"2025-08-21T05:19:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T05:19:44.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"M\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":100,\\\\\\\"unit_price\\\\\\\":90,\\\\\\\"gst\\\\\\\":18,\\\\\\\"quantity\\\\\\\":50},{\\\\\\\"size\\\\\\\":\\\\\\\"L\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"Blue\\\\\\\",\\\\\\\"price\\\\\\\":120,\\\\\\\"unit_price\\\\\\\":110,\\\\\\\"gst\\\\\\\":18,\\\\\\\"quantity\\\\\\\":30}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":17,\\\"user_id\\\":5,\\\"product_id\\\":20,\\\"color\\\":\\\"brown\\\",\\\"size\\\":\\\"6\\\",\\\"quantity\\\":100,\\\"created_at\\\":\\\"2025-08-21T10:18:23.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:18:23.000000Z\\\",\\\"product\\\":{\\\"id\\\":20,\\\"name\\\":\\\"red boots\\\",\\\"sku\\\":\\\"721\\\",\\\"category\\\":\\\"women\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"200.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T05:28:30.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T05:28:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"6\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"brown\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":18,\\\"user_id\\\":5,\\\"product_id\\\":20,\\\"color\\\":\\\"brown\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":20,\\\"created_at\\\":\\\"2025-08-21T10:18:23.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T10:18:23.000000Z\\\",\\\"product\\\":{\\\"id\\\":20,\\\"name\\\":\\\"red boots\\\",\\\"sku\\\":\\\"721\\\",\\\"category\\\":\\\"women\\\",\\\"price\\\":\\\"100.00\\\",\\\"unit_price\\\":\\\"200.00\\\",\\\"low_stock_threshold\\\":10,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"18.00\\\",\\\"total_amount\\\":\\\"118.00\\\",\\\"total_price\\\":null,\\\"description\\\":null,\\\"created_at\\\":\\\"2025-08-21T05:28:30.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T05:28:30.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"size\\\\\\\":\\\\\\\"6\\\\\\\",\\\\\\\"color\\\\\\\":\\\\\\\"brown\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"200\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"image\\\\\\\":null}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[]}},{\\\"id\\\":19,\\\"user_id\\\":5,\\\"product_id\\\":8,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"quantity\\\":12,\\\"created_at\\\":\\\"2025-08-21T12:32:40.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T12:32:40.000000Z\\\",\\\"product\\\":{\\\"id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"sku\\\":\\\"2014\\\",\\\"category\\\":null,\\\"price\\\":\\\"15.00\\\",\\\"unit_price\\\":\\\"150.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.70\\\",\\\"total_amount\\\":\\\"17.70\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"sdrfv\\\",\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"150\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"12\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"20\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"13\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"102\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"22\\\\\\\"}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}}]}},{\\\"id\\\":20,\\\"user_id\\\":5,\\\"product_id\\\":8,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"5\\\",\\\"quantity\\\":12,\\\"created_at\\\":\\\"2025-08-21T12:35:42.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T12:35:42.000000Z\\\",\\\"product\\\":{\\\"id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"sku\\\":\\\"2014\\\",\\\"category\\\":null,\\\"price\\\":\\\"15.00\\\",\\\"unit_price\\\":\\\"150.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.70\\\",\\\"total_amount\\\":\\\"17.70\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"sdrfv\\\",\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"150\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"12\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"20\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"13\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"102\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"22\\\\\\\"}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}}]}},{\\\"id\\\":21,\\\"user_id\\\":5,\\\"product_id\\\":8,\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"8\\\",\\\"quantity\\\":12,\\\"created_at\\\":\\\"2025-08-21T13:10:57.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-21T13:10:57.000000Z\\\",\\\"product\\\":{\\\"id\\\":8,\\\"name\\\":\\\"Sneakers\\\",\\\"sku\\\":\\\"2014\\\",\\\"category\\\":null,\\\"price\\\":\\\"15.00\\\",\\\"unit_price\\\":\\\"150.00\\\",\\\"low_stock_threshold\\\":0,\\\"tax_rate\\\":\\\"18.00\\\",\\\"tax_amount\\\":\\\"2.70\\\",\\\"total_amount\\\":\\\"17.70\\\",\\\"total_price\\\":null,\\\"description\\\":\\\"sdrfv\\\",\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"image\\\":null,\\\"colors\\\":null,\\\"sizes\\\":null,\\\"variations\\\":\\\"[{\\\\\\\"color\\\\\\\":\\\\\\\"white\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"15\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"150\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"12\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"black\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"10\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"100\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"20\\\\\\\"},{\\\\\\\"color\\\\\\\":\\\\\\\"Red\\\\\\\",\\\\\\\"price\\\\\\\":\\\\\\\"13\\\\\\\",\\\\\\\"unit_price\\\\\\\":\\\\\\\"102\\\\\\\",\\\\\\\"gst\\\\\\\":\\\\\\\"18\\\\\\\",\\\\\\\"quantity\\\\\\\":\\\\\\\"22\\\\\\\"}]\\\",\\\"total_quantity\\\":0,\\\"warehouses\\\":[{\\\"id\\\":1,\\\"name\\\":\\\"Mannan\\\",\\\"location\\\":\\\"Hyderabad\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:28.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":1,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}},{\\\"id\\\":2,\\\"name\\\":\\\"Sultan\\\",\\\"location\\\":\\\"Bangalore\\\",\\\"description\\\":\\\"New\\\",\\\"created_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-05-27T06:58:36.000000Z\\\",\\\"pivot\\\":{\\\"product_id\\\":8,\\\"warehouse_id\\\":2,\\\"quantity\\\":0,\\\"created_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\",\\\"updated_at\\\":\\\"2025-08-18T12:50:47.000000Z\\\"}}]}}]\"',78740.00,14173.20,92913.20,'cod','placed','wdf','2025-08-21 07:41:31','2025-08-21 07:41:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,8,9200.00,'2025-06-14','Bank Transfer','knk','2025-06-13 23:43:01','2025-06-13 23:43:01'),(2,7,107000.00,'2025-06-14','Cash','asda','2025-06-13 23:43:47','2025-06-13 23:43:47'),(3,7,50000.00,'2025-08-19','Bank Transfer',NULL,'2025-08-19 03:53:40','2025-08-19 03:53:40');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
INSERT INTO `payrolls` VALUES (1,1,10000.00,'2025-05-05','April','2025-05-29 04:15:23','2025-05-29 04:15:23','sa',0.00,0.00,10000.00,'pending',NULL,NULL,NULL),(2,1,10000.00,'2025-06-13','Salary','2025-06-13 06:06:11','2025-06-13 06:06:11','sa',0.00,0.00,10000.00,'pending',NULL,NULL,NULL),(3,2,10000.00,'2025-06-16',NULL,'2025-06-16 07:28:50','2025-06-16 07:28:50','sa',0.00,0.00,10000.00,'pending',NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage hr','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(2,'view hr','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(3,'manage sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(4,'view sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(5,'manage inventory','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(6,'view inventory','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(7,'manage finance','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(8,'view finance','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(9,'manage settings','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(10,'view dashboard','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(11,'view reports','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(12,'manage users','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(13,'view notifications','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(14,'sales','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(15,'view production','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(16,'manage production','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(17,'view employee portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(18,'access employee portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(19,'manage productions','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(20,'view productions','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(21,'manage notifications','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(22,'manage roles','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(23,'view roles','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(24,'access manager portal','web','2025-05-29 03:59:10','2025-05-29 03:59:10'),(25,'sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(26,'view sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(27,'manage sales dashboard','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(28,'manage quotations','web','2025-05-29 04:08:05','2025-05-29 04:08:05'),(29,'production','web','2025-05-31 00:42:12','2025-05-31 00:42:12'),(30,'process production','web','2025-05-31 00:42:43','2025-05-31 00:42:43'),(31,'manage tenants','web','2025-06-10 06:30:08','2025-06-10 06:30:08'),(32,'approve transactions','web','2025-06-13 04:14:23','2025-06-13 04:14:23'),(33,'manage payroll','web','2025-06-16 05:28:40','2025-06-16 05:28:40'),(34,'client.orders','web','2025-08-18 00:45:52','2025-08-18 00:45:52');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
INSERT INTO `processes` VALUES (1,'stag1',NULL,NULL,2,'In Progress',0,'2025-08-16 23:01:45','2025-08-16 23:26:54'),(2,'stage2',NULL,NULL,0,'Pending',0,'2025-08-16 23:27:07','2025-08-16 23:27:07'),(3,'bottom',NULL,NULL,0,'Pending',0,'2025-08-17 01:57:01','2025-08-17 01:57:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_quotation`
--

LOCK TABLES `product_quotation` WRITE;
/*!40000 ALTER TABLE `product_quotation` DISABLE KEYS */;
INSERT INTO `product_quotation` VALUES (6,2,2,1,100.00,NULL,NULL),(7,4,2,1,100.00,NULL,NULL),(8,5,2,1,100.00,NULL,NULL),(10,6,2,80,100.00,NULL,NULL),(11,7,3,200,900.00,NULL,NULL),(12,8,8,1,15.00,NULL,NULL),(13,9,8,1,15.00,NULL,NULL),(14,10,7,1,45.00,NULL,NULL),(15,13,3,1,900.00,NULL,NULL),(16,14,9,1,12.00,NULL,NULL),(17,15,15,1,100.00,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_warehouse`
--

LOCK TABLES `product_warehouse` WRITE;
/*!40000 ALTER TABLE `product_warehouse` DISABLE KEYS */;
INSERT INTO `product_warehouse` VALUES (3,2,1,0,'2025-05-27 01:29:18','2025-05-29 04:13:23'),(4,2,2,100,'2025-05-27 01:29:18','2025-05-27 01:29:18'),(5,3,1,100,'2025-06-12 23:31:16','2025-06-12 23:31:16'),(6,3,2,200,'2025-06-12 23:31:16','2025-06-12 23:31:16'),(9,5,1,5,'2025-08-14 05:20:25','2025-08-14 05:20:25'),(10,5,2,2,'2025-08-14 05:20:25','2025-08-14 05:20:25'),(11,6,1,1,'2025-08-18 06:13:26','2025-08-18 06:13:26'),(12,6,2,12,'2025-08-18 06:13:26','2025-08-18 06:13:26'),(13,7,1,4,'2025-08-18 07:10:05','2025-08-18 07:18:30'),(14,7,2,5,'2025-08-18 07:10:05','2025-08-18 07:18:30'),(15,8,1,0,'2025-08-18 07:20:47','2025-08-18 07:20:47'),(16,8,2,0,'2025-08-18 07:20:47','2025-08-18 07:20:47'),(17,9,1,0,'2025-08-18 07:41:26','2025-08-18 08:21:12'),(18,9,2,0,'2025-08-18 07:41:26','2025-08-18 08:21:12'),(19,10,1,10,'2025-08-20 04:32:44','2025-08-20 04:38:30'),(20,10,2,10,'2025-08-20 04:32:44','2025-08-20 04:38:30'),(21,11,1,0,'2025-08-20 06:35:21','2025-08-20 06:36:23'),(22,11,2,0,'2025-08-20 06:35:21','2025-08-20 06:36:23');
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
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_orders_quotation_id_foreign` (`quotation_id`),
  KEY `fk_production_orders_batch` (`batch_id`),
  KEY `production_orders_client_order_id_foreign` (`client_order_id`),
  CONSTRAINT `fk_production_orders_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `production_orders_client_order_id_foreign` FOREIGN KEY (`client_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `production_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_orders`
--

LOCK TABLES `production_orders` WRITE;
/*!40000 ALTER TABLE `production_orders` DISABLE KEYS */;
INSERT INTO `production_orders` VALUES (1,1,3,NULL,'delivered','2025-06-30','2025-05-29 09:25:01','2025-05-31 00:56:14',NULL),(2,1,4,NULL,'delivered','2025-07-01','2025-05-31 01:34:03','2025-05-31 01:34:10',2),(3,1,5,NULL,'delivered','2025-07-02','2025-05-31 01:55:33','2025-05-31 01:56:29',3),(4,1,7,NULL,'delivered','2025-07-03','2025-06-13 07:46:01','2025-06-13 07:46:13',NULL),(5,1,6,NULL,'delivered','2025-07-04','2025-06-13 07:57:32','2025-06-13 07:57:58',2),(7,1,6,NULL,'processing','2025-08-25','2025-08-16 08:18:21','2025-08-16 09:18:48',NULL),(8,1,NULL,NULL,'pending','2025-08-26','2025-08-19 04:29:10','2025-08-19 04:29:10',NULL),(9,1,NULL,11,'delivered','2025-08-26','2025-08-19 04:36:26','2025-08-19 05:18:43',NULL),(10,1,NULL,12,'delivered','2025-08-26','2025-08-19 05:14:29','2025-08-19 05:16:51',NULL),(11,1,NULL,13,'partially delivered','2025-08-26','2025-08-19 05:24:32','2025-08-19 05:25:08',NULL),(12,1,NULL,14,'pending','2025-08-26','2025-08-19 05:30:28','2025-08-19 05:30:28',NULL),(13,1,NULL,15,'pending','2025-08-27','2025-08-20 04:18:13','2025-08-20 04:18:13',NULL),(14,1,NULL,16,'pending','2025-08-28','2025-08-21 03:21:29','2025-08-21 03:21:29',NULL),(15,1,NULL,17,'pending','2025-08-28','2025-08-21 03:24:50','2025-08-21 03:24:50',NULL),(16,1,15,NULL,'pending',NULL,'2025-08-21 03:38:51','2025-08-21 03:38:51',NULL),(17,1,8,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(18,1,9,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(19,1,10,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(20,1,13,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(21,1,14,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(22,1,15,NULL,'delivered',NULL,'2025-08-21 09:26:02','2025-08-21 09:26:02',NULL),(23,1,NULL,18,'pending','2025-08-28','2025-08-21 04:21:25','2025-08-21 04:21:25',NULL),(24,1,NULL,57,'pending','2025-08-28','2025-08-21 07:41:31','2025-08-21 07:41:31',NULL);
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
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `process_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_quantity` int(11) DEFAULT 0,
  `completed_quantity` int(11) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `operator` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_processes_product_id_foreign` (`product_id`),
  CONSTRAINT `production_processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_processes`
--

LOCK TABLES `production_processes` WRITE;
/*!40000 ALTER TABLE `production_processes` DISABLE KEYS */;
INSERT INTO `production_processes` VALUES (1,16,NULL,1,NULL,0,0,'stag1','Pending','Pending',NULL,NULL,NULL),(2,16,NULL,2,NULL,0,0,'stage2','Pending','Pending',NULL,NULL,NULL),(3,18,NULL,2,NULL,0,0,'stage2','Pending','Pending',NULL,NULL,NULL),(4,19,NULL,1,NULL,0,0,'stag1','Pending','Pending',NULL,NULL,NULL),(5,19,NULL,3,NULL,0,0,'bottom','Pending','Pending',NULL,NULL,NULL),(6,20,NULL,2,NULL,0,0,'stage2','Pending','Pending',NULL,NULL,NULL),(7,20,NULL,3,NULL,0,0,'bottom','Pending','Pending',NULL,NULL,NULL);
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
  `category` varchar(255) DEFAULT NULL,
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
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`colors`)),
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sizes`)),
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variations`)),
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (2,'Blue Rose','2002',NULL,100.00,0.00,10,0.00,0.00,100.00,NULL,'Blue','2025-05-27 01:29:18','2025-08-18 06:51:24','products/Uh6rMxynj46SaQ11lfU6TAGRK7Lrt16FQCsdofOg.jpg',NULL,NULL,NULL,0),(3,'Sun Flower','2009',NULL,900.00,0.00,10,0.00,0.00,900.00,NULL,'Fresh','2025-06-12 23:31:16','2025-06-12 23:31:16',NULL,NULL,NULL,NULL,0),(5,'upper part footwear','2003',NULL,120000.00,0.00,10,5.00,6000.00,126000.00,NULL,NULL,'2025-08-14 05:20:25','2025-08-14 05:20:25',NULL,NULL,NULL,NULL,0),(6,'Upper part','2005',NULL,1200.00,0.00,10,0.00,0.00,1200.00,NULL,NULL,'2025-08-18 06:13:26','2025-08-18 06:51:39','products/IoGD6q4CKmGi6XzV0v5XtbGUi08Axrf7z3ul6NBF.jpg',NULL,NULL,NULL,0),(7,'Finished Product','2015',NULL,45.00,145.00,10,18.00,8.10,53.10,NULL,'xs','2025-08-18 07:10:05','2025-08-18 07:19:10',NULL,NULL,NULL,'[{\"color\":\"white\",\"price\":\"12\",\"unit_price\":\"111\",\"gst\":\"18\",\"quantity\":\"45\"}]',0),(8,'Sneakers','2014',NULL,15.00,150.00,0,18.00,2.70,17.70,NULL,'sdrfv','2025-08-18 07:20:47','2025-08-18 07:20:47',NULL,NULL,NULL,'\"[{\\\"color\\\":\\\"white\\\",\\\"price\\\":\\\"15\\\",\\\"unit_price\\\":\\\"150\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"12\\\"},{\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"10\\\",\\\"unit_price\\\":\\\"100\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"20\\\"},{\\\"color\\\":\\\"Red\\\",\\\"price\\\":\\\"13\\\",\\\"unit_price\\\":\\\"102\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"22\\\"}]\"',0),(9,'bata','2018',NULL,12.00,120.00,0,18.00,2.16,14.16,NULL,'cefcr','2025-08-18 07:41:26','2025-08-18 08:21:12',NULL,NULL,NULL,'\"[{\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"price\\\":\\\"12\\\",\\\"unit_price\\\":\\\"120\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"40\\\",\\\"image\\\":null},{\\\"color\\\":\\\"Red\\\",\\\"size\\\":\\\"8\\\",\\\"price\\\":\\\"20\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"15\\\",\\\"image\\\":null}]\"',0),(10,'Black chappals','2019','Mens Footwear',120.00,199.00,10,18.00,21.60,141.60,NULL,'wdxrv','2025-08-20 04:32:44','2025-08-20 04:38:30',NULL,NULL,NULL,'\"[{\\\"color\\\":\\\"white\\\",\\\"size\\\":\\\"7\\\",\\\"price\\\":\\\"120\\\",\\\"unit_price\\\":\\\"199\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"10\\\",\\\"image\\\":null}]\"',0),(11,'kirsn dhoe11','100','ladies',100.00,150.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 06:35:21','2025-08-20 06:36:23',NULL,NULL,NULL,'\"[{\\\"color\\\":\\\"black\\\",\\\"size\\\":\\\"35\\\\\\/7\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"150\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"200\\\",\\\"image\\\":\\\"products\\\\\\/variations\\\\\\/zFyD66Z1KIzu6PxVKJtnWypXsAxxuRic0vH2BnVO.png\\\"},{\\\"color\\\":\\\"black\\\",\\\"size\\\":\\\"35\\\\\\/4\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"150\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"200\\\",\\\"image\\\":null}]\"',0),(12,'black sneakers','700','Mens Footwear',100.00,200.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:08:11','2025-08-20 23:08:11',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"8\\\",\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"10\\\",\\\"image\\\":null}]\"',10),(13,'black sneakers','701','Mens Footwear',100.00,200.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:21:50','2025-08-20 23:21:50',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"9\\\",\\\"color\\\":\\\"white\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"200\\\",\\\"image\\\":null}]\"',200),(14,'black sneakers','708','Mens Footwear',100.00,180.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:28:56','2025-08-20 23:28:56',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"8\\\",\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"180\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"image\\\":null}]\"',100),(15,'red sneakers','710','Mens Footwear',100.00,199.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:33:12','2025-08-20 23:33:12',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"8\\\",\\\"color\\\":\\\"white\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"199\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"image\\\":null}]\"',100),(16,'red shoes','711','Mens Footwear',120.00,200.00,10,18.00,21.60,141.60,NULL,NULL,'2025-08-20 23:41:40','2025-08-20 23:41:40',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"8\\\",\\\"color\\\":\\\"black\\\",\\\"price\\\":\\\"120\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"image\\\":null}]\"',100),(17,'Test Product','SKU12345','Electronics',100.00,90.00,10,18.00,18.00,118.00,NULL,'Sample test product','2025-08-20 23:49:44','2025-08-20 23:49:44',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"M\\\",\\\"color\\\":\\\"Red\\\",\\\"price\\\":100,\\\"unit_price\\\":90,\\\"gst\\\":18,\\\"quantity\\\":50},{\\\"size\\\":\\\"L\\\",\\\"color\\\":\\\"Blue\\\",\\\"price\\\":120,\\\"unit_price\\\":110,\\\"gst\\\":18,\\\"quantity\\\":30}]\"',80),(18,'woodland','715','womens',100.00,200.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:52:18','2025-08-20 23:52:18',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"8\\\",\\\"color\\\":\\\"brown\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"image\\\":null}]\"',100),(19,'leather','722','women',100.00,200.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:56:38','2025-08-20 23:56:38',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"7\\\",\\\"color\\\":\\\"brown\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"25\\\",\\\"image\\\":null}]\"',25),(20,'red boots','721','women',100.00,200.00,10,18.00,18.00,118.00,NULL,NULL,'2025-08-20 23:58:30','2025-08-20 23:58:30',NULL,NULL,NULL,'\"[{\\\"size\\\":\\\"6\\\",\\\"color\\\":\\\"brown\\\",\\\"price\\\":\\\"100\\\",\\\"unit_price\\\":\\\"200\\\",\\\"gst\\\":\\\"18\\\",\\\"quantity\\\":\\\"100\\\",\\\"image\\\":null}]\"',100);
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
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `salesperson_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_salesperson_id_foreign` (`salesperson_id`),
  KEY `quotations_warehouse_id_foreign` (`warehouse_id`),
  KEY `quotations_client_id_foreign` (`client_id`),
  CONSTRAINT `fk_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotations_salesperson_id_foreign` FOREIGN KEY (`salesperson_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
INSERT INTO `quotations` VALUES (1,1,2,'cancelled',0.00,0.00,0.00,'2025-05-29 08:33:43','2025-05-29 09:07:51',1),(2,1,2,'approved',0.00,0.00,0.00,'2025-05-29 09:06:09','2025-05-29 09:06:19',1),(3,1,2,'approved',0.00,0.00,0.00,'2025-05-29 09:24:57','2025-05-29 09:25:01',1),(4,1,2,'approved',0.00,0.00,0.00,'2025-05-31 01:34:00','2025-05-31 01:34:03',2),(5,1,2,'approved',0.00,0.00,0.00,'2025-05-31 01:55:16','2025-05-31 01:55:33',1),(6,1,2,'approved',8000.00,1200.00,9200.00,'2025-06-12 23:30:40','2025-06-13 07:57:32',1),(7,1,2,'approved',180000.00,27000.00,207000.00,'2025-06-12 23:31:42','2025-06-13 07:46:01',1),(8,NULL,5,'pending',15.00,2.25,17.25,'2025-08-20 02:04:13','2025-08-20 02:04:13',1),(9,1,5,'pending',15.00,2.25,17.25,'2025-08-20 02:13:14','2025-08-20 02:13:14',1),(10,NULL,5,'pending',45.00,6.75,51.75,'2025-08-20 02:39:53','2025-08-20 02:39:53',2),(13,23,5,'pending',900.00,135.00,1035.00,'2025-08-20 02:54:04','2025-08-20 02:54:04',2),(14,24,5,'pending',12.00,1.80,13.80,'2025-08-20 03:00:27','2025-08-20 03:00:27',1),(15,29,5,'approved',100.00,15.00,115.00,'2025-08-21 03:36:31','2025-08-21 03:38:51',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raw_materials`
--

LOCK TABLES `raw_materials` WRITE;
/*!40000 ALTER TABLE `raw_materials` DISABLE KEYS */;
INSERT INTO `raw_materials` VALUES (1,19,'rubber','120',NULL,0,'2025-08-20 23:56:38','2025-08-20 23:56:38'),(2,20,'leathe','120',NULL,0,'2025-08-20 23:58:30','2025-08-20 23:58:30');
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
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(1,15),(1,16),(1,17),(2,1),(2,2),(2,3),(2,15),(2,16),(2,17),(3,1),(3,4),(3,11),(3,17),(4,1),(4,4),(4,5),(4,11),(4,17),(5,1),(5,6),(5,17),(6,1),(6,6),(6,7),(6,17),(7,1),(7,8),(7,14),(7,17),(8,1),(8,8),(8,9),(8,14),(8,17),(9,1),(9,17),(10,1),(10,2),(10,3),(10,4),(10,5),(10,6),(10,7),(10,8),(10,9),(10,12),(10,17),(11,1),(11,2),(11,4),(11,6),(11,8),(11,17),(12,1),(12,17),(13,1),(13,2),(13,3),(13,4),(13,5),(13,6),(13,7),(13,8),(13,9),(13,10),(13,14),(13,16),(13,17),(14,1),(14,17),(15,1),(15,4),(15,10),(15,17),(16,1),(16,17),(17,1),(17,10),(17,17),(18,1),(18,10),(18,17),(19,1),(19,17),(20,1),(20,17),(21,1),(21,2),(21,4),(21,6),(21,8),(21,17),(22,1),(22,17),(23,1),(23,17),(24,1),(24,16),(24,17),(25,1),(25,17),(26,1),(26,5),(26,17),(27,1),(27,17),(28,1),(28,17),(29,1),(29,17),(30,1),(30,4),(30,17),(31,1),(31,17),(32,1),(32,8),(32,14),(32,17),(33,1),(33,2),(33,17),(34,17);
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
INSERT INTO `roles` VALUES (1,'Admin','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(2,'HR Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(3,'HR Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(4,'Sales Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(5,'Sales Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(6,'Inventory Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(7,'Inventory Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(8,'Finance Manager','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(9,'Finance Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(10,'Employee','web','2025-05-27 01:05:15','2025-05-27 01:05:15'),(11,'salesperson','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(12,'client','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(14,'accountant','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(15,'hr','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(16,'manager','web','2025-05-29 04:49:38','2025-05-29 04:49:38'),(17,'super_admin','web','2025-06-10 06:32:10','2025-06-10 06:32:10');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (2,2,100,2000.00,0.00,0.00,0.00,200000.00,'2025-05-29','kya kare','King@Florist.com','lkzcjh','2025-05-29 04:13:14','2025-05-29 04:13:23',1);
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
INSERT INTO `sessions` VALUES ('iqXqUmIr2nfo2TVszf6B8cjjXkklhP9a1JbQIXlu',19,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWk9UNGtjaklOMkZNUGVzb095VXFrNVViR29wenlZcTkzdEd3MTR4WiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvcHJvZHVjdHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxOTt9',1755783142);
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
  `value` varchar(255) NOT NULL,
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
INSERT INTO `settings` VALUES (1,'default_region','in','2025-05-27 01:05:14','2025-06-02 06:42:58'),(2,'default_currency','INR','2025-05-27 01:05:14','2025-06-02 06:42:58'),(3,'logo_path','logos/plR7mhB5IRfnE9BRB4fNfUlLILSPsjRbD4qrsDDB.png','2025-06-02 00:56:00','2025-06-13 05:55:15');
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
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_supply_chain_product` (`product_id`),
  CONSTRAINT `fk_supply_chain_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
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
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `db_name` varchar(255) NOT NULL,
  `db_username` varchar(255) NOT NULL,
  `db_password` varchar(255) NOT NULL,
  `db_host` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `db_port` varchar(255) NOT NULL DEFAULT '3306',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_name_unique` (`name`),
  UNIQUE KEY `tenants_db_name_unique` (`db_name`),
  UNIQUE KEY `tenants_db_username_unique` (`db_username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (7,'rmz','tenant_rmz','user_rmz','lkFSUOD[43n:','127.0.0.1','3306','2025-06-12 08:20:46','2025-06-12 08:20:46'),(13,'Mannan','tenant_mannan','user_mannan','f2I7x[Eu,dtD','127.0.0.1','3306','2025-06-12 23:36:03','2025-06-12 23:36:03'),(15,'shoeb','tenant_shoeb','user_shoeb','Xt7n>c#,QNP1','127.0.0.1','3306','2025-06-13 00:17:50','2025-06-13 00:17:50');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (2,'Sale of Blue Rose to kya kare','income',NULL,'approved',2,'2025-06-13 04:24:24',200000.00,'2025-05-29','2025-05-29 04:13:14','2025-06-13 04:24:24','in',0.00,0.00,200000.00),(3,'Rent','expense','Rent','approved',2,'2025-06-13 04:23:57',20500.00,'2025-02-05','2025-05-29 04:13:58','2025-06-13 04:23:57','sa',0.00,0.00,20500.00),(4,'Rent','expense','Rent','approved',2,'2025-06-13 04:23:55',20500.00,'2025-03-05','2025-05-29 04:14:14','2025-06-13 04:23:55','sa',0.00,0.00,20500.00),(5,'Rent','expense',NULL,'approved',9,'2025-06-13 04:25:38',20500.00,'2025-04-05','2025-05-29 04:14:31','2025-06-13 04:25:38','in',0.00,0.00,20500.00),(6,'Rent','expense','other','rejected',2,'2025-06-13 07:56:23',20500.00,'2025-06-13','2025-06-13 07:56:15','2025-06-13 07:56:23','in',0.00,0.00,20500.00);
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
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
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
  `category` enum('wholesale','retail') DEFAULT NULL,
  `aadhar_number` varchar(255) NOT NULL,
  `aadhar_certificate` varchar(255) NOT NULL,
  `gst_certificate` varchar(255) NOT NULL,
  `electricity_certificate` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_manager_id_foreign` (`manager_id`),
  CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Test User','test@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$LgbRy/Es8tfIc.GMyPQbn.zNt2fFh1x84PJ7Sf2/y6MvHAmKa2gIK','njcU3Q43rR','2025-05-27 01:05:15','2025-05-27 01:05:15',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(2,NULL,'Admin','admin@example.com',NULL,NULL,NULL,NULL,NULL,NULL,'profile_pictures/Q1nf4kZHLGrJKqWAExvCbtSgzWaDMdnxh9U2SEhh.png','2025-05-27 01:05:15','$2y$12$J0Z2.AI9Z81/1Lsy4/4wUew9//TwQY5z5.LmYTV5JK5RX75O92qrq',NULL,'2025-05-27 01:05:15','2025-08-14 04:02:31',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(3,NULL,'Hr manager','hr_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$EY4sT0yz.8KmOHiYqE/YRuWsRBAPp7XCT43J94dyVk3G.K2vJkb/m',NULL,'2025-05-27 01:05:15','2025-06-16 05:26:48',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(4,NULL,'Hr employee','hr_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:15','$2y$12$YWVpGZrJv2YnBj8GxouKa.qUAFkQ3W1TruSK3Z9nyD86fQrxA4yeS',NULL,'2025-05-27 01:05:15','2025-05-27 01:05:15',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(5,NULL,'Sales manager','sales_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$azrz4ynl80JYdwJxArqU7OcZhm5fiasQFMn0Fwj0ne.LCsma7gRRS',NULL,'2025-05-27 01:05:16','2025-08-14 04:08:09',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(6,NULL,'Sales employee','sales_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$.n8jAqEN5MnvgrsE.EgDKuAbec9MUsp.p63J9I8CcTjHhTHskXU.i',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(7,NULL,'Inventory manager','inventory_manager@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$niayWw0ocjwGNesNoRf//enhckknlMIQzM6DnN.dqlGPqywgWsKeK',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(8,NULL,'Inventory employee','inventory_employee@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$Pz/BtovBzmzWVm5SlEKF8ebxJn72kkqpemwUBnBc/zJ33IBNXcEf.',NULL,'2025-05-27 01:05:16','2025-05-27 01:05:16',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(9,NULL,'Finance manager','finance_manager@example.com','IN',NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:16','$2y$12$ARrpt7DqTaEZ9zqS6FlyDO7YjeCZmxHeG85R9LWPP.4YW4zKFMIl2',NULL,'2025-05-27 01:05:16','2025-06-13 04:00:24',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(10,NULL,'Finance employee','finance_employee@example.com','IN',NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-27 01:05:17','$2y$12$bbF.9lhDM9hAyGMYR34ezu/soC3c.BUGx1A7431oG79xdDWqzFC56',NULL,'2025-05-27 01:05:17','2025-06-14 01:37:44',9,0,0,NULL,NULL,NULL,NULL,'','','',''),(13,NULL,'Mohammed Shoebuddin','shoeb@titlysolutions.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$s8inz5GEUhRdmILSfGGA4ecf0VSFl0VkCdgleRfIv3ZnBq8nAaVES',NULL,'2025-05-29 03:56:55','2025-05-31 03:20:12',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(14,NULL,'Kings Florist','king@florist.com',NULL,NULL,NULL,NULL,'7867867860','jambag',NULL,NULL,'$2y$12$k60.4SxhqWNgkdiEusBSH.PblFVTFepbGPFwuWe.mH..Xmc80FrSC',NULL,'2025-05-29 05:51:45','2025-08-18 00:33:29',NULL,0,0,'GRowMore',NULL,'27ABCDE1234F1Z5','wholesale','','','',''),(16,NULL,'Admin','john@admin.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$w51nRpj5A7/eYZbJbaz2y.tGl9ulrjB0rjnQlVrUXwnVPBvOKPyPu',NULL,'2025-08-14 03:46:23','2025-08-14 03:46:23',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(19,NULL,'MD Mannan','mannan@gmail.com',NULL,NULL,NULL,NULL,'5555555548','Malakpet, Hyderabad',NULL,NULL,'$2y$12$Vl/29X4CbgSARr5cfNC6a.HIUqmXCBVUsqrChwBbbXZ/wzuWB2PIG',NULL,'2025-08-18 00:34:22','2025-08-18 00:38:31',NULL,0,0,'Macroman','clients/documents/BzklkQjrfBOMeU1Fm7VA7KXHGMR8bvHPOQrPdCgY.png','27ABCDE1234F1Z4','retail','','','',''),(21,NULL,'Sales Employee','kiran@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$mKU44LIhqsjbHNox2jx8Y.CFYTnHsWkrjEgZDiwNTdEuyr.IH4ZXu',NULL,'2025-08-19 05:59:25','2025-08-19 05:59:25',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(22,NULL,'Kiran','kiranazmeera420@gmail.com',NULL,NULL,NULL,NULL,'4564561592','wmdebhj',NULL,NULL,'$2y$12$PzXJJNpzoxCvJ81OQWo6fO3yD4zX87oz5QlDyWIe07hSToTd7wqiy',NULL,'2025-08-20 02:32:23','2025-08-20 02:32:23',NULL,0,0,'FLorist','clients/documents/OzTdAMDWs6xi7CttAoa28pDYsXhIn9IUSrtwVfIy.png','27ABCDE1234F1Z4','retail','','','',''),(23,NULL,'Sharath','sharath@manager.com',NULL,NULL,NULL,NULL,'1591591598','Saroor nagar, Hyderabad',NULL,NULL,'$2y$12$44vMR/xOw5pyBU4iL3628OiQd.t.H04ifZN5cAPIJl2T7wxzp7FeW',NULL,'2025-08-20 02:39:25','2025-08-20 02:39:25',NULL,0,0,'Dry fruits','clients/documents/rTqXSKupEclUhjbFB3JELgUcABjcZw56bGx61Vpg.png','27ABCDE1234F1Z7','wholesale','','','',''),(24,NULL,'nanditha','nanu@gmail.com',NULL,NULL,NULL,NULL,'1230251235','Balanagar',NULL,NULL,'$2y$12$74BbmiM/sxNKM12ZCfkvne1q.q3l3syu6qqCYUk3SA7.GWij/yzmK',NULL,'2025-08-20 03:00:01','2025-08-20 03:00:01',NULL,0,0,'Logistics','clients/documents/ByXcDYEAXuen9geSp0u3ZQNnis1oMqNdx0GSlX1V.png','27ABCDE1234F1Z9','wholesale','','','',''),(25,NULL,'Super Admin','superadmin@example.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$Y4qJzP3BvXezGVQZ6WSA4ObZanYSTvRqSH6EkVfoxpLn28HHMC7FO',NULL,'2025-08-20 05:01:17','2025-08-20 05:01:17',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(26,NULL,'bharath','bharath@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$P1LyWoui/EmgU/ku1gOzceU.L2YNQgBQT0YkG4JgH/gQe00a89YfW',NULL,'2025-08-20 05:59:23','2025-08-20 05:59:23',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(27,NULL,'rohith','rohith@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$D79dYXKhJx.RiLlHvE1hMO7fT35kzIDNgP1yrK2sVqsk.9R9.FeGy',NULL,'2025-08-20 06:01:25','2025-08-20 06:01:25',NULL,0,0,NULL,NULL,NULL,NULL,'','','',''),(28,NULL,'ravi','ravi@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$6yLpUo/mYXDmoc1hB.ItLu1It/v/qAET/zZP87T.U1Xr2b8OGKrwa',NULL,'2025-08-20 07:28:27','2025-08-20 07:28:27',NULL,0,0,'godaddy','documents/company/JnLsk0hWQ8bLqMXVYNfs3hmjvaDGagnM5x17g5Gr.pdf','27ABCDE1234F1Z8',NULL,'775308575692','documents/aadhar/TL5rTv8ddDM6vug6V0gX1gKGM3U64zOAlhsc3OrM.pdf','documents/gst/g1IFvGalJMgJYAudM0S2gqtYHW1sEw8Us8yQZHlU.pdf','documents/electricity/UH5OcGJHTEpzEvw2mGw4i0t0WJNJPH9xxoYU4hwv.pdf'),(29,NULL,'Rahul','rahul@yahoo.com',NULL,NULL,NULL,NULL,'1231321259','Hyderabad',NULL,NULL,'$2y$12$muAosYyVjfCdgvyTMVKhROIWiu2/X6mYqHaKYWD0DNbptVjHNwlXu',NULL,'2025-08-21 03:35:59','2025-08-21 03:35:59',NULL,0,0,'Prudent','clients/documents/mFHszMcuh47TlQSJLRgksNlGQCpgD9Cc5K2MeyDZ.png','27ABCDE1234F1Z7','wholesale','775308575695','clients/documents/myKnXteoKnQZL6Rsny8u8wNjXfxuGsJ3aGD4wlcp.png','clients/documents/TWR3no10DzCItWDsMf9lMjBxTOOVfV8la5xRydE3.jpg','clients/documents/Q7kZoIkw7prcmqoJVq1opW8BkxMpG7GkTMYL41ur.png');
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

-- Dump completed on 2025-08-21 19:06:29
