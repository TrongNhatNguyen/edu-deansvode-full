-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 20, 2020 at 09:34 AM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deansvote`
--

--
-- Dumping data for table `palm`
--

INSERT INTO `palm` (`id`, `value`, `name`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'LOCAL Reference', 100, 1, '2010-07-16 15:48:52', '2010-07-16 15:48:52'),
(2, 2, 'GOOD Business School', 100, 1, '2010-07-16 13:13:10', '2010-07-16 14:57:21'),
(3, 3, 'EXCELLENT Business School', 100, 1, '2010-07-16 13:12:39', '2010-07-30 12:15:24'),
(4, 4, 'TOP Business School', 100, 1, '2010-07-16 13:12:16', '2010-07-16 14:57:13'),
(5, 5, 'UNIVERSAL Business School', 100, 1, '2010-07-16 13:11:55', '2010-10-29 12:36:59');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
