-- Add new fields to dvi_cancelled_itineraries for selective cancellation tracking
ALTER TABLE `dvi_cancelled_itineraries`
ADD COLUMN `cancellation_reason` TEXT NULL AFTER `itinerary_plan_id`,
ADD COLUMN `cancellation_reference` VARCHAR(100) NULL AFTER `cancellation_reason`,
ADD COLUMN `modify_hotspot` TINYINT NOT NULL DEFAULT 1 AFTER `cancellation_reference`,
ADD COLUMN `modify_hotel` TINYINT NOT NULL DEFAULT 1 AFTER `modify_hotspot`,
ADD COLUMN `modify_vehicle` TINYINT NOT NULL DEFAULT 1 AFTER `modify_hotel`,
ADD COLUMN `modify_guide` TINYINT NOT NULL DEFAULT 1 AFTER `modify_vehicle`,
ADD COLUMN `modify_activity` TINYINT NOT NULL DEFAULT 1 AFTER `modify_guide`,
ADD COLUMN `cancelled_by` INT NOT NULL DEFAULT 0 AFTER `modify_activity`,
ADD COLUMN `cancelled_on` DATETIME NULL AFTER `cancelled_by`,
ADD COLUMN `cancellation_status` VARCHAR(50) NOT NULL DEFAULT 'pending' AFTER `cancelled_on`;

-- Create index for faster lookups
CREATE INDEX `idx_cancellation_reference` ON `dvi_cancelled_itineraries` (`cancellation_reference`);
CREATE INDEX `idx_itinerary_plan_id` ON `dvi_cancelled_itineraries` (`itinerary_plan_id`);

-- Create cancellation logs table for audit trail
CREATE TABLE IF NOT EXISTS `dvi_cancellation_logs` (
  `log_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cancellation_id` INT NOT NULL,
  `itinerary_plan_id` INT NOT NULL,
  `action_type` VARCHAR(100) NOT NULL COMMENT 'e.g., hotspot_cancelled, hotel_cancelled, vehicle_cancelled, refund_processed',
  `action_details` TEXT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'success',
  `error_message` TEXT NULL,
  `created_by` INT NOT NULL DEFAULT 0,
  `created_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_cancellation_id` (`cancellation_id`),
  INDEX `idx_itinerary_plan_id` (`itinerary_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
