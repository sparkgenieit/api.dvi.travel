-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 06, 2025 at 06:55 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dvi_travels`
--

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_activity_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_activity_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_activity_details` (
  `accounts_itinerary_activity_details_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `route_activity_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `activity_amount` float NOT NULL DEFAULT '0',
  `total_payable` float NOT NULL DEFAULT '0',
  `total_paid` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_activity_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_activity_transaction_history`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_activity_transaction_history`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_activity_transaction_history` (
  `accounts_itinerary_activity_transaction_history_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `accounts_itinerary_activity_details_ID` int NOT NULL DEFAULT '0',
  `transaction_amount` float NOT NULL DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `transaction_done_by` text COLLATE utf8mb4_general_ci,
  `mode_of_pay` int NOT NULL DEFAULT '0',
  `transaction_utr_no` text COLLATE utf8mb4_general_ci,
  `transaction_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_activity_transaction_history_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_details` (
  `accounts_itinerary_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `agent_id` int NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_quote_ID` text COLLATE utf8mb4_general_ci,
  `trip_start_date_and_time` datetime DEFAULT NULL,
  `trip_end_date_and_time` datetime DEFAULT NULL,
  `total_billed_amount` float NOT NULL DEFAULT '0',
  `total_received_amount` float NOT NULL DEFAULT '0',
  `total_receivable_amount` float NOT NULL DEFAULT '0',
  `total_payable_amount` float NOT NULL DEFAULT '0',
  `total_payout_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_guide_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_guide_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_guide_details` (
  `accounts_itinerary_guide_details_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_guide_slot_cost_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `guide_slot_cost_details_ID` int NOT NULL DEFAULT '0',
  `route_guide_ID` int NOT NULL DEFAULT '0',
  `guide_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary, 2 - Day Wise',
  `guide_slot` int NOT NULL DEFAULT '0',
  `guide_slot_cost` float NOT NULL DEFAULT '0',
  `total_payable` float NOT NULL DEFAULT '0',
  `total_paid` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_guide_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_guide_transaction_history`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_guide_transaction_history`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_guide_transaction_history` (
  `accounts_itinerary_guide_transaction_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `accounts_itinerary_guide_details_ID` int NOT NULL DEFAULT '0',
  `transaction_amount` float NOT NULL DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `transaction_done_by` text COLLATE utf8mb4_general_ci,
  `mode_of_pay` int NOT NULL DEFAULT '0',
  `transaction_utr_no` text COLLATE utf8mb4_general_ci,
  `transaction_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_guide_transaction_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_hotel_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_hotel_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_hotel_details` (
  `accounts_itinerary_hotel_details_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_plan_hotel_voucher_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `total_hotel_cost` float NOT NULL DEFAULT '0',
  `total_hotel_tax_amount` float NOT NULL DEFAULT '0',
  `total_purchase_cost` float NOT NULL DEFAULT '0',
  `total_payable` float NOT NULL DEFAULT '0',
  `total_paid` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_hotel_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_hotel_transaction_history`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_hotel_transaction_history`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_hotel_transaction_history` (
  `accounts_itinerary_hotel_transaction_history_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_hotel_details_ID` int NOT NULL DEFAULT '0',
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `transaction_amount` float NOT NULL DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `transaction_done_by` text COLLATE utf8mb4_general_ci,
  `mode_of_pay` int NOT NULL DEFAULT '0',
  `transaction_utr_no` text COLLATE utf8mb4_general_ci,
  `transaction_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_hotel_transaction_history_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_hotspot_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_hotspot_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_hotspot_details` (
  `accounts_itinerary_hotspot_details_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_amount` float NOT NULL DEFAULT '0',
  `total_payable` float NOT NULL DEFAULT '0',
  `total_paid` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_hotspot_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_hotspot_transaction_history`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_hotspot_transaction_history`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_hotspot_transaction_history` (
  `dvi_accounts_itinerary_hotspot_transaction_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `accounts_itinerary_hotspot_details_ID` int NOT NULL DEFAULT '0',
  `transaction_amount` float NOT NULL DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `transaction_done_by` text COLLATE utf8mb4_general_ci,
  `mode_of_pay` int NOT NULL DEFAULT '0',
  `transaction_utr_no` text COLLATE utf8mb4_general_ci,
  `transaction_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`dvi_accounts_itinerary_hotspot_transaction_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_vehicle_details` (
  `accounts_itinerary_vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_plan_vehicle_voucher_details_ID` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_grand_total` float NOT NULL DEFAULT '0',
  `total_vehicle_qty` int NOT NULL DEFAULT '0',
  `total_purchase` float NOT NULL DEFAULT '0',
  `total_payable` float NOT NULL DEFAULT '0',
  `total_paid` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_vehicle_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_accounts_itinerary_vehicle_transaction_history`
--

DROP TABLE IF EXISTS `dvi_accounts_itinerary_vehicle_transaction_history`;
CREATE TABLE IF NOT EXISTS `dvi_accounts_itinerary_vehicle_transaction_history` (
  `accounts_itinerary_vehicle_transaction_ID` int NOT NULL AUTO_INCREMENT,
  `accounts_itinerary_details_ID` int NOT NULL DEFAULT '0',
  `accounts_itinerary_vehicle_details_ID` int NOT NULL DEFAULT '0',
  `transaction_amount` float NOT NULL DEFAULT '0',
  `transaction_date` datetime DEFAULT NULL,
  `transaction_done_by` text COLLATE utf8mb4_general_ci,
  `mode_of_pay` int NOT NULL DEFAULT '0',
  `transaction_utr_no` text COLLATE utf8mb4_general_ci,
  `transaction_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`accounts_itinerary_vehicle_transaction_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_activity`
--

DROP TABLE IF EXISTS `dvi_activity`;
CREATE TABLE IF NOT EXISTS `dvi_activity` (
  `activity_id` int NOT NULL AUTO_INCREMENT,
  `activity_title` text COLLATE utf8mb4_general_ci,
  `hotspot_id` int NOT NULL DEFAULT '0',
  `max_allowed_person_count` int NOT NULL DEFAULT '0',
  `activity_duration` time DEFAULT NULL,
  `activity_description` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_id`),
  KEY `idx_activity_hotspot_id` (`hotspot_id`),
  KEY `idx_activity_max_allow_per` (`max_allowed_person_count`),
  KEY `idx_activity_createdby` (`createdby`),
  KEY `idx_activity_createdon` (`createdon`),
  KEY `idx_activity_updatedon` (`updatedon`),
  KEY `idx_activity_deleted` (`deleted`),
  KEY `idx_activity_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_activity_image_gallery_details`
--

DROP TABLE IF EXISTS `dvi_activity_image_gallery_details`;
CREATE TABLE IF NOT EXISTS `dvi_activity_image_gallery_details` (
  `activity_image_gallery_details_id` int NOT NULL AUTO_INCREMENT,
  `activity_id` int DEFAULT '0',
  `activity_image_gallery_name` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_image_gallery_details_id`),
  KEY `idx_act_img_gal_dls_activity_id` (`activity_id`),
  KEY `idx_act_img_gal_dls_createdby` (`createdby`),
  KEY `idx_act_img_gal_dls_createdon` (`createdon`),
  KEY `idx_act_img_gal_dls_updatedon` (`updatedon`),
  KEY `idx_act_img_gal_dls_deleted` (`deleted`),
  KEY `idx_act_img_gal_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_activity_pricebook`
--

DROP TABLE IF EXISTS `dvi_activity_pricebook`;
CREATE TABLE IF NOT EXISTS `dvi_activity_pricebook` (
  `activity_price_book_id` int NOT NULL AUTO_INCREMENT,
  `hotspot_id` bigint NOT NULL DEFAULT '0',
  `activity_id` int NOT NULL DEFAULT '0',
  `nationality` int NOT NULL DEFAULT '0' COMMENT '1-Indian | 2- Non-Indian',
  `price_type` int NOT NULL DEFAULT '0' COMMENT '1- Adult | 2-Child |3- Infant',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_price_book_id`),
  KEY `idx_act_pb_hotspot_id` (`hotspot_id`),
  KEY `idx_act_pb_activity_id` (`activity_id`),
  KEY `idx_act_pb_nationality` (`nationality`),
  KEY `idx_act_pb_price_type` (`price_type`),
  KEY `idx_act_pb_year` (`year`),
  KEY `idx_act_pb_month` (`month`),
  KEY `idx_act_pb_createdby` (`createdby`),
  KEY `idx_act_pb_createdon` (`createdon`),
  KEY `idx_act_pb_updatedon` (`updatedon`),
  KEY `idx_act_pb_deleted` (`deleted`),
  KEY `idx_act_pb_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_activity_review_details`
--

DROP TABLE IF EXISTS `dvi_activity_review_details`;
CREATE TABLE IF NOT EXISTS `dvi_activity_review_details` (
  `activity_review_id` int NOT NULL AUTO_INCREMENT,
  `activity_id` int NOT NULL DEFAULT '0',
  `activity_rating` text COLLATE utf8mb4_general_ci,
  `activity_description` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_review_id`),
  KEY `idx_act_rev_dls_activity_id` (`activity_id`),
  KEY `idx_act_rev_dls_activity_rating` (`activity_rating`(768)),
  KEY `idx_act_rev_dls_createdby` (`createdby`),
  KEY `idx_act_rev_dls_createdon` (`createdon`),
  KEY `idx_act_rev_dls_updatedon` (`updatedon`),
  KEY `idx_act_rev_dls_deleted` (`deleted`),
  KEY `idx_act_rev_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_activity_time_slot_details`
--

DROP TABLE IF EXISTS `dvi_activity_time_slot_details`;
CREATE TABLE IF NOT EXISTS `dvi_activity_time_slot_details` (
  `activity_time_slot_ID` int NOT NULL AUTO_INCREMENT,
  `activity_id` int NOT NULL DEFAULT '0',
  `time_slot_type` int NOT NULL DEFAULT '0' COMMENT '1- Default time slots for all days | 2- special time slot for a date',
  `special_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_time_slot_ID`),
  KEY `idx_act_tm_slt_dls_activity_id` (`activity_id`),
  KEY `idx_act_tm_slt_dls_tm_slt_type` (`time_slot_type`),
  KEY `idx_act_tm_slt_dls_spl_dt` (`special_date`),
  KEY `idx_act_tm_slt_dls_createdby` (`createdby`),
  KEY `idx_act_tm_slt_dls_createdon` (`createdon`),
  KEY `idx_act_tm_slt_dls_updatedon` (`updatedon`),
  KEY `idx_act_tm_slt_dls_deleted` (`deleted`),
  KEY `idx_act_tm_slt_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_agent`
--

DROP TABLE IF EXISTS `dvi_agent`;
CREATE TABLE IF NOT EXISTS `dvi_agent` (
  `agent_ID` int NOT NULL AUTO_INCREMENT,
  `travel_expert_id` int NOT NULL DEFAULT '0',
  `subscription_plan_id` int NOT NULL DEFAULT '0',
  `sponsor_id` int NOT NULL DEFAULT '0',
  `agent_ip_address` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_ref_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `itinerary_margin_discount_percentage` float NOT NULL DEFAULT '0',
  `agent_margin` float NOT NULL DEFAULT '0',
  `agent_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `agent_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `total_coupon_wallet` decimal(10,0) NOT NULL DEFAULT '0',
  `total_cash_wallet` decimal(10,0) NOT NULL DEFAULT '0',
  `agent_name` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_lastname` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_primary_mobile_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_alternative_mobile_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_email_id` text COLLATE utf8mb4_general_ci,
  `agent_country` int NOT NULL DEFAULT '0',
  `agent_state` int NOT NULL DEFAULT '0',
  `agent_city` int NOT NULL DEFAULT '0',
  `agent_gst_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_gst_attachment` text COLLATE utf8mb4_general_ci,
  `createdby` int DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`agent_ID`),
  KEY `idx_agent_trvl_expt_id` (`travel_expert_id`),
  KEY `idx_agent_sub_pln_id` (`subscription_plan_id`),
  KEY `idx_agent_sponsor_id` (`sponsor_id`),
  KEY `idx_agent_agent_mar` (`agent_margin`),
  KEY `idx_agent_createdby` (`createdby`),
  KEY `idx_agent_createdon` (`createdon`),
  KEY `idx_agent_updatedon` (`updatedon`),
  KEY `idx_agent_deleted` (`deleted`),
  KEY `idx_agent_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_agent_configuration`
--

DROP TABLE IF EXISTS `dvi_agent_configuration`;
CREATE TABLE IF NOT EXISTS `dvi_agent_configuration` (
  `agent_config_id` int NOT NULL AUTO_INCREMENT,
  `agent_id` int DEFAULT '0',
  `site_logo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_address` text COLLATE utf8mb4_general_ci,
  `terms_condition` text COLLATE utf8mb4_general_ci,
  `invoice_logo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_gstin_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_pan_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_address` text COLLATE utf8mb4_general_ci,
  `quotation_no_format` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancellation_charge` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`agent_config_id`),
  KEY `idx_agent_cnf_agent_id` (`agent_id`),
  KEY `idx_agent_cnf_createdby` (`createdby`),
  KEY `idx_agent_cnf_createdon` (`createdon`),
  KEY `idx_agent_cnf_updatedon` (`updatedon`),
  KEY `idx_agent_cnf_deleted` (`deleted`),
  KEY `idx_agent_cnf_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_agent_subscribed_plans`
--

DROP TABLE IF EXISTS `dvi_agent_subscribed_plans`;
CREATE TABLE IF NOT EXISTS `dvi_agent_subscribed_plans` (
  `agent_subscribed_plan_ID` int NOT NULL AUTO_INCREMENT,
  `agent_ID` int NOT NULL DEFAULT '0',
  `subscription_plan_ID` int NOT NULL DEFAULT '0',
  `subscription_plan_title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `itinerary_allowed` int DEFAULT '0',
  `subscription_type` int DEFAULT '0' COMMENT '1 - Paid | 2 - Free',
  `subscription_amount` float DEFAULT '0',
  `joining_bonus` float DEFAULT '0',
  `admin_count` int DEFAULT '0',
  `staff_count` int DEFAULT '0',
  `additional_charge_for_per_staff` float DEFAULT '0',
  `per_itinerary_cost` float DEFAULT '0',
  `validity_start` date DEFAULT NULL,
  `validity_end` date DEFAULT NULL,
  `subscription_notes` text COLLATE utf8mb4_general_ci,
  `subscription_payment_status` int NOT NULL DEFAULT '0' COMMENT '0 - Free | 1 - Paid',
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subscription_status` int NOT NULL DEFAULT '0' COMMENT '0 - Free | 1 - Paid',
  `additional_staff_count` int NOT NULL DEFAULT '0',
  `additional_staff_charge` float NOT NULL DEFAULT '0',
  `additional_staff_approved_by` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`agent_subscribed_plan_ID`),
  KEY `idx_agent_sub_sbd_pln_agent_id` (`agent_ID`),
  KEY `idx_agent_sub_sbd_pln_subs_pln_id` (`subscription_plan_ID`),
  KEY `idx_agent_sub_sbd_pln_createdby` (`createdby`),
  KEY `idx_agent_sub_sbd_pln_createdon` (`createdon`),
  KEY `idx_agent_sub_sbd_pln_updatedon` (`updatedon`),
  KEY `idx_agent_sub_sbd_pln_deleted` (`deleted`),
  KEY `idx_agent_sub_sbd_pln_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_agent_subscribed_plans_additional_info`
--

DROP TABLE IF EXISTS `dvi_agent_subscribed_plans_additional_info`;
CREATE TABLE IF NOT EXISTS `dvi_agent_subscribed_plans_additional_info` (
  `agent_subscribed_plan_additional_info_ID` int NOT NULL AUTO_INCREMENT,
  `agent_ID` int NOT NULL DEFAULT '0',
  `agent_subscribed_plan_ID` int NOT NULL DEFAULT '0',
  `no_of_additional_staff` int NOT NULL DEFAULT '0',
  `total_additional_staff_charges` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 - Not Approved | 1 - Approved | 2 - Declined',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`agent_subscribed_plan_additional_info_ID`),
  KEY `idx_agent_sub_pln_addi_info_agent_id` (`agent_ID`),
  KEY `idx_agent_sub_pln_addi_info_subs_pln_id` (`agent_subscribed_plan_ID`),
  KEY `idx_agent_sub_pln_addi_info_createdby` (`createdby`),
  KEY `idx_agent_sub_pln_addi_info_createdon` (`createdon`),
  KEY `idx_agent_sub_pln_addi_info_updatedon` (`updatedon`),
  KEY `idx_agent_sub_pln_addi_info_deleted` (`deleted`),
  KEY `idx_agent_sub_pln_addi_info_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_agent_subscription_plan`
--

DROP TABLE IF EXISTS `dvi_agent_subscription_plan`;
CREATE TABLE IF NOT EXISTS `dvi_agent_subscription_plan` (
  `agent_subscription_plan_ID` int NOT NULL AUTO_INCREMENT,
  `agent_subscription_plan_title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `itinerary_allowed` int DEFAULT '0',
  `subscription_type` int DEFAULT '0' COMMENT '1 - Paid | 2 - Free',
  `subscription_amount` float DEFAULT '0',
  `joining_bonus` float DEFAULT '0',
  `admin_count` int DEFAULT '0',
  `staff_count` int DEFAULT '0',
  `additional_charge_for_per_staff` float DEFAULT '0',
  `per_itinerary_cost` float DEFAULT '0',
  `validity_in_days` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `recommended_status` int NOT NULL DEFAULT '0',
  `subscription_notes` text COLLATE utf8mb4_general_ci,
  `createdby` int DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`agent_subscription_plan_ID`),
  KEY `idx_dvi_agent_subscription_plan_agent_subscription_plan_ID` (`agent_subscription_plan_ID`),
  KEY `idx_dvi_agent_subscription_plan_agent_subscription_plan_title` (`agent_subscription_plan_title`),
  KEY `idx_dvi_agent_subscription_plan_itinerary_allowed` (`itinerary_allowed`),
  KEY `idx_dvi_agent_subscription_plan_subscription_type` (`subscription_type`),
  KEY `idx_dvi_agent_subscription_plan_subscription_amount` (`subscription_amount`),
  KEY `idx_dvi_agent_subscription_plan_joining_bonus` (`joining_bonus`),
  KEY `idx_dvi_agent_subscription_plan_admin_count` (`admin_count`),
  KEY `idx_dvi_agent_subscription_plan_staff_count` (`staff_count`),
  KEY `idx_dvi_agent_subscription_plan_additional_charge_for_per_staff` (`additional_charge_for_per_staff`),
  KEY `idx_dvi_agent_subscription_plan_per_itinerary_cost` (`per_itinerary_cost`),
  KEY `idx_dvi_agent_subscription_plan_validity_in_days` (`validity_in_days`),
  KEY `idx_dvi_agent_subscription_plan_recommended_status` (`recommended_status`),
  KEY `idx_dvi_agent_subscription_plan_subscription_notes` (`subscription_notes`(768)),
  KEY `idx_dvi_agent_subscription_plan_createdby` (`createdby`),
  KEY `idx_dvi_agent_subscription_plan_createdon` (`createdon`),
  KEY `idx_dvi_agent_subscription_plan_updatedon` (`updatedon`),
  KEY `idx_dvi_agent_subscription_plan_status` (`status`),
  KEY `idx_dvi_agent_subscription_plan_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itineraries`
--

DROP TABLE IF EXISTS `dvi_cancelled_itineraries`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itineraries` (
  `cancelled_itinerary_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `total_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_refund_amount` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_details` (
  `cancelled_itinerary_details_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_id` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_hotspot_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_activity_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_guide_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_vehicle_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_hotel_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_extrabed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_childwithbed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_childwithoutbed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_breakfast_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_lunch_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_dinner_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_hotel_amenities_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `cancellation_date` datetime DEFAULT NULL,
  `cancelled_by` int NOT NULL DEFAULT '0',
  `total_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_refund_amount` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_hotel_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_hotel_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_hotel_details` (
  `cancelled_itinerary_plan_hotel_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `itinerary_route_location` text COLLATE utf8mb4_general_ci,
  `hotel_required` int NOT NULL DEFAULT '0',
  `hotel_category_id` int NOT NULL DEFAULT '0',
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_margin_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `hotel_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_rate` float NOT NULL DEFAULT '0',
  `hotel_margin_rate_tax_amt` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_persons` int NOT NULL DEFAULT '0' COMMENT 'No of Adult + No of \r\n Children ',
  `total_hotel_meal_plan_cost` float NOT NULL DEFAULT '0',
  `total_hotel_meal_plan_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_rooms` int NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_cost` float NOT NULL DEFAULT '0',
  `total_amenities_cost` float NOT NULL DEFAULT '0',
  `total_amenities_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_tax_amount` float NOT NULL DEFAULT '0',
  `hotel_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No |1- Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `total_hotel_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_hotel_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_hotel_refund_amount` float NOT NULL DEFAULT '0',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_hotel_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_hotel_room_amenities`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_hotel_room_amenities`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_hotel_room_amenities` (
  `cancelled_itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_amenities_id` int NOT NULL DEFAULT '0',
  `total_qty` int NOT NULL DEFAULT '0',
  `amenitie_rate` float NOT NULL DEFAULT '0',
  `total_amenitie_cost` float NOT NULL DEFAULT '0',
  `total_amenitie_gst_amount` float NOT NULL DEFAULT '0',
  `amenitie_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No |1- Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `amenitie_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `amenitie_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_cancelled_amenitie_service_amount` float NOT NULL DEFAULT '0',
  `total_amenitie_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_amenitie_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_hotel_room_amenities_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_hotel_room_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_hotel_room_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_hotel_room_details` (
  `cancelled_itinerary_plan_hotel_room_details_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_qty` int NOT NULL DEFAULT '0',
  `room_rate` float NOT NULL DEFAULT '0',
  `gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `gst_percentage` float NOT NULL DEFAULT '0',
  `extra_bed_count` int NOT NULL DEFAULT '0',
  `extra_bed_rate` float NOT NULL DEFAULT '0',
  `child_without_bed_count` int NOT NULL DEFAULT '0',
  `child_without_bed_charges` float NOT NULL DEFAULT '0',
  `child_with_bed_count` int NOT NULL DEFAULT '0',
  `child_with_bed_charges` float NOT NULL DEFAULT '0',
  `breakfast_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `lunch_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `dinner_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `breakfast_cost_per_person` float NOT NULL DEFAULT '0',
  `lunch_cost_per_person` float NOT NULL DEFAULT '0',
  `dinner_cost_per_person` float NOT NULL DEFAULT '0',
  `total_breafast_cost` float NOT NULL DEFAULT '0',
  `total_lunch_cost` float NOT NULL DEFAULT '0',
  `total_dinner_cost` float NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `room_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `room_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `room_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_room_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_room_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_room_refund_amount` float NOT NULL DEFAULT '0',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_hotel_room_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_hotel_room_service_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_hotel_room_service_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_hotel_room_service_details` (
  `cancelled_itinerary_plan_hotel_room_service_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_hotel_room_service_details_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_service_type` int NOT NULL DEFAULT '0' COMMENT '1- extra bed | 2 - child without bed | 3 - child with bed | 4 - Breakfast | 5 - Lunch | 6 - Dinner',
  `room_service_count` int NOT NULL DEFAULT '0',
  `service_cost_per_person` float NOT NULL DEFAULT '0',
  `total_room_service_rate` float NOT NULL DEFAULT '0',
  `service_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `room_service_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `room_service_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_cancelled_room_service_amount` float NOT NULL DEFAULT '0',
  `total_room_service_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_room_service_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_hotel_room_service_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_vendor_eligible_list`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_vendor_eligible_list`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_vendor_eligible_list` (
  `cancelled_itinerary_plan_vendor_eligible_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itineary_plan_assigned_status` int NOT NULL DEFAULT '0' COMMENT '0 - Not Selected | 1 - Selected',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `total_vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `outstation_allowed_km_per_day` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_orign` text COLLATE utf8mb4_general_ci,
  `vehicle_count` int NOT NULL DEFAULT '0',
  `total_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_outstation_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_rental_charges` float NOT NULL DEFAULT '0',
  `total_toll_charges` float NOT NULL DEFAULT '0',
  `total_parking_charges` float NOT NULL DEFAULT '0',
  `total_driver_charges` float NOT NULL DEFAULT '0',
  `total_permit_charges` float NOT NULL DEFAULT '0',
  `total_before_6_am_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_after_8_pm_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `extra_km_rate` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'Common for Local / Outstation',
  `total_allowed_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Allowed KM',
  `total_extra_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Extra KM',
  `total_extra_kms_charge` float NOT NULL DEFAULT '0' COMMENT 'For Outstation Extra KM Charges',
  `total_allowed_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms_charge` float NOT NULL DEFAULT '0',
  `vehicle_gst_type` int NOT NULL DEFAULT '0',
  `vehicle_gst_percentage` float NOT NULL DEFAULT '0',
  `vehicle_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_total_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_type` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_grand_total` float NOT NULL DEFAULT '0',
  `vehicle_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No |1- Yes',
  `cancelled_on` date DEFAULT NULL,
  `vehicle_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `vehicle_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_vehicle_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_vehicle_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_vehicle_refund_amount` float NOT NULL DEFAULT '0',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_vendor_eligible_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_plan_vendor_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_plan_vendor_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_plan_vendor_vehicle_details` (
  `cancelled_itinerary_plan_vendor_vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_vendor_vehicle_details_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_vendor_vehicle_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL,
  `itinerary_route_id` int NOT NULL,
  `itinerary_route_date` date DEFAULT NULL,
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `time_limit_id` int NOT NULL DEFAULT '0',
  `travel_type` int NOT NULL DEFAULT '0' COMMENT '1 - Local Trip | 2 - Outstation Trip',
  `itinerary_route_location_from` text COLLATE utf8mb4_general_ci,
  `itinerary_route_location_to` text COLLATE utf8mb4_general_ci,
  `total_running_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_running_time` time DEFAULT NULL,
  `total_siteseeing_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_siteseeing_time` time DEFAULT NULL,
  `total_pickup_km` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total_pickup_duration` time DEFAULT NULL,
  `total_drop_km` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_drop_duration` time DEFAULT NULL,
  `total_extra_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extra_km_rate` float NOT NULL DEFAULT '0',
  `total_extra_km_charges` float NOT NULL DEFAULT '0',
  `total_travelled_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_travelled_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_rental_charges` float NOT NULL DEFAULT '0',
  `vehicle_toll_charges` float NOT NULL DEFAULT '0',
  `vehicle_parking_charges` float NOT NULL DEFAULT '0',
  `vehicle_driver_charges` float NOT NULL DEFAULT '0',
  `vehicle_permit_charges` float NOT NULL DEFAULT '0',
  `before_6_am_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `after_8_pm_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_vehicle_amount` float NOT NULL DEFAULT '0',
  `driver_opening_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `opening_speedmeter_image` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `driver_closing_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `closing_speedmeter_image` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_plan_vendor_vehicle_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_activity_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_activity_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_activity_details` (
  `cancelled_route_activity_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_activity_ID` int NOT NULL DEFAULT '0',
  `route_activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `guide_activity_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_statys` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_activity_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_order` int NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_infant` float NOT NULL DEFAULT '0',
  `activity_charges_for_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_infant` float NOT NULL DEFAULT '0',
  `activity_amout` float NOT NULL DEFAULT '0',
  `activity_traveling_time` time DEFAULT NULL,
  `activity_start_time` time DEFAULT NULL,
  `activity_end_time` time DEFAULT NULL,
  `route_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `total_route_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_route_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_route_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_route_activity_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_activity_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_activity_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_activity_entry_cost_details` (
  `cancelled_itinerary_activity_cost_detail_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `cancelled_route_activity_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_activity_cost_detail_ID` int NOT NULL DEFAULT '0',
  `activity_cost_detail_id` int NOT NULL DEFAULT '0',
  `route_activity_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `entry_cost_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | - Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `defect_type` float NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `entry_cost_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_entry_cost_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_entry_cost_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_entry_cost_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_activity_cost_detail_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_guide_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_guide_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_guide_details` (
  `cancelled_route_guide_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_route_guide_ID` int NOT NULL DEFAULT '0',
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `route_guide_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_guide_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary,\r\n2 - Day Wise',
  `guide_language` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_slot` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_cost` float NOT NULL DEFAULT '0',
  `route_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `total_route_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_route_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_route_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_route_guide_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_guide_slot_cost_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_guide_slot_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_guide_slot_cost_details` (
  `cancelled_itinerary_guide_slot_cost_details_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_guide_slot_cost_details_ID` int NOT NULL DEFAULT '0',
  `guide_slot_cost_details_id` int NOT NULL DEFAULT '0',
  `route_guide_id` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary, 2 - Day Wise',
  `guide_slot` int NOT NULL DEFAULT '0',
  `guide_slot_cost` float NOT NULL DEFAULT '0',
  `slot_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `slot_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_slot_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_slot_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_slot_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_guide_slot_cost_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_hotspot_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_hotspot_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_hotspot_details` (
  `cancelled_route_hotspot_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_hotspot_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `item_type` int NOT NULL DEFAULT '0' COMMENT '1 - Refreshment | 2 - Direct Destination Traveling | 3 - Site Seeing Traveling | 4 - Hotspots | 5 - Traveling to Hotel Location | 6 - Return to Hotel | 7 - Return to Departure Location\r\n',
  `hotspot_order` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `guide_hotspot_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_hotspot_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_amout` float NOT NULL DEFAULT '0',
  `hotspot_traveling_time` time NOT NULL DEFAULT '00:00:00',
  `itinerary_travel_type_buffer_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_travelling_distance` text COLLATE utf8mb4_general_ci,
  `hotspot_start_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_end_time` time NOT NULL DEFAULT '00:00:00',
  `allow_break_hours` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `allow_via_route` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `via_location_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_plan_own_way` int NOT NULL DEFAULT '0',
  `route_cancellation_status` int NOT NULL DEFAULT '0',
  `cancelled_on` datetime DEFAULT NULL,
  `total_route_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_route_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_route_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_route_hotspot_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cancelled_itinerary_route_hotspot_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_cancelled_itinerary_route_hotspot_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` (
  `cancelled_itinerary_hotspot_cost_detail_ID` int NOT NULL AUTO_INCREMENT,
  `cancelled_itinerary_ID` int NOT NULL DEFAULT '0',
  `cnf_itinerary_hotspot_cost_detail_ID` int NOT NULL DEFAULT '0',
  `hotspot_cost_detail_id` int NOT NULL DEFAULT '0',
  `route_hotspot_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `entry_cost_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancelled_on` datetime DEFAULT NULL,
  `defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `entry_cost_cancellation_percentage` float NOT NULL DEFAULT '0',
  `total_entry_cost_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_entry_cost_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_entry_cost_refund_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cancelled_itinerary_hotspot_cost_detail_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cash_wallet`
--

DROP TABLE IF EXISTS `dvi_cash_wallet`;
CREATE TABLE IF NOT EXISTS `dvi_cash_wallet` (
  `cash_wallet_ID` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL DEFAULT '0',
  `transaction_date` date DEFAULT NULL,
  `transaction_amount` float DEFAULT '0',
  `transaction_type` int DEFAULT '0' COMMENT '1 - credit | 2 - debit',
  `remarks` text COLLATE utf8mb4_general_ci,
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cash_wallet_ID`),
  KEY `idx_cash_wlt_agent_id` (`agent_id`),
  KEY `idx_cash_wlt_trans_type` (`transaction_type`),
  KEY `idx_cash_wlt_createdby` (`createdby`),
  KEY `idx_cash_wlt_createdon` (`createdon`),
  KEY `idx_cash_wlt_updatedon` (`updatedon`),
  KEY `idx_cash_wlt_deleted` (`deleted`),
  KEY `idx_cash_wlt_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_cities`
--

DROP TABLE IF EXISTS `dvi_cities`;
CREATE TABLE IF NOT EXISTS `dvi_cities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `state_id` int NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_city_agent_id` (`state_id`),
  KEY `idx_city_name` (`name`),
  KEY `idx_city_createdon` (`createdon`),
  KEY `idx_city_updatedon` (`updatedon`),
  KEY `idx_city_deleted` (`deleted`),
  KEY `idx_city_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_driver_uploadimage`
--

DROP TABLE IF EXISTS `dvi_confirmed_driver_uploadimage`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_driver_uploadimage` (
  `driver_uploadimage_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `driver_upload_image` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_uploadimage_ID`),
  KEY `idx_cnf_dvr_img_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_dvr_img_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_dvr_img_createdby` (`createdby`),
  KEY `idx_cnf_dvr_img_createdon` (`createdon`),
  KEY `idx_cnf_dvr_img_updatedon` (`updatedon`),
  KEY `idx_cnf_dvr_img_deleted` (`deleted`),
  KEY `idx_cnf_dvr_img_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_cancellation_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_cancellation_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_cancellation_details` (
  `confirmed_itinerary_cancellation_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_hotspot_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_activity_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_guide_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_vehicle_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_hotel_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_extrabed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_childwithbed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_childwithoutbed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_breakfast_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_lunch_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `itinerary_room_dinner_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `cancellation_date` datetime DEFAULT NULL,
  `cancelled_by` int NOT NULL DEFAULT '0',
  `total_cancelled_service_amount` float NOT NULL DEFAULT '0',
  `total_cancellation_charge` float NOT NULL DEFAULT '0',
  `total_refund_amount` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_cancellation_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_customer_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_customer_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_customer_details` (
  `confirmed_itinerary_customer_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `agent_id` int NOT NULL DEFAULT '0',
  `primary_customer` int NOT NULL DEFAULT '0' COMMENT '1 - Primary Customer ',
  `customer_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2- Children | 3 - Infant',
  `customer_salutation` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_name` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_age` int NOT NULL DEFAULT '0',
  `primary_contact_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `altenative_contact_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_id` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arrival_date_and_time` datetime DEFAULT NULL,
  `arrival_place` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arrival_flight_details` text COLLATE utf8mb4_general_ci,
  `departure_date_and_time` datetime DEFAULT NULL,
  `departure_place` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `departure_flight_details` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_customer_ID`),
  KEY `idx_cnf_iti_cus_dls_cnf_plan_id` (`confirmed_itinerary_plan_ID`),
  KEY `idx_cnf_iti_cus_dls_cus_type` (`customer_type`),
  KEY `idx_cnf_iti_cus_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_cus_dls_agent_id` (`agent_id`),
  KEY `idx_cnf_iti_cus_dls_primary_cus` (`primary_customer`),
  KEY `idx_cnf_iti_cus_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_cus_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_cus_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_cus_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_cus_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_customer_feedback`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_customer_feedback`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_customer_feedback` (
  `customer_feedback_ID` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `customer_rating` text COLLATE utf8mb4_general_ci,
  `feedback_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`customer_feedback_ID`),
  KEY `idx_cnf_iti_cstmr_fd_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_cstmr_fd_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_cstmr_fd_createdby` (`createdby`),
  KEY `idx_cnf_iti_cstmr_fd_createdon` (`createdon`),
  KEY `idx_cnf_iti_cstmr_fd_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_cstmr_fd_deleted` (`deleted`),
  KEY `idx_cnf_iti_cstmr_fd_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_dailymoment_charge`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_dailymoment_charge`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_dailymoment_charge` (
  `driver_charge_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `charge_type` text COLLATE utf8mb4_general_ci,
  `charge_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_charge_ID`),
  KEY `idx_cnf_iti_dlymnt_char_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_dlymnt_char_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_dlymnt_char_charge_type` (`charge_type`(768)),
  KEY `idx_cnf_iti_dlymnt_char_createdby` (`createdby`),
  KEY `idx_cnf_iti_dlymnt_char_createdon` (`createdon`),
  KEY `idx_cnf_iti_dlymnt_char_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_dlymnt_char_deleted` (`deleted`),
  KEY `idx_cnf_iti_dlymnt_char_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_driver_feedback`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_driver_feedback`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_driver_feedback` (
  `driver_feedback_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `driver_rating` text COLLATE utf8mb4_general_ci,
  `driver_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_feedback_ID`),
  KEY `idx_cnf_iti_dvr_fd_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_dvr_fd_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_dvr_fd_createdby` (`createdby`),
  KEY `idx_cnf_iti_dvr_fd_createdon` (`createdon`),
  KEY `idx_cnf_iti_dvr_fd_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_dvr_fd_deleted` (`deleted`),
  KEY `idx_cnf_iti_dvr_fd_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_incidental_expenses`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_incidental_expenses`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_incidental_expenses` (
  `confirmed_itinerary_incidental_expenses_main_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `component_type` int NOT NULL DEFAULT '0' COMMENT '1 - Guide | 2 - Hotspot | 3 - Activity | 4 - Hotel | 5 - Vendor',
  `component_id` int NOT NULL DEFAULT '0',
  `total_amount` float NOT NULL DEFAULT '0',
  `total_payed` float NOT NULL DEFAULT '0',
  `total_balance` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_incidental_expenses_main_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_incidental_expenses_history`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_incidental_expenses_history`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_incidental_expenses_history` (
  `confirmed_itinerary_incidental_expenses_history_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_incidental_expenses_main_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `confirmed_route_guide_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_hotspot_ID` int NOT NULL DEFAULT '0',
  `confirmed_route_activity_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `component_type` int NOT NULL DEFAULT '0' COMMENT '1 - Guide | 2 - Hotspot | 3 - Activity | 4 - Hotel | 5 - Vendor',
  `component_id` int NOT NULL DEFAULT '0',
  `incidental_amount` float NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_incidental_expenses_history_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_details` (
  `confirmed_itinerary_plan_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `agent_id` int NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0',
  `location_id` bigint NOT NULL DEFAULT '0',
  `arrival_location` text COLLATE utf8mb4_general_ci,
  `departure_location` text COLLATE utf8mb4_general_ci,
  `itinerary_quote_ID` text COLLATE utf8mb4_general_ci,
  `trip_start_date_and_time` datetime DEFAULT NULL,
  `trip_end_date_and_time` datetime DEFAULT NULL,
  `arrival_type` int NOT NULL DEFAULT '0' COMMENT '1 - via Air | 2 - via Road | 3 -\r\nvia Train',
  `departure_type` int NOT NULL DEFAULT '0' COMMENT '1 - via Air | 2 - via Road | 3 -\r\nvia Train',
  `expecting_budget` float NOT NULL DEFAULT '0',
  `itinerary_type` int NOT NULL DEFAULT '0' COMMENT '1 - Default | 2 - Customize',
  `entry_ticket_required` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `no_of_routes` int NOT NULL DEFAULT '0',
  `no_of_days` int NOT NULL DEFAULT '0',
  `no_of_nights` int NOT NULL DEFAULT '0',
  `total_adult` int NOT NULL DEFAULT '0',
  `total_children` int NOT NULL DEFAULT '0',
  `total_infants` int NOT NULL DEFAULT '0',
  `nationality` int NOT NULL DEFAULT '0',
  `itinerary_preference` int NOT NULL DEFAULT '0' COMMENT '1 - Hotel | 2 - Vehicle | 3 - Both | 4-Flights',
  `meal_plan_breakfast` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `meal_plan_lunch` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `meal_plan_dinner` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `preferred_room_count` int DEFAULT '0',
  `total_extra_bed` int NOT NULL DEFAULT '0',
  `total_child_with_bed` int NOT NULL DEFAULT '0',
  `total_child_without_bed` int NOT NULL DEFAULT '0',
  `guide_for_itinerary` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `food_type` int NOT NULL DEFAULT '0' COMMENT '1-Vegetarian | 2-Non Vegetarian| 3-Both',
  `special_instructions` text COLLATE utf8mb4_general_ci,
  `pick_up_date_and_time` datetime DEFAULT NULL,
  `hotel_terms_condition` text COLLATE utf8mb4_general_ci,
  `vehicle_terms_condition` text COLLATE utf8mb4_general_ci,
  `hotel_rates_visibility` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `total_hotspot_charges` float NOT NULL DEFAULT '0',
  `total_activity_charges` float NOT NULL DEFAULT '0',
  `total_hotel_charges` float NOT NULL DEFAULT '0',
  `total_vehicle_charges` float NOT NULL DEFAULT '0',
  `total_guide_charges` float NOT NULL DEFAULT '0',
  `itinerary_sub_total` float NOT NULL DEFAULT '0',
  `itinerary_agent_margin_percentage` float NOT NULL DEFAULT '0',
  `itinerary_agent_margin_charges` float NOT NULL DEFAULT '0',
  `itinerary_agent_margin_gst_type` int NOT NULL DEFAULT '0',
  `itinerary_agent_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `itinerary_agent_margin_gst_total` float NOT NULL DEFAULT '0',
  `itinerary_gross_total_amount` float NOT NULL DEFAULT '0',
  `itinerary_coupon_discount_percentage` float NOT NULL DEFAULT '0',
  `itinerary_total_margin_cost` float NOT NULL DEFAULT '0',
  `itinerary_total_coupon_discount_amount` float NOT NULL DEFAULT '0',
  `itinerary_total_net_payable_amount` float NOT NULL DEFAULT '0',
  `itinerary_total_paid_amount` float NOT NULL DEFAULT '0',
  `itinerary_total_balance_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_ID`),
  KEY `idx_cnf_iti_pln_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_pln_dls_agent_id` (`agent_id`),
  KEY `idx_cnf_iti_pln_dls_staff_id` (`staff_id`),
  KEY `idx_cnf_iti_pln_dls_loc_id` (`location_id`),
  KEY `idx_cnf_iti_pln_dls_arr_loc` (`arrival_location`(768)),
  KEY `idx_cnf_iti_pln_dls_dept_loc` (`departure_location`(768)),
  KEY `idx_cnf_iti_pln_dls_iti_pref` (`itinerary_preference`),
  KEY `idx_cnf_iti_pln_dls_iti_type` (`itinerary_type`),
  KEY `idx_cnf_iti_pln_dls_gud_for_iti` (`guide_for_itinerary`),
  KEY `idx_cnf_iti_pln_dls_bf` (`meal_plan_breakfast`),
  KEY `idx_cnf_iti_pln_dls_lun` (`meal_plan_lunch`),
  KEY `idx_cnf_iti_pln_dls_din` (`meal_plan_dinner`),
  KEY `idx_cnf_iti_pln_dls_exta_bed` (`total_extra_bed`),
  KEY `idx_cnf_iti_pln_dls_ch_with_bed` (`total_child_with_bed`),
  KEY `idx_cnf_iti_pln_dls_ch_without_bed` (`total_child_without_bed`),
  KEY `idx_cnf_iti_pln_dls_food_type` (`food_type`),
  KEY `idx_cnf_iti_pln_dls_ttl_adult` (`total_adult`),
  KEY `idx_cnf_iti_pln_dls_ttl_children` (`total_children`),
  KEY `idx_cnf_iti_pln_dls_ttl_infants` (`total_infants`),
  KEY `idx_cnf_iti_pln_dls_nationality` (`nationality`),
  KEY `idx_cnf_iti_pln_dls_spl_instr` (`special_instructions`(768)),
  KEY `idx_cnf_iti_pln_dls_htl_rate_vis` (`hotel_rates_visibility`),
  KEY `idx_cnf_iti_pln_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_cancellation_policy`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_cancellation_policy`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` (
  `cnf_itinerary_plan_hotel_cancellation_policy_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `hotel_id` int NOT NULL DEFAULT '0',
  `cancellation_descrption` text COLLATE utf8mb4_general_ci,
  `cancellation_date` date DEFAULT NULL,
  `cancellation_percentage` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_plan_hotel_cancellation_policy_ID`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_hotel_id` (`hotel_id`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_cnl_dt` (`cancellation_date`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_cnl_perc` (`cancellation_percentage`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_htl_cnl_plcy_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_details` (
  `confirmed_itinerary_plan_hotel_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `itinerary_route_location` text COLLATE utf8mb4_general_ci,
  `hotel_required` int NOT NULL DEFAULT '0',
  `hotel_category_id` int NOT NULL DEFAULT '0',
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_margin_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `hotel_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_rate` float NOT NULL DEFAULT '0',
  `hotel_margin_rate_tax_amt` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_persons` int NOT NULL DEFAULT '0' COMMENT 'No of Adult + No of \r\n Children ',
  `total_hotel_meal_plan_cost` float NOT NULL DEFAULT '0',
  `total_hotel_meal_plan_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_rooms` int NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_cost` float NOT NULL DEFAULT '0',
  `total_amenities_cost` float NOT NULL DEFAULT '0',
  `total_amenities_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_tax_amount` float NOT NULL DEFAULT '0',
  `hotel_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No |1- Yes',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_hotel_details_ID`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_dl_id` (`itinerary_plan_hotel_details_ID`),
  KEY `idx_cnf_iti_pln_htl_dls_group_type` (`group_type`),
  KEY `idx_cnf_iti_pln_htl_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_htl_dls_route_id` (`itinerary_route_id`),
  KEY `idx_cnf_iti_pln_htl_dls_route_dt` (`itinerary_route_date`),
  KEY `idx_cnf_iti_pln_htl_dls_route_loc` (`itinerary_route_location`(768)),
  KEY `idx_cnf_iti_pln_htl_dls_htl_req` (`hotel_required`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_cat_id` (`hotel_category_id`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_id` (`hotel_id`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mar_per` (`hotel_margin_percentage`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mar_gst_type` (`hotel_margin_gst_type`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mar_gst_perc` (`hotel_margin_gst_percentage`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mar_rate` (`hotel_margin_rate`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mar_tax_amt` (`hotel_margin_rate_tax_amt`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_bf_cst` (`hotel_breakfast_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_bf_gst_cst` (`hotel_breakfast_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_lun_cst` (`hotel_lunch_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_lun_gst_cst` (`hotel_lunch_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_din_cst` (`hotel_dinner_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_din_gst_cst` (`hotel_dinner_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mealplan_cst` (`total_hotel_meal_plan_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_mealplan_gst_cst` (`total_hotel_meal_plan_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_ex_bed_cst` (`total_extra_bed_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_ex_bed_gst_cst` (`total_extra_bed_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_chw_bed_cst` (`total_childwith_bed_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_chw_bed_gst_cst` (`total_childwith_bed_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_chwo_bed_cst` (`total_childwithout_bed_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_chwo_bed_gst_cst` (`total_childwithout_bed_cost_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_no_of_room` (`total_no_of_rooms`),
  KEY `idx_cnf_iti_pln_htl_dls_room_cost` (`total_room_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_room_gst_cost` (`total_room_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_cost` (`total_hotel_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_amen_cost` (`total_amenities_cost`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_amen_gst_cst` (`total_amenities_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_htl_gst_cst` (`total_hotel_tax_amount`),
  KEY `idx_cnf_iti_pln_htl_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_htl_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_htl_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_htl_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_htl_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_room_amenities`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_room_amenities`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_room_amenities` (
  `confirmed_itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_amenities_id` int NOT NULL DEFAULT '0',
  `total_qty` int NOT NULL DEFAULT '0',
  `amenitie_rate` float NOT NULL DEFAULT '0',
  `total_amenitie_cost` float NOT NULL DEFAULT '0',
  `total_amenitie_gst_amount` float NOT NULL DEFAULT '0',
  `amenitie_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No |1- Yes',
  `amenitie_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side ',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_hotel_room_amenities_details_ID`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_rm_dl_id` (`itinerary_plan_hotel_room_amenities_details_ID`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_htl_dl_id` (`itinerary_plan_hotel_details_id`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_group_type` (`group_type`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_route_id` (`itinerary_route_id`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_route_dt` (`itinerary_route_date`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_htl_id` (`hotel_id`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_htl_amen_id` (`hotel_amenities_id`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_total_qty` (`total_qty`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_htl_amen_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_room_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_room_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_room_details` (
  `confirmed_itinerary_plan_hotel_room_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_qty` int NOT NULL DEFAULT '0',
  `room_rate` float NOT NULL DEFAULT '0',
  `gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `gst_percentage` float NOT NULL DEFAULT '0',
  `extra_bed_count` int NOT NULL DEFAULT '0',
  `extra_bed_rate` float NOT NULL DEFAULT '0',
  `child_without_bed_count` int NOT NULL DEFAULT '0',
  `child_without_bed_charges` float NOT NULL DEFAULT '0',
  `child_with_bed_count` int NOT NULL DEFAULT '0',
  `child_with_bed_charges` float NOT NULL DEFAULT '0',
  `breakfast_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `lunch_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `dinner_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `breakfast_cost_per_person` float NOT NULL DEFAULT '0',
  `lunch_cost_per_person` float NOT NULL DEFAULT '0',
  `dinner_cost_per_person` float NOT NULL DEFAULT '0',
  `total_breafast_cost` float NOT NULL DEFAULT '0',
  `total_lunch_cost` float NOT NULL DEFAULT '0',
  `total_dinner_cost` float NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `room_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `extra_bed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `child_without_bed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `child_with_bed_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `breakfast_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `lunch_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `dinner_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `room_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side ',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_hotel_room_details_ID`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_rm_dl_id` (`itinerary_plan_hotel_room_details_ID`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_htl_dl_id` (`itinerary_plan_hotel_details_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_group_type` (`group_type`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_route_id` (`itinerary_route_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_route_dt` (`itinerary_route_date`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_htl_id` (`hotel_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_htl_rm_type_id` (`room_type_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_room_id` (`room_id`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_room_qty` (`room_qty`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_room_rate` (`room_rate`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_gst_type` (`gst_type`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_gst_perc` (`gst_percentage`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_ex_bed_count` (`extra_bed_count`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_ex_bed_rate` (`extra_bed_rate`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_chwo_bed_count` (`child_without_bed_count`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_chwo_bed_charges` (`child_without_bed_charges`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_chw_bed_count` (`child_with_bed_count`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_chw_bed_charges` (`child_with_bed_charges`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_bf_req` (`breakfast_required`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_lun_req` (`lunch_required`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_din_req` (`dinner_required`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_bf_cos_per` (`breakfast_cost_per_person`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_lun_cos_per` (`lunch_cost_per_person`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_din_cos_per` (`dinner_cost_per_person`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_bf_tot_cost` (`total_breafast_cost`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_lun_tot_cost` (`total_lunch_cost`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_din_tot_cost` (`total_dinner_cost`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_rm_tot_cost` (`total_room_cost`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_rm_tot_gst_cost` (`total_room_gst_amount`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_htl_rm_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_room_service_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_room_service_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_room_service_details` (
  `confirmed_itinerary_plan_hotel_room_service_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_room_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_service_type` int NOT NULL DEFAULT '0' COMMENT '1- extra bed | 2 - child without bed | 3 - child with bed | 4 - Breakfast | 5 - Lunch | 6 - Dinner',
  `room_service_count` int NOT NULL DEFAULT '0',
  `service_cost_per_person` float NOT NULL DEFAULT '0',
  `total_room_service_rate` float NOT NULL DEFAULT '0',
  `service_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `room_service_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_hotel_room_service_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_hotel_voucher_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_hotel_voucher_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_hotel_voucher_details` (
  `cnf_itinerary_plan_hotel_voucher_details_ID` int NOT NULL AUTO_INCREMENT,
  `confirmed_itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_hotel_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_confirmed_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_confirmed_email_id` text COLLATE utf8mb4_general_ci,
  `hotel_confirmed_mobile_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_to` int NOT NULL DEFAULT '0' COMMENT '1 - Bill against DVI | 2 - Bill against Agent',
  `hotel_booking_status` int NOT NULL DEFAULT '0' COMMENT '1 - Awaiting | 2 - Waitinglist | 3 - Block | 4 - Confirmed',
  `hotel_voucher_terms_condition` text COLLATE utf8mb4_general_ci,
  `hotel_confirmed_reservation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_confirmation_verified_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_confirmation_verified_mobile_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_confirmation_verified_email_id` text COLLATE utf8mb4_general_ci,
  `hotel_confirmation_status_remarks` text COLLATE utf8mb4_general_ci,
  `hotel_voucher_cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_plan_hotel_voucher_details_ID`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_htl_dl_id` (`itinerary_plan_hotel_details_ID`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_iti_pln_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_route_dt` (`itinerary_route_date`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_htl_id` (`hotel_id`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_htm_cnf_by` (`hotel_confirmed_by`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_invoice_to` (`invoice_to`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_htl_bk_sts` (`hotel_booking_status`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_htl_vhcr_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_route_permit_charge`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_route_permit_charge`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_route_permit_charge` (
  `cnf_itinerary_route_permit_charge_ID` int NOT NULL AUTO_INCREMENT,
  `route_permit_charge_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `source_state_id` int NOT NULL DEFAULT '0',
  `destination_state_id` int NOT NULL DEFAULT '0',
  `permit_cost` float NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_route_permit_charge_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_id` (`vendor_id`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_itinerary_plan_route_permit_charge_source_state_id` (`source_state_id`),
  KEY `idx_itinerary_plan_route_permit_charge_destination_state_id` (`destination_state_id`),
  KEY `idx_itinerary_plan_route_permit_charge_permit_cost` (`permit_cost`),
  KEY `idx_itinerary_plan_route_permit_charge_createdon` (`createdon`),
  KEY `idx_itinerary_plan_route_permit_charge_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_route_permit_charge_status` (`status`),
  KEY `idx_itinerary_plan_route_permit_charge_deleted` (`deleted`),
  KEY `idx_itinerary_plan_route_permit_charge_createdby` (`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` (
  `cnf_itinerary_plan_vehicle_cancellation_policy_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `cancellation_descrption` text COLLATE utf8mb4_general_ci,
  `cancellation_date` date DEFAULT NULL,
  `cancellation_percentage` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_plan_vehicle_cancellation_policy_ID`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_vendor_id` (`vendor_id`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_vendor_vt_id` (`vendor_vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_cnl_dt` (`cancellation_date`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_cnl_perc` (`cancellation_percentage`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_vhl_cnl_plcy_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_vehicle_details` (
  `confirmed_vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `vehicle_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_count` int NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_vehicle_details_ID`),
  KEY `idx_cnf_iti_pln_vhl_dls_vh_dls_id` (`vehicle_details_ID`),
  KEY `idx_cnf_iti_pln_vhl_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_vhl_dls_vt_id` (`vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vhl_dls_vh_count` (`vehicle_count`),
  KEY `idx_cnf_iti_pln_vhl_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_vhl_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_vhl_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_vhl_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_vhl_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_vehicle_voucher_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_vehicle_voucher_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_vehicle_voucher_details` (
  `cnf_itinerary_plan_vehicle_voucher_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_confirmed_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_confirmed_email_id` text COLLATE utf8mb4_general_ci,
  `vehicle_confirmed_mobile_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_to` int NOT NULL DEFAULT '0' COMMENT '1 - Bill against DVI | 2 - Bill against Agent',
  `vehicle_booking_status` int NOT NULL DEFAULT '0' COMMENT '1 - Awaiting | 2 - Waitinglist | 3 - Block | 4 - Confirmed',
  `vehicle_voucher_terms_condition` text COLLATE utf8mb4_general_ci,
  `vehicle_confirmed_reservation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_confirmation_verified_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_confirmation_verified_mobile_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_confirmation_verified_email_id` text COLLATE utf8mb4_general_ci,
  `vehicle_confirmation_status_remarks` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_plan_vehicle_voucher_details_ID`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vr_eli_id` (`itinerary_plan_vendor_eligible_ID`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vt_id` (`vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vr_id` (`vendor_id`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_id` (`vehicle_id`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vr_br_id` (`vendor_branch_id`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_by` (`vehicle_confirmed_by`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_email` (`vehicle_confirmed_email_id`(768)),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_mob` (`vehicle_confirmed_mobile_no`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_invoice_to` (`invoice_to`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_bk_sts` (`vehicle_booking_status`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_res` (`vehicle_confirmed_reservation`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_rec_by` (`vehicle_confirmation_verified_by`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_ver_mob` (`vehicle_confirmation_verified_mobile_no`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_ver_email` (`vehicle_confirmation_verified_email_id`(768)),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_vh_cnf_sts_rm` (`vehicle_confirmation_status_remarks`(768)),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_vhl_vhcr_dls_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_vendor_eligible_list`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_vendor_eligible_list`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_vendor_eligible_list` (
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itineary_plan_assigned_status` int NOT NULL DEFAULT '0' COMMENT '0 - Not Selected | 1 - Selected',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `total_vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `outstation_allowed_km_per_day` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_orign` text COLLATE utf8mb4_general_ci,
  `vehicle_count` int NOT NULL DEFAULT '0',
  `total_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_outstation_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_rental_charges` float NOT NULL DEFAULT '0',
  `total_toll_charges` float NOT NULL DEFAULT '0',
  `total_parking_charges` float NOT NULL DEFAULT '0',
  `total_driver_charges` float NOT NULL DEFAULT '0',
  `total_permit_charges` float NOT NULL DEFAULT '0',
  `total_before_6_am_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_after_8_pm_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `extra_km_rate` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'Common for Local / Outstation',
  `total_allowed_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Allowed KM',
  `total_extra_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Extra KM',
  `total_extra_kms_charge` float NOT NULL DEFAULT '0' COMMENT 'For Outstation Extra KM Charges',
  `total_allowed_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms_charge` float NOT NULL DEFAULT '0',
  `vehicle_gst_type` int NOT NULL DEFAULT '0',
  `vehicle_gst_percentage` float NOT NULL DEFAULT '0',
  `vehicle_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_total_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_type` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_grand_total` float NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1-Yes',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `vehicle_defect_type` int NOT NULL DEFAULT '0' COMMENT '1 - From Customer | 2 - From DVI',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_vendor_eligible_ID`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_pln_vr_eli_id` (`itinerary_plan_vendor_eligible_ID`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_ass_sts` (`itineary_plan_assigned_status`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vt_id` (`vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vh_qty` (`total_vehicle_qty`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vr_id` (`vendor_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_outs_allowed_km` (`outstation_allowed_km_per_day`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vr_vh_vt_id` (`vendor_vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vh_id` (`vehicle_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vr_br_id` (`vendor_branch_id`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vh_org` (`vehicle_orign`(768)),
  KEY `idx_cnf_iti_pln_vr_eli_lt_vh_count` (`vehicle_count`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_km` (`total_kms`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_out_km` (`total_outstation_km`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_time` (`total_time`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_rent_char` (`total_rental_charges`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_toll_char` (`total_toll_charges`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_parki_char` (`total_parking_charges`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_driver_char` (`total_driver_charges`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_permit_char` (`total_permit_charges`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_6_am_ex_time` (`total_before_6_am_extra_time`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_8_pm_ex_time` (`total_after_8_pm_extra_time`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_6_am_char_for_dr` (`total_before_6_am_charges_for_driver`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_6_am_char_for_vh` (`total_before_6_am_charges_for_vehicle`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_8_pm_char_for_dr` (`total_after_8_pm_charges_for_driver`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_8_pm_char_for_vh` (`total_after_8_pm_charges_for_vehicle`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_ext_km_rate` (`extra_km_rate`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_allow_km` (`total_allowed_kms`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_extra_km` (`total_extra_kms`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_extra_km_char` (`total_extra_kms_charge`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_allow_loc_km` (`total_allowed_local_kms`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_extra_loc_km` (`total_extra_local_kms`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_allow_loc_km_char` (`total_extra_local_kms_charge`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_gst_type` (`vehicle_gst_type`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_gst_perc` (`vehicle_gst_percentage`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_gst_amt` (`vehicle_gst_amount`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_tot_amt` (`vehicle_total_amount`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_mar_perc` (`vendor_margin_percentage`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_mar_gst_type` (`vendor_margin_gst_type`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_mar_gst_perc` (`vendor_margin_gst_percentage`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_mar_amt` (`vendor_margin_amount`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_mar_gst_amt` (`vendor_margin_gst_amount`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_tot_vehicle_grand_tot` (`vehicle_grand_total`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_deleted` (`deleted`),
  KEY `idx_cnf_iti_pln_vr_eli_lt_status` (`status`),
  KEY `idx_itinerary_plan` (`itinerary_plan_id`,`itineary_plan_assigned_status`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_plan_vendor_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_plan_vendor_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_plan_vendor_vehicle_details` (
  `confirmed_itinerary_plan_vendor_vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_vendor_vehicle_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `confirmed_itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL,
  `itinerary_route_id` int NOT NULL,
  `itinerary_route_date` date DEFAULT NULL,
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `time_limit_id` int NOT NULL DEFAULT '0',
  `travel_type` int NOT NULL DEFAULT '0' COMMENT '1 - Local Trip | 2 - Outstation Trip',
  `itinerary_route_location_from` text COLLATE utf8mb4_general_ci,
  `itinerary_route_location_to` text COLLATE utf8mb4_general_ci,
  `total_running_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_running_time` time DEFAULT NULL,
  `total_siteseeing_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_siteseeing_time` time DEFAULT NULL,
  `total_pickup_km` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total_pickup_duration` time DEFAULT NULL,
  `total_drop_km` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_drop_duration` time DEFAULT NULL,
  `total_extra_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `extra_km_rate` float NOT NULL DEFAULT '0',
  `total_extra_km_charges` float NOT NULL DEFAULT '0',
  `total_travelled_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_travelled_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_rental_charges` float NOT NULL DEFAULT '0',
  `vehicle_toll_charges` float NOT NULL DEFAULT '0',
  `vehicle_parking_charges` float NOT NULL DEFAULT '0',
  `vehicle_driver_charges` float NOT NULL DEFAULT '0',
  `vehicle_permit_charges` float NOT NULL DEFAULT '0',
  `before_6_am_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `after_8_pm_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_vehicle_amount` float NOT NULL DEFAULT '0',
  `driver_start_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_end_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_opening_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `opening_speedmeter_image` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_closing_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `closing_speedmeter_image` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1- Yes',
  `added_via_amendment` int NOT NULL DEFAULT '0',
  `defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side ',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_plan_vendor_vehicle_details_ID`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vr_vh_dls_id` (`itinerary_plan_vendor_vehicle_details_ID`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vr_eli_id` (`itinerary_plan_vendor_eligible_ID`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_route_id` (`itinerary_route_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_route_date` (`itinerary_route_date`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vendor_id` (`vendor_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_id` (`vehicle_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_time_limit_id` (`time_limit_id`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_travel_type` (`travel_type`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_location_from` (`itinerary_route_location_from`(768)),
  KEY `idx_cnf_iti_pln_vr_vh_dls_location_to` (`itinerary_route_location_to`(768)),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_running_km` (`total_running_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_running_time` (`total_running_time`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_siteseeing_km` (`total_siteseeing_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_siteseeing_time` (`total_siteseeing_time`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_pickup_km` (`total_pickup_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_pickup_duration` (`total_pickup_duration`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_drop_km` (`total_drop_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_drop_duration` (`total_drop_duration`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_extra_km` (`total_extra_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_extra_km_rate` (`extra_km_rate`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_extra_km_charges` (`total_extra_km_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_travelled_km` (`total_travelled_km`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_travelled_time` (`total_travelled_time`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_rental_charges` (`vehicle_rental_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_toll_charges` (`vehicle_toll_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_parking_charges` (`vehicle_parking_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_driver_charges` (`vehicle_driver_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_vehicle_permit_charges` (`vehicle_permit_charges`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_before_6_am_extra_time` (`before_6_am_extra_time`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_after_8_pm_extra_time` (`after_8_pm_extra_time`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_before_6_am_charges_driver` (`before_6_am_charges_for_driver`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_before_6_am_charges_vehicle` (`before_6_am_charges_for_vehicle`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_after_8_pm_charges_driver` (`after_8_pm_charges_for_driver`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_after_8_pm_charges_vehicle` (`after_8_pm_charges_for_vehicle`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_total_vehicle_amount` (`total_vehicle_amount`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_status` (`status`),
  KEY `idx_cnf_iti_pln_vr_vh_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_activity_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_activity_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_activity_details` (
  `confirmed_route_activity_ID` int NOT NULL AUTO_INCREMENT,
  `route_activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `guide_activity_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_statys` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_activity_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_order` int NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_infant` float NOT NULL DEFAULT '0',
  `activity_charges_for_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_infant` float NOT NULL DEFAULT '0',
  `activity_amout` float NOT NULL DEFAULT '0',
  `activity_traveling_time` time DEFAULT NULL,
  `activity_start_time` time DEFAULT NULL,
  `activity_end_time` time DEFAULT NULL,
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT ' 0 - No | 1 - Yes',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_route_activity_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_rt_activity_id` (`route_activity_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_route_hotspot_id` (`route_hotspot_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_hotspot_id` (`hotspot_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_activity_id` (`activity_ID`),
  KEY `idx_cnf_iti_rt_activity_dls_guide_status` (`guide_activity_status`),
  KEY `idx_cnf_iti_rt_activity_dls_guide_not_visited_desc` (`guide_not_visited_description`),
  KEY `idx_cnf_iti_rt_activity_dls_activity_status` (`activity_statys`),
  KEY `idx_cnf_iti_rt_activity_dls_driver_status` (`driver_activity_status`),
  KEY `idx_cnf_iti_rt_activity_dls_driver_not_visited_desc` (`driver_not_visited_description`),
  KEY `idx_cnf_iti_rt_activity_dls_order` (`activity_order`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_foreign_adult` (`activity_charges_for_foreign_adult`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_foreign_children` (`activity_charges_for_foreign_children`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_foreign_infant` (`activity_charges_for_foreign_infant`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_adult` (`activity_charges_for_adult`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_children` (`activity_charges_for_children`),
  KEY `idx_cnf_iti_rt_activity_dls_charges_infant` (`activity_charges_for_infant`),
  KEY `idx_cnf_iti_rt_activity_dls_amount` (`activity_amout`),
  KEY `idx_cnf_iti_rt_activity_dls_traveling_time` (`activity_traveling_time`),
  KEY `idx_cnf_iti_rt_activity_dls_start_time` (`activity_start_time`),
  KEY `idx_cnf_iti_rt_activity_dls_end_time` (`activity_end_time`),
  KEY `idx_cnf_iti_rt_activity_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_rt_activity_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_rt_activity_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_rt_activity_dls_status` (`status`),
  KEY `idx_cnf_iti_rt_activity_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_activity_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_activity_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_activity_entry_cost_details` (
  `cnf_itinerary_activity_cost_detail_ID` int NOT NULL AUTO_INCREMENT,
  `activity_cost_detail_id` int NOT NULL DEFAULT '0',
  `route_activity_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancellation_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side ',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_activity_cost_detail_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_details` (
  `confirmed_itinerary_route_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `location_id` bigint NOT NULL DEFAULT '0',
  `location_name` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `itinerary_route_date` date DEFAULT NULL,
  `no_of_days` int NOT NULL DEFAULT '0',
  `no_of_km` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direct_to_next_visiting_place` int NOT NULL DEFAULT '0',
  `next_visiting_location` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `route_start_time` time DEFAULT NULL,
  `route_end_time` time DEFAULT NULL,
  `wholeday_guidehotspot_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_trip_completed` int NOT NULL DEFAULT '0' COMMENT '1 - Trip Completed | 0 - Not Completed',
  `guide_trip_completed` int NOT NULL DEFAULT '0' COMMENT '1 - Trip Completed | 0 - Not Completed',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_route_ID`),
  KEY `idx_cnf_iti_rt_dls_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_rt_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_rt_dls_location_id` (`location_id`),
  KEY `idx_cnf_iti_rt_dls_location_name` (`location_name`),
  KEY `idx_cnf_iti_rt_dls_route_date` (`itinerary_route_date`),
  KEY `idx_cnf_iti_rt_dls_no_of_days` (`no_of_days`),
  KEY `idx_cnf_iti_rt_dls_no_of_km` (`no_of_km`),
  KEY `idx_cnf_iti_rt_dls_direct_to_next_location` (`direct_to_next_visiting_place`),
  KEY `idx_cnf_iti_rt_dls_next_location` (`next_visiting_location`),
  KEY `idx_cnf_iti_rt_dls_start_time` (`route_start_time`),
  KEY `idx_cnf_iti_rt_dls_end_time` (`route_end_time`),
  KEY `idx_cnf_iti_rt_dls_guidehotspot_status` (`wholeday_guidehotspot_status`),
  KEY `idx_cnf_iti_rt_dls_guide_not_visited_desc` (`guide_not_visited_description`),
  KEY `idx_cnf_iti_rt_dls_driver_trip_completed` (`driver_trip_completed`),
  KEY `idx_cnf_iti_rt_dls_guide_trip_completed` (`guide_trip_completed`),
  KEY `idx_cnf_iti_rt_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_rt_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_rt_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_rt_dls_status` (`status`),
  KEY `idx_cnf_iti_rt_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_guide_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_guide_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_guide_details` (
  `confirmed_route_guide_ID` int NOT NULL AUTO_INCREMENT,
  `route_guide_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_guide_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary,\r\n2 - Day Wise',
  `guide_language` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_slot` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_cost` float NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_route_guide_ID`),
  KEY `idx_cnf_iti_rt_gd_dls_route_guide_id` (`route_guide_ID`),
  KEY `idx_cnf_iti_rt_gd_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_rt_gd_dls_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_id` (`guide_id`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_status` (`guide_status`),
  KEY `idx_cnf_iti_rt_gd_dls_not_visited_desc` (`not_visited_description`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_not_visited_desc` (`guide_not_visited_description`),
  KEY `idx_cnf_iti_rt_gd_dls_driver_guide_status` (`driver_guide_status`),
  KEY `idx_cnf_iti_rt_gd_dls_driver_not_visited_desc` (`driver_not_visited_description`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_type` (`guide_type`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_language` (`guide_language`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_slot` (`guide_slot`),
  KEY `idx_cnf_iti_rt_gd_dls_guide_cost` (`guide_cost`),
  KEY `idx_cnf_iti_rt_gd_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_rt_gd_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_rt_gd_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_rt_gd_dls_status` (`status`),
  KEY `idx_cnf_iti_rt_gd_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_guide_slot_cost_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_guide_slot_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_guide_slot_cost_details` (
  `cnf_itinerary_guide_slot_cost_details_ID` int NOT NULL AUTO_INCREMENT,
  `guide_slot_cost_details_id` int NOT NULL DEFAULT '0',
  `route_guide_id` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary, 2 - Day Wise',
  `guide_slot` int NOT NULL DEFAULT '0',
  `guide_slot_cost` float NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancellation_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_guide_slot_cost_details_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_hotspot_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_hotspot_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_hotspot_details` (
  `confirmed_route_hotspot_ID` int NOT NULL AUTO_INCREMENT,
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `item_type` int NOT NULL DEFAULT '0' COMMENT '1 - Refreshment | 2 - Direct Destination Traveling | 3 - Site Seeing Traveling | 4 - Hotspots | 5 - Traveling to Hotel Location | 6 - Return to Hotel | 7 - Return to Departure Location\r\n',
  `hotspot_order` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `guide_hotspot_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `guide_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_hotspot_status` int NOT NULL DEFAULT '0' COMMENT '1 - Visited | 2 - Not Visited',
  `driver_not_visited_description` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_amout` float NOT NULL DEFAULT '0',
  `hotspot_traveling_time` time NOT NULL DEFAULT '00:00:00',
  `itinerary_travel_type_buffer_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_travelling_distance` text COLLATE utf8mb4_general_ci,
  `hotspot_start_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_end_time` time NOT NULL DEFAULT '00:00:00',
  `allow_break_hours` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `allow_via_route` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `via_location_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_plan_own_way` int NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_route_hotspot_ID`),
  KEY `idx_cnf_iti_rt_htspt_dls_route_hotspot_id` (`route_hotspot_ID`),
  KEY `idx_cnf_iti_rt_htspt_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_rt_htspt_dls_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_rt_htspt_dls_item_type` (`item_type`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_order` (`hotspot_order`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_id` (`hotspot_ID`),
  KEY `idx_cnf_iti_rt_htspt_dls_guide_hotspot_status` (`guide_hotspot_status`),
  KEY `idx_cnf_iti_rt_htspt_dls_guide_not_visited_desc` (`guide_not_visited_description`),
  KEY `idx_cnf_iti_rt_htspt_dls_driver_hotspot_status` (`driver_hotspot_status`),
  KEY `idx_cnf_iti_rt_htspt_dls_driver_not_visited_desc` (`driver_not_visited_description`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_adult_entry_cost` (`hotspot_adult_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_child_entry_cost` (`hotspot_child_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_infant_entry_cost` (`hotspot_infant_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_foreign_adult_entry_cost` (`hotspot_foreign_adult_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_foreign_child_entry_cost` (`hotspot_foreign_child_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_foreign_infant_entry_cost` (`hotspot_foreign_infant_entry_cost`),
  KEY `idx_cnf_iti_rt_htspt_dls_hotspot_amount` (`hotspot_amout`),
  KEY `idx_cnf_iti_rt_htspt_dls_traveling_time` (`hotspot_traveling_time`),
  KEY `idx_cnf_iti_rt_htspt_dls_buffer_time` (`itinerary_travel_type_buffer_time`),
  KEY `idx_cnf_iti_rt_htspt_dls_travelling_distance` (`hotspot_travelling_distance`(768)),
  KEY `idx_cnf_iti_rt_htspt_dls_start_time` (`hotspot_start_time`),
  KEY `idx_cnf_iti_rt_htspt_dls_end_time` (`hotspot_end_time`),
  KEY `idx_cnf_iti_rt_htspt_dls_break_hours` (`allow_break_hours`),
  KEY `idx_cnf_iti_rt_htspt_dls_allow_via_route` (`allow_via_route`),
  KEY `idx_cnf_iti_rt_htspt_dls_via_location_name` (`via_location_name`),
  KEY `idx_cnf_iti_rt_htspt_dls_plan_own_way` (`hotspot_plan_own_way`),
  KEY `idx_cnf_iti_rt_htspt_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_rt_htspt_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_rt_htspt_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_rt_htspt_dls_status` (`status`),
  KEY `idx_cnf_iti_rt_htspt_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_hotspot_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_hotspot_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_hotspot_entry_cost_details` (
  `cnf_itinerary_hotspot_cost_detail_ID` int NOT NULL AUTO_INCREMENT,
  `hotspot_cost_detail_id` int NOT NULL DEFAULT '0',
  `route_hotspot_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `cancellation_status` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `cancellation_defect_type` int NOT NULL DEFAULT '0' COMMENT '1- from customer | 2 - From DVI Side',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`cnf_itinerary_hotspot_cost_detail_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_route_hotspot_parking_charge`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_route_hotspot_parking_charge`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_route_hotspot_parking_charge` (
  `confirmed_itinerary_hotspot_parking_charge_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_hotspot_parking_charge_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `vehicle_type` int NOT NULL DEFAULT '0',
  `vehicle_qty` int NOT NULL DEFAULT '0',
  `parking_charges_amt` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_hotspot_parking_charge_ID`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_parking_charge_id` (`itinerary_hotspot_parking_charge_ID`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_hotspot_id` (`hotspot_ID`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_vehicle_type` (`vehicle_type`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_vehicle_qty` (`vehicle_qty`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_parking_charges_amt` (`parking_charges_amt`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_createdby` (`createdby`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_createdon` (`createdon`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_status` (`status`),
  KEY `idx_cnf_iti_htspt_prkng_chrg_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_traveller_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_traveller_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_traveller_details` (
  `confirmed_traveller_details_ID` int NOT NULL AUTO_INCREMENT,
  `traveller_details_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `room_id` int DEFAULT '0',
  `traveller_age` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `child_bed_type` int NOT NULL DEFAULT '0' COMMENT '1 - Without Bed | 2 - With Bed',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_traveller_details_ID`),
  KEY `idx_cnf_iti_trv_dls_traveller_details_id` (`traveller_details_ID`),
  KEY `idx_cnf_iti_trv_dls_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_trv_dls_traveller_type` (`traveller_type`),
  KEY `idx_cnf_iti_trv_dls_room_id` (`room_id`),
  KEY `idx_cnf_iti_trv_dls_traveller_age` (`traveller_age`),
  KEY `idx_cnf_iti_trv_dls_child_bed_type` (`child_bed_type`),
  KEY `idx_cnf_iti_trv_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_trv_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_trv_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_trv_dls_status` (`status`),
  KEY `idx_cnf_iti_trv_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_vendor_driver_assigned`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_vendor_driver_assigned`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_vendor_driver_assigned` (
  `driver_assigned_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `driver_id` int NOT NULL DEFAULT '0',
  `trip_start_date_and_time` datetime DEFAULT NULL,
  `trip_end_date_and_time` datetime DEFAULT NULL,
  `assigned_driver_status` int NOT NULL DEFAULT '0',
  `driver_assigned_on` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_assigned_ID`),
  KEY `idx_cnf_iti_vndr_drv_assgn_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_vndr_drv_assgn_vendor_id` (`vendor_id`),
  KEY `idx_cnf_iti_vndr_drv_assgn_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_cnf_iti_vndr_drv_assgn_vehicle_id` (`vehicle_id`),
  KEY `idx_cnf_iti_vndr_drv_assgn_driver_id` (`driver_id`),
  KEY `idx_cnf_iti_vndr_drv_assgn_trip_start_date_and_time` (`trip_start_date_and_time`),
  KEY `idx_cnf_iti_vndr_drv_assgn_trip_end_date_and_time` (`trip_end_date_and_time`),
  KEY `idx_cnf_iti_vndr_drv_assgn_assigned_driver_status` (`assigned_driver_status`),
  KEY `idx_cnf_iti_vndr_drv_assgn_driver_assigned_on` (`driver_assigned_on`),
  KEY `idx_cnf_iti_vndr_drv_assgn_createdby` (`createdby`),
  KEY `idx_cnf_iti_vndr_drv_assgn_createdon` (`createdon`),
  KEY `idx_cnf_iti_vndr_drv_assgn_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_vndr_drv_assgn_status` (`status`),
  KEY `idx_cnf_iti_vndr_drv_assgn_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_vendor_vehicle_assigned`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_vendor_vehicle_assigned`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_vendor_vehicle_assigned` (
  `vendor_vehicle_assigned_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `trip_start_date_and_time` datetime DEFAULT NULL,
  `trip_end_date_and_time` datetime DEFAULT NULL,
  `assigned_vehicle_status` int NOT NULL DEFAULT '0',
  `assigned_on` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_vehicle_assigned_ID`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_vendor_id` (`vendor_id`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_vehicle_id` (`vehicle_id`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_trip_start_date_and_time` (`trip_start_date_and_time`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_trip_end_date_and_time` (`trip_end_date_and_time`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_assigned_vehicle_status` (`assigned_vehicle_status`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_assigned_on` (`assigned_on`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_createdby` (`createdby`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_createdon` (`createdon`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_status` (`status`),
  KEY `idx_cnf_iti_vndr_vhcl_assgn_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_confirmed_itinerary_via_route_details`
--

DROP TABLE IF EXISTS `dvi_confirmed_itinerary_via_route_details`;
CREATE TABLE IF NOT EXISTS `dvi_confirmed_itinerary_via_route_details` (
  `confirmed_itinerary_via_route_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_via_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `source_location` text COLLATE utf8mb4_general_ci,
  `destination_location` text COLLATE utf8mb4_general_ci,
  `itinerary_via_location_ID` int NOT NULL DEFAULT '0',
  `itinerary_via_location_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `itinerary_session_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`confirmed_itinerary_via_route_ID`),
  KEY `idx_cnf_iti_via_route_dls_itinerary_via_route_id` (`itinerary_via_route_ID`),
  KEY `idx_cnf_iti_via_route_dls_itinerary_route_id` (`itinerary_route_ID`),
  KEY `idx_cnf_iti_via_route_dls_itinerary_plan_id` (`itinerary_plan_ID`),
  KEY `idx_cnf_iti_via_route_dls_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_cnf_iti_via_route_dls_source_location` (`source_location`(768)),
  KEY `idx_cnf_iti_via_route_dls_destination_location` (`destination_location`(768)),
  KEY `idx_cnf_iti_via_route_dls_itinerary_via_location_id` (`itinerary_via_location_ID`),
  KEY `idx_cnf_iti_via_route_dls_itinerary_via_location_name` (`itinerary_via_location_name`(768)),
  KEY `idx_cnf_iti_via_route_dls_itinerary_session_id` (`itinerary_session_id`),
  KEY `idx_cnf_iti_via_route_dls_createdby` (`createdby`),
  KEY `idx_cnf_iti_via_route_dls_createdon` (`createdon`),
  KEY `idx_cnf_iti_via_route_dls_updatedon` (`updatedon`),
  KEY `idx_cnf_iti_via_route_dls_status` (`status`),
  KEY `idx_cnf_iti_via_route_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_countries`
--

DROP TABLE IF EXISTS `dvi_countries`;
CREATE TABLE IF NOT EXISTS `dvi_countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shortname` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `phonecode` int NOT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_cnf_countries_shortname` (`shortname`),
  KEY `idx_cnf_countries_name` (`name`),
  KEY `idx_cnf_countries_phonecode` (`phonecode`),
  KEY `idx_cnf_countries_createdby` (`createdby`),
  KEY `idx_cnf_countries_createdon` (`createdon`),
  KEY `idx_cnf_countries_updatedon` (`updatedon`),
  KEY `idx_cnf_countries_status` (`status`),
  KEY `idx_cnf_countries_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_coupon_wallet`
--

DROP TABLE IF EXISTS `dvi_coupon_wallet`;
CREATE TABLE IF NOT EXISTS `dvi_coupon_wallet` (
  `coupon_wallet_ID` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL DEFAULT '0',
  `transaction_date` date DEFAULT NULL,
  `transaction_amount` float DEFAULT '0',
  `transaction_type` int DEFAULT '0' COMMENT '1 - credit | 2 - debit',
  `remarks` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`coupon_wallet_ID`),
  KEY `idx_cnf_coupon_wallet_agent_id` (`agent_id`),
  KEY `idx_cnf_coupon_wallet_transaction_date` (`transaction_date`),
  KEY `idx_cnf_coupon_wallet_transaction_amount` (`transaction_amount`),
  KEY `idx_cnf_coupon_wallet_transaction_type` (`transaction_type`),
  KEY `idx_cnf_coupon_wallet_remarks` (`remarks`(768)),
  KEY `idx_cnf_coupon_wallet_createdby` (`createdby`),
  KEY `idx_cnf_coupon_wallet_createdon` (`createdon`),
  KEY `idx_cnf_coupon_wallet_updatedon` (`updatedon`),
  KEY `idx_cnf_coupon_wallet_status` (`status`),
  KEY `idx_cnf_coupon_wallet_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_driver_costdetails`
--

DROP TABLE IF EXISTS `dvi_driver_costdetails`;
CREATE TABLE IF NOT EXISTS `dvi_driver_costdetails` (
  `driver_costdetails_id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int NOT NULL DEFAULT '0',
  `driver_salary` float NOT NULL DEFAULT '0',
  `driver_food_cost` float NOT NULL DEFAULT '0',
  `driver_accomdation_cost` float NOT NULL DEFAULT '0',
  `driver_bhatta_cost` float NOT NULL DEFAULT '0',
  `driver_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Included | 2 - Excluded',
  `driver_early_morning_charges` float NOT NULL DEFAULT '0' COMMENT 'Before 6 am',
  `driver_evening_charges` float NOT NULL DEFAULT '0' COMMENT 'After 6 pm',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_costdetails_id`),
  KEY `idx_cnf_driver_costdetails_driver_id` (`driver_id`),
  KEY `idx_cnf_driver_costdetails_driver_salary` (`driver_salary`),
  KEY `idx_cnf_driver_costdetails_driver_food_cost` (`driver_food_cost`),
  KEY `idx_cnf_driver_costdetails_driver_accomdation_cost` (`driver_accomdation_cost`),
  KEY `idx_cnf_driver_costdetails_driver_bhatta_cost` (`driver_bhatta_cost`),
  KEY `idx_cnf_driver_costdetails_driver_gst_type` (`driver_gst_type`),
  KEY `idx_cnf_driver_costdetails_driver_early_morning_charges` (`driver_early_morning_charges`),
  KEY `idx_cnf_driver_costdetails_driver_evening_charges` (`driver_evening_charges`),
  KEY `idx_cnf_driver_costdetails_createdon` (`createdon`),
  KEY `idx_cnf_driver_costdetails_updatedon` (`updatedon`),
  KEY `idx_cnf_driver_costdetails_createdby` (`createdby`),
  KEY `idx_cnf_driver_costdetails_status` (`status`),
  KEY `idx_cnf_driver_costdetails_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_driver_details`
--

DROP TABLE IF EXISTS `dvi_driver_details`;
CREATE TABLE IF NOT EXISTS `dvi_driver_details` (
  `driver_id` int NOT NULL AUTO_INCREMENT,
  `driver_code` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `driver_name` text COLLATE utf8mb4_general_ci,
  `driver_primary_mobile_number` text COLLATE utf8mb4_general_ci,
  `driver_alternate_mobile_number` text COLLATE utf8mb4_general_ci,
  `driver_whatsapp_mobile_number` text COLLATE utf8mb4_general_ci,
  `driver_email` text COLLATE utf8mb4_general_ci,
  `driver_aadharcard_num` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_voter_id_num` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_pan_card` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_license_issue_date` date DEFAULT NULL,
  `driver_license_expiry_date` date DEFAULT NULL,
  `driver_license_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `driver_blood_group` int NOT NULL DEFAULT '0',
  `driver_gender` int NOT NULL DEFAULT '0' COMMENT '1 - Male,\r\n2 - Female,\r\n3 - Transgender,\r\n4 - Others',
  `driver_date_of_birth` date DEFAULT NULL,
  `driver_profile_image` text COLLATE utf8mb4_general_ci,
  `driver_address` text COLLATE utf8mb4_general_ci,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_id`),
  KEY `idx_cnf_driver_details_driver_code` (`driver_code`),
  KEY `idx_cnf_driver_details_vendor_id` (`vendor_id`),
  KEY `idx_cnf_driver_details_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_cnf_driver_details_driver_name` (`driver_name`(768)),
  KEY `idx_cnf_driver_details_driver_primary_mobile_number` (`driver_primary_mobile_number`(768)),
  KEY `idx_cnf_driver_details_driver_alternate_mobile_number` (`driver_alternate_mobile_number`(768)),
  KEY `idx_cnf_driver_details_driver_email` (`driver_email`(768)),
  KEY `idx_cnf_driver_details_driver_aadharcard_num` (`driver_aadharcard_num`),
  KEY `idx_cnf_driver_details_driver_voter_id_num` (`driver_voter_id_num`),
  KEY `idx_cnf_driver_details_driver_pan_card` (`driver_pan_card`),
  KEY `idx_cnf_driver_details_driver_license_issue_date` (`driver_license_issue_date`),
  KEY `idx_cnf_driver_details_driver_license_expiry_date` (`driver_license_expiry_date`),
  KEY `idx_cnf_driver_details_driver_license_number` (`driver_license_number`),
  KEY `idx_cnf_driver_details_driver_blood_group` (`driver_blood_group`),
  KEY `idx_cnf_driver_details_driver_gender` (`driver_gender`),
  KEY `idx_cnf_driver_details_driver_date_of_birth` (`driver_date_of_birth`),
  KEY `idx_cnf_driver_details_driver_profile_image` (`driver_profile_image`(768)),
  KEY `idx_cnf_driver_details_driver_address` (`driver_address`(768)),
  KEY `idx_cnf_driver_details_createdon` (`createdon`),
  KEY `idx_cnf_driver_details_updatedon` (`updatedon`),
  KEY `idx_cnf_driver_details_createdby` (`createdby`),
  KEY `idx_cnf_driver_details_status` (`status`),
  KEY `idx_cnf_driver_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_driver_document_details`
--

DROP TABLE IF EXISTS `dvi_driver_document_details`;
CREATE TABLE IF NOT EXISTS `dvi_driver_document_details` (
  `driver_document_details_id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int DEFAULT '0',
  `document_type` text COLLATE utf8mb4_general_ci,
  `driver_document_name` text COLLATE utf8mb4_general_ci,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_document_details_id`),
  KEY `idx_cnf_driver_document_details_driver_id` (`driver_id`),
  KEY `idx_cnf_driver_document_details_document_type` (`document_type`(768)),
  KEY `idx_cnf_driver_document_details_driver_document_name` (`driver_document_name`(768)),
  KEY `idx_cnf_driver_document_details_createdon` (`createdon`),
  KEY `idx_cnf_driver_document_details_updatedon` (`updatedon`),
  KEY `idx_cnf_driver_document_details_createdby` (`createdby`),
  KEY `idx_cnf_driver_document_details_status` (`status`),
  KEY `idx_cnf_driver_document_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_driver_license_renewal_log_details`
--

DROP TABLE IF EXISTS `dvi_driver_license_renewal_log_details`;
CREATE TABLE IF NOT EXISTS `dvi_driver_license_renewal_log_details` (
  `driver_license_renewal_log_ID` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `driver_id` int NOT NULL DEFAULT '0',
  `license_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_license_renewal_log_ID`),
  KEY `idx_cnf_driver_license_renewal_log_vendor_id` (`vendor_id`),
  KEY `idx_cnf_driver_license_renewal_log_driver_id` (`driver_id`),
  KEY `idx_cnf_driver_license_renewal_log_license_number` (`license_number`),
  KEY `idx_cnf_driver_license_renewal_log_start_date` (`start_date`),
  KEY `idx_cnf_driver_license_renewal_log_end_date` (`end_date`),
  KEY `idx_cnf_driver_license_renewal_log_createdon` (`createdon`),
  KEY `idx_cnf_driver_license_renewal_log_updatedon` (`updatedon`),
  KEY `idx_cnf_driver_license_renewal_log_createdby` (`createdby`),
  KEY `idx_cnf_driver_license_renewal_log_status` (`status`),
  KEY `idx_cnf_driver_license_renewal_log_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_driver_review_details`
--

DROP TABLE IF EXISTS `dvi_driver_review_details`;
CREATE TABLE IF NOT EXISTS `dvi_driver_review_details` (
  `driver_review_id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int NOT NULL DEFAULT '0',
  `driver_rating` text COLLATE utf8mb4_general_ci,
  `driver_description` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_review_id`),
  KEY `idx_cnf_driver_review_details_driver_id` (`driver_id`),
  KEY `idx_cnf_driver_review_details_driver_rating` (`driver_rating`(768)),
  KEY `idx_cnf_driver_review_details_driver_description` (`driver_description`),
  KEY `idx_cnf_driver_review_details_createdby` (`createdby`),
  KEY `idx_cnf_driver_review_details_createdon` (`createdon`),
  KEY `idx_cnf_driver_review_details_updatedon` (`updatedon`),
  KEY `idx_cnf_driver_review_details_status` (`status`),
  KEY `idx_cnf_driver_review_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_global_settings`
--

DROP TABLE IF EXISTS `dvi_global_settings`;
CREATE TABLE IF NOT EXISTS `dvi_global_settings` (
  `global_settings_ID` int NOT NULL AUTO_INCREMENT,
  `eligibile_country_code` text COLLATE utf8mb4_general_ci,
  `extrabed_rate_percentage` float NOT NULL DEFAULT '0',
  `childwithbed_rate_percentage` float NOT NULL DEFAULT '0',
  `childnobed_rate_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin` float NOT NULL DEFAULT '0',
  `hotel_margin_gst_type` tinyint(1) NOT NULL DEFAULT '0',
  `hotel_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `itinerary_distance_limit` float DEFAULT '0' COMMENT 'Distsnce Limit between source and destination',
  `allowed_km_limit_per_day` float NOT NULL DEFAULT '0',
  `itinerary_common_buffer_time` time DEFAULT NULL,
  `itinerary_travel_by_flight_buffer_time` time DEFAULT NULL,
  `itinerary_travel_by_train_buffer_time` time DEFAULT NULL,
  `itinerary_travel_by_road_buffer_time` time DEFAULT NULL,
  `itinerary_break_time` text COLLATE utf8mb4_general_ci,
  `itinerary_hotel_start` text COLLATE utf8mb4_general_ci,
  `itinerary_hotel_return` text COLLATE utf8mb4_general_ci,
  `itinerary_additional_margin_percentage` float NOT NULL DEFAULT '0',
  `itinerary_additional_margin_day_limit` float NOT NULL DEFAULT '0',
  `custom_hotspot_or_activity` text COLLATE utf8mb4_general_ci,
  `accommodation_return` text COLLATE utf8mb4_general_ci,
  `vehicle_terms_condition` text COLLATE utf8mb4_general_ci,
  `itinerary_local_speed_limit` float NOT NULL DEFAULT '0',
  `itinerary_outstation_speed_limit` float NOT NULL DEFAULT '0',
  `agent_referral_bonus_credit` float NOT NULL DEFAULT '0',
  `hotel_terms_condition` text COLLATE utf8mb4_general_ci,
  `hotel_voucher_terms_condition` text COLLATE utf8mb4_general_ci,
  `vehicle_voucher_terms_condition` text COLLATE utf8mb4_general_ci,
  `site_title` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_address` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_pincode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_gstin_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_pan_no` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_contact_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_email_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_logo` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_hsn` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_hsn` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `service_component_hsn` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site_seeing_restriction_km_limit` float NOT NULL DEFAULT '0',
  `youtube_link` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook_link` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instagram_link` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `linkedin_link` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cc_email_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `default_hotel_voucher_email_id` text COLLATE utf8mb4_general_ci,
  `default_vehicle_voucher_email_id` text COLLATE utf8mb4_general_ci,
  `default_accounts_email_id` text COLLATE utf8mb4_general_ci,
  `company_cin` varchar(25) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_acc_holder_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_acc_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_ifsc_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `branch_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`global_settings_ID`),
  KEY `idx_slcm_global_settings_global_settings_ID` (`global_settings_ID`),
  KEY `idx_slcm_global_settings_institution_name` (`itinerary_distance_limit`),
  KEY `idx_slcm_global_settings_status` (`status`),
  KEY `idx_slcm_global_settings_deleted` (`deleted`),
  KEY `idx_cnf_global_settings_itinerary_distance_limit` (`itinerary_distance_limit`),
  KEY `idx_cnf_global_settings_allowed_km_limit_per_day` (`allowed_km_limit_per_day`),
  KEY `idx_cnf_global_settings_itinerary_common_buffer_time` (`itinerary_common_buffer_time`),
  KEY `idx_cnf_global_settings_itinerary_travel_by_flight_buffer_time` (`itinerary_travel_by_flight_buffer_time`),
  KEY `idx_cnf_global_settings_itinerary_travel_by_train_buffer_time` (`itinerary_travel_by_train_buffer_time`),
  KEY `idx_cnf_global_settings_itinerary_travel_by_road_buffer_time` (`itinerary_travel_by_road_buffer_time`),
  KEY `idx_cnf_global_settings_itinerary_break_time` (`itinerary_break_time`(768)),
  KEY `idx_cnf_global_settings_itinerary_hotel_return` (`itinerary_hotel_return`(768)),
  KEY `idx_cnf_global_settings_custom_hotspot_or_activity` (`custom_hotspot_or_activity`(768)),
  KEY `idx_cnf_global_settings_accommodation_return` (`accommodation_return`(768)),
  KEY `idx_cnf_global_settings_vehicle_terms_condition` (`vehicle_terms_condition`(768)),
  KEY `idx_cnf_global_settings_itinerary_local_speed_limit` (`itinerary_local_speed_limit`),
  KEY `idx_cnf_global_settings_itinerary_outstation_speed_limit` (`itinerary_outstation_speed_limit`),
  KEY `idx_cnf_global_settings_agent_referral_bonus_credit` (`agent_referral_bonus_credit`),
  KEY `idx_cnf_global_settings_hotel_terms_condition` (`hotel_terms_condition`(768)),
  KEY `idx_cnf_global_settings_hotel_voucher_terms_condition` (`hotel_voucher_terms_condition`(768)),
  KEY `idx_cnf_global_settings_vehicle_voucher_terms_condition` (`vehicle_voucher_terms_condition`(768)),
  KEY `idx_cnf_global_settings_site_title` (`site_title`),
  KEY `idx_cnf_global_settings_company_name` (`company_name`),
  KEY `idx_cnf_global_settings_company_address` (`company_address`),
  KEY `idx_cnf_global_settings_company_pincode` (`company_pincode`),
  KEY `idx_cnf_global_settings_company_gstin_no` (`company_gstin_no`),
  KEY `idx_cnf_global_settings_company_pan_no` (`company_pan_no`),
  KEY `idx_cnf_global_settings_company_contact_no` (`company_contact_no`),
  KEY `idx_cnf_global_settings_company_email_id` (`company_email_id`),
  KEY `idx_cnf_global_settings_company_logo` (`company_logo`),
  KEY `idx_cnf_global_settings_site_seeing_restriction_km_limit` (`site_seeing_restriction_km_limit`),
  KEY `idx_cnf_global_settings_youtube_link` (`youtube_link`),
  KEY `idx_cnf_global_settings_facebook_link` (`facebook_link`),
  KEY `idx_cnf_global_settings_instagram_link` (`instagram_link`),
  KEY `idx_cnf_global_settings_linkedin_link` (`linkedin_link`),
  KEY `idx_cnf_global_settings_cc_email_id` (`cc_email_id`),
  KEY `idx_cnf_global_settings_createdby` (`createdby`),
  KEY `idx_cnf_global_settings_createdon` (`createdon`),
  KEY `idx_cnf_global_settings_updatedon` (`updatedon`),
  KEY `idx_cnf_global_settings_status` (`status`),
  KEY `idx_cnf_global_settings_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_gst_setting`
--

DROP TABLE IF EXISTS `dvi_gst_setting`;
CREATE TABLE IF NOT EXISTS `dvi_gst_setting` (
  `gst_setting_id` int NOT NULL AUTO_INCREMENT,
  `gst_title` text COLLATE utf8mb4_general_ci,
  `gst_value` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cgst_value` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sgst_value` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `igst_value` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`gst_setting_id`),
  KEY `idx_cnf_gst_setting_gst_title` (`gst_title`(768)),
  KEY `idx_cnf_gst_setting_gst_value` (`gst_value`),
  KEY `idx_cnf_gst_setting_cgst_value` (`cgst_value`),
  KEY `idx_cnf_gst_setting_sgst_value` (`sgst_value`),
  KEY `idx_cnf_gst_setting_igst_value` (`igst_value`),
  KEY `idx_cnf_gst_setting_createdon` (`createdon`),
  KEY `idx_cnf_gst_setting_updatedon` (`updatedon`),
  KEY `idx_cnf_gst_setting_createdby` (`createdby`),
  KEY `idx_cnf_gst_setting_status` (`status`),
  KEY `idx_cnf_gst_setting_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_guide_details`
--

DROP TABLE IF EXISTS `dvi_guide_details`;
CREATE TABLE IF NOT EXISTS `dvi_guide_details` (
  `guide_id` int NOT NULL AUTO_INCREMENT,
  `guide_name` text COLLATE utf8mb4_general_ci,
  `guide_dob` date DEFAULT NULL,
  `guide_bloodgroup` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_gender` tinyint NOT NULL DEFAULT '0' COMMENT '1-Male|2-female|3-Transgender|4-others',
  `guide_primary_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_alternative_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_emergency_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_language_proficiency` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_aadhar_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_experience` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_country` int NOT NULL DEFAULT '0',
  `guide_state` int NOT NULL DEFAULT '0',
  `guide_city` int NOT NULL DEFAULT '0',
  `gst_type` int DEFAULT '0' COMMENT '   1 - Included | 2 - Excluded     ',
  `guide_gst` float DEFAULT '0',
  `guide_available_slot` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_bank_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_bank_branch_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_ifsc_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_account_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_preffered_for` tinyint NOT NULL DEFAULT '0',
  `applicable_hotspot_places` text COLLATE utf8mb4_general_ci,
  `applicable_activity_places` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`guide_id`),
  KEY `idx_cnf_guide_details_guide_name` (`guide_name`(768)),
  KEY `idx_cnf_guide_details_guide_dob` (`guide_dob`),
  KEY `idx_cnf_guide_details_guide_bloodgroup` (`guide_bloodgroup`),
  KEY `idx_cnf_guide_details_guide_gender` (`guide_gender`),
  KEY `idx_cnf_guide_details_guide_primary_mobile_number` (`guide_primary_mobile_number`),
  KEY `idx_cnf_guide_details_guide_alternative_mobile_number` (`guide_alternative_mobile_number`),
  KEY `idx_cnf_guide_details_guide_email` (`guide_email`),
  KEY `idx_cnf_guide_details_guide_emergency_mobile_number` (`guide_emergency_mobile_number`),
  KEY `idx_cnf_guide_details_guide_language_proficiency` (`guide_language_proficiency`),
  KEY `idx_cnf_guide_details_guide_aadhar_number` (`guide_aadhar_number`),
  KEY `idx_cnf_guide_details_guide_experience` (`guide_experience`),
  KEY `idx_cnf_guide_details_guide_country` (`guide_country`),
  KEY `idx_cnf_guide_details_guide_state` (`guide_state`),
  KEY `idx_cnf_guide_details_guide_city` (`guide_city`),
  KEY `idx_cnf_guide_details_gst_type` (`gst_type`),
  KEY `idx_cnf_guide_details_guide_gst` (`guide_gst`),
  KEY `idx_cnf_guide_details_guide_available_slot` (`guide_available_slot`),
  KEY `idx_cnf_guide_details_guide_bank_name` (`guide_bank_name`),
  KEY `idx_cnf_guide_details_guide_bank_branch_name` (`guide_bank_branch_name`),
  KEY `idx_cnf_guide_details_guide_ifsc_code` (`guide_ifsc_code`),
  KEY `idx_cnf_guide_details_guide_account_number` (`guide_account_number`),
  KEY `idx_cnf_guide_details_guide_preffered_for` (`guide_preffered_for`),
  KEY `idx_cnf_guide_details_applicable_hotspot_places` (`applicable_hotspot_places`(768)),
  KEY `idx_cnf_guide_details_applicable_activity_places` (`applicable_activity_places`(768)),
  KEY `idx_cnf_guide_details_createdby` (`createdby`),
  KEY `idx_cnf_guide_details_createdon` (`createdon`),
  KEY `idx_cnf_guide_details_updatedon` (`updatedon`),
  KEY `idx_cnf_guide_details_status` (`status`),
  KEY `idx_cnf_guide_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_guide_pricebook`
--

DROP TABLE IF EXISTS `dvi_guide_pricebook`;
CREATE TABLE IF NOT EXISTS `dvi_guide_pricebook` (
  `guide_price_book_ID` int NOT NULL AUTO_INCREMENT,
  `guide_id` int NOT NULL DEFAULT '0',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pax_count` int NOT NULL DEFAULT '0' COMMENT '1 - 1-5 pax| 2 - 6-14 pax | 3 - 15-40 pax',
  `slot_type` int NOT NULL DEFAULT '0' COMMENT '1 - Slot 1: 8 AM to 1 PM | 2 - Slot 2: 1 PM to 6 PM | 3 - Slot 3: 6 PM to 9 PM',
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`guide_price_book_ID`),
  KEY `idx_cnf_guide_pricebook_guide_price_book_ID` (`guide_price_book_ID`),
  KEY `idx_cnf_guide_pricebook_guide_id` (`guide_id`),
  KEY `idx_cnf_guide_pricebook_year` (`year`),
  KEY `idx_cnf_guide_pricebook_month` (`month`),
  KEY `idx_cnf_guide_pricebook_pax_count` (`pax_count`),
  KEY `idx_cnf_guide_pricebook_slot_type` (`slot_type`),
  KEY `idx_cnf_guide_pricebook_day_1` (`day_1`),
  KEY `idx_cnf_guide_pricebook_day_2` (`day_2`),
  KEY `idx_cnf_guide_pricebook_day_3` (`day_3`),
  KEY `idx_cnf_guide_pricebook_day_4` (`day_4`),
  KEY `idx_cnf_guide_pricebook_day_5` (`day_5`),
  KEY `idx_cnf_guide_pricebook_day_6` (`day_6`),
  KEY `idx_cnf_guide_pricebook_day_7` (`day_7`),
  KEY `idx_cnf_guide_pricebook_day_8` (`day_8`),
  KEY `idx_cnf_guide_pricebook_day_9` (`day_9`),
  KEY `idx_cnf_guide_pricebook_day_10` (`day_10`),
  KEY `idx_cnf_guide_pricebook_day_11` (`day_11`),
  KEY `idx_cnf_guide_pricebook_day_12` (`day_12`),
  KEY `idx_cnf_guide_pricebook_day_13` (`day_13`),
  KEY `idx_cnf_guide_pricebook_day_14` (`day_14`),
  KEY `idx_cnf_guide_pricebook_day_15` (`day_15`),
  KEY `idx_cnf_guide_pricebook_day_16` (`day_16`),
  KEY `idx_cnf_guide_pricebook_day_17` (`day_17`),
  KEY `idx_cnf_guide_pricebook_day_18` (`day_18`),
  KEY `idx_cnf_guide_pricebook_day_19` (`day_19`),
  KEY `idx_cnf_guide_pricebook_day_20` (`day_20`),
  KEY `idx_cnf_guide_pricebook_day_21` (`day_21`),
  KEY `idx_cnf_guide_pricebook_day_22` (`day_22`),
  KEY `idx_cnf_guide_pricebook_day_23` (`day_23`),
  KEY `idx_cnf_guide_pricebook_day_24` (`day_24`),
  KEY `idx_cnf_guide_pricebook_day_25` (`day_25`),
  KEY `idx_cnf_guide_pricebook_day_26` (`day_26`),
  KEY `idx_cnf_guide_pricebook_day_27` (`day_27`),
  KEY `idx_cnf_guide_pricebook_day_28` (`day_28`),
  KEY `idx_cnf_guide_pricebook_day_29` (`day_29`),
  KEY `idx_cnf_guide_pricebook_day_30` (`day_30`),
  KEY `idx_cnf_guide_pricebook_day_31` (`day_31`),
  KEY `idx_cnf_guide_pricebook_createdby` (`createdby`),
  KEY `idx_cnf_guide_pricebook_createdon` (`createdon`),
  KEY `idx_cnf_guide_pricebook_updatedon` (`updatedon`),
  KEY `idx_cnf_guide_pricebook_status` (`status`),
  KEY `idx_cnf_guide_pricebook_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_guide_review_details`
--

DROP TABLE IF EXISTS `dvi_guide_review_details`;
CREATE TABLE IF NOT EXISTS `dvi_guide_review_details` (
  `guide_review_id` int NOT NULL AUTO_INCREMENT,
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_rating` text COLLATE utf8mb4_general_ci,
  `guide_description` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`guide_review_id`),
  KEY `idx_guide_review_guide_id` (`guide_id`),
  KEY `idx_guide_review_guide_rating` (`guide_rating`(768)),
  KEY `idx_guide_review_guide_description` (`guide_description`(768)),
  KEY `idx_guide_review_createdby` (`createdby`),
  KEY `idx_guide_review_createdon` (`createdon`),
  KEY `idx_guide_review_updatedon` (`updatedon`),
  KEY `idx_guide_review_status` (`status`),
  KEY `idx_guide_review_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel`
--

DROP TABLE IF EXISTS `dvi_hotel`;
CREATE TABLE IF NOT EXISTS `dvi_hotel` (
  `hotel_id` int NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_code` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_mobile` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_email` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_state` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_place` text COLLATE utf8mb4_general_ci,
  `hotel_address` text COLLATE utf8mb4_general_ci,
  `hotel_pincode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotel_margin` float NOT NULL DEFAULT '0',
  `hotel_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `hotel_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `hotel_longitude` mediumtext COLLATE utf8mb4_general_ci,
  `hotel_latitude` mediumtext COLLATE utf8mb4_general_ci,
  `hotel_category` int NOT NULL DEFAULT '0',
  `hotel_cancel_policy` mediumtext COLLATE utf8mb4_general_ci,
  `hotel_power_backup` int NOT NULL DEFAULT '0',
  `hotel_free_cancel_policy_no_of_days_from_booking_date` int NOT NULL DEFAULT '0',
  `hotel_cancel_policy_percentage` float NOT NULL DEFAULT '0',
  `hotel_hotspot_status` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`hotel_id`),
  KEY `idx_hotel_hotel_id` (`hotel_id`),
  KEY `idx_hotel_hotel_name` (`hotel_name`),
  KEY `idx_hotel_hotel_code` (`hotel_code`),
  KEY `idx_hotel_hotel_mobile` (`hotel_mobile`),
  KEY `idx_hotel_hotel_email` (`hotel_email`),
  KEY `idx_hotel_hotel_country` (`hotel_country`),
  KEY `idx_hotel_hotel_city` (`hotel_city`),
  KEY `idx_hotel_hotel_state` (`hotel_state`),
  KEY `idx_hotel_hotel_place` (`hotel_place`(768)),
  KEY `idx_hotel_hotel_address` (`hotel_address`(768)),
  KEY `idx_hotel_hotel_pincode` (`hotel_pincode`),
  KEY `idx_hotel_hotel_margin` (`hotel_margin`),
  KEY `idx_hotel_hotel_margin_gst_type` (`hotel_margin_gst_type`),
  KEY `idx_hotel_hotel_margin_gst_percentage` (`hotel_margin_gst_percentage`),
  KEY `idx_hotel_hotel_longitude` (`hotel_longitude`(768)),
  KEY `idx_hotel_hotel_latitude` (`hotel_latitude`(768)),
  KEY `idx_hotel_hotel_category` (`hotel_category`),
  KEY `idx_hotel_hotel_cancel_policy` (`hotel_cancel_policy`(768)),
  KEY `idx_hotel_hotel_power_backup` (`hotel_power_backup`),
  KEY `idx_hotel_hotel_free_cancel_policy_no_of_days_from_booking_date` (`hotel_free_cancel_policy_no_of_days_from_booking_date`),
  KEY `idx_hotel_hotel_cancel_policy_percentage` (`hotel_cancel_policy_percentage`),
  KEY `idx_hotel_hotel_hotspot_status` (`hotel_hotspot_status`),
  KEY `idx_hotel_createdby` (`createdby`),
  KEY `idx_hotel_createdon` (`createdon`),
  KEY `idx_hotel_updatedon` (`updatedon`),
  KEY `idx_hotel_status` (`status`),
  KEY `idx_hotel_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_amenities`
--

DROP TABLE IF EXISTS `dvi_hotel_amenities`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_amenities` (
  `hotel_amenities_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int DEFAULT '0',
  `amenities_title` text COLLATE utf8mb4_general_ci,
  `amenities_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `availability_type` int NOT NULL DEFAULT '0' COMMENT '1 - 24/7 | 2 - Duration',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int DEFAULT '0',
  `status` tinyint DEFAULT '0',
  `deleted` tinyint DEFAULT '0',
  PRIMARY KEY (`hotel_amenities_id`),
  KEY `idx_hotel_amenities_hotel_id` (`hotel_id`),
  KEY `idx_hotel_amenities_amenities_title` (`amenities_title`(768)),
  KEY `idx_hotel_amenities_amenities_code` (`amenities_code`),
  KEY `idx_hotel_amenities_quantity` (`quantity`),
  KEY `idx_hotel_amenities_availability_type` (`availability_type`),
  KEY `idx_hotel_amenities_start_time` (`start_time`),
  KEY `idx_hotel_amenities_end_time` (`end_time`),
  KEY `idx_hotel_amenities_createdon` (`createdon`),
  KEY `idx_hotel_amenities_updatedon` (`updatedon`),
  KEY `idx_hotel_amenities_createdby` (`createdby`),
  KEY `idx_hotel_amenities_status` (`status`),
  KEY `idx_hotel_amenities_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_amenities_price_book`
--

DROP TABLE IF EXISTS `dvi_hotel_amenities_price_book`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_amenities_price_book` (
  `hotel_amenities_price_book_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_amenities_id` int NOT NULL DEFAULT '0',
  `pricetype` tinyint NOT NULL DEFAULT '0' COMMENT '1 - day,2- hour',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_2` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_3` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_4` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_5` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_6` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_7` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_8` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_9` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_10` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_11` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_12` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_13` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_14` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_15` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_16` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_17` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_18` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_19` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_20` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_21` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_22` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_23` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_24` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_25` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_26` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_27` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_28` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_29` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_30` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_31` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_amenities_price_book_id`),
  UNIQUE KEY `unique_price_book` (`hotel_id`,`hotel_amenities_id`,`pricetype`,`year`,`month`),
  KEY `idx_hotel_amenities_price_book_hotel_id` (`hotel_id`),
  KEY `idx_hotel_amenities_price_book_hotel_amenities_id` (`hotel_amenities_id`),
  KEY `idx_hotel_amenities_price_book_pricetype` (`pricetype`),
  KEY `idx_hotel_amenities_price_book_year` (`year`),
  KEY `idx_hotel_amenities_price_book_month` (`month`),
  KEY `idx_hotel_amenities_price_book_day_1` (`day_1`),
  KEY `idx_hotel_amenities_price_book_day_2` (`day_2`),
  KEY `idx_hotel_amenities_price_book_day_3` (`day_3`),
  KEY `idx_hotel_amenities_price_book_day_4` (`day_4`),
  KEY `idx_hotel_amenities_price_book_day_5` (`day_5`),
  KEY `idx_hotel_amenities_price_book_day_6` (`day_6`),
  KEY `idx_hotel_amenities_price_book_day_7` (`day_7`),
  KEY `idx_hotel_amenities_price_book_day_8` (`day_8`),
  KEY `idx_hotel_amenities_price_book_day_9` (`day_9`),
  KEY `idx_hotel_amenities_price_book_day_10` (`day_10`),
  KEY `idx_hotel_amenities_price_book_day_11` (`day_11`),
  KEY `idx_hotel_amenities_price_book_day_12` (`day_12`),
  KEY `idx_hotel_amenities_price_book_day_13` (`day_13`),
  KEY `idx_hotel_amenities_price_book_day_14` (`day_14`),
  KEY `idx_hotel_amenities_price_book_day_15` (`day_15`),
  KEY `idx_hotel_amenities_price_book_day_16` (`day_16`),
  KEY `idx_hotel_amenities_price_book_day_17` (`day_17`),
  KEY `idx_hotel_amenities_price_book_day_18` (`day_18`),
  KEY `idx_hotel_amenities_price_book_day_19` (`day_19`),
  KEY `idx_hotel_amenities_price_book_day_20` (`day_20`),
  KEY `idx_hotel_amenities_price_book_day_21` (`day_21`),
  KEY `idx_hotel_amenities_price_book_day_22` (`day_22`),
  KEY `idx_hotel_amenities_price_book_day_23` (`day_23`),
  KEY `idx_hotel_amenities_price_book_day_24` (`day_24`),
  KEY `idx_hotel_amenities_price_book_day_25` (`day_25`),
  KEY `idx_hotel_amenities_price_book_day_26` (`day_26`),
  KEY `idx_hotel_amenities_price_book_day_27` (`day_27`),
  KEY `idx_hotel_amenities_price_book_day_28` (`day_28`),
  KEY `idx_hotel_amenities_price_book_day_29` (`day_29`),
  KEY `idx_hotel_amenities_price_book_day_30` (`day_30`),
  KEY `idx_hotel_amenities_price_book_day_31` (`day_31`),
  KEY `idx_hotel_amenities_price_book_createdby` (`createdby`),
  KEY `idx_hotel_amenities_price_book_createdon` (`createdon`),
  KEY `idx_hotel_amenities_price_book_updatedon` (`updatedon`),
  KEY `idx_hotel_amenities_price_book_status` (`status`),
  KEY `idx_hotel_amenities_price_book_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_category`
--

DROP TABLE IF EXISTS `dvi_hotel_category`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_category` (
  `hotel_category_id` int NOT NULL AUTO_INCREMENT,
  `hotel_category_title` text COLLATE utf8mb4_general_ci,
  `hotel_category_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_category_id`),
  KEY `idx_hotel_category_hotel_category_title` (`hotel_category_title`(768)),
  KEY `idx_hotel_category_hotel_category_code` (`hotel_category_code`),
  KEY `idx_hotel_category_createdby` (`createdby`),
  KEY `idx_hotel_category_createdon` (`createdon`),
  KEY `idx_hotel_category_updatedon` (`updatedon`),
  KEY `idx_hotel_category_status` (`status`),
  KEY `idx_hotel_category_deleted` (`deleted`),
  KEY `idx_cat_active` (`status`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_meal_price_book`
--

DROP TABLE IF EXISTS `dvi_hotel_meal_price_book`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_meal_price_book` (
  `hotel_meal_price_book_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL DEFAULT '0',
  `meal_type` int NOT NULL DEFAULT '0' COMMENT '1-Breakfast | 2- Lunch | 3- Dinner',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_meal_price_book_id`),
  KEY `idx_hotel_meal_price_book_hotel_id` (`hotel_id`),
  KEY `idx_hotel_meal_price_book_meal_type` (`meal_type`),
  KEY `idx_hotel_meal_price_book_year` (`year`),
  KEY `idx_hotel_meal_price_book_month` (`month`),
  KEY `idx_hotel_meal_price_book_day_1` (`day_1`),
  KEY `idx_hotel_meal_price_book_day_2` (`day_2`),
  KEY `idx_hotel_meal_price_book_day_3` (`day_3`),
  KEY `idx_hotel_meal_price_book_day_4` (`day_4`),
  KEY `idx_hotel_meal_price_book_day_5` (`day_5`),
  KEY `idx_hotel_meal_price_book_day_6` (`day_6`),
  KEY `idx_hotel_meal_price_book_day_7` (`day_7`),
  KEY `idx_hotel_meal_price_book_day_8` (`day_8`),
  KEY `idx_hotel_meal_price_book_day_9` (`day_9`),
  KEY `idx_hotel_meal_price_book_day_10` (`day_10`),
  KEY `idx_hotel_meal_price_book_day_11` (`day_11`),
  KEY `idx_hotel_meal_price_book_day_12` (`day_12`),
  KEY `idx_hotel_meal_price_book_day_13` (`day_13`),
  KEY `idx_hotel_meal_price_book_day_14` (`day_14`),
  KEY `idx_hotel_meal_price_book_day_15` (`day_15`),
  KEY `idx_hotel_meal_price_book_day_16` (`day_16`),
  KEY `idx_hotel_meal_price_book_day_17` (`day_17`),
  KEY `idx_hotel_meal_price_book_day_18` (`day_18`),
  KEY `idx_hotel_meal_price_book_day_19` (`day_19`),
  KEY `idx_hotel_meal_price_book_day_20` (`day_20`),
  KEY `idx_hotel_meal_price_book_day_21` (`day_21`),
  KEY `idx_hotel_meal_price_book_day_22` (`day_22`),
  KEY `idx_hotel_meal_price_book_day_23` (`day_23`),
  KEY `idx_hotel_meal_price_book_day_24` (`day_24`),
  KEY `idx_hotel_meal_price_book_day_25` (`day_25`),
  KEY `idx_hotel_meal_price_book_day_26` (`day_26`),
  KEY `idx_hotel_meal_price_book_day_27` (`day_27`),
  KEY `idx_hotel_meal_price_book_day_28` (`day_28`),
  KEY `idx_hotel_meal_price_book_day_29` (`day_29`),
  KEY `idx_hotel_meal_price_book_day_30` (`day_30`),
  KEY `idx_hotel_meal_price_book_day_31` (`day_31`),
  KEY `idx_hotel_meal_price_book_createdby` (`createdby`),
  KEY `idx_hotel_meal_price_book_createdon` (`createdon`),
  KEY `idx_hotel_meal_price_book_updatedon` (`updatedon`),
  KEY `idx_hotel_meal_price_book_status` (`status`),
  KEY `idx_hotel_meal_price_book_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_review_details`
--

DROP TABLE IF EXISTS `dvi_hotel_review_details`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_review_details` (
  `hotel_review_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_rating` text COLLATE utf8mb4_general_ci,
  `hotel_description` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_review_id`),
  KEY `idx_hotel_review_details_hotel_id` (`hotel_id`),
  KEY `idx_hotel_review_details_hotel_rating` (`hotel_rating`(768)),
  KEY `idx_hotel_review_details_createdby` (`createdby`),
  KEY `idx_hotel_review_details_createdon` (`createdon`),
  KEY `idx_hotel_review_details_updatedon` (`updatedon`),
  KEY `idx_hotel_review_details_status` (`status`),
  KEY `idx_hotel_review_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_rooms`
--

DROP TABLE IF EXISTS `dvi_hotel_rooms`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_rooms` (
  `room_ID` bigint NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `preferred_for` text COLLATE utf8mb4_general_ci,
  `room_title` text COLLATE utf8mb4_general_ci,
  `no_of_rooms_available` int NOT NULL DEFAULT '0',
  `room_ref_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `air_conditioner_availability` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `total_max_adults` int NOT NULL DEFAULT '0',
  `total_max_childrens` int NOT NULL DEFAULT '0',
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Included | 2 - Excluded',
  `gst_percentage` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breakfast_included` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `lunch_included` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `dinner_included` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `inbuilt_amenities` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`room_ID`),
  KEY `idx_hotel_rooms_hotel_id` (`hotel_id`),
  KEY `idx_hotel_rooms_room_type_id` (`room_type_id`),
  KEY `idx_hotel_rooms_preferred_for` (`preferred_for`(768)),
  KEY `idx_hotel_rooms_room_title` (`room_title`(768)),
  KEY `idx_hotel_rooms_no_of_rooms_available` (`no_of_rooms_available`),
  KEY `idx_hotel_rooms_room_ref_code` (`room_ref_code`),
  KEY `idx_hotel_rooms_air_conditioner_availability` (`air_conditioner_availability`),
  KEY `idx_hotel_rooms_total_max_adults` (`total_max_adults`),
  KEY `idx_hotel_rooms_total_max_childrens` (`total_max_childrens`),
  KEY `idx_hotel_rooms_check_in_time` (`check_in_time`),
  KEY `idx_hotel_rooms_check_out_time` (`check_out_time`),
  KEY `idx_hotel_rooms_gst_type` (`gst_type`),
  KEY `idx_hotel_rooms_gst_percentage` (`gst_percentage`),
  KEY `idx_hotel_rooms_breakfast_included` (`breakfast_included`),
  KEY `idx_hotel_rooms_lunch_included` (`lunch_included`),
  KEY `idx_hotel_rooms_dinner_included` (`dinner_included`),
  KEY `idx_hotel_rooms_inbuilt_amenities` (`inbuilt_amenities`(768)),
  KEY `idx_hotel_rooms_createdby` (`createdby`),
  KEY `idx_hotel_rooms_createdon` (`createdon`),
  KEY `idx_hotel_rooms_updatedon` (`updatedon`),
  KEY `idx_hotel_rooms_status` (`status`),
  KEY `idx_hotel_rooms_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_roomtype`
--

DROP TABLE IF EXISTS `dvi_hotel_roomtype`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_roomtype` (
  `room_type_id` int NOT NULL AUTO_INCREMENT,
  `room_type_title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int DEFAULT '0',
  `status` tinyint DEFAULT '0',
  `deleted` tinyint DEFAULT '0',
  PRIMARY KEY (`room_type_id`),
  KEY `idx_hotel_roomtype_room_type_title` (`room_type_title`),
  KEY `idx_hotel_roomtype_createdon` (`createdon`),
  KEY `idx_hotel_roomtype_updatedon` (`updatedon`),
  KEY `idx_hotel_roomtype_createdby` (`createdby`),
  KEY `idx_hotel_roomtype_status` (`status`),
  KEY `idx_hotel_roomtype_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_room_gallery_details`
--

DROP TABLE IF EXISTS `dvi_hotel_room_gallery_details`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_room_gallery_details` (
  `hotel_room_gallery_details_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_gallery_name` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_room_gallery_details_id`),
  KEY `idx_hotel_room_gallery_details_hotel_id` (`hotel_id`),
  KEY `idx_hotel_room_gallery_details_room_id` (`room_id`),
  KEY `idx_hotel_room_gallery_details_room_gallery_name` (`room_gallery_name`(768)),
  KEY `idx_hotel_room_gallery_details_createdby` (`createdby`),
  KEY `idx_hotel_room_gallery_details_createdon` (`createdon`),
  KEY `idx_hotel_room_gallery_details_updatedon` (`updatedon`),
  KEY `idx_hotel_room_gallery_details_status` (`status`),
  KEY `idx_hotel_room_gallery_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotel_room_price_book`
--

DROP TABLE IF EXISTS `dvi_hotel_room_price_book`;
CREATE TABLE IF NOT EXISTS `dvi_hotel_room_price_book` (
  `hotel_price_book_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `price_type` int NOT NULL DEFAULT '0' COMMENT '0 - Room Rate | 1-Extra bed Rate | 2- Child with bed |3- Child without bed',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotel_price_book_id`),
  KEY `idx_hotel_room_price_book_hotel_id` (`hotel_id`),
  KEY `idx_hotel_room_price_book_room_type_id` (`room_type_id`),
  KEY `idx_hotel_room_price_book_room_id` (`room_id`),
  KEY `idx_hotel_room_price_book_price_type` (`price_type`),
  KEY `idx_hotel_room_price_book_year` (`year`),
  KEY `idx_hotel_room_price_book_month` (`month`),
  KEY `idx_hotel_room_price_book_day_1` (`day_1`),
  KEY `idx_hotel_room_price_book_day_2` (`day_2`),
  KEY `idx_hotel_room_price_book_day_3` (`day_3`),
  KEY `idx_hotel_room_price_book_day_4` (`day_4`),
  KEY `idx_hotel_room_price_book_day_5` (`day_5`),
  KEY `idx_hotel_room_price_book_day_6` (`day_6`),
  KEY `idx_hotel_room_price_book_day_7` (`day_7`),
  KEY `idx_hotel_room_price_book_day_8` (`day_8`),
  KEY `idx_hotel_room_price_book_day_9` (`day_9`),
  KEY `idx_hotel_room_price_book_day_10` (`day_10`),
  KEY `idx_hotel_room_price_book_day_11` (`day_11`),
  KEY `idx_hotel_room_price_book_day_12` (`day_12`),
  KEY `idx_hotel_room_price_book_day_13` (`day_13`),
  KEY `idx_hotel_room_price_book_day_14` (`day_14`),
  KEY `idx_hotel_room_price_book_day_15` (`day_15`),
  KEY `idx_hotel_room_price_book_day_16` (`day_16`),
  KEY `idx_hotel_room_price_book_day_17` (`day_17`),
  KEY `idx_hotel_room_price_book_day_18` (`day_18`),
  KEY `idx_hotel_room_price_book_day_19` (`day_19`),
  KEY `idx_hotel_room_price_book_day_20` (`day_20`),
  KEY `idx_hotel_room_price_book_day_21` (`day_21`),
  KEY `idx_hotel_room_price_book_day_22` (`day_22`),
  KEY `idx_hotel_room_price_book_day_23` (`day_23`),
  KEY `idx_hotel_room_price_book_day_24` (`day_24`),
  KEY `idx_hotel_room_price_book_day_25` (`day_25`),
  KEY `idx_hotel_room_price_book_day_26` (`day_26`),
  KEY `idx_hotel_room_price_book_day_27` (`day_27`),
  KEY `idx_hotel_room_price_book_day_28` (`day_28`),
  KEY `idx_hotel_room_price_book_day_29` (`day_29`),
  KEY `idx_hotel_room_price_book_day_30` (`day_30`),
  KEY `idx_hotel_room_price_book_day_31` (`day_31`),
  KEY `idx_hotel_room_price_book_createdby` (`createdby`),
  KEY `idx_hotel_room_price_book_createdon` (`createdon`),
  KEY `idx_hotel_room_price_book_updatedon` (`updatedon`),
  KEY `idx_hotel_room_price_book_status` (`status`),
  KEY `idx_hotel_room_price_book_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotspot_gallery_details`
--

DROP TABLE IF EXISTS `dvi_hotspot_gallery_details`;
CREATE TABLE IF NOT EXISTS `dvi_hotspot_gallery_details` (
  `hotspot_gallery_details_id` int NOT NULL AUTO_INCREMENT,
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_gallery_name` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotspot_gallery_details_id`),
  KEY `idx_hotspot_gallery_gallery_name` (`hotspot_gallery_name`(768)),
  KEY `idx_hotspot_gallery_createdby` (`createdby`),
  KEY `idx_hotspot_gallery_createdon` (`createdon`),
  KEY `idx_hotspot_gallery_updatedon` (`updatedon`),
  KEY `idx_hotspot_gallery_status` (`status`),
  KEY `idx_hotspot_gallery_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotspot_place`
--

DROP TABLE IF EXISTS `dvi_hotspot_place`;
CREATE TABLE IF NOT EXISTS `dvi_hotspot_place` (
  `hotspot_ID` int NOT NULL AUTO_INCREMENT,
  `hotspot_type` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_name` text COLLATE utf8mb4_general_ci,
  `hotspot_description` text COLLATE utf8mb4_general_ci,
  `hotspot_address` text COLLATE utf8mb4_general_ci,
  `hotspot_landmark` tinytext COLLATE utf8mb4_general_ci,
  `hotspot_location` text COLLATE utf8mb4_general_ci,
  `hotspot_priority` int NOT NULL DEFAULT '0',
  `hotspot_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_adult_entry_cost` float DEFAULT '0',
  `hotspot_foreign_child_entry_cost` float DEFAULT '0',
  `hotspot_foreign_infant_entry_cost` float DEFAULT '0',
  `hotspot_duration` time DEFAULT NULL,
  `hotspot_rating` float NOT NULL DEFAULT '0',
  `hotspot_latitude` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_longitude` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_video_url` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotspot_ID`),
  KEY `idx_hotspot_place_hotspot_type` (`hotspot_type`),
  KEY `idx_hotspot_place_hotspot_name` (`hotspot_name`(768)),
  KEY `idx_hotspot_place_hotspot_description` (`hotspot_description`(768)),
  KEY `idx_hotspot_place_hotspot_address` (`hotspot_address`(768)),
  KEY `idx_hotspot_place_hotspot_landmark` (`hotspot_landmark`(63)),
  KEY `idx_hotspot_place_hotspot_location` (`hotspot_location`(768)),
  KEY `idx_hotspot_place_hotspot_priority` (`hotspot_priority`),
  KEY `idx_hotspot_place_hotspot_adult_entry_cost` (`hotspot_adult_entry_cost`),
  KEY `idx_hotspot_place_hotspot_child_entry_cost` (`hotspot_child_entry_cost`),
  KEY `idx_hotspot_place_hotspot_infant_entry_cost` (`hotspot_infant_entry_cost`),
  KEY `idx_hotspot_place_hotspot_foreign_adult_entry_cost` (`hotspot_foreign_adult_entry_cost`),
  KEY `idx_hotspot_place_hotspot_foreign_child_entry_cost` (`hotspot_foreign_child_entry_cost`),
  KEY `idx_hotspot_place_hotspot_foreign_infant_entry_cost` (`hotspot_foreign_infant_entry_cost`),
  KEY `idx_hotspot_place_hotspot_duration` (`hotspot_duration`),
  KEY `idx_hotspot_place_hotspot_rating` (`hotspot_rating`),
  KEY `idx_hotspot_place_hotspot_latitude` (`hotspot_latitude`),
  KEY `idx_hotspot_place_hotspot_longitude` (`hotspot_longitude`),
  KEY `idx_hotspot_place_hotspot_video_url` (`hotspot_video_url`(768)),
  KEY `idx_hotspot_place_createdby` (`createdby`),
  KEY `idx_hotspot_place_createdon` (`createdon`),
  KEY `idx_hotspot_place_updatedon` (`updatedon`),
  KEY `idx_hotspot_place_status` (`status`),
  KEY `idx_hotspot_place_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotspot_timing`
--

DROP TABLE IF EXISTS `dvi_hotspot_timing`;
CREATE TABLE IF NOT EXISTS `dvi_hotspot_timing` (
  `hotspot_timing_ID` int NOT NULL AUTO_INCREMENT,
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_timing_day` int DEFAULT '0' COMMENT '0 - Monday,\r\n1 - Tuesday,\r\n2 - Wednesday,\r\n3 - Thursday,\r\n4 - Friday,\r\n5 - Saturday,\r\n6 - Sunday',
  `hotspot_start_time` time DEFAULT NULL,
  `hotspot_end_time` time DEFAULT NULL,
  `hotspot_closed` int NOT NULL DEFAULT '0' COMMENT '0 - Not Closed, 1 - Closed ',
  `hotspot_open_all_time` int NOT NULL DEFAULT '0' COMMENT '0 - Not 24 Hours, 1 - Open 24 Hours',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotspot_timing_ID`),
  KEY `idx_hotspot_timing_hotspot_id` (`hotspot_ID`),
  KEY `idx_hotspot_timing_hotspot_timing_day` (`hotspot_timing_day`),
  KEY `idx_hotspot_timing_hotspot_start_time` (`hotspot_start_time`),
  KEY `idx_hotspot_timing_hotspot_end_time` (`hotspot_end_time`),
  KEY `idx_hotspot_timing_hotspot_closed` (`hotspot_closed`),
  KEY `idx_hotspot_timing_hotspot_open_all_time` (`hotspot_open_all_time`),
  KEY `idx_hotspot_timing_createdby` (`createdby`),
  KEY `idx_hotspot_timing_createdon` (`createdon`),
  KEY `idx_hotspot_timing_updatedon` (`updatedon`),
  KEY `idx_hotspot_timing_status` (`status`),
  KEY `idx_hotspot_timing_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_hotspot_vehicle_parking_charges`
--

DROP TABLE IF EXISTS `dvi_hotspot_vehicle_parking_charges`;
CREATE TABLE IF NOT EXISTS `dvi_hotspot_vehicle_parking_charges` (
  `vehicle_parking_charge_ID` bigint NOT NULL AUTO_INCREMENT,
  `hotspot_id` bigint NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `parking_charge` float NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_parking_charge_ID`),
  KEY `idx_vehicle_parking_charge_hotspot_id` (`hotspot_id`),
  KEY `idx_vehicle_parking_charge_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_vehicle_parking_charge_parking_charge` (`parking_charge`),
  KEY `idx_vehicle_parking_charge_createdon` (`createdon`),
  KEY `idx_vehicle_parking_charge_updatedon` (`updatedon`),
  KEY `idx_vehicle_parking_charge_createdby` (`createdby`),
  KEY `idx_vehicle_parking_charge_status` (`status`),
  KEY `idx_vehicle_parking_charge_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_inbuilt_amenities`
--

DROP TABLE IF EXISTS `dvi_inbuilt_amenities`;
CREATE TABLE IF NOT EXISTS `dvi_inbuilt_amenities` (
  `inbuilt_amenity_type_id` int NOT NULL AUTO_INCREMENT,
  `inbuilt_amenity_title` text COLLATE utf8mb4_general_ci,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`inbuilt_amenity_type_id`),
  KEY `idx_inbuilt_amenity_title` (`inbuilt_amenity_title`(768)),
  KEY `idx_inbuilt_amenity_createdon` (`createdon`),
  KEY `idx_inbuilt_amenity_updatedon` (`updatedon`),
  KEY `idx_inbuilt_amenity_createdby` (`createdby`),
  KEY `idx_inbuilt_amenity_status` (`status`),
  KEY `idx_inbuilt_amenity_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_details` (
  `itinerary_plan_ID` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0',
  `location_id` bigint NOT NULL DEFAULT '0',
  `arrival_location` text COLLATE utf8mb4_general_ci,
  `departure_location` text COLLATE utf8mb4_general_ci,
  `itinerary_quote_ID` text COLLATE utf8mb4_general_ci,
  `trip_start_date_and_time` datetime DEFAULT NULL,
  `trip_end_date_and_time` datetime DEFAULT NULL,
  `arrival_type` int NOT NULL DEFAULT '0' COMMENT '1 - via Air | 2 - via Road | 3 -\r\nvia Train',
  `departure_type` int NOT NULL DEFAULT '0' COMMENT '1 - via Air | 2 - via Road | 3 -\r\nvia Train',
  `expecting_budget` float NOT NULL DEFAULT '0',
  `itinerary_type` int NOT NULL DEFAULT '0' COMMENT '1 - Default | 2 - Customize',
  `entry_ticket_required` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `no_of_routes` int NOT NULL DEFAULT '0',
  `no_of_days` int NOT NULL DEFAULT '0',
  `no_of_nights` int NOT NULL DEFAULT '0',
  `total_adult` int NOT NULL DEFAULT '0',
  `total_children` int NOT NULL DEFAULT '0',
  `total_infants` int NOT NULL DEFAULT '0',
  `nationality` int NOT NULL DEFAULT '0',
  `itinerary_preference` int NOT NULL DEFAULT '0' COMMENT '1 - Hotel | 2 - Vehicle | 3 - Both | 4-Flights',
  `meal_plan_breakfast` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `meal_plan_lunch` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `meal_plan_dinner` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `preferred_room_count` int DEFAULT '0',
  `total_extra_bed` int NOT NULL DEFAULT '0',
  `total_child_with_bed` int NOT NULL DEFAULT '0',
  `total_child_without_bed` int NOT NULL DEFAULT '0',
  `guide_for_itinerary` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `food_type` int NOT NULL DEFAULT '0' COMMENT '1-Vegetarian | 2-Non Vegetarian| 3-Both',
  `special_instructions` text COLLATE utf8mb4_general_ci,
  `pick_up_date_and_time` datetime DEFAULT NULL,
  `hotel_rates_visibility` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `quotation_status` int NOT NULL DEFAULT '0' COMMENT '0 - Not Confirmed | 1- Confirmed',
  `agent_margin` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  `preferred_hotel_category` text COLLATE utf8mb4_general_ci,
  `hotel_facilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`itinerary_plan_ID`),
  KEY `idx_itinerary_plan_agent_id` (`agent_id`),
  KEY `idx_itinerary_plan_staff_id` (`staff_id`),
  KEY `idx_itinerary_plan_location_id` (`location_id`),
  KEY `idx_itinerary_plan_arrival_location` (`arrival_location`(768)),
  KEY `idx_itinerary_plan_departure_location` (`departure_location`(768)),
  KEY `idx_itinerary_plan_itinerary_quote_ID` (`itinerary_quote_ID`(768)),
  KEY `idx_itinerary_plan_trip_start_date_and_time` (`trip_start_date_and_time`),
  KEY `idx_itinerary_plan_trip_end_date_and_time` (`trip_end_date_and_time`),
  KEY `idx_itinerary_plan_arrival_type` (`arrival_type`),
  KEY `idx_itinerary_plan_departure_type` (`departure_type`),
  KEY `idx_itinerary_plan_expecting_budget` (`expecting_budget`),
  KEY `idx_itinerary_plan_itinerary_type` (`itinerary_type`),
  KEY `idx_itinerary_plan_entry_ticket_required` (`entry_ticket_required`),
  KEY `idx_itinerary_plan_no_of_routes` (`no_of_routes`),
  KEY `idx_itinerary_plan_no_of_days` (`no_of_days`),
  KEY `idx_itinerary_plan_no_of_nights` (`no_of_nights`),
  KEY `idx_itinerary_plan_total_adult` (`total_adult`),
  KEY `idx_itinerary_plan_total_children` (`total_children`),
  KEY `idx_itinerary_plan_total_infants` (`total_infants`),
  KEY `idx_itinerary_plan_nationality` (`nationality`),
  KEY `idx_itinerary_plan_itinerary_preference` (`itinerary_preference`),
  KEY `idx_itinerary_plan_meal_plan_breakfast` (`meal_plan_breakfast`),
  KEY `idx_itinerary_plan_meal_plan_lunch` (`meal_plan_lunch`),
  KEY `idx_itinerary_plan_meal_plan_dinner` (`meal_plan_dinner`),
  KEY `idx_itinerary_plan_preferred_room_count` (`preferred_room_count`),
  KEY `idx_itinerary_plan_total_extra_bed` (`total_extra_bed`),
  KEY `idx_itinerary_plan_total_child_with_bed` (`total_child_with_bed`),
  KEY `idx_itinerary_plan_total_child_without_bed` (`total_child_without_bed`),
  KEY `idx_itinerary_plan_guide_for_itinerary` (`guide_for_itinerary`),
  KEY `idx_itinerary_plan_food_type` (`food_type`),
  KEY `idx_itinerary_plan_special_instructions` (`special_instructions`(768)),
  KEY `idx_itinerary_plan_pick_up_date_and_time` (`pick_up_date_and_time`),
  KEY `idx_itinerary_plan_hotel_rates_visibility` (`hotel_rates_visibility`),
  KEY `idx_itinerary_plan_quotation_status` (`quotation_status`),
  KEY `idx_itinerary_plan_createdon` (`createdon`),
  KEY `idx_itinerary_plan_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_status` (`status`),
  KEY `idx_itinerary_plan_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_hotel_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_hotel_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_hotel_details` (
  `itinerary_plan_hotel_details_ID` int NOT NULL AUTO_INCREMENT,
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `itinerary_route_location` text COLLATE utf8mb4_general_ci,
  `hotel_required` int NOT NULL DEFAULT '0',
  `hotel_category_id` int NOT NULL DEFAULT '0',
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_margin_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `hotel_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `hotel_margin_rate` float NOT NULL DEFAULT '0',
  `hotel_margin_rate_tax_amt` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost` float NOT NULL DEFAULT '0',
  `hotel_breakfast_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost` float NOT NULL DEFAULT '0',
  `hotel_lunch_cost_gst_amount` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost` float NOT NULL DEFAULT '0',
  `hotel_dinner_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_persons` int NOT NULL DEFAULT '0' COMMENT 'No of Adult + No of \r\n Children ',
  `total_hotel_meal_plan_cost` float NOT NULL DEFAULT '0',
  `total_hotel_meal_plan_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost` float NOT NULL DEFAULT '0',
  `total_extra_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwith_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost` float NOT NULL DEFAULT '0',
  `total_childwithout_bed_cost_gst_amount` float NOT NULL DEFAULT '0',
  `total_no_of_rooms` int NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_cost` float NOT NULL DEFAULT '0',
  `total_amenities_cost` float NOT NULL DEFAULT '0',
  `total_amenities_gst_amount` float NOT NULL DEFAULT '0',
  `total_hotel_tax_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_plan_hotel_details_ID`),
  KEY `idx_itinerary_plan_hotel_group_type` (`group_type`),
  KEY `idx_itinerary_plan_hotel_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_itinerary_plan_hotel_itinerary_route_id` (`itinerary_route_id`),
  KEY `idx_itinerary_plan_hotel_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_itinerary_plan_hotel_itinerary_route_location` (`itinerary_route_location`(768)),
  KEY `idx_itinerary_plan_hotel_hotel_required` (`hotel_required`),
  KEY `idx_itinerary_plan_hotel_hotel_category_id` (`hotel_category_id`),
  KEY `idx_itinerary_plan_hotel_hotel_id` (`hotel_id`),
  KEY `idx_itinerary_plan_hotel_hotel_margin_percentage` (`hotel_margin_percentage`),
  KEY `idx_itinerary_plan_hotel_hotel_margin_gst_type` (`hotel_margin_gst_type`),
  KEY `idx_itinerary_plan_hotel_hotel_margin_gst_percentage` (`hotel_margin_gst_percentage`),
  KEY `idx_itinerary_plan_hotel_hotel_margin_rate` (`hotel_margin_rate`),
  KEY `idx_itinerary_plan_hotel_hotel_margin_rate_tax_amt` (`hotel_margin_rate_tax_amt`),
  KEY `idx_itinerary_plan_hotel_hotel_breakfast_cost` (`hotel_breakfast_cost`),
  KEY `idx_itinerary_plan_hotel_hotel_breakfast_cost_gst_amount` (`hotel_breakfast_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_hotel_lunch_cost` (`hotel_lunch_cost`),
  KEY `idx_itinerary_plan_hotel_hotel_lunch_cost_gst_amount` (`hotel_lunch_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_hotel_dinner_cost` (`hotel_dinner_cost`),
  KEY `idx_itinerary_plan_hotel_hotel_dinner_cost_gst_amount` (`hotel_dinner_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_no_of_persons` (`total_no_of_persons`),
  KEY `idx_itinerary_plan_hotel_total_hotel_meal_plan_cost` (`total_hotel_meal_plan_cost`),
  KEY `idx_itinerary_plan_hotel_total_hotel_meal_plan_cost_gst_amount` (`total_hotel_meal_plan_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_extra_bed_cost` (`total_extra_bed_cost`),
  KEY `idx_itinerary_plan_hotel_total_extra_bed_cost_gst_amount` (`total_extra_bed_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_childwith_bed_cost` (`total_childwith_bed_cost`),
  KEY `idx_itinerary_plan_hotel_total_childwith_bed_cost_gst_amount` (`total_childwith_bed_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_childwithout_bed_cost` (`total_childwithout_bed_cost`),
  KEY `idx_itinerary_plan_hotel_total_childwithout_bed_cost_gst_amount` (`total_childwithout_bed_cost_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_no_of_rooms` (`total_no_of_rooms`),
  KEY `idx_itinerary_plan_hotel_total_room_cost` (`total_room_cost`),
  KEY `idx_itinerary_plan_hotel_total_room_gst_amount` (`total_room_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_hotel_cost` (`total_hotel_cost`),
  KEY `idx_itinerary_plan_hotel_total_amenities_cost` (`total_amenities_cost`),
  KEY `idx_itinerary_plan_hotel_total_amenities_gst_amount` (`total_amenities_gst_amount`),
  KEY `idx_itinerary_plan_hotel_total_hotel_tax_amount` (`total_hotel_tax_amount`),
  KEY `idx_itinerary_plan_hotel_createdon` (`createdon`),
  KEY `idx_itinerary_plan_hotel_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_hotel_status` (`status`),
  KEY `idx_itinerary_plan_hotel_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_hotel_room_amenities`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_hotel_room_amenities`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_hotel_room_amenities` (
  `itinerary_plan_hotel_room_amenities_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `hotel_amenities_id` int NOT NULL DEFAULT '0',
  `total_qty` int NOT NULL DEFAULT '0',
  `amenitie_rate` float NOT NULL DEFAULT '0',
  `total_amenitie_cost` float NOT NULL DEFAULT '0',
  `total_amenitie_gst_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_plan_hotel_room_amenities_details_ID`),
  KEY `idx_itinerary_plan_hotel_room_amenities_group_type` (`group_type`),
  KEY `idx_itinerary_plan_hotel_room_amenities_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_itinerary_plan_hotel_room_amenities_itinerary_route_id` (`itinerary_route_id`),
  KEY `idx_itinerary_plan_hotel_room_amenities_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_itinerary_plan_hotel_room_amenities_hotel_id` (`hotel_id`),
  KEY `idx_itinerary_plan_hotel_room_amenities_hotel_amenities_id` (`hotel_amenities_id`),
  KEY `idx_itinerary_plan_hotel_room_amenities_total_qty` (`total_qty`),
  KEY `idx_itinerary_plan_hotel_room_amenities_amenitie_rate` (`amenitie_rate`),
  KEY `idx_itinerary_plan_hotel_room_amenities_total_amenitie_cost` (`total_amenitie_cost`),
  KEY `idx_itinerary_plan_hotel_room_amenities_tot_amen_gst_amount` (`total_amenitie_gst_amount`),
  KEY `idx_itinerary_plan_hotel_room_amenities_createdon` (`createdon`),
  KEY `idx_itinerary_plan_hotel_room_amenities_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_hotel_room_amenities_status` (`status`),
  KEY `idx_itinerary_plan_hotel_room_amenities_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_hotel_room_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_hotel_room_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_hotel_room_details` (
  `itinerary_plan_hotel_room_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_hotel_details_id` int NOT NULL DEFAULT '0',
  `group_type` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `hotel_id` int NOT NULL DEFAULT '0',
  `room_type_id` int NOT NULL DEFAULT '0',
  `room_id` int NOT NULL DEFAULT '0',
  `room_qty` int NOT NULL DEFAULT '0',
  `room_rate` float NOT NULL DEFAULT '0',
  `gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `gst_percentage` float NOT NULL DEFAULT '0',
  `extra_bed_count` int NOT NULL DEFAULT '0',
  `extra_bed_rate` float NOT NULL DEFAULT '0',
  `child_without_bed_count` int NOT NULL DEFAULT '0',
  `child_without_bed_charges` float NOT NULL DEFAULT '0',
  `child_with_bed_count` int NOT NULL DEFAULT '0',
  `child_with_bed_charges` float NOT NULL DEFAULT '0',
  `breakfast_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `lunch_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `dinner_required` int NOT NULL DEFAULT '0' COMMENT '0 - Not Required | 1 - Required',
  `breakfast_cost_per_person` float NOT NULL DEFAULT '0',
  `lunch_cost_per_person` float NOT NULL DEFAULT '0',
  `dinner_cost_per_person` float NOT NULL DEFAULT '0',
  `total_breafast_cost` float NOT NULL DEFAULT '0',
  `total_lunch_cost` float NOT NULL DEFAULT '0',
  `total_dinner_cost` float NOT NULL DEFAULT '0',
  `total_room_cost` float NOT NULL DEFAULT '0',
  `total_room_gst_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_plan_hotel_room_details_ID`),
  KEY `idx_itinerary_plan_hotel_room_details_group_type` (`group_type`),
  KEY `idx_itinerary_plan_hotel_room_details_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_itinerary_plan_hotel_room_details_itinerary_route_id` (`itinerary_route_id`),
  KEY `idx_itinerary_plan_hotel_room_details_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_itinerary_plan_hotel_room_details_hotel_id` (`hotel_id`),
  KEY `idx_itinerary_plan_hotel_room_details_room_type_id` (`room_type_id`),
  KEY `idx_itinerary_plan_hotel_room_details_room_id` (`room_id`),
  KEY `idx_itinerary_plan_hotel_room_details_room_qty` (`room_qty`),
  KEY `idx_itinerary_plan_hotel_room_details_room_rate` (`room_rate`),
  KEY `idx_itinerary_plan_hotel_room_details_gst_type` (`gst_type`),
  KEY `idx_itinerary_plan_hotel_room_details_gst_percentage` (`gst_percentage`),
  KEY `idx_itinerary_plan_hotel_room_details_extra_bed_count` (`extra_bed_count`),
  KEY `idx_itinerary_plan_hotel_room_details_extra_bed_rate` (`extra_bed_rate`),
  KEY `idx_itinerary_plan_hotel_room_details_child_without_bed_count` (`child_without_bed_count`),
  KEY `idx_itinerary_plan_hotel_room_details_child_without_bed_charges` (`child_without_bed_charges`),
  KEY `idx_itinerary_plan_hotel_room_details_child_with_bed_count` (`child_with_bed_count`),
  KEY `idx_itinerary_plan_hotel_room_details_child_with_bed_charges` (`child_with_bed_charges`),
  KEY `idx_itinerary_plan_hotel_room_details_breakfast_required` (`breakfast_required`),
  KEY `idx_itinerary_plan_hotel_room_details_lunch_required` (`lunch_required`),
  KEY `idx_itinerary_plan_hotel_room_details_dinner_required` (`dinner_required`),
  KEY `idx_itinerary_plan_hotel_room_details_breakfast_cost_per_person` (`breakfast_cost_per_person`),
  KEY `idx_itinerary_plan_hotel_room_details_lunch_cost_per_person` (`lunch_cost_per_person`),
  KEY `idx_itinerary_plan_hotel_room_details_dinner_cost_per_person` (`dinner_cost_per_person`),
  KEY `idx_itinerary_plan_hotel_room_details_total_breafast_cost` (`total_breafast_cost`),
  KEY `idx_itinerary_plan_hotel_room_details_total_lunch_cost` (`total_lunch_cost`),
  KEY `idx_itinerary_plan_hotel_room_details_total_dinner_cost` (`total_dinner_cost`),
  KEY `idx_itinerary_plan_hotel_room_details_total_room_cost` (`total_room_cost`),
  KEY `idx_itinerary_plan_hotel_room_details_total_room_gst_amount` (`total_room_gst_amount`),
  KEY `idx_itinerary_plan_hotel_room_details_createdon` (`createdon`),
  KEY `idx_itinerary_plan_hotel_room_details_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_hotel_room_details_status` (`status`),
  KEY `idx_itinerary_plan_hotel_room_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_route_permit_charge`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_route_permit_charge`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_route_permit_charge` (
  `route_permit_charge_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `source_state_id` int NOT NULL DEFAULT '0',
  `destination_state_id` int NOT NULL DEFAULT '0',
  `permit_cost` float NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`route_permit_charge_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_itinerary_plan_route_permit_charge_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_id` (`vendor_id`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_itinerary_plan_route_permit_charge_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_itinerary_plan_route_permit_charge_source_state_id` (`source_state_id`),
  KEY `idx_itinerary_plan_route_permit_charge_destination_state_id` (`destination_state_id`),
  KEY `idx_itinerary_plan_route_permit_charge_permit_cost` (`permit_cost`),
  KEY `idx_itinerary_plan_route_permit_charge_createdon` (`createdon`),
  KEY `idx_itinerary_plan_route_permit_charge_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_route_permit_charge_status` (`status`),
  KEY `idx_itinerary_plan_route_permit_charge_deleted` (`deleted`),
  KEY `idx_itinerary_plan_route_permit_charge_createdby` (`createdby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_vehicle_details` (
  `vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_count` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_details_ID`),
  KEY `idx_itinerary_plan_vehicle_details_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_itinerary_plan_vehicle_details_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_itinerary_plan_vehicle_details_vehicle_count` (`vehicle_count`),
  KEY `idx_itinerary_plan_vehicle_details_createdby` (`createdby`),
  KEY `idx_itinerary_plan_vehicle_details_createdon` (`createdon`),
  KEY `idx_itinerary_plan_vehicle_details_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_vehicle_details_status` (`status`),
  KEY `idx_itinerary_plan_vehicle_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_vendor_eligible_list`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_vendor_eligible_list`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_vendor_eligible_list` (
  `itinerary_plan_vendor_eligible_ID` int NOT NULL AUTO_INCREMENT,
  `itineary_plan_assigned_status` int NOT NULL DEFAULT '0' COMMENT '0 - Not Selected | 1 - Selected',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `total_vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `outstation_allowed_km_per_day` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_orign` text COLLATE utf8mb4_general_ci,
  `vehicle_count` int NOT NULL DEFAULT '0',
  `total_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_outstation_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_rental_charges` float NOT NULL DEFAULT '0',
  `total_toll_charges` float NOT NULL DEFAULT '0',
  `total_parking_charges` float NOT NULL DEFAULT '0',
  `total_driver_charges` float NOT NULL DEFAULT '0',
  `total_permit_charges` float NOT NULL DEFAULT '0',
  `total_before_6_am_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_after_8_pm_extra_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `total_after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `extra_km_rate` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'Common for Local / Outstation',
  `total_allowed_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Allowed KM',
  `total_extra_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT 'For Outstation Extra KM',
  `total_extra_kms_charge` float NOT NULL DEFAULT '0' COMMENT 'For Outstation Extra KM Charges',
  `total_allowed_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_extra_local_kms_charge` float NOT NULL DEFAULT '0',
  `vehicle_gst_type` int NOT NULL DEFAULT '0',
  `vehicle_gst_percentage` float NOT NULL DEFAULT '0',
  `vehicle_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_total_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_type` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `vendor_margin_amount` float NOT NULL DEFAULT '0',
  `vendor_margin_gst_amount` float NOT NULL DEFAULT '0',
  `vehicle_grand_total` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_plan_vendor_eligible_ID`),
  KEY `idx_itinerary_plan_vendor_eligible_list_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_id` (`vendor_id`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_orign` (`vehicle_orign`(768)),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_count` (`vehicle_count`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_kms` (`total_kms`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_outstation_km` (`total_outstation_km`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_time` (`total_time`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_rental_charges` (`total_rental_charges`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_toll_charges` (`total_toll_charges`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_parking_charges` (`total_parking_charges`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_driver_charges` (`total_driver_charges`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_permit_charges` (`total_permit_charges`),
  KEY `idx_itinerary_plan_vendor_elig_lt_tot_before_6_am_extra_tm` (`total_before_6_am_extra_time`),
  KEY `idx_itinerary_plan_vendor_elig_lt_tot_after_8_pm_extra_tm` (`total_after_8_pm_extra_time`),
  KEY `idx_itinerary_plan_vendor_eligible_list_extra_km_rate` (`extra_km_rate`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_allowed_kms` (`total_allowed_kms`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_extra_kms` (`total_extra_kms`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_extra_kms_charge` (`total_extra_kms_charge`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_allowed_local_kms` (`total_allowed_local_kms`),
  KEY `idx_itinerary_plan_vendor_eligible_list_total_extra_local_kms` (`total_extra_local_kms`),
  KEY `idx_itinerary_plan_vendor_elig_lt_tot_extra_local_kms_charge` (`total_extra_local_kms_charge`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_gst_type` (`vehicle_gst_type`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_gst_percentage` (`vehicle_gst_percentage`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_gst_amount` (`vehicle_gst_amount`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_total_amount` (`vehicle_total_amount`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_margin_percentage` (`vendor_margin_percentage`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_margin_gst_type` (`vendor_margin_gst_type`),
  KEY `idx_itinerary_plan_vendor_elig_lt_vendor_margin_gst_perc` (`vendor_margin_gst_percentage`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_margin_amount` (`vendor_margin_amount`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vendor_margin_gst_amount` (`vendor_margin_gst_amount`),
  KEY `idx_itinerary_plan_vendor_eligible_list_vehicle_grand_total` (`vehicle_grand_total`),
  KEY `idx_itinerary_plan_vendor_eligible_list_createdby` (`createdby`),
  KEY `idx_itinerary_plan_vendor_eligible_list_createdon` (`createdon`),
  KEY `idx_itinerary_plan_vendor_eligible_list_updatedon` (`updatedon`),
  KEY `idx_itinerary_plan_vendor_eligible_list_status` (`status`),
  KEY `idx_itinerary_plan_vendor_eligible_list_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_plan_vendor_vehicle_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_plan_vendor_vehicle_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_plan_vendor_vehicle_details` (
  `itinerary_plan_vendor_vehicle_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_vendor_eligible_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL,
  `itinerary_route_id` int NOT NULL,
  `itinerary_route_date` date DEFAULT NULL,
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_qty` int NOT NULL DEFAULT '0',
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `time_limit_id` int NOT NULL DEFAULT '0',
  `kms_limit_id` int NOT NULL DEFAULT '0',
  `travel_type` int NOT NULL DEFAULT '0' COMMENT '1 - Local Trip | 2 - Outstation Trip',
  `itinerary_route_location_from` text COLLATE utf8mb4_general_ci,
  `itinerary_route_location_to` text COLLATE utf8mb4_general_ci,
  `total_running_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_running_time` time DEFAULT NULL,
  `total_siteseeing_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_siteseeing_time` time DEFAULT NULL,
  `total_pickup_km` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `total_pickup_duration` time DEFAULT NULL,
  `total_drop_km` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '0',
  `total_drop_duration` time DEFAULT NULL,
  `total_extra_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extra_km_rate` float NOT NULL DEFAULT '0',
  `total_extra_km_charges` float NOT NULL DEFAULT '0',
  `total_travelled_km` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_travelled_time` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_rental_charges` float NOT NULL DEFAULT '0',
  `vehicle_toll_charges` float NOT NULL DEFAULT '0',
  `vehicle_parking_charges` float NOT NULL DEFAULT '0',
  `vehicle_driver_charges` float NOT NULL DEFAULT '0',
  `vehicle_permit_charges` float NOT NULL DEFAULT '0',
  `before_6_am_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `after_8_pm_extra_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `before_6_am_charges_for_driver` float NOT NULL DEFAULT '0',
  `before_6_am_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_driver` float NOT NULL DEFAULT '0',
  `after_8_pm_charges_for_vehicle` float NOT NULL DEFAULT '0',
  `total_vehicle_amount` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_plan_vendor_vehicle_details_ID`),
  KEY `idx_iti_pln_vr_vh_dls_itinerary_plan_id` (`itinerary_plan_id`),
  KEY `idx_iti_pln_vr_vh_dls_itinerary_route_id` (`itinerary_route_id`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_iti_pln_vr_vh_dls_vendor_id` (`vendor_id`),
  KEY `idx_iti_pln_vr_vh_dls_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_id` (`vehicle_id`),
  KEY `idx_iti_pln_vr_vh_dls_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_iti_pln_vr_vh_dls_time_limit_id` (`time_limit_id`),
  KEY `idx_iti_pln_vr_vh_dls_kms_limit_id` (`kms_limit_id`),
  KEY `idx_iti_pln_vr_vh_dls_travel_type` (`travel_type`),
  KEY `idx_iti_pln_vr_vh_dls_itinerary_route_location_from` (`itinerary_route_location_from`(768)),
  KEY `idx_iti_pln_vr_vh_dls_itinerary_route_location_to` (`itinerary_route_location_to`(768)),
  KEY `idx_iti_pln_vr_vh_dls_total_running_km` (`total_running_km`),
  KEY `idx_iti_pln_vr_vh_dls_total_running_time` (`total_running_time`),
  KEY `idx_iti_pln_vr_vh_dls_total_siteseeing_km` (`total_siteseeing_km`),
  KEY `idx_iti_pln_vr_vh_dls_total_siteseeing_time` (`total_siteseeing_time`),
  KEY `idx_iti_pln_vr_vh_dls_total_pickup_km` (`total_pickup_km`),
  KEY `idx_iti_pln_vr_vh_dls_total_pickup_duration` (`total_pickup_duration`),
  KEY `idx_iti_pln_vr_vh_dls_total_drop_km` (`total_drop_km`),
  KEY `idx_iti_pln_vr_vh_dls_total_drop_duration` (`total_drop_duration`),
  KEY `idx_iti_pln_vr_vh_dls_total_extra_km` (`total_extra_km`),
  KEY `idx_iti_pln_vr_vh_dls_extra_km_rate` (`extra_km_rate`),
  KEY `idx_iti_pln_vr_vh_dls_total_extra_km_charges` (`total_extra_km_charges`),
  KEY `idx_iti_pln_vr_vh_dls_total_travelled_km` (`total_travelled_km`),
  KEY `idx_iti_pln_vr_vh_dls_total_travelled_time` (`total_travelled_time`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_rental_charges` (`vehicle_rental_charges`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_toll_charges` (`vehicle_toll_charges`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_parking_charges` (`vehicle_parking_charges`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_driver_charges` (`vehicle_driver_charges`),
  KEY `idx_iti_pln_vr_vh_dls_vehicle_permit_charges` (`vehicle_permit_charges`),
  KEY `idx_iti_pln_vr_vh_dls_before_6_am_extra_time` (`before_6_am_extra_time`),
  KEY `idx_iti_pln_vr_vh_dls_after_8_pm_extra_time` (`after_8_pm_extra_time`),
  KEY `idx_iti_pln_vr_vh_dls_before_6_am_charges_for_driver` (`before_6_am_charges_for_driver`),
  KEY `idx_iti_pln_vr_vh_dls_before_6_am_charges_for_vehicle` (`before_6_am_charges_for_vehicle`),
  KEY `idx_iti_pln_vr_vh_dls_after_8_pm_charges_for_driver` (`after_8_pm_charges_for_driver`),
  KEY `idx_iti_pln_vr_vh_dls_after_8_pm_charges_for_vehicle` (`after_8_pm_charges_for_vehicle`),
  KEY `idx_iti_pln_vr_vh_dls_total_vehicle_amount` (`total_vehicle_amount`),
  KEY `idx_iti_pln_vr_vh_dls_createdby` (`createdby`),
  KEY `idx_iti_pln_vr_vh_dls_createdon` (`createdon`),
  KEY `idx_iti_pln_vr_vh_dls_updatedon` (`updatedon`),
  KEY `idx_iti_pln_vr_vh_dls_status` (`status`),
  KEY `idx_iti_pln_vr_vh_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_activity_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_activity_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_activity_details` (
  `route_activity_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `route_hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `activity_order` int NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_foreign_infant` float NOT NULL DEFAULT '0',
  `activity_charges_for_adult` float NOT NULL DEFAULT '0',
  `activity_charges_for_children` float NOT NULL DEFAULT '0',
  `activity_charges_for_infant` float NOT NULL DEFAULT '0',
  `activity_amout` float NOT NULL DEFAULT '0',
  `activity_traveling_time` time DEFAULT NULL,
  `activity_start_time` time DEFAULT NULL,
  `activity_end_time` time DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`route_activity_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_itinerary_plan_id` (`itinerary_plan_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_route_hotspot_ID` (`route_hotspot_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_hotspot_ID` (`hotspot_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_ID` (`activity_ID`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_order` (`activity_order`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_foreign_adult` (`activity_charges_for_foreign_adult`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_foreign_children` (`activity_charges_for_foreign_children`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_foreign_infant` (`activity_charges_for_foreign_infant`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_adult` (`activity_charges_for_adult`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_children` (`activity_charges_for_children`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_charges_for_infant` (`activity_charges_for_infant`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_amout` (`activity_amout`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_traveling_time` (`activity_traveling_time`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_start_time` (`activity_start_time`),
  KEY `idx_dvi_iti_rt_acti_dls_activity_end_time` (`activity_end_time`),
  KEY `idx_dvi_iti_rt_acti_dls_createdby` (`createdby`),
  KEY `idx_dvi_iti_rt_acti_dls_createdon` (`createdon`),
  KEY `idx_dvi_iti_rt_acti_dls_updatedon` (`updatedon`),
  KEY `idx_dvi_iti_rt_acti_dls_status` (`status`),
  KEY `idx_dvi_iti_rt_acti_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_activity_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_activity_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_activity_entry_cost_details` (
  `activity_cost_detail_id` int NOT NULL AUTO_INCREMENT,
  `route_activity_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `activity_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`activity_cost_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_details` (
  `itinerary_route_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `location_id` bigint NOT NULL DEFAULT '0',
  `location_name` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `itinerary_route_date` date DEFAULT NULL,
  `no_of_days` int NOT NULL DEFAULT '0',
  `no_of_km` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direct_to_next_visiting_place` int NOT NULL DEFAULT '0',
  `next_visiting_location` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `route_start_time` time DEFAULT NULL,
  `route_end_time` time DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_route_ID`),
  KEY `idx_dvi_itinerary_route_details_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_itinerary_route_details_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_itinerary_route_details_location_id` (`location_id`),
  KEY `idx_dvi_itinerary_route_details_location_name` (`location_name`),
  KEY `idx_dvi_itinerary_route_details_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_dvi_itinerary_route_details_no_of_days` (`no_of_days`),
  KEY `idx_dvi_itinerary_route_details_no_of_km` (`no_of_km`),
  KEY `idx_dvi_itinerary_route_details_direct_to_next_visiting_place` (`direct_to_next_visiting_place`),
  KEY `idx_dvi_itinerary_route_details_next_visiting_location` (`next_visiting_location`),
  KEY `idx_dvi_itinerary_route_details_route_start_time` (`route_start_time`),
  KEY `idx_dvi_itinerary_route_details_route_end_time` (`route_end_time`),
  KEY `idx_dvi_itinerary_route_details_createdby` (`createdby`),
  KEY `idx_dvi_itinerary_route_details_createdon` (`createdon`),
  KEY `idx_dvi_itinerary_route_details_updatedon` (`updatedon`),
  KEY `idx_dvi_itinerary_route_details_status` (`status`),
  KEY `idx_dvi_itinerary_route_details_deleted` (`deleted`),
  KEY `idx_route_plan` (`itinerary_plan_ID`,`itinerary_route_date`),
  KEY `idx_route_location` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_guide_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_guide_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_guide_details` (
  `route_guide_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary,\r\n2 - Day Wise',
  `guide_language` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_slot` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_cost` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`route_guide_ID`),
  KEY `idx_dvi_itinerary_route_guide_details_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_itinerary_route_guide_details_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_itinerary_route_guide_details_guide_id` (`guide_id`),
  KEY `idx_dvi_itinerary_route_guide_details_guide_type` (`guide_type`),
  KEY `idx_dvi_itinerary_route_guide_details_guide_language` (`guide_language`),
  KEY `idx_dvi_itinerary_route_guide_details_guide_slot` (`guide_slot`),
  KEY `idx_dvi_itinerary_route_guide_details_guide_cost` (`guide_cost`),
  KEY `idx_dvi_itinerary_route_guide_details_createdby` (`createdby`),
  KEY `idx_dvi_itinerary_route_guide_details_createdon` (`createdon`),
  KEY `idx_dvi_itinerary_route_guide_details_updatedon` (`updatedon`),
  KEY `idx_dvi_itinerary_route_guide_details_status` (`status`),
  KEY `idx_dvi_itinerary_route_guide_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_guide_slot_cost_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_guide_slot_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_guide_slot_cost_details` (
  `guide_slot_cost_details_id` int NOT NULL AUTO_INCREMENT,
  `route_guide_id` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `guide_id` int NOT NULL DEFAULT '0',
  `guide_type` int NOT NULL DEFAULT '0' COMMENT '1 - Itinerary, 2 - Day Wise',
  `guide_slot` int NOT NULL DEFAULT '0' COMMENT '0 - All Slots | 1 - Slot 1 | 2 - Slot 2 | 3 - Slot 3',
  `guide_slot_cost` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`guide_slot_cost_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_hotspot_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_hotspot_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_hotspot_details` (
  `route_hotspot_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `item_type` int NOT NULL DEFAULT '0' COMMENT '1 - Refreshment | 2 - Direct Destination Traveling | 3 - Site Seeing Traveling | 4 - Hotspots | 5 - Traveling to Hotel Location | 6 - Return to Hotel | 7 - Return to Departure Location\r\n',
  `hotspot_order` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `hotspot_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_adult_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_child_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_foreign_infant_entry_cost` float NOT NULL DEFAULT '0',
  `hotspot_amout` float NOT NULL DEFAULT '0',
  `hotspot_traveling_time` time NOT NULL DEFAULT '00:00:00',
  `itinerary_travel_type_buffer_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_travelling_distance` text COLLATE utf8mb4_general_ci,
  `hotspot_start_time` time NOT NULL DEFAULT '00:00:00',
  `hotspot_end_time` time NOT NULL DEFAULT '00:00:00',
  `allow_break_hours` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `allow_via_route` int NOT NULL DEFAULT '0' COMMENT '0 - No | 1 - Yes',
  `via_location_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotspot_plan_own_way` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`route_hotspot_ID`),
  KEY `idx_dvi_iti_rt_hotspot_dls_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_iti_rt_hotspot_dls_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_iti_rt_hotspot_dls_item_type` (`item_type`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_order` (`hotspot_order`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_ID` (`hotspot_ID`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_adult_entry_cost` (`hotspot_adult_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_child_entry_cost` (`hotspot_child_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_infant_entry_cost` (`hotspot_infant_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_foreign_adult_entry_cost` (`hotspot_foreign_adult_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_foreign_child_entry_cost` (`hotspot_foreign_child_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_foreign_infant_entry_cost` (`hotspot_foreign_infant_entry_cost`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_amout` (`hotspot_amout`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_traveling_time` (`hotspot_traveling_time`),
  KEY `idx_dvi_iti_rt_hotspot_dls_itinerary_travel_type_buffer_time` (`itinerary_travel_type_buffer_time`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_travelling_distance` (`hotspot_travelling_distance`(768)),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_start_time` (`hotspot_start_time`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_end_time` (`hotspot_end_time`),
  KEY `idx_dvi_iti_rt_hotspot_dls_allow_break_hours` (`allow_break_hours`),
  KEY `idx_dvi_iti_rt_hotspot_dls_allow_via_route` (`allow_via_route`),
  KEY `idx_dvi_iti_rt_hotspot_dls_via_location_name` (`via_location_name`),
  KEY `idx_dvi_iti_rt_hotspot_dls_hotspot_plan_own_way` (`hotspot_plan_own_way`),
  KEY `idx_dvi_iti_rt_hotspot_dls_createdby` (`createdby`),
  KEY `idx_dvi_iti_rt_hotspot_dls_createdon` (`createdon`),
  KEY `idx_dvi_iti_rt_hotspot_dls_updatedon` (`updatedon`),
  KEY `idx_dvi_iti_rt_hotspot_dls_status` (`status`),
  KEY `idx_dvi_iti_rt_hotspot_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_hotspot_entry_cost_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_hotspot_entry_cost_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_hotspot_entry_cost_details` (
  `hotspot_cost_detail_id` int NOT NULL AUTO_INCREMENT,
  `route_hotspot_id` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_id` int NOT NULL DEFAULT '0',
  `itinerary_route_id` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `traveller_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `entry_ticket_cost` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`hotspot_cost_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_route_hotspot_parking_charge`
--

DROP TABLE IF EXISTS `dvi_itinerary_route_hotspot_parking_charge`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_route_hotspot_parking_charge` (
  `itinerary_hotspot_parking_charge_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `hotspot_ID` int NOT NULL DEFAULT '0',
  `vehicle_type` int NOT NULL DEFAULT '0',
  `vehicle_qty` int NOT NULL DEFAULT '0',
  `parking_charges_amt` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_hotspot_parking_charge_ID`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_hotspot_ID` (`hotspot_ID`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_vehicle_type` (`vehicle_type`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_vehicle_qty` (`vehicle_qty`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_parking_charges_amt` (`parking_charges_amt`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_createdby` (`createdby`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_createdon` (`createdon`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_updatedon` (`updatedon`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_status` (`status`),
  KEY `idx_dvi_iti_rt_hotspot_parking_charge_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_traveller_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_traveller_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_traveller_details` (
  `traveller_details_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `traveller_type` int NOT NULL DEFAULT '0' COMMENT '1 - Adult | 2 - Children | 3- Infant',
  `room_id` int DEFAULT '0',
  `traveller_age` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `child_bed_type` int NOT NULL DEFAULT '0' COMMENT '1 - Without Bed | 2 - With Bed',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`traveller_details_ID`),
  KEY `idx_dvi_itinerary_traveller_details_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_itinerary_traveller_details_traveller_type` (`traveller_type`),
  KEY `idx_dvi_itinerary_traveller_details_room_id` (`room_id`),
  KEY `idx_dvi_itinerary_traveller_details_traveller_age` (`traveller_age`),
  KEY `idx_dvi_itinerary_traveller_details_child_bed_type` (`child_bed_type`),
  KEY `idx_dvi_itinerary_traveller_details_createdby` (`createdby`),
  KEY `idx_dvi_itinerary_traveller_details_createdon` (`createdon`),
  KEY `idx_dvi_itinerary_traveller_details_updatedon` (`updatedon`),
  KEY `idx_dvi_itinerary_traveller_details_status` (`status`),
  KEY `idx_dvi_itinerary_traveller_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_itinerary_via_route_details`
--

DROP TABLE IF EXISTS `dvi_itinerary_via_route_details`;
CREATE TABLE IF NOT EXISTS `dvi_itinerary_via_route_details` (
  `itinerary_via_route_ID` int NOT NULL AUTO_INCREMENT,
  `itinerary_route_ID` int NOT NULL DEFAULT '0',
  `itinerary_plan_ID` int NOT NULL DEFAULT '0',
  `itinerary_route_date` date DEFAULT NULL,
  `source_location` text COLLATE utf8mb4_general_ci,
  `destination_location` text COLLATE utf8mb4_general_ci,
  `itinerary_via_location_ID` int NOT NULL DEFAULT '0',
  `itinerary_via_location_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `itinerary_session_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`itinerary_via_route_ID`),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_route_ID` (`itinerary_route_ID`),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_plan_ID` (`itinerary_plan_ID`),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_route_date` (`itinerary_route_date`),
  KEY `idx_dvi_iti_via_rt_dls_source_location` (`source_location`(768)),
  KEY `idx_dvi_iti_via_rt_dls_destination_location` (`destination_location`(768)),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_via_location_ID` (`itinerary_via_location_ID`),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_via_location_name` (`itinerary_via_location_name`(768)),
  KEY `idx_dvi_iti_via_rt_dls_itinerary_session_id` (`itinerary_session_id`),
  KEY `idx_dvi_iti_via_rt_dls_createdby` (`createdby`),
  KEY `idx_dvi_iti_via_rt_dls_createdon` (`createdon`),
  KEY `idx_dvi_iti_via_rt_dls_updatedon` (`updatedon`),
  KEY `idx_dvi_iti_via_rt_dls_status` (`status`),
  KEY `idx_dvi_iti_via_rt_dls_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_kms_limit`
--

DROP TABLE IF EXISTS `dvi_kms_limit`;
CREATE TABLE IF NOT EXISTS `dvi_kms_limit` (
  `kms_limit_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `kms_limit_title` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kms_limit` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`kms_limit_id`),
  KEY `idx_dvi_kms_limit_vendor_id` (`vendor_id`),
  KEY `idx_dvi_kms_limit_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_dvi_kms_limit_kms_limit_title` (`kms_limit_title`),
  KEY `idx_dvi_kms_limit_kms_limit` (`kms_limit`),
  KEY `idx_dvi_kms_limit_createdby` (`createdby`),
  KEY `idx_dvi_kms_limit_createdon` (`createdon`),
  KEY `idx_dvi_kms_limit_updatedon` (`updatedon`),
  KEY `idx_dvi_kms_limit_status` (`status`),
  KEY `idx_dvi_kms_limit_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_language`
--

DROP TABLE IF EXISTS `dvi_language`;
CREATE TABLE IF NOT EXISTS `dvi_language` (
  `language_id` int NOT NULL AUTO_INCREMENT,
  `language` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`),
  KEY `idx_dvi_language_language` (`language`),
  KEY `idx_dvi_language_createdby` (`createdby`),
  KEY `idx_dvi_language_createdon` (`createdon`),
  KEY `idx_dvi_language_updatedon` (`updatedon`),
  KEY `idx_dvi_language_status` (`status`),
  KEY `idx_dvi_language_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_pagemenu`
--

DROP TABLE IF EXISTS `dvi_pagemenu`;
CREATE TABLE IF NOT EXISTS `dvi_pagemenu` (
  `page_menu_id` int NOT NULL AUTO_INCREMENT,
  `page_title` text COLLATE utf8mb4_general_ci,
  `page_name` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_menu_id`),
  KEY `idx_dvi_pagemenu_page_title` (`page_title`(768)),
  KEY `idx_dvi_pagemenu_page_name` (`page_name`(768)),
  KEY `idx_dvi_pagemenu_createdby` (`createdby`),
  KEY `idx_dvi_pagemenu_createdon` (`createdon`),
  KEY `idx_dvi_pagemenu_updatedon` (`updatedon`),
  KEY `idx_dvi_pagemenu_status` (`status`),
  KEY `idx_dvi_pagemenu_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_permit_cost`
--

DROP TABLE IF EXISTS `dvi_permit_cost`;
CREATE TABLE IF NOT EXISTS `dvi_permit_cost` (
  `permit_cost_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `source_state_id` int NOT NULL DEFAULT '0',
  `destination_state_id` int NOT NULL DEFAULT '0',
  `permit_cost` float NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`permit_cost_id`),
  KEY `idx_dvi_permit_cost_vendor_id` (`vendor_id`),
  KEY `idx_dvi_permit_cost_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_permit_cost_source_state_id` (`source_state_id`),
  KEY `idx_dvi_permit_cost_destination_state_id` (`destination_state_id`),
  KEY `idx_dvi_permit_cost_createdby` (`createdby`),
  KEY `idx_dvi_permit_cost_createdon` (`createdon`),
  KEY `idx_dvi_permit_cost_updatedon` (`updatedon`),
  KEY `idx_dvi_permit_cost_status` (`status`),
  KEY `idx_dvi_permit_cost_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_permit_state`
--

DROP TABLE IF EXISTS `dvi_permit_state`;
CREATE TABLE IF NOT EXISTS `dvi_permit_state` (
  `permit_state_id` int NOT NULL AUTO_INCREMENT,
  `state_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `state_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`permit_state_id`),
  KEY `idx_dvi_permit_state_state_name` (`state_name`),
  KEY `idx_dvi_permit_state_state_code` (`state_code`),
  KEY `idx_dvi_permit_state_createdby` (`createdby`),
  KEY `idx_dvi_permit_state_createdon` (`createdon`),
  KEY `idx_dvi_permit_state_updatedon` (`updatedon`),
  KEY `idx_dvi_permit_state_status` (`status`),
  KEY `idx_dvi_permit_state_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_pwd_activate_log`
--

DROP TABLE IF EXISTS `dvi_pwd_activate_log`;
CREATE TABLE IF NOT EXISTS `dvi_pwd_activate_log` (
  `pwd_reset_ID` int NOT NULL AUTO_INCREMENT,
  `email_ID` text COLLATE utf8mb4_general_ci,
  `userID` int NOT NULL DEFAULT '0',
  `agent_ID` int NOT NULL DEFAULT '0',
  `reset_key` text COLLATE utf8mb4_general_ci,
  `expiry_date` datetime DEFAULT NULL,
  `createdby` bigint NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`pwd_reset_ID`),
  KEY `idx_dvi_pwd_activate_log_email_ID` (`email_ID`(768)),
  KEY `idx_dvi_pwd_activate_log_userID` (`userID`),
  KEY `idx_dvi_pwd_activate_log_agent_ID` (`agent_ID`),
  KEY `idx_dvi_pwd_activate_log_reset_key` (`reset_key`(768)),
  KEY `idx_dvi_pwd_activate_log_expiry_date` (`expiry_date`),
  KEY `idx_dvi_pwd_activate_log_createdby` (`createdby`),
  KEY `idx_dvi_pwd_activate_log_createdon` (`createdon`),
  KEY `idx_dvi_pwd_activate_log_updatedon` (`updatedon`),
  KEY `idx_dvi_pwd_activate_log_status` (`status`),
  KEY `idx_dvi_pwd_activate_log_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_pwd_reset_log`
--

DROP TABLE IF EXISTS `dvi_pwd_reset_log`;
CREATE TABLE IF NOT EXISTS `dvi_pwd_reset_log` (
  `pwd_reset_ID` int NOT NULL AUTO_INCREMENT,
  `email_ID` text COLLATE utf8mb4_general_ci,
  `userID` int NOT NULL DEFAULT '0',
  `guide_ID` int NOT NULL DEFAULT '0',
  `vendor_ID` int NOT NULL DEFAULT '0',
  `staff_ID` int NOT NULL DEFAULT '0',
  `agent_ID` int NOT NULL DEFAULT '0',
  `reset_key` text COLLATE utf8mb4_general_ci,
  `expiry_date` datetime DEFAULT NULL,
  `createdby` bigint NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`pwd_reset_ID`),
  KEY `idx_dvi_pwd_reset_log_email_ID` (`email_ID`(768)),
  KEY `idx_dvi_pwd_reset_log_userID` (`userID`),
  KEY `idx_dvi_pwd_reset_log_guide_ID` (`guide_ID`),
  KEY `idx_dvi_pwd_reset_log_vendor_ID` (`vendor_ID`),
  KEY `idx_dvi_pwd_reset_log_staff_ID` (`staff_ID`),
  KEY `idx_dvi_pwd_reset_log_agent_ID` (`agent_ID`),
  KEY `idx_dvi_pwd_reset_log_reset_key` (`reset_key`(768)),
  KEY `idx_dvi_pwd_reset_log_expiry_date` (`expiry_date`),
  KEY `idx_dvi_pwd_reset_log_createdby` (`createdby`),
  KEY `idx_dvi_pwd_reset_log_createdon` (`createdon`),
  KEY `idx_dvi_pwd_reset_log_updatedon` (`updatedon`),
  KEY `idx_dvi_pwd_reset_log_status` (`status`),
  KEY `idx_dvi_pwd_reset_log_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_rolemenu`
--

DROP TABLE IF EXISTS `dvi_rolemenu`;
CREATE TABLE IF NOT EXISTS `dvi_rolemenu` (
  `role_ID` int NOT NULL AUTO_INCREMENT,
  `role_name` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_ID`),
  KEY `idx_dvi_rolemenu_role_name` (`role_name`(768)),
  KEY `idx_dvi_rolemenu_createdby` (`createdby`),
  KEY `idx_dvi_rolemenu_createdon` (`createdon`),
  KEY `idx_dvi_rolemenu_updatedon` (`updatedon`),
  KEY `idx_dvi_rolemenu_status` (`status`),
  KEY `idx_dvi_rolemenu_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_role_access`
--

DROP TABLE IF EXISTS `dvi_role_access`;
CREATE TABLE IF NOT EXISTS `dvi_role_access` (
  `role_access_ID` int NOT NULL AUTO_INCREMENT,
  `role_ID` int NOT NULL,
  `page_menu_id` int NOT NULL,
  `role_name` text COLLATE utf8mb4_general_ci,
  `read_access` int NOT NULL DEFAULT '0' COMMENT '0 - not allowed | 1 -  allowed',
  `write_access` int NOT NULL DEFAULT '0' COMMENT '0 - not allowed | 1 - allowed',
  `modify_access` int NOT NULL DEFAULT '0' COMMENT '0 - not allowed | 1 - allowed',
  `full_access` int NOT NULL DEFAULT '0' COMMENT '0 - not allowed | 1 - allowed',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_access_ID`),
  KEY `idx_dvi_role_access_role_ID` (`role_ID`),
  KEY `idx_dvi_role_access_page_menu_id` (`page_menu_id`),
  KEY `idx_dvi_role_access_role_name` (`role_name`(768)),
  KEY `idx_dvi_role_access_createdby` (`createdby`),
  KEY `idx_dvi_role_access_createdon` (`createdon`),
  KEY `idx_dvi_role_access_updatedon` (`updatedon`),
  KEY `idx_dvi_role_access_status` (`status`),
  KEY `idx_dvi_role_access_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_staff`
--

DROP TABLE IF EXISTS `dvi_staff`;
CREATE TABLE IF NOT EXISTS `dvi_staff` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int DEFAULT NULL,
  `staff_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_email` text COLLATE utf8mb4_general_ci,
  `staff_mobile_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`),
  KEY `idx_dvi_staff_vendor_id` (`vendor_id`),
  KEY `idx_dvi_staff_staff_name` (`staff_name`),
  KEY `idx_dvi_staff_staff_email` (`staff_email`(768)),
  KEY `idx_dvi_staff_staff_mobile_number` (`staff_mobile_number`),
  KEY `idx_dvi_staff_createdby` (`createdby`),
  KEY `idx_dvi_staff_createdon` (`createdon`),
  KEY `idx_dvi_staff_updatedon` (`updatedon`),
  KEY `idx_dvi_staff_status` (`status`),
  KEY `idx_dvi_staff_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_staff_details`
--

DROP TABLE IF EXISTS `dvi_staff_details`;
CREATE TABLE IF NOT EXISTS `dvi_staff_details` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL DEFAULT '0',
  `staff_name` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_email` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roleID` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`staff_id`),
  KEY `idx_dvi_staff_details_agent_id` (`agent_id`),
  KEY `idx_dvi_staff_details_staff_name` (`staff_name`),
  KEY `idx_dvi_staff_details_staff_mobile` (`staff_mobile`),
  KEY `idx_dvi_staff_details_staff_email` (`staff_email`),
  KEY `idx_dvi_staff_details_roleID` (`roleID`),
  KEY `idx_dvi_staff_details_createdby` (`createdby`),
  KEY `idx_dvi_staff_details_createdon` (`createdon`),
  KEY `idx_dvi_staff_details_updatedon` (`updatedon`),
  KEY `idx_dvi_staff_details_status` (`status`),
  KEY `idx_dvi_staff_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_states`
--

DROP TABLE IF EXISTS `dvi_states`;
CREATE TABLE IF NOT EXISTS `dvi_states` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_onground_support_number` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_escalation_call_number` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country_id` int NOT NULL DEFAULT '1',
  `createdby` int NOT NULL DEFAULT '0',
  `updatedon` datetime DEFAULT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_dvi_states_country_id` (`country_id`),
  KEY `idx_dvi_states_name` (`name`),
  KEY `idx_dvi_states_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_stored_locations`
--

DROP TABLE IF EXISTS `dvi_stored_locations`;
CREATE TABLE IF NOT EXISTS `dvi_stored_locations` (
  `location_ID` bigint NOT NULL AUTO_INCREMENT,
  `source_location` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_lattitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_longitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_city` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_state` longtext COLLATE utf8mb4_general_ci,
  `destination_location` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_lattitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_longitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_city` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_state` longtext COLLATE utf8mb4_general_ci,
  `distance` double NOT NULL DEFAULT '0',
  `duration` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_general_ci,
  `created_from` int NOT NULL DEFAULT '0' COMMENT '0 - Using API | 1 - Manual',
  `createdby` bigint NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_ID`),
  KEY `source_location_2` (`source_location`(768)),
  KEY `idx_stored_locations_status` (`status`),
  KEY `idx_stored_locations_deleted` (`deleted`),
  KEY `idx_main_source_location` (`source_location`(768)),
  KEY `idx_main_destination_location` (`destination_location`(768)),
  KEY `idx_dvi_stored_locations_source_location` (`source_location`(768)),
  KEY `idx_dvi_stored_locations_source_location_lattitude` (`source_location_lattitude`(768)),
  KEY `idx_dvi_stored_locations_source_location_longitude` (`source_location_longitude`(768)),
  KEY `idx_dvi_stored_locations_source_location_city` (`source_location_city`(768)),
  KEY `idx_dvi_stored_locations_source_location_state` (`source_location_state`(768)),
  KEY `idx_dvi_stored_locations_destination_location` (`destination_location`(768)),
  KEY `idx_dvi_stored_locations_destination_location_lattitude` (`destination_location_lattitude`(768)),
  KEY `idx_dvi_stored_locations_destination_location_longitude` (`destination_location_longitude`(768)),
  KEY `idx_dvi_stored_locations_destination_location_city` (`destination_location_city`(768)),
  KEY `idx_dvi_stored_locations_destination_location_state` (`destination_location_state`(768)),
  KEY `idx_dvi_stored_locations_distance` (`distance`),
  KEY `idx_dvi_stored_locations_duration` (`duration`),
  KEY `idx_dvi_stored_locations_location_description` (`location_description`(768)),
  KEY `idx_dvi_stored_locations_created_from` (`created_from`),
  KEY `idx_dvi_stored_locations_createdby` (`createdby`),
  KEY `idx_dvi_stored_locations_createdon` (`createdon`),
  KEY `idx_dvi_stored_locations_updatedon` (`updatedon`),
  KEY `idx_dvi_stored_locations_status` (`status`),
  KEY `idx_dvi_stored_locations_deleted` (`deleted`),
  KEY `idx_loc_deleted_status` (`deleted`,`status`,`location_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_stored_location_via_routes`
--

DROP TABLE IF EXISTS `dvi_stored_location_via_routes`;
CREATE TABLE IF NOT EXISTS `dvi_stored_location_via_routes` (
  `via_route_location_ID` bigint NOT NULL AUTO_INCREMENT,
  `location_id` bigint NOT NULL DEFAULT '0',
  `via_route_location` longtext COLLATE utf8mb4_general_ci,
  `via_route_location_lattitude` longtext COLLATE utf8mb4_general_ci,
  `via_route_location_longitude` longtext COLLATE utf8mb4_general_ci,
  `via_route_location_state` longtext COLLATE utf8mb4_general_ci,
  `via_route_location_city` longtext COLLATE utf8mb4_general_ci,
  `distance_from_source_to_via_route` longtext COLLATE utf8mb4_general_ci,
  `duration_from_source_to_via_route` longtext COLLATE utf8mb4_general_ci,
  `duration_from_via_route_to_destination` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `distance_from_via_route_to_destination` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_from` int NOT NULL DEFAULT '0' COMMENT '0 - From API | 1 - Manual Entry',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`via_route_location_ID`),
  KEY `idx_dvi_stored_location_via_routes_location_id` (`location_id`),
  KEY `idx_dvi_stored_location_via_routes_via_route_location` (`via_route_location`(768)),
  KEY `idx_dvi_stored_location_via_routes_via_route_location_state` (`via_route_location_state`(768)),
  KEY `idx_dvi_stored_location_via_routes_via_route_location_city` (`via_route_location_city`(768)),
  KEY `idx_dvi_stored_location_via_routes_createdby` (`createdby`),
  KEY `idx_dvi_stored_location_via_routes_createdon` (`createdon`),
  KEY `idx_dvi_stored_location_via_routes_updatedon` (`updatedon`),
  KEY `idx_dvi_stored_location_via_routes_status` (`status`),
  KEY `idx_dvi_stored_location_via_routes_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_stored_routes`
--

DROP TABLE IF EXISTS `dvi_stored_routes`;
CREATE TABLE IF NOT EXISTS `dvi_stored_routes` (
  `stored_route_ID` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL DEFAULT '0',
  `route_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_of_nights` int NOT NULL DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`stored_route_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_stored_route_location_details`
--

DROP TABLE IF EXISTS `dvi_stored_route_location_details`;
CREATE TABLE IF NOT EXISTS `dvi_stored_route_location_details` (
  `stored_route_location_ID` int NOT NULL AUTO_INCREMENT,
  `stored_route_id` int NOT NULL DEFAULT '0',
  `route_location_id` int NOT NULL DEFAULT '0',
  `route_location_name` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`stored_route_location_ID`),
  KEY `idx_dvi_stored_route_location_details_route_location_id` (`route_location_id`),
  KEY `idx_dvi_stored_route_location_details_route_location_name` (`route_location_name`),
  KEY `idx_dvi_stored_route_location_details_createdby` (`createdby`),
  KEY `idx_dvi_stored_route_location_details_createdon` (`createdon`),
  KEY `idx_dvi_stored_route_location_details_updatedon` (`updatedon`),
  KEY `idx_dvi_stored_route_location_details_status` (`status`),
  KEY `idx_dvi_stored_route_location_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_tempcsv`
--

DROP TABLE IF EXISTS `dvi_tempcsv`;
CREATE TABLE IF NOT EXISTS `dvi_tempcsv` (
  `temp_id` int NOT NULL AUTO_INCREMENT,
  `csvtype` int NOT NULL DEFAULT '0' COMMENT '1 - hotel_room | 2 - hotel_amenities | 3 - vehicle_cost',
  `sessionID` text COLLATE utf8mb4_general_ci,
  `field1` text COLLATE utf8mb4_general_ci,
  `field2` text COLLATE utf8mb4_general_ci,
  `field3` text COLLATE utf8mb4_general_ci,
  `field4` text COLLATE utf8mb4_general_ci,
  `field5` text COLLATE utf8mb4_general_ci,
  `field6` text COLLATE utf8mb4_general_ci,
  `field7` text COLLATE utf8mb4_general_ci,
  `field8` text COLLATE utf8mb4_general_ci,
  `field9` text COLLATE utf8mb4_general_ci,
  `field10` text COLLATE utf8mb4_general_ci,
  `field11` text COLLATE utf8mb4_general_ci,
  `field12` text COLLATE utf8mb4_general_ci,
  `field13` text COLLATE utf8mb4_general_ci,
  `field14` text COLLATE utf8mb4_general_ci,
  `field15` text COLLATE utf8mb4_general_ci,
  `field16` text COLLATE utf8mb4_general_ci,
  `field17` text COLLATE utf8mb4_general_ci,
  `field18` text COLLATE utf8mb4_general_ci,
  `field19` text COLLATE utf8mb4_general_ci,
  `field20` text COLLATE utf8mb4_general_ci,
  `field21` text COLLATE utf8mb4_general_ci,
  `field22` text COLLATE utf8mb4_general_ci,
  `field23` text COLLATE utf8mb4_general_ci,
  `field24` text COLLATE utf8mb4_general_ci,
  `field25` text COLLATE utf8mb4_general_ci,
  `field26` text COLLATE utf8mb4_general_ci,
  `field27` text COLLATE utf8mb4_general_ci,
  `field28` text COLLATE utf8mb4_general_ci,
  `field29` text COLLATE utf8mb4_general_ci,
  `field30` text COLLATE utf8mb4_general_ci,
  `field31` text COLLATE utf8mb4_general_ci,
  `field32` text COLLATE utf8mb4_general_ci,
  `field33` text COLLATE utf8mb4_general_ci,
  `field34` text COLLATE utf8mb4_general_ci,
  `field35` text COLLATE utf8mb4_general_ci,
  `field36` text COLLATE utf8mb4_general_ci,
  `field37` text COLLATE utf8mb4_general_ci,
  `field38` text COLLATE utf8mb4_general_ci,
  `field39` text COLLATE utf8mb4_general_ci,
  `field40` text COLLATE utf8mb4_general_ci,
  `available` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0' COMMENT '1-available, 2-imported, 3-not imported',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  PRIMARY KEY (`temp_id`),
  KEY `idx_dvi_tempcsv_csvtype` (`csvtype`),
  KEY `idx_dvi_tempcsv_sessionID` (`sessionID`(768)),
  KEY `idx_dvi_tempcsv_field1` (`field1`(768)),
  KEY `idx_dvi_tempcsv_field2` (`field2`(768)),
  KEY `idx_dvi_tempcsv_field3` (`field3`(768)),
  KEY `idx_dvi_tempcsv_field4` (`field4`(768)),
  KEY `idx_dvi_tempcsv_field5` (`field5`(768)),
  KEY `idx_dvi_tempcsv_field6` (`field6`(768)),
  KEY `idx_dvi_tempcsv_field7` (`field7`(768)),
  KEY `idx_dvi_tempcsv_field8` (`field8`(768)),
  KEY `idx_dvi_tempcsv_field9` (`field9`(768)),
  KEY `idx_dvi_tempcsv_field10` (`field10`(768)),
  KEY `idx_dvi_tempcsv_field11` (`field11`(768)),
  KEY `idx_dvi_tempcsv_field12` (`field12`(768)),
  KEY `idx_dvi_tempcsv_field13` (`field13`(768)),
  KEY `idx_dvi_tempcsv_field14` (`field14`(768)),
  KEY `idx_dvi_tempcsv_field15` (`field15`(768)),
  KEY `idx_dvi_tempcsv_field16` (`field16`(768)),
  KEY `idx_dvi_tempcsv_field17` (`field17`(768)),
  KEY `idx_dvi_tempcsv_field18` (`field18`(768)),
  KEY `idx_dvi_tempcsv_field19` (`field19`(768)),
  KEY `idx_dvi_tempcsv_field20` (`field20`(768)),
  KEY `idx_dvi_tempcsv_field21` (`field21`(768)),
  KEY `idx_dvi_tempcsv_field22` (`field22`(768)),
  KEY `idx_dvi_tempcsv_field23` (`field23`(768)),
  KEY `idx_dvi_tempcsv_field24` (`field24`(768)),
  KEY `idx_dvi_tempcsv_field25` (`field25`(768)),
  KEY `idx_dvi_tempcsv_field26` (`field26`(768)),
  KEY `idx_dvi_tempcsv_field27` (`field27`(768)),
  KEY `idx_dvi_tempcsv_field28` (`field28`(768)),
  KEY `idx_dvi_tempcsv_field29` (`field29`(768)),
  KEY `idx_dvi_tempcsv_field30` (`field30`(768)),
  KEY `idx_dvi_tempcsv_field31` (`field31`(768)),
  KEY `idx_dvi_tempcsv_field32` (`field32`(768)),
  KEY `idx_dvi_tempcsv_field33` (`field33`(768)),
  KEY `idx_dvi_tempcsv_field34` (`field34`(768)),
  KEY `idx_dvi_tempcsv_field35` (`field35`(768)),
  KEY `idx_dvi_tempcsv_field36` (`field36`(768)),
  KEY `idx_dvi_tempcsv_field37` (`field37`(768)),
  KEY `idx_dvi_tempcsv_field38` (`field38`(768)),
  KEY `idx_dvi_tempcsv_field39` (`field39`(768)),
  KEY `idx_dvi_tempcsv_field40` (`field40`(768)),
  KEY `idx_dvi_tempcsv_available` (`available`),
  KEY `idx_dvi_tempcsv_status` (`status`),
  KEY `idx_dvi_tempcsv_createdon` (`createdon`),
  KEY `idx_dvi_tempcsv_updatedon` (`updatedon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_time_limit`
--

DROP TABLE IF EXISTS `dvi_time_limit`;
CREATE TABLE IF NOT EXISTS `dvi_time_limit` (
  `time_limit_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_vehicle_type_id` int NOT NULL DEFAULT '0',
  `time_limit_title` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hours_limit` float DEFAULT '0',
  `km_limit` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`time_limit_id`),
  KEY `idx_dvi_time_limit_vendor_id` (`vendor_id`),
  KEY `idx_dvi_time_limit_vendor_vehicle_type_id` (`vendor_vehicle_type_id`),
  KEY `idx_dvi_time_limit_time_limit_title` (`time_limit_title`),
  KEY `idx_dvi_time_limit_hours_limit` (`hours_limit`),
  KEY `idx_dvi_time_limit_km_limit` (`km_limit`),
  KEY `idx_dvi_time_limit_createdby` (`createdby`),
  KEY `idx_dvi_time_limit_createdon` (`createdon`),
  KEY `idx_dvi_time_limit_updatedon` (`updatedon`),
  KEY `idx_dvi_time_limit_status` (`status`),
  KEY `idx_dvi_time_limit_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_users`
--

DROP TABLE IF EXISTS `dvi_users`;
CREATE TABLE IF NOT EXISTS `dvi_users` (
  `userID` bigint NOT NULL AUTO_INCREMENT,
  `guide_id` int NOT NULL DEFAULT '0',
  `vendor_id` bigint NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0',
  `agent_id` int NOT NULL DEFAULT '0',
  `usertoken` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_profile` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` mediumtext COLLATE utf8mb4_general_ci,
  `useremail` mediumtext COLLATE utf8mb4_general_ci,
  `password` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roleID` int NOT NULL DEFAULT '0' COMMENT '1 - Super Admin | 2 - Vendor | 3 - Travel Expert| 4 - Agent | 5 - Guide',
  `google_auth_code` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userlogtime` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userlogkey` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_loggedon` datetime DEFAULT NULL,
  `userapproved` int NOT NULL DEFAULT '0',
  `userbanned` int NOT NULL DEFAULT '0',
  `createdby` bigint NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`),
  KEY `idx_dvi_users_guide_id` (`guide_id`),
  KEY `idx_dvi_users_vendor_id` (`vendor_id`),
  KEY `idx_dvi_users_staff_id` (`staff_id`),
  KEY `idx_dvi_users_agent_id` (`agent_id`),
  KEY `idx_dvi_users_usertoken` (`usertoken`),
  KEY `idx_dvi_users_user_profile` (`user_profile`),
  KEY `idx_dvi_users_username` (`username`(768)),
  KEY `idx_dvi_users_useremail` (`useremail`(768)),
  KEY `idx_dvi_users_password` (`password`),
  KEY `idx_dvi_users_roleID` (`roleID`),
  KEY `idx_dvi_users_google_auth_code` (`google_auth_code`),
  KEY `idx_dvi_users_userlogtime` (`userlogtime`),
  KEY `idx_dvi_users_userlogkey` (`userlogkey`),
  KEY `idx_dvi_users_last_loggedon` (`last_loggedon`),
  KEY `idx_dvi_users_userapproved` (`userapproved`),
  KEY `idx_dvi_users_userbanned` (`userbanned`),
  KEY `idx_dvi_users_createdby` (`createdby`),
  KEY `idx_dvi_users_createdon` (`createdon`),
  KEY `idx_dvi_users_updatedon` (`updatedon`),
  KEY `idx_dvi_users_status` (`status`),
  KEY `idx_dvi_users_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle`
--

DROP TABLE IF EXISTS `dvi_vehicle`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle` (
  `vehicle_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_location_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int DEFAULT NULL,
  `registration_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `engine_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_contact_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_email_id` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_country` int NOT NULL DEFAULT '0',
  `owner_state` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_city` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_pincode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `owner_address` text COLLATE utf8mb4_general_ci,
  `chassis_number` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_fc_expiry_date` date DEFAULT NULL,
  `fuel_type` int NOT NULL DEFAULT '0' COMMENT '1 - Petrol\r\n2 - Diesel\r\n3- Electric',
  `early_morning_charges` float NOT NULL DEFAULT '0' COMMENT 'Before 6 AM',
  `evening_charges` float NOT NULL DEFAULT '0' COMMENT 'After 8 PM',
  `vehicle_video_url` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extra_km_charge` float NOT NULL DEFAULT '0',
  `extra_hour_charge` float NOT NULL DEFAULT '0',
  `insurance_policy_number` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `insurance_start_date` date DEFAULT NULL,
  `insurance_end_date` date DEFAULT NULL,
  `insurance_contact_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `RTO_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_id`),
  KEY `idx_dvi_vehicle_vendor_id` (`vendor_id`),
  KEY `idx_dvi_vehicle_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_dvi_vehicle_vehicle_location_id` (`vehicle_location_id`),
  KEY `idx_dvi_vehicle_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_vehicle_registration_number` (`registration_number`),
  KEY `idx_dvi_vehicle_registration_date` (`registration_date`),
  KEY `idx_dvi_vehicle_engine_number` (`engine_number`),
  KEY `idx_dvi_vehicle_owner_name` (`owner_name`),
  KEY `idx_dvi_vehicle_owner_contact_no` (`owner_contact_no`),
  KEY `idx_dvi_vehicle_owner_email_id` (`owner_email_id`),
  KEY `idx_dvi_vehicle_owner_country` (`owner_country`),
  KEY `idx_dvi_vehicle_owner_state` (`owner_state`),
  KEY `idx_dvi_vehicle_owner_city` (`owner_city`),
  KEY `idx_dvi_vehicle_owner_pincode` (`owner_pincode`),
  KEY `idx_dvi_vehicle_owner_address` (`owner_address`(768)),
  KEY `idx_dvi_vehicle_chassis_number` (`chassis_number`),
  KEY `idx_dvi_vehicle_vehicle_fc_expiry_date` (`vehicle_fc_expiry_date`),
  KEY `idx_dvi_vehicle_fuel_type` (`fuel_type`),
  KEY `idx_dvi_vehicle_early_morning_charges` (`early_morning_charges`),
  KEY `idx_dvi_vehicle_evening_charges` (`evening_charges`),
  KEY `idx_dvi_vehicle_vehicle_video_url` (`vehicle_video_url`),
  KEY `idx_dvi_vehicle_extra_km_charge` (`extra_km_charge`),
  KEY `idx_dvi_vehicle_extra_hour_charge` (`extra_hour_charge`),
  KEY `idx_dvi_vehicle_insurance_policy_number` (`insurance_policy_number`),
  KEY `idx_dvi_vehicle_insurance_start_date` (`insurance_start_date`),
  KEY `idx_dvi_vehicle_insurance_end_date` (`insurance_end_date`),
  KEY `idx_dvi_vehicle_insurance_contact_no` (`insurance_contact_no`),
  KEY `idx_dvi_vehicle_RTO_code` (`RTO_code`),
  KEY `idx_dvi_vehicle_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_status` (`status`),
  KEY `idx_dvi_vehicle_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_fc_renewal_log_details`
--

DROP TABLE IF EXISTS `dvi_vehicle_fc_renewal_log_details`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_fc_renewal_log_details` (
  `vehicle_fc_renewal_log_details_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vehicle_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_fc_expired_on` date DEFAULT NULL,
  `vehicle_fc_renewal_on` date DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_fc_renewal_log_details_id`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_vehicle_id` (`vehicle_id`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_vehicle_code` (`vehicle_code`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_vehicle_fc_expired_on` (`vehicle_fc_expired_on`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_vehicle_fc_renewal_on` (`vehicle_fc_renewal_on`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_status` (`status`),
  KEY `idx_dvi_vehicle_fc_renewal_log_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_gallery_details`
--

DROP TABLE IF EXISTS `dvi_vehicle_gallery_details`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_gallery_details` (
  `vehicle_gallery_details_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_id` int DEFAULT '0',
  `image_type` int NOT NULL DEFAULT '0' COMMENT '1 - RC Document\r\n2 - FC Document\r\n3 - Government ID Proof\r\n4 - Driver License Proof\r\n5 - Permit Proof\r\n6 - Insurance Copy\r\n7 - Interior\r\n8 - Exterior\r\n9 - Videos\r\n10 - Others',
  `vehicle_gallery_name` text COLLATE utf8mb4_general_ci,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_gallery_details_id`),
  KEY `idx_dvi_vehicle_gallery_details_vehicle_id` (`vehicle_id`),
  KEY `idx_dvi_vehicle_gallery_details_image_type` (`image_type`),
  KEY `idx_dvi_vehicle_gallery_details_vehicle_gallery_name` (`vehicle_gallery_name`(768)),
  KEY `idx_dvi_vehicle_gallery_details_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_gallery_details_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_gallery_details_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_gallery_details_status` (`status`),
  KEY `idx_dvi_vehicle_gallery_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_local_pricebook`
--

DROP TABLE IF EXISTS `dvi_vehicle_local_pricebook`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_local_pricebook` (
  `vehicle_price_book_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `time_limit_id` int NOT NULL DEFAULT '0',
  `cost_type` int NOT NULL DEFAULT '0' COMMENT '1-localcost',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_price_book_id`),
  KEY `idx_dvi_vehicle_local_pricebook_vendor_id` (`vendor_id`),
  KEY `idx_dvi_vehicle_local_pricebook_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_dvi_vehicle_local_pricebook_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_vehicle_local_pricebook_time_limit_id` (`time_limit_id`),
  KEY `idx_dvi_vehicle_local_pricebook_cost_type` (`cost_type`),
  KEY `idx_dvi_vehicle_local_pricebook_year` (`year`),
  KEY `idx_dvi_vehicle_local_pricebook_month` (`month`),
  KEY `idx_dvi_vehicle_local_pricebook_day_1` (`day_1`),
  KEY `idx_dvi_vehicle_local_pricebook_day_2` (`day_2`),
  KEY `idx_dvi_vehicle_local_pricebook_day_3` (`day_3`),
  KEY `idx_dvi_vehicle_local_pricebook_day_4` (`day_4`),
  KEY `idx_dvi_vehicle_local_pricebook_day_5` (`day_5`),
  KEY `idx_dvi_vehicle_local_pricebook_day_6` (`day_6`),
  KEY `idx_dvi_vehicle_local_pricebook_day_7` (`day_7`),
  KEY `idx_dvi_vehicle_local_pricebook_day_8` (`day_8`),
  KEY `idx_dvi_vehicle_local_pricebook_day_9` (`day_9`),
  KEY `idx_dvi_vehicle_local_pricebook_day_10` (`day_10`),
  KEY `idx_dvi_vehicle_local_pricebook_day_11` (`day_11`),
  KEY `idx_dvi_vehicle_local_pricebook_day_12` (`day_12`),
  KEY `idx_dvi_vehicle_local_pricebook_day_13` (`day_13`),
  KEY `idx_dvi_vehicle_local_pricebook_day_14` (`day_14`),
  KEY `idx_dvi_vehicle_local_pricebook_day_15` (`day_15`),
  KEY `idx_dvi_vehicle_local_pricebook_day_16` (`day_16`),
  KEY `idx_dvi_vehicle_local_pricebook_day_17` (`day_17`),
  KEY `idx_dvi_vehicle_local_pricebook_day_18` (`day_18`),
  KEY `idx_dvi_vehicle_local_pricebook_day_19` (`day_19`),
  KEY `idx_dvi_vehicle_local_pricebook_day_20` (`day_20`),
  KEY `idx_dvi_vehicle_local_pricebook_day_21` (`day_21`),
  KEY `idx_dvi_vehicle_local_pricebook_day_22` (`day_22`),
  KEY `idx_dvi_vehicle_local_pricebook_day_23` (`day_23`),
  KEY `idx_dvi_vehicle_local_pricebook_day_24` (`day_24`),
  KEY `idx_dvi_vehicle_local_pricebook_day_25` (`day_25`),
  KEY `idx_dvi_vehicle_local_pricebook_day_26` (`day_26`),
  KEY `idx_dvi_vehicle_local_pricebook_day_27` (`day_27`),
  KEY `idx_dvi_vehicle_local_pricebook_day_28` (`day_28`),
  KEY `idx_dvi_vehicle_local_pricebook_day_29` (`day_29`),
  KEY `idx_dvi_vehicle_local_pricebook_day_30` (`day_30`),
  KEY `idx_dvi_vehicle_local_pricebook_day_31` (`day_31`),
  KEY `idx_dvi_vehicle_local_pricebook_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_local_pricebook_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_local_pricebook_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_local_pricebook_status` (`status`),
  KEY `idx_dvi_vehicle_local_pricebook_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_outstation_price_book`
--

DROP TABLE IF EXISTS `dvi_vehicle_outstation_price_book`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_outstation_price_book` (
  `vehicle_outstation_price_book_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `kms_limit_id` int NOT NULL DEFAULT '0',
  `year` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `month` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `day_1` float DEFAULT '0',
  `day_2` float DEFAULT '0',
  `day_3` float DEFAULT '0',
  `day_4` float DEFAULT '0',
  `day_5` float DEFAULT '0',
  `day_6` float DEFAULT '0',
  `day_7` float DEFAULT '0',
  `day_8` float DEFAULT '0',
  `day_9` float DEFAULT '0',
  `day_10` float DEFAULT '0',
  `day_11` float DEFAULT '0',
  `day_12` float DEFAULT '0',
  `day_13` float DEFAULT '0',
  `day_14` float DEFAULT '0',
  `day_15` float DEFAULT '0',
  `day_16` float DEFAULT '0',
  `day_17` float DEFAULT '0',
  `day_18` float DEFAULT '0',
  `day_19` float DEFAULT '0',
  `day_20` float DEFAULT '0',
  `day_21` float DEFAULT '0',
  `day_22` float DEFAULT '0',
  `day_23` float DEFAULT '0',
  `day_24` float DEFAULT '0',
  `day_25` float DEFAULT '0',
  `day_26` float DEFAULT '0',
  `day_27` float DEFAULT '0',
  `day_28` float DEFAULT '0',
  `day_29` float DEFAULT '0',
  `day_30` float DEFAULT '0',
  `day_31` float DEFAULT '0',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_outstation_price_book_id`),
  KEY `idx_dvi_vehicle_outstation_price_book_vendor_id` (`vendor_id`),
  KEY `idx_dvi_vehicle_outstation_price_book_vendor_branch_id` (`vendor_branch_id`),
  KEY `idx_dvi_vehicle_outstation_price_book_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_vehicle_outstation_price_book_kms_limit_id` (`kms_limit_id`),
  KEY `idx_dvi_vehicle_outstation_price_book_year` (`year`),
  KEY `idx_dvi_vehicle_outstation_price_book_month` (`month`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_1` (`day_1`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_2` (`day_2`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_3` (`day_3`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_4` (`day_4`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_5` (`day_5`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_6` (`day_6`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_7` (`day_7`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_8` (`day_8`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_9` (`day_9`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_10` (`day_10`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_11` (`day_11`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_12` (`day_12`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_13` (`day_13`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_14` (`day_14`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_15` (`day_15`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_16` (`day_16`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_17` (`day_17`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_18` (`day_18`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_19` (`day_19`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_20` (`day_20`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_21` (`day_21`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_22` (`day_22`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_23` (`day_23`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_24` (`day_24`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_25` (`day_25`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_26` (`day_26`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_27` (`day_27`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_28` (`day_28`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_29` (`day_29`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_30` (`day_30`),
  KEY `idx_dvi_vehicle_outstation_price_book_day_31` (`day_31`),
  KEY `idx_dvi_vehicle_outstation_price_book_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_outstation_price_book_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_outstation_price_book_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_outstation_price_book_status` (`status`),
  KEY `idx_dvi_vehicle_outstation_price_book_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_review_details`
--

DROP TABLE IF EXISTS `dvi_vehicle_review_details`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_review_details` (
  `vehicle_review_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_id` int NOT NULL DEFAULT '0',
  `vehicle_rating` text COLLATE utf8mb4_general_ci,
  `vehicle_description` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_review_id`),
  KEY `idx_dvi_vehicle_review_details_vehicle_review_id` (`vehicle_review_id`),
  KEY `idx_dvi_vehicle_review_details_vehicle_id` (`vehicle_id`),
  KEY `idx_dvi_vehicle_review_details_vehicle_rating` (`vehicle_rating`(768)),
  KEY `idx_dvi_vehicle_review_details_vehicle_description` (`vehicle_description`),
  KEY `idx_dvi_vehicle_review_details_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_review_details_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_review_details_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_review_details_status` (`status`),
  KEY `idx_dvi_vehicle_review_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_toll_charges`
--

DROP TABLE IF EXISTS `dvi_vehicle_toll_charges`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_toll_charges` (
  `vehicle_toll_charge_ID` int NOT NULL AUTO_INCREMENT,
  `location_id` bigint NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `toll_charge` float NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_toll_charge_ID`),
  KEY `idx_dvi_vehicle_toll_charges_location_id` (`location_id`),
  KEY `idx_dvi_vehicle_toll_charges_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_vehicle_toll_charges_toll_charge` (`toll_charge`),
  KEY `idx_dvi_vehicle_toll_charges_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_toll_charges_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_toll_charges_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_toll_charges_status` (`status`),
  KEY `idx_dvi_vehicle_toll_charges_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vehicle_type`
--

DROP TABLE IF EXISTS `dvi_vehicle_type`;
CREATE TABLE IF NOT EXISTS `dvi_vehicle_type` (
  `vehicle_type_id` int NOT NULL AUTO_INCREMENT,
  `vehicle_type_title` text COLLATE utf8mb4_general_ci,
  `occupancy` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0' COMMENT '1- SuperADMIN | 2- Vendor\r\n',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_type_id`),
  KEY `idx_dvi_vehicle_type_vehicle_type_title` (`vehicle_type_title`(768)),
  KEY `idx_dvi_vehicle_type_occupancy` (`occupancy`),
  KEY `idx_dvi_vehicle_type_createdon` (`createdon`),
  KEY `idx_dvi_vehicle_type_updatedon` (`updatedon`),
  KEY `idx_dvi_vehicle_type_createdby` (`createdby`),
  KEY `idx_dvi_vehicle_type_status` (`status`),
  KEY `idx_dvi_vehicle_type_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vendor_branches`
--

DROP TABLE IF EXISTS `dvi_vendor_branches`;
CREATE TABLE IF NOT EXISTS `dvi_vendor_branches` (
  `vendor_branch_id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vendor_branch_name` text COLLATE utf8mb4_general_ci,
  `vendor_branch_emailid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_branch_primary_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_branch_alternative_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_branch_country` int NOT NULL DEFAULT '0',
  `vendor_branch_state` int NOT NULL DEFAULT '0',
  `vendor_branch_city` int NOT NULL DEFAULT '0',
  `vendor_branch_pincode` int NOT NULL DEFAULT '0',
  `vendor_branch_location` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_branch_gst_type` int NOT NULL DEFAULT '0' COMMENT '1- Included | 2-Excluded',
  `vendor_branch_gst` int NOT NULL DEFAULT '0',
  `vendor_branch_address` text COLLATE utf8mb4_general_ci,
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_branch_id`),
  KEY `idx_dvi_vendor_branches_vendor_id` (`vendor_id`),
  KEY `idx_dvi_vendor_branches_vendor_branch_name` (`vendor_branch_name`(768)),
  KEY `idx_dvi_vendor_branches_vendor_branch_emailid` (`vendor_branch_emailid`),
  KEY `idx_dvi_vendor_branches_vendor_branch_primary_mobile_number` (`vendor_branch_primary_mobile_number`),
  KEY `idx_dvi_vendor_branches_vendor_branch_alternative_mobile_number` (`vendor_branch_alternative_mobile_number`),
  KEY `idx_dvi_vendor_branches_vendor_branch_country` (`vendor_branch_country`),
  KEY `idx_dvi_vendor_branches_vendor_branch_state` (`vendor_branch_state`),
  KEY `idx_dvi_vendor_branches_vendor_branch_city` (`vendor_branch_city`),
  KEY `idx_dvi_vendor_branches_vendor_branch_pincode` (`vendor_branch_pincode`),
  KEY `idx_dvi_vendor_branches_vendor_branch_location` (`vendor_branch_location`),
  KEY `idx_dvi_vendor_branches_vendor_branch_gst_type` (`vendor_branch_gst_type`),
  KEY `idx_dvi_vendor_branches_vendor_branch_gst` (`vendor_branch_gst`),
  KEY `idx_dvi_vendor_branches_vendor_branch_address` (`vendor_branch_address`(768)),
  KEY `idx_dvi_vendor_branches_createdby` (`createdby`),
  KEY `idx_dvi_vendor_branches_createdon` (`createdon`),
  KEY `idx_dvi_vendor_branches_updatedon` (`updatedon`),
  KEY `idx_dvi_vendor_branches_status` (`status`),
  KEY `idx_dvi_vendor_branches_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vendor_details`
--

DROP TABLE IF EXISTS `dvi_vendor_details`;
CREATE TABLE IF NOT EXISTS `dvi_vendor_details` (
  `vendor_id` int NOT NULL AUTO_INCREMENT,
  `vendor_name` text COLLATE utf8mb4_general_ci,
  `vendor_code` text COLLATE utf8mb4_general_ci,
  `vendor_email` text COLLATE utf8mb4_general_ci,
  `vendor_primary_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_alternative_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_country` int NOT NULL DEFAULT '0',
  `vendor_state` int NOT NULL DEFAULT '0',
  `vendor_city` int DEFAULT '0',
  `vendor_pincode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_othernumber` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vendor_margin` int NOT NULL DEFAULT '0',
  `vendor_margin_gst_type` int NOT NULL DEFAULT '0' COMMENT '1 - Inclusive | 2 - Exclusive',
  `vendor_margin_gst_percentage` float NOT NULL DEFAULT '0',
  `vendor_address` text COLLATE utf8mb4_general_ci,
  `invoice_gstin_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_pan_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_mobile_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_email` text COLLATE utf8mb4_general_ci,
  `vendor_company_name` text COLLATE utf8mb4_general_ci,
  `invoice_logo` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_pincode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_address` text COLLATE utf8mb4_general_ci,
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `createdby` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_id`),
  KEY `idx_dvi_vendor_details_vendor_name` (`vendor_name`(768)),
  KEY `idx_dvi_vendor_details_vendor_code` (`vendor_code`(768)),
  KEY `idx_dvi_vendor_details_vendor_email` (`vendor_email`(768)),
  KEY `idx_dvi_vendor_details_vendor_primary_mobile_number` (`vendor_primary_mobile_number`),
  KEY `idx_dvi_vendor_details_vendor_alternative_mobile_number` (`vendor_alternative_mobile_number`),
  KEY `idx_dvi_vendor_details_vendor_country` (`vendor_country`),
  KEY `idx_dvi_vendor_details_vendor_state` (`vendor_state`),
  KEY `idx_dvi_vendor_details_vendor_city` (`vendor_city`),
  KEY `idx_dvi_vendor_details_vendor_pincode` (`vendor_pincode`),
  KEY `idx_dvi_vendor_details_vendor_othernumber` (`vendor_othernumber`),
  KEY `idx_dvi_vendor_details_vendor_margin` (`vendor_margin`),
  KEY `idx_dvi_vendor_details_vendor_margin_gst_type` (`vendor_margin_gst_type`),
  KEY `idx_dvi_vendor_details_vendor_margin_gst_percentage` (`vendor_margin_gst_percentage`),
  KEY `idx_dvi_vendor_details_vendor_address` (`vendor_address`(768)),
  KEY `idx_dvi_vendor_details_invoice_gstin_number` (`invoice_gstin_number`),
  KEY `idx_dvi_vendor_details_invoice_pan_number` (`invoice_pan_number`),
  KEY `idx_dvi_vendor_details_invoice_mobile_number` (`invoice_mobile_number`),
  KEY `idx_dvi_vendor_details_invoice_email` (`invoice_email`(768)),
  KEY `idx_dvi_vendor_details_vendor_company_name` (`vendor_company_name`(768)),
  KEY `idx_dvi_vendor_details_invoice_logo` (`invoice_logo`),
  KEY `idx_dvi_vendor_details_invoice_pincode` (`invoice_pincode`),
  KEY `idx_dvi_vendor_details_invoice_address` (`invoice_address`(768)),
  KEY `idx_dvi_vendor_details_createdon` (`createdon`),
  KEY `idx_dvi_vendor_details_updatedon` (`updatedon`),
  KEY `idx_dvi_vendor_details_createdby` (`createdby`),
  KEY `idx_dvi_vendor_details_status` (`status`),
  KEY `idx_dvi_vendor_details_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dvi_vendor_vehicle_types`
--

DROP TABLE IF EXISTS `dvi_vendor_vehicle_types`;
CREATE TABLE IF NOT EXISTS `dvi_vendor_vehicle_types` (
  `vendor_vehicle_type_ID` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL DEFAULT '0',
  `vehicle_type_id` int NOT NULL DEFAULT '0',
  `driver_batta` float NOT NULL DEFAULT '0',
  `food_cost` float NOT NULL DEFAULT '0',
  `accomodation_cost` float NOT NULL DEFAULT '0',
  `extra_cost` float NOT NULL DEFAULT '0',
  `driver_early_morning_charges` float NOT NULL DEFAULT '0' COMMENT 'Before 6AM',
  `driver_evening_charges` float NOT NULL DEFAULT '0' COMMENT 'After 8 PM',
  `createdby` int NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_vehicle_type_ID`),
  KEY `idx_dvi_vendor_vehicle_types_vendor_id` (`vendor_id`),
  KEY `idx_dvi_vendor_vehicle_types_vehicle_type_id` (`vehicle_type_id`),
  KEY `idx_dvi_vendor_vehicle_types_driver_batta` (`driver_batta`),
  KEY `idx_dvi_vendor_vehicle_types_food_cost` (`food_cost`),
  KEY `idx_dvi_vendor_vehicle_types_accomodation_cost` (`accomodation_cost`),
  KEY `idx_dvi_vendor_vehicle_types_extra_cost` (`extra_cost`),
  KEY `idx_dvi_vendor_vehicle_types_driver_early_morning_charges` (`driver_early_morning_charges`),
  KEY `idx_dvi_vendor_vehicle_types_driver_evening_charges` (`driver_evening_charges`),
  KEY `idx_dvi_vendor_vehicle_types_createdby` (`createdby`),
  KEY `idx_dvi_vendor_vehicle_types_createdon` (`createdon`),
  KEY `idx_dvi_vendor_vehicle_types_updatedon` (`updatedon`),
  KEY `idx_dvi_vendor_vehicle_types_status` (`status`),
  KEY `idx_dvi_vendor_vehicle_types_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old_dvi_stored_locations`
--

DROP TABLE IF EXISTS `old_dvi_stored_locations`;
CREATE TABLE IF NOT EXISTS `old_dvi_stored_locations` (
  `location_ID` bigint NOT NULL AUTO_INCREMENT,
  `source_location` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_lattitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_longitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_city` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `source_location_state` longtext COLLATE utf8mb4_general_ci,
  `destination_location` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_lattitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_longitude` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_city` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `destination_location_state` longtext COLLATE utf8mb4_general_ci,
  `distance` double NOT NULL DEFAULT '0',
  `duration` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_general_ci,
  `created_from` int NOT NULL DEFAULT '0' COMMENT '0 - Using API | 1 - Manual',
  `createdby` bigint NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_ID`),
  KEY `source_location_2` (`source_location`(768)),
  KEY `idx_stored_locations_status` (`status`),
  KEY `idx_stored_locations_deleted` (`deleted`),
  KEY `idx_main_source_location` (`source_location`(768)),
  KEY `idx_main_destination_location` (`destination_location`(768)),
  KEY `idx_dvi_stored_locations_source_location` (`source_location`(768)),
  KEY `idx_dvi_stored_locations_source_location_lattitude` (`source_location_lattitude`(768)),
  KEY `idx_dvi_stored_locations_source_location_longitude` (`source_location_longitude`(768)),
  KEY `idx_dvi_stored_locations_source_location_city` (`source_location_city`(768)),
  KEY `idx_dvi_stored_locations_source_location_state` (`source_location_state`(768)),
  KEY `idx_dvi_stored_locations_destination_location` (`destination_location`(768)),
  KEY `idx_dvi_stored_locations_destination_location_lattitude` (`destination_location_lattitude`(768)),
  KEY `idx_dvi_stored_locations_destination_location_longitude` (`destination_location_longitude`(768)),
  KEY `idx_dvi_stored_locations_destination_location_city` (`destination_location_city`(768)),
  KEY `idx_dvi_stored_locations_destination_location_state` (`destination_location_state`(768)),
  KEY `idx_dvi_stored_locations_distance` (`distance`),
  KEY `idx_dvi_stored_locations_duration` (`duration`),
  KEY `idx_dvi_stored_locations_location_description` (`location_description`(768)),
  KEY `idx_dvi_stored_locations_created_from` (`created_from`),
  KEY `idx_dvi_stored_locations_createdby` (`createdby`),
  KEY `idx_dvi_stored_locations_createdon` (`createdon`),
  KEY `idx_dvi_stored_locations_updatedon` (`updatedon`),
  KEY `idx_dvi_stored_locations_status` (`status`),
  KEY `idx_dvi_stored_locations_deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
