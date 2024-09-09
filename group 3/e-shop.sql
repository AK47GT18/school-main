-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 01:00 PM
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
-- Database: `e-shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `Product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expirationTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `created_at`, `expirationTime`) VALUES
(30, 'arthonykanjira444@gmail.com', '504de267ce0cffb82a56', '2024-08-27 13:06:07', '2024-08-27 15:21:07'),
(31, 'arthonykanjira444@gmail.com', '228e326a62c0c49eeea4', '2024-08-27 13:10:47', '2024-08-27 15:25:47'),
(32, 'arthonykanjira444@gmail.com', '1f5135d1a153677db26b', '2024-08-27 13:21:08', '2024-08-27 15:36:08'),
(33, 'arthonykanjira444@gmail.com', '9eba8725e399fcc3a015', '2024-08-27 13:30:02', '2024-08-27 15:45:02'),
(34, 'arthonykanjira444@gmail.com', 'ddde0db4297f979d3d49', '2024-08-27 13:44:55', '2024-08-27 15:59:55'),
(35, 'arthonykanjira444@gmail.com', '1ab2b9cac6ba97fdc52c', '2024-08-27 13:46:53', '2024-08-27 16:01:53'),
(36, 'arthonykanjira444@gmail.com', '7f2fb8f93ab7166c2d0a', '2024-08-27 13:51:57', '2024-08-27 16:06:57'),
(37, 'arthonykanjira444@gmail.com', '2d9c99e49ba0da80fe99', '2024-08-27 13:53:06', '2024-08-27 16:08:06'),
(38, 'arthonykanjira444@gmail.com', '0656ba9563a8258ef109', '2024-08-27 13:55:32', '2024-08-27 16:10:32'),
(41, 'arthonykanjira444@gmail.com', 'e494efc41c682481652c', '2024-08-30 07:47:06', '2024-08-30 10:02:06'),
(42, 'arthonykanjira444@gmail.com', 'd599a7e1ea652016042a', '2024-08-30 07:54:00', '2024-08-30 10:09:00'),
(43, 'arthonykanjira444@gmail.com', 'b4afa02d259965d14bdc', '2024-08-30 11:59:15', '2024-08-30 14:14:15'),
(44, 'arthonykanjira444@gmail.com', '8daf0543a31b7c886db1', '2024-08-30 12:00:25', '2024-08-30 14:15:25'),
(45, 'arthonykanjira444@gmail.com', '0498eafe30dd58bdd6ac', '2024-08-30 14:30:15', '2024-08-30 16:45:15'),
(46, 'arthonykanjira444@gmail.com', '3ca7d4eda7053574791c', '2024-08-30 14:43:25', '2024-08-30 16:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `buy_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `inventory_stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `Image`, `price`, `buy_count`, `created_at`, `description`, `category`, `inventory_stock`) VALUES
(14, 'Mouse', 'images\\pascal-m-4PchFKrUw84-unsplash.jpg', 10000.00, 0, '2024-09-04 19:27:26', 'okcposdcpsoa', 'Electronics', 23),
(15, 'Diamond-Ring', 'images\\sabrianna-Y_bxfTa_iUA-unsplash.jpg', 200000.00, 0, '2024-09-05 17:15:29', 'xbvxc  bvsdcsdcad', 'Accessories ', 54);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Country` varchar(50) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Password`, `Country`, `PhoneNumber`, `Email`) VALUES
(1, 'Arthony', 'Kanjira', '$2y$10$vEvvl37Hic4qSxdg39PzFu6SAXAj/c3uCa7AfJh9O/uTZ19glCKZS', 'Malawi', '885620899', 'arthonykanjira444@gmail.com'),
(4, 'Arthony', 'Kanjira', '$2y$10$BNgl1UaC6ZR0bIpsArGCgO.1H1WT/FxhighXGd1Tar6BlD146qmCS', 'Malawi', '885620896', 'admin@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`User_ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `Product_id` (`Product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`Product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
