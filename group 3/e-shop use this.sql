-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2024 at 03:41 AM
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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalPrice` int(11) NOT NULL,
  `Products` text DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'MWK',
  `payment_status` varchar(20) DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `ChargeID` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `User_ID`, `created_at`, `TotalPrice`, `Products`, `currency`, `payment_status`, `transaction_id`, `ChargeID`) VALUES
(133, 4, '2024-09-12 01:33:01', 330000, '[{\"ID\":null,\"Image\":\"http://localhost/school-main/group%203/images/pascal-m-4PchFKrUw84-unsplash.jpg\",\"Name\":\"Mouse\",\"Price\":\"MWK 10000\"},{\"ID\":\"15\",\"Image\":\"http://localhost/school-main/group%203/images/sabrianna-Y_bxfTa_iUA-unsplash.jpg\",\"Name\":\"Diamond-Ring\",\"Price\":\"MWK 200000\"},{\"ID\":\"16\",\"Image\":\"http://localhost/school-main/group%203/images/paolo-giubilato-ZwKCWVFdrcs-unsplash.jpg\",\"Name\":\"I-phone\",\"Price\":\"MWK 120000\"}]', 'MWK', 'completed', NULL, 'd6101e858af28b5e19ddec2bbf7f9d15'),
(134, 4, '2024-09-12 01:38:15', 330000, '[{\"ID\":null,\"Image\":\"http://localhost/school-main/group%203/images/pascal-m-4PchFKrUw84-unsplash.jpg\",\"Name\":\"Mouse\",\"Price\":\"MWK 10000\"},{\"ID\":\"15\",\"Image\":\"http://localhost/school-main/group%203/images/sabrianna-Y_bxfTa_iUA-unsplash.jpg\",\"Name\":\"Diamond-Ring\",\"Price\":\"MWK 200000\"},{\"ID\":\"16\",\"Image\":\"http://localhost/school-main/group%203/images/paolo-giubilato-ZwKCWVFdrcs-unsplash.jpg\",\"Name\":\"I-phone\",\"Price\":\"MWK 120000\"}]', 'MWK', 'completed', NULL, '854e76da8c8c90be279bbd062f6e0e0a');

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

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
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
(14, 'Mouse', 'images\\pascal-m-4PchFKrUw84-unsplash.jpg', 10000, 0, '2024-09-04 19:27:26', 'okcposdcpsoa', 'Electronics', 23),
(15, 'Diamond-Ring', 'images\\sabrianna-Y_bxfTa_iUA-unsplash.jpg', 200000, 0, '2024-09-05 17:15:29', 'xbvxc  bvsdcsdcad', 'women', 54),
(16, 'I-phone', 'images\\paolo-giubilato-ZwKCWVFdrcs-unsplash.jpg', 120000, 0, '2024-09-11 09:58:59', 'A cheap old i-phone', 'Electronics', 1),
(24, 'bn', 'images\\paolo-giubilato-ZwKCWVFdrcs-unsplash.jpg', 45678, 0, '2024-09-12 01:09:42', 'sdfghjkk', 'Electronics', 233),
(31, 'as', 'images\\pascal-m-4PchFKrUw84-unsplash.jpg', 342, 0, '2024-09-12 01:29:06', 'sdfgdbj', 'Electronics', 243);

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
  `Email` varchar(100) NOT NULL,
  `roles` text DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Password`, `Country`, `PhoneNumber`, `Email`, `roles`) VALUES
(1, 'Arthony', 'Kanjira', '$2y$10$vEvvl37Hic4qSxdg39PzFu6SAXAj/c3uCa7AfJh9O/uTZ19glCKZS', 'Malawi', '885620899', 'arthonykanjira444@gmail.com', 'customer'),
(4, 'Admin', 'Kanjira', '$2y$10$BNgl1UaC6ZR0bIpsArGCgO.1H1WT/FxhighXGd1Tar6BlD146qmCS', 'Malawi', '885620896', 'admin@gmail.com', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`User_ID`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
