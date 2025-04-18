-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 02:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `limuaa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `pickup_date` datetime DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_name`, `description`, `phone_number`, `customer_name`, `total_amount`, `deposit`, `balance`, `pickup_date`, `image1`, `image2`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(1, 'Happy Birthday Me', 'Blended Whisky Cake\r\n2 Kgs\r\nColors: Similar to Black forest\r\nSpongy\r\nDecoration : Stars', '0115268401', 'Mumo Felix', 3500.00, 3500.00, 0.00, '2025-01-21 07:00:00', 'uploads/orders/1737384333_1_kg_hard_icing_2000.png', 'uploads/orders/1737384333_1_kg_hard_icing_2000.png', 'picked', 'Muasya', NULL, '2025-01-20 15:43:59', NULL),
(4, 'Happy Anniversary', 'Writings : Happy 4th anniversary\r\nColors Pink and Blue\r\nPoppers \r\nBalloons', '0769094030', 'Faith Mwende', 2500.00, 2500.00, 0.00, '2025-01-21 08:00:00', NULL, NULL, 'completed', 'Lily', NULL, '2025-01-20 16:10:18', NULL),
(5, 'Happy Birthday Bridgite ', '1 1/2 kg\r\nColors Blue and pink', '0797162817', 'Eliza Bridgite', 2000.00, 1000.00, 1000.00, '2025-01-21 13:00:00', NULL, NULL, 'in_progress', 'Lily', NULL, '2025-01-21 05:46:03', NULL),
(6, 'Ngasya', 'Mwende\'s Ngasya\r\nHard Icing\r\n2Kg', '0728416797', 'Mwende Kikungu', 3500.00, 2000.00, 1500.00, '2025-01-21 09:00:00', NULL, NULL, 'in_progress', '3', NULL, '2025-01-21 06:51:08', NULL),
(7, 'Maina Wed Joyce', '6 - 2 Kg cakes', '0797162817', 'Maina', 10000.00, 5000.00, 5000.00, '2025-01-25 09:00:00', NULL, NULL, 'pending', '3', NULL, '2025-01-21 09:14:26', NULL),
(8, 'Congrats Lucy', '2 Kg Vanilla and Lemon\r\nColor: White and Blue\r\nWriting: Congrats Lucy\r\n', '0743762400', 'Lucy', 2500.00, 2500.00, 0.00, '2025-04-04 07:00:00', 'uploads/orders/174352194917380528831_kg_hard_icing_2000.png', 'uploads/orders/174352194917380528831-2_kg_750_.._soft.png', 'completed', '1', NULL, '2025-04-01 18:36:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `description` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(500) NOT NULL,
  `user_id` varchar(60) NOT NULL,
  `date` datetime NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `description`, `qty`, `amount`, `image`, `user_id`, `date`, `views`) VALUES
