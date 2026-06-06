-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 06:26 AM
-- Server version: 12.2.2-MariaDB-log
-- PHP Version: 8.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoran`
--
CREATE DATABASE IF NOT EXISTS `restoran` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `restoran`;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('food','drink','snack') NOT NULL,
  `price` int(11) NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `status`, `image`, `description`) VALUES
(1, 'Nasi Goreng Spesial', 'food', 25000, 'available', 'https://media.istockphoto.com/id/1246401756/photo/nasi-goreng-indonesian-chicken-fried-rice-on-black-plate-indonesian-cuisine-dish-balinese.jpg', 'Nasi goreng dengan telur, ayam, dan sayuran'),
(2, 'Ayam Bakar', 'food', 35000, 'available', 'https://media.istockphoto.com/id/1390217899/photo/ayam-bakar-madu-roasted-chicken-with-honey-herb-and-spice-from-indonesia.jpg', ''),
(3, 'Soto Ayam solo', 'food', 28000, 'available', 'https://www.shutterstock.com/image-photo/soto-ayam-typical-indonesian-food-600nw-2517244091.jpg', ''),
(4, 'Es Teh Manis', 'drink', 8000, 'available', 'https://img.magnific.com/premium-photo/tamarind-agua-fresca_198067-110187.jpg', 'Es teh dengan gula batu di padukan dengan lemon segar'),
(5, 'Jus Alpukat', 'drink', 15000, 'available', 'https://www.topwisata.info/wp-content/uploads/2022/05/Jus-Alpukat-1.jpg', 'Jus alpukat dengan susu coklat'),
(6, 'americano ice/hot', 'drink', 12000, 'available', 'https://kitchenpedia.org/wp-content/uploads/2025/01/Iced_Americano.jpg', 'Kopi hitam pilihan nusantara'),
(7, 'Kentang Goreng', 'snack', 18000, 'available', 'https://www.rumahmesin.com/wp-content/uploads/2023/07/Cara-Membuat-Kentang-Goreng-Renyah-ala-KFC-dan-McD.jpeg', 'Kentang goreng renyah bumbu gurih di sertai sauce tomat dan mayones'),
(8, 'Singkong Keju', 'snack', 15000, 'available', 'https://picsum.photos/300/200?random=snack2', 'Singkong goreng dengan taburan keju melimpah'),
(9, 'Mie Goreng', 'food', 22000, 'available', 'https://picsum.photos/300/200?random=food4', 'Mie goreng jawa spesial'),
(10, 'Es Jeruk', 'drink', 10000, 'available', 'https://picsum.photos/300/200?random=drink4', 'Perasan jeruk peras asli segar');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `total_amount` int(11) DEFAULT 0,
  `status` enum('pending','ready','paid') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `tip_amount` decimal(10,2) DEFAULT 0.00,
  `discount_percent` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `customer_name`, `total_amount`, `status`, `created_at`, `tip_amount`, `discount_percent`) VALUES
(1, 5, 'if', 25000, 'paid', '2026-06-02 01:34:43', 0.00, 0.00),
(2, 14, 'tukang bom', 5000, 'paid', '2026-06-02 01:39:37', 0.00, 0.00),
(3, 6, 'ba', 93000, 'paid', '2026-06-04 09:04:37', 0.00, 0.00),
(4, 7, 'me', 30000, 'paid', '2026-06-06 00:56:57', 0.00, 0.00),
(5, 6, 'aku', 237000, 'paid', '2026-06-06 01:26:36', 10000.00, 10.00),
(6, 11, 'dia', 25000, 'paid', '2026-06-06 01:31:36', 5000.00, 50.00),
(7, 18, 'Diriku', 120000, 'paid', '2026-06-06 01:36:15', 10000.00, 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 25000),
(3, 3, 3, 1, 28000),
(4, 3, 4, 1, 8000),
(5, 3, 8, 1, 15000),
(6, 3, 9, 1, 22000),
(7, 3, 10, 2, 10000),
(8, 4, 5, 1, 15000),
(9, 4, 8, 1, 15000),
(10, 5, 2, 3, 35000),
(11, 5, 4, 2, 8000),
(12, 5, 5, 1, 15000),
(13, 5, 7, 3, 18000),
(14, 5, 8, 1, 15000),
(15, 5, 10, 1, 10000),
(16, 5, 9, 1, 22000),
(17, 6, 1, 1, 25000),
(18, 7, 2, 1, 35000),
(19, 7, 3, 1, 28000),
(20, 7, 5, 2, 15000),
(21, 7, 6, 1, 12000),
(22, 7, 8, 1, 15000);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tables`
--

CREATE TABLE `restaurant_tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `status` enum('empty','occupied','ready') DEFAULT 'empty',
  `customer_name` varchar(100) DEFAULT '',
  `order_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_tables`
--

INSERT INTO `restaurant_tables` (`id`, `table_number`, `status`, `customer_name`, `order_time`) VALUES
(1, 'Meja 01', 'empty', '', NULL),
(2, 'Meja 02', 'empty', '', NULL),
(3, 'Meja 03', 'empty', '', NULL),
(4, 'Meja 04', 'empty', '', NULL),
(5, 'Meja 05', 'empty', '', NULL),
(6, 'Meja 06', 'empty', '', NULL),
(7, 'Meja 07', 'empty', '', NULL),
(8, 'Meja 08', 'empty', '', NULL),
(9, 'Meja 09', 'empty', '', NULL),
(10, 'Meja 10', 'empty', '', NULL),
(11, 'Meja 11', 'empty', '', NULL),
(12, 'Meja 12', 'empty', '', NULL),
(13, 'Meja 13', 'empty', '', NULL),
(14, 'Meja 14', 'empty', '', NULL),
(15, 'Meja 15', 'empty', '', NULL),
(16, 'Meja 16', 'empty', '', NULL),
(17, 'Meja 17', 'empty', '', NULL),
(18, 'Meja 18', 'empty', '', NULL),
(19, 'Meja 19', 'empty', '', NULL),
(20, 'Meja 20', 'empty', '', NULL),
(21, 'Meja 21', 'empty', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('waiter','kitchen','cashier','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'pelayan', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'waiter', '2026-06-06 01:13:16', '2026-06-06 01:13:16'),
(2, 'dapur', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'kitchen', '2026-06-06 01:13:16', '2026-06-06 01:13:16'),
(3, 'kasir', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'cashier', '2026-06-06 01:13:16', '2026-06-06 01:13:16'),
(4, 'admin', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'admin', '2026-06-06 01:13:16', '2026-06-06 01:13:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `1` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
