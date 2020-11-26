-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 19, 2020 at 07:16 AM
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
-- Dumping data for table `zone`
--

INSERT INTO `zone` (`id`, `name`, `slug`, `image`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Africa', 'africa', 'africa.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(2, 'North America', 'north-america', 'north_america.jpg', 0, 1, '2007-10-02 14:42:57', '2011-10-04 11:29:05'),
(3, 'Latin America', 'latin-america', 'latin_america.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(4, 'Central Asia', 'central-asia', 'center_asia.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(5, 'Eurasia & the Middle East', 'eurasia---the-middle-east', 'mid_eurasia.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(6, 'Eastern Europe', 'eastern-europe', 'eastern_europe.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(7, 'Western Europe', 'western-europe', 'western_europe.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(8, 'Oceania', 'oceania', 'oceania.jpg', 0, 1, '2007-10-02 14:42:57', '2007-10-02 14:42:57'),
(9, 'Far East Asia', 'far-eastern-asia', 'far_east_asia.jpg', 0, 1, '2007-10-02 14:42:57', '2011-01-27 17:37:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
