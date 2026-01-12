-- Create TBO Hotel Master Data table
-- This table stores hotel static data (name, address, rating, etc.) synced from TBO
-- Data is fetched from TBO's GetHotels API and refreshed periodically

CREATE TABLE IF NOT EXISTS tbo_hotel_master (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tbo_hotel_code VARCHAR(20) NOT NULL UNIQUE,
  tbo_city_code VARCHAR(10) NOT NULL,
  hotel_name VARCHAR(255) NOT NULL,
  hotel_address VARCHAR(500),
  city_name VARCHAR(100),
  star_rating INT DEFAULT 0,
  hotel_image_url VARCHAR(500),
  description TEXT,
  check_in_time VARCHAR(50),
  check_out_time VARCHAR(50),
  facilities TEXT COMMENT 'JSON array of facility names',
  last_synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_tbo_city_code (tbo_city_code),
  KEY idx_hotel_name (hotel_name),
  KEY idx_last_synced (last_synced_at)
);

-- Index for fast lookups
CREATE INDEX idx_tbo_hotel_code ON tbo_hotel_master(tbo_hotel_code);
