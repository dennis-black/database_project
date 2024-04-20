-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2024 年 04 月 20 日 13:21
-- 伺服器版本： 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP 版本： 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `crisperBox`
--

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `ID` int(5) NOT NULL COMMENT 'ID',
  `role` varchar(10) NOT NULL COMMENT '身分組',
  `userRealName` varchar(25) NOT NULL COMMENT '使用者姓名',
  `email` varchar(55) NOT NULL COMMENT 'Email',
  `phoneNumber` varchar(10) NOT NULL COMMENT '手機號碼',
  `bloodType` varchar(8) NOT NULL COMMENT '血型',
  `birthday` varchar(20) NOT NULL COMMENT '生日',
  `username` varchar(25) NOT NULL COMMENT '使用者名稱',
  `password` varchar(150) NOT NULL COMMENT '密碼'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`ID`, `role`, `userRealName`, `email`, `phoneNumber`, `bloodType`, `birthday`, `username`, `password`) VALUES
(7, 'admin', '管理員', 'admin@chrisperbox', 'n/a', 'n/a', 'n/a', 'admin', '$2y$10$2uoWEYY.C1u/PMCRvlDiT.D0zcsHPTttzgKKkH0wIF/A1XQ5YalCa'),
(8, 'root', '超級管理員', 'root@chrisperbox', 'n/a', 'n/a', 'n/a', 'root', '$2y$10$jO0QRC8uG1Ajipu.Zx9VKeQd0YSzI78Tvdc/Vap4xke.Y8PuvJdvu'),
(13, 'user', '測試使用者1', 'test1@crisperbox', '1234567890', 'B', '04/20/2024', 'test1', '$2y$10$oO0EwUX7Q7eYbc5n6qw4iOPKXRfO7eJwknrgwiOYoRj/W72rd4ZcS'),
(14, 'user', '測試使用者2', 'test2@crisperbox', '1234567890', 'Rh-', '04/20/2024', 'test2', '$2y$10$MsPLpe7MYRKpB5up5IL.8eDudGdZluVnnmmZvA5IFmo97gH6zZWMu'),
(15, 'user', '測試使用者3', 'test3@crisperbox', '1234567890', 'other', '04/20/2024', 'test3', '$2y$10$nw92naXu2khE9tu3nXYhs.Hrqgo4kLCPzXSek30YAbae9CNjT0Kie');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