(1, '2222850988291', 'American Doughnut', 48, 50.00, 'uploads/7d287ee88cd4ec738de188541a5c9d1604d34523_3100.png', '1', '2025-01-20 14:59:50', 2),
(2, '2222793046557', 'Banana Cake Slice', 48, 150.00, 'uploads/343518586c815670bd22823e93bb9cc540e7c48b_5338.png', '1', '2025-01-20 15:00:55', 2),
(3, '2222567793731', 'Big Cookies', 48, 50.00, 'uploads/bb22bfc30a3891c643f52e7c4cb3faf38ebd1180_7495.png', '1', '2025-01-20 15:01:39', 2),
(4, '2222966048975', 'Block Cake', 43, 100.00, 'uploads/6a3b4c3c13199ee47c6564690d131313a9a8ec5c_6290.png', '1', '2025-01-20 15:02:20', 7),
(5, '2222103452280', 'Block', 48, 50.00, 'uploads/fb3653f6b4d9589ce5b46273ea19c6da3a9aa87a_6264.png', '1', '2025-01-20 15:03:44', 2),
(6, '2222439738761', 'Bread Roll', 44, 25.00, 'uploads/70cf425b2f959de2ba7b4fb674dab1835644a7f4_8410.png', '1', '2025-01-20 15:04:16', 6),
(7, '2222913955883', 'Bread', 45, 65.00, 'uploads/cb7c303da9d8201213d6e899df5cdacf1243f2ef_3044.png', '1', '2025-01-20 15:04:52', 5),
(8, '2222336968222', 'Chocolate Rolade', 47, 250.00, 'uploads/a431d38775ee8126436d8cc3692316a769c44b6a_1548.png', '1', '2025-01-20 15:06:32', 3),
(9, '2222590991336', 'Doughnut', 48, 50.00, 'uploads/d1f962b77b2ca44afaf601545f95358a1b229aed_4165.png', '1', '2025-01-20 15:07:02', 2),
(10, '2222629516563', 'Meat Pie', 44, 100.00, 'uploads/0ab4dd57b31bebf023f7971e332587846d1cfcc8_8871.png', '1', '2025-01-20 15:07:32', 4),
(11, '2222275531364', 'Queen Cake', 48, 20.00, 'uploads/bb394fff605f7a87b5f1616ed29e1e52b2fdf154_2286.png', '1', '2025-01-20 15:08:09', 2),
(12, '2222532926545', 'Queen Cake Packet', 46, 120.00, 'uploads/abc6787e9a42553d5fcbe408af06722ba55cd2ab_5218.png', '1', '2025-01-20 15:08:47', 4),
(13, '2222984788922', 'Tea Scone', 48, 100.00, 'uploads/e1003631c5c953b0e1987c69d9d5e0ecce681180_6665.png', '1', '2025-01-20 15:09:26', 2),
(14, '2222970766995', 'Buger', 10, 500.00, 'uploads/11db5845a666b9102bf7c5949a2693ce593415f4_6646.jpeg', '1', '2025-04-07 03:25:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `receipt_no` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `barcode`, `receipt_no`, `description`, `qty`, `amount`, `total`, `date`, `user_id`) VALUES
(1, '2222850988291', 1, 'American Doughnut', 1, 50.00, 50.00, '2025-01-20 15:33:42', '2'),
(2, '2222793046557', 1, 'Banana Cake Slice', 1, 150.00, 150.00, '2025-01-20 15:33:42', '2'),
(3, '2222567793731', 1, 'Big Cookies', 1, 50.00, 50.00, '2025-01-20 15:33:42', '2'),
(4, '2222439738761', 1, 'Bread Roll', 1, 25.00, 25.00, '2025-01-20 15:33:42', '2'),
(5, '2222103452280', 1, 'Block', 1, 50.00, 50.00, '2025-01-20 15:33:42', '2'),
(6, '2222966048975', 1, 'Block Cake', 1, 100.00, 100.00, '2025-01-20 15:33:42', '2'),
(7, '2222629516563', 1, 'Meat Pie', 1, 100.00, 100.00, '2025-01-20 15:33:42', '2'),
(8, '2222913955883', 1, 'Bread', 1, 65.00, 65.00, '2025-01-20 15:33:42', '2'),
(9, '2222336968222', 1, 'Chocolate Rolade', 1, 250.00, 250.00, '2025-01-20 15:33:42', '2'),
(10, '2222590991336', 1, 'Doughnut', 1, 50.00, 50.00, '2025-01-20 15:33:42', '2'),
(11, '2222532926545', 1, 'Queen Cake Packet', 1, 120.00, 120.00, '2025-01-20 15:33:42', '2'),
(12, '2222275531364', 1, 'Queen Cake', 1, 20.00, 20.00, '2025-01-20 15:33:42', '2'),
(13, '2222984788922', 1, 'Tea Scone', 1, 100.00, 100.00, '2025-01-20 15:33:42', '2'),
(14, '2222850988291', 2, 'American Doughnut', 1, 50.00, 50.00, '2025-01-20 15:33:50', '2'),
(15, '2222793046557', 2, 'Banana Cake Slice', 1, 150.00, 150.00, '2025-01-20 15:33:50', '2'),
(16, '2222567793731', 2, 'Big Cookies', 1, 50.00, 50.00, '2025-01-20 15:33:50', '2'),
(17, '2222439738761', 2, 'Bread Roll', 1, 25.00, 25.00, '2025-01-20 15:33:50', '2'),
(18, '2222103452280', 2, 'Block', 1, 50.00, 50.00, '2025-01-20 15:33:50', '2'),
(19, '2222966048975', 2, 'Block Cake', 1, 100.00, 100.00, '2025-01-20 15:33:50', '2'),
(20, '2222629516563', 2, 'Meat Pie', 1, 100.00, 100.00, '2025-01-20 15:33:50', '2'),
(21, '2222913955883', 2, 'Bread', 1, 65.00, 65.00, '2025-01-20 15:33:50', '2'),
(22, '2222336968222', 2, 'Chocolate Rolade', 1, 250.00, 250.00, '2025-01-20 15:33:50', '2'),
(23, '2222590991336', 2, 'Doughnut', 1, 50.00, 50.00, '2025-01-20 15:33:50', '2'),
(24, '2222532926545', 2, 'Queen Cake Packet', 1, 120.00, 120.00, '2025-01-20 15:33:50', '2'),
(25, '2222275531364', 2, 'Queen Cake', 1, 20.00, 20.00, '2025-01-20 15:33:50', '2'),
(26, '2222984788922', 2, 'Tea Scone', 1, 100.00, 100.00, '2025-01-20 15:33:50', '2'),
(27, '2222966048975', 3, 'Block Cake', 1, 100.00, 100.00, '2025-04-01 18:57:01', '1'),
(28, '2222336968222', 4, 'Chocolate Rolade', 1, 250.00, 250.00, '2025-04-01 18:58:01', '1'),
(29, '2222913955883', 4, 'Bread', 1, 65.00, 65.00, '2025-04-01 18:58:01', '1'),
(30, '2222439738761', 4, 'Bread Roll', 1, 25.00, 25.00, '2025-04-01 18:58:01', '1'),
(31, '2222439738761', 5, 'Bread Roll', 1, 25.00, 25.00, '2025-04-01 20:54:45', '1'),
(32, '2222966048975', 5, 'Block Cake', 1, 100.00, 100.00, '2025-04-01 20:54:45', '1'),
(33, '2222532926545', 6, 'Queen Cake Packet', 1, 120.00, 120.00, '2025-04-04 10:20:23', '1'),
(34, '2222629516563', 6, 'Meat Pie', 1, 100.00, 100.00, '2025-04-04 10:20:23', '1'),
(35, '2222966048975', 6, 'Block Cake', 1, 100.00, 100.00, '2025-04-04 10:20:23', '1'),
(36, '2222439738761', 7, 'Bread Roll', 1, 25.00, 25.00, '2025-04-04 10:57:28', '1'),
(37, '2222966048975', 7, 'Block Cake', 1, 100.00, 100.00, '2025-04-04 10:57:28', '1'),
(38, '2222913955883', 7, 'Bread', 1, 65.00, 65.00, '2025-04-04 10:57:28', '1'),
(39, '2222532926545', 7, 'Queen Cake Packet', 1, 120.00, 120.00, '2025-04-04 10:57:28', '1'),
(40, '2222629516563', 7, 'Meat Pie', 3, 100.00, 300.00, '2025-04-04 10:57:28', '1'),
(41, '2222439738761', 8, 'Bread Roll', 1, 25.00, 25.00, '2025-04-07 00:36:02', '1'),
(42, '2222966048975', 8, 'Block Cake', 1, 100.00, 100.00, '2025-04-07 00:36:02', '1'),
(43, '2222913955883', 8, 'Bread', 1, 65.00, 65.00, '2025-04-07 00:36:02', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `gender` varchar(6) NOT NULL DEFAULT 'male',
  `deletable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `date`, `image`, `role`, `gender`, `deletable`) VALUES
(1, 'Muasya', 'sammuasya04@gmail.com', '$2y$10$5Rs6mDrYxjj2GORdpC1Nb.rhETBHRYHtRWQdO0QFi3XE72LWBkYqy', '2025-01-20 00:00:00', NULL, 'admin', 'male', 0),
(2, 'Lily', 'lilyndanu2002@gmail.com', '$2y$10$U/k.W7V9a2bAIoxBMGXAReHkPHvffBklh.aezxrs.5LjSKk5toOQG', '2025-01-20 14:45:58', NULL, 'supervisor', 'female', 1),
(3, 'Victor', 'victorlimua@gmail.com', '$2y$10$Vtg6L.sDDqNtAnFPoECoDehOZsgATPBV7zoMzdm4cF4RrqLLCkl6m', '2025-01-20 16:02:31', NULL, 'cashier', 'male', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `description` (`description`),
  ADD KEY `qty` (`qty`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `views` (`views`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `description` (`description`),
  ADD KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `date` (`date`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
