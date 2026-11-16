-- import.sql generated from json/items.json
-- Creates database, tables, and inserts items

CREATE DATABASE IF NOT EXISTS `webshop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webshop`;

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DOUBLE NOT NULL,
  `description` TEXT,
  `image` VARCHAR(255),
  `quantity` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert items from json/items.json
INSERT INTO `items` (`name`, `price`, `description`, `image`, `quantity`) VALUES
('T-shirt', 19.99, 'A comfortable cotton t-shirt.', 'img/1tshirt.png', 27),
('Jeans', 39.99, 'Stylish jeans.', 'img/2jeans.jpg', 13),
('Sneakers', 59.99, 'Trendy sneakers for everyday wear.', 'img/3sneakers.jpg', 42),
('Watch', 99.99, 'A sleek wristwatch with a modern design.', 'img/4watch.jpg', 8),
('Backpack', 49.99, 'A durable backpack for daily use.', 'img/5backpack.jpg', 34),
('Sunglasses', 29.99, 'Stylish sunglasses for sunny days.', 'img/6sunglasses.jpg', 19),
('Top-Hat', 14.99, 'A fashionable hat for all seasons.', 'img/7hat.jpeg', 22),
('Jacket', 89.99, 'A warm jacket for cold weather.', 'img/8jacket.jpg', 15);

-- Set the AUTO_INCREMENT to the next id
ALTER TABLE `items` AUTO_INCREMENT = 9;

-- (Optional) Create `orders` table like in includes/db.php
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `time` TEXT NOT NULL,
  `total_price` DOUBLE NOT NULL,
  `items` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
