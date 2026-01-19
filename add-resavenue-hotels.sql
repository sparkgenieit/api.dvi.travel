-- ============================================
-- ResAvenue Integration - Schema Changes
-- Date: 2026-01-20
-- ============================================

-- Step 1: Add resavenue_hotel_code column to dvi_hotel table
ALTER TABLE `dvi_hotel` 
ADD COLUMN `resavenue_hotel_code` VARCHAR(200) NULL 
COMMENT 'ResAvenue hotel code (e.g., "261")' 
AFTER `tbo_city_code`;

-- Step 2: Add index for resavenue_hotel_code for fast lookups
CREATE INDEX `idx_hotel_resavenue_hotel_code` ON `dvi_hotel` (`resavenue_hotel_code`);

-- ============================================
-- Insert 3 ResAvenue Test Hotels
-- ============================================

-- Hotel 1: PMS Test Hotel (Gwalior)
INSERT INTO `dvi_hotel` (
    `hotel_name`,
    `hotel_code`,
    `resavenue_hotel_code`,
    `hotel_city`,
    `hotel_state`,
    `hotel_country`,
    `hotel_category`,
    `status`,
    `deleted`,
    `createdby`,
    `createdon`
) VALUES (
    'PMS Test Hotel',
    'RESAVENUE-261',
    '261',
    'Gwalior',
    'Madhya Pradesh',
    'India',
    3, -- 3-star category (adjust as needed)
    1, -- Active
    0, -- Not deleted
    1, -- System admin
    NOW()
);

-- Hotel 2: TM Globus (Darjiling)
INSERT INTO `dvi_hotel` (
    `hotel_name`,
    `hotel_code`,
    `resavenue_hotel_code`,
    `hotel_city`,
    `hotel_state`,
    `hotel_country`,
    `hotel_category`,
    `status`,
    `deleted`,
    `createdby`,
    `createdon`
) VALUES (
    'TM Globus',
    'RESAVENUE-285',
    '285',
    'Darjiling',
    'West Bengal',
    'India',
    3, -- 3-star category (adjust as needed)
    1, -- Active
    0, -- Not deleted
    1, -- System admin
    NOW()
);

-- Hotel 3: TMahal Palace (Mumbai)
INSERT INTO `dvi_hotel` (
    `hotel_name`,
    `hotel_code`,
    `resavenue_hotel_code`,
    `hotel_city`,
    `hotel_state`,
    `hotel_country`,
    `hotel_category`,
    `status`,
    `deleted`,
    `createdby`,
    `createdon`
) VALUES (
    'TMahal Palace',
    'RESAVENUE-1098',
    '1098',
    'Mumbai',
    'Maharashtra',
    'India',
    4, -- 4-star category (adjust as needed)
    1, -- Active
    0, -- Not deleted
    1, -- System admin
    NOW()
);

-- ============================================
-- Verification Queries
-- ============================================

-- Check if hotels were inserted
SELECT 
    hotel_id,
    hotel_name,
    hotel_code,
    resavenue_hotel_code,
    hotel_city,
    hotel_state,
    status
FROM dvi_hotel 
WHERE resavenue_hotel_code IS NOT NULL
ORDER BY hotel_id DESC;

-- Check total ResAvenue hotels
SELECT COUNT(*) as total_resavenue_hotels 
FROM dvi_hotel 
WHERE resavenue_hotel_code IS NOT NULL 
AND deleted = 0;
