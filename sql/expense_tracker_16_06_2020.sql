-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2020 at 05:21 PM
-- Server version: 5.7.30-0ubuntu0.18.04.1
-- PHP Version: 7.3.18-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` enum('deposit','expense') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`, `parent_id`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'expense', 'deposit', 0, 9, 1, '2020-06-14 21:18:06', '2020-06-15 17:17:08'),
(2, 'no name', 'deposit', 0, 9, 1, '2020-06-14 21:18:17', '2020-06-15 17:12:15'),
(3, 'home-rent', 'deposit', 1, 9, 1, '2020-06-14 21:20:47', '2020-06-15 17:17:33'),
(4, 'home-rent', 'deposit', 1, 1, 1, '2020-06-14 21:40:33', '2020-06-14 21:40:33'),
(5, 'home-rent', 'deposit', 1, 1, 1, '2020-06-14 21:40:48', '2020-06-14 21:40:48'),
(6, 'test cat', 'deposit', 0, 1, 1, '2020-06-15 14:01:04', '2020-06-15 14:01:04'),
(7, 'test cat', 'deposit', 0, 1, 1, '2020-06-15 14:01:36', '2020-06-15 14:01:36'),
(8, 'no name', 'deposit', 0, 1, 1, '2020-06-15 17:11:59', '2020-06-15 17:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` float(14,2) NOT NULL,
  `description` text,
  `cause` varchar(32) NOT NULL,
  `explanation` varchar(128) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `category_id`, `amount`, `description`, `cause`, `explanation`, `status`, `created_by`, `created_at`) VALUES
(1, 3, 10.00, NULL, 'deposit', '10 deposit for home-rent', 1, 1, '2020-06-15 15:39:48'),
(2, 3, -6.00, NULL, 'expense', '6 Tk expense for home-rent', 1, 1, '2020-06-15 15:40:47'),
(3, 3, -6.55, NULL, 'expense', '6.555 tk expense for home-rent', 1, 1, '2020-06-15 16:24:03'),
(4, 3, -6.55, NULL, 'expense', ' number_format(6.555,2) tk expense for home-rent', 1, 1, '2020-06-15 16:25:18'),
(5, 3, -6.55, NULL, 'expense', ' {number_format(6.555,2)} tk expense for home-rent', 1, 1, '2020-06-15 16:25:48'),
(6, 3, -6.55, NULL, 'expense', '6.56tk expense for home-rent', 1, 1, '2020-06-15 16:26:23'),
(7, 3, 6.56, NULL, 'expense', '6.56tk expense for home-rent', 1, 1, '2020-06-15 16:27:02'),
(8, 3, -6.56, NULL, 'expense', '6.56tk expense for home-rent', 1, 1, '2020-06-15 16:28:00'),
(9, 3, -6.56, NULL, 'expense', '6.56 tk expense for home-rent', 1, 1, '2020-06-15 16:28:25'),
(10, 3, -6.56, NULL, 'expense', '6.56 tk expense for home-rent', 1, 1, '2020-06-15 16:31:48'),
(11, 3, -6.56, NULL, 'expense', '6.56 tk expense for home-rent', 1, 1, '2020-06-15 17:10:18'),
(12, 3, -6.56, NULL, 'expense', '6.56 tk expense for home-rent', 1, 1, '2020-06-15 17:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Shahriyear', 'admin@admin.com', '$2y$12$85do14U6Thhh7MbAvhpCrugNx/15gqy1bpg6H.VR0d3/ALgNw0Vey', 1, 1, '2020-06-14 15:35:22', '2020-06-14 15:36:37'),
(2, 'update', 'abc@gmail.com', '$2y$10$8CU7IOCv.RYREhmr1wxq5u54Zgdf0bDo2c9Fv9HhMnF45eOHvx9Oq', 9, 1, '2020-06-15 14:46:04', '2020-06-15 14:49:13'),
(3, 'test cat', 'admin2@admin.com', '$2y$10$ybJZMMBaMAiBqHJZt9AGtu50UZehfUhdJV2ugsrMwpQZanj5.cCKu', 1, 1, '2020-06-15 14:46:13', '2020-06-15 14:46:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
