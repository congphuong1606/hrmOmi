-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2018 at 03:29 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_category_id` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_order` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_category_id`, `description`, `current_order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Đào tạo hội nhập', '1', NULL, '2018-07-04 17:54:20', '2018-07-04 17:54:20'),
(2, 2, 'Đào tạo hội nhập', '2', NULL, '2018-07-04 17:54:51', '2018-07-04 17:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `course_score_excel_files`
--

CREATE TABLE `course_score_excel_files` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PRODUCT', 'Product', 'Sản Phẩm', '2018-07-03 01:39:02', '2018-07-03 01:39:04', NULL),
(2, 'BrSE & PM', 'BrSE & PM', 'BrSE & PM', '2018-07-03 18:50:55', '2018-07-03 18:50:55', NULL),
(3, 'HCNS', 'Hành chính Nhân sự', 'Hành chính Nhân sự', '2018-07-03 18:51:09', '2018-07-03 18:51:09', NULL),
(4, 'MOBILE', 'Mobile', 'Phòng Mobile', '2018-07-03 18:51:27', '2018-07-03 18:51:27', NULL),
(5, 'SYSTEM', 'System', 'Phòng System development', '2018-07-03 18:52:29', '2018-07-03 18:52:29', NULL),
(6, 'QAQC', 'QA & QC', 'Phòng QA & QC', '2018-07-03 18:52:48', '2018-07-03 18:52:48', NULL),
(7, 'COMTOR', 'Comtor', 'Team Comtor', '2018-07-03 18:53:07', '2018-07-03 18:53:07', NULL),
(8, 'BP', 'Phòng Business Promotion', 'Phòng Business Promotion', '2018-07-03 18:53:28', '2018-07-03 18:53:28', NULL),
(9, 'Ban Giám Đốc', 'Ban Giám Đốc', 'Ban Giám Đốc', '2018-07-03 19:19:14', '2018-07-03 19:19:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fcm_device_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `email`, `fcm_device_token`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin@ominext.com', 'c0ZFcIjNZ1I:APA91bEa0JPPqfmC3gRSvvN41s88qURLLIE2SwZ_o911X4-LMpSfoDPi5naRoVTAS-06UW4-8UCWZQElXDJeV4s3uNvR1FspCJJ_BOoBaJtSh9xSDhADFBlDWRnGshlEiKjZviCZEULg', 2, '2018-07-03 18:39:47', '2018-07-03 18:39:47', NULL),
(61, 'sonla@ominext.com', 'd7qZBupem3E:APA91bHWF90xOhr1SX4b4gxwWSThdx8aZ-SmCtMYugKw8ZkGzW6FMd3vF7265aKgKJ9BU2chw8NINwIYx8HzqOxpkGK8xMbutUENe5Wit6zMhRODItYjRaTT0uG_TYtKrPyxyVFOhLxcJD-REKw6DAPLEgmaWAtViQ', 1, '2018-07-06 11:06:06', '2018-07-06 11:06:06', NULL),
(65, 'admin@ominext.com', 'f2RyH9gDfrE:APA91bF4U3ni9f8mWyROcZoxY5MpN1DG4dX2XkAkgEWICeXk1FrHEPL9nuhCTMvazINdhl4g-BgkT0h8py4ongXX_OGZyl4niQSA-gL2wXFWVaVVkp0QbMm_2kGW4_G8E2mzDVI0zH9x', 2, '2018-07-06 11:48:47', '2018-07-06 11:48:47', NULL),
(74, 'sonla@ominext.com', 'fcWAErkKK_I:APA91bHAiASFfzpNAYXq2qAX_cDSneKjnylShjZKxQYOG-QbDH1rw1H3p3dPjbsyZNRFmp18tW29gcFjED6guDtrOXd6ht7kSUOBwMHvv_afZ09hNIoqHtecFL1mMdi2xs1wAkC8jgDzL3kUj48paUqztMMtdAKsfg', 1, '2018-07-06 16:26:02', '2018-07-06 16:26:02', NULL),
(76, 'sonla@ominext.com', 'deyDuJt-rno:APA91bH2u7S2UglxILhcxChYBY6BCOVu7MRXQarxSGmyBOJqi16_InUAxsrtc-8N6v6aeqKxUmhUk5JclVVpGJ5F5cYLqcZsIhdPt2_sTfqljdYQF7ycHY-QjYCUW59lupuBZnMwZxjp9gaNJ3YU4uyc3WXQLgjeQQ', 0, '2018-07-09 17:58:25', '2018-07-09 18:01:03', NULL),
(83, 'sonla@ominext.com', 'eOpmy0xBaS8:APA91bF5b7fvIOti9RhPxFP0iONfBi_J5eML3w5XVZD4iPBsUi2hngyVhsBThI7EqSrR9dfmD9Ry0XHeeduGQBuznSHR-HZlj1JlQND7uBmaBNe4eTdihy3RqsdfV6S0vBQorwkDOpEAt8NxlHdOLmTUmMge0vYYCg', 0, '2018-07-11 09:47:56', '2018-07-12 17:34:17', NULL),
(86, 'sonla@ominext.com', 'fPjfbs3to5o:APA91bGQFRBb1HRHXECkRykyGNlCPO8A44WLzDL2-qkC_M1fTuXqXajDnmSHF84TWMUIzbvZa2ijOqNRyXSGpmO10zX56eOgkg2pOkeOW18SXI24yYH73Lp_7gqLcwWjnehnWBxFtpASAYorpGk3CVDOVqZhKzOBBg', 0, '2018-07-12 18:09:41', '2018-07-12 18:09:41', NULL),
(90, 'sonla@ominext.com', 'fiwLMq1873c:APA91bGJXxA_MWLEzXvWvpFqDnWszVn9r3hbma4cLGqPO0kpRZejyoJMHPNmKYQBeWv4QBaKnN5t-wgL-fPPSjLIfyIaq4uI_NF6OGAlkLYO6rJBl9unB6ZEn13PngsX-J-1TU_52sWFoTOB60jvbfV6hLhP30Xk4Q', 0, '2018-07-13 14:54:07', '2018-07-13 14:54:07', NULL),
(99, 'chinhdh@ominext.com', 'cBQd-nPvhzU:APA91bEBPWWD-8EWG33dqIinggFJyio-Dp1QVnDfsiJAf1Rb7gBFsFLuLZuBB6biVS-_psW82VO9nxBKyoLH8YBM07-UmtuDZENPNNCg6F2Cc-A0KsxVJfEow4vv73zxHaO_-bYLEIIj', 2, '2018-07-16 11:18:04', '2018-07-16 11:18:04', NULL),
(102, 'sonla@ominext.com', 'fkCfVyCoyG8:APA91bH56gIySAiPhvlAS7lDaAPLdn0Z6m5A_0G5P1BLr607uDMH72k3ux-f0k3qWdwGzOJX0NAaMVmEby0QMjPWohLI9Z0rFh_Xp68f-g_3q7f79BggiUPsyEIz3SQYvudLq1xoBvY2nfLK8yMH87Lg_OQbUITLrw', 0, '2018-07-17 11:20:55', '2018-07-17 14:34:39', NULL),
(108, 'thanhnc@ominext.com', 'cn9FXlkYp4s:APA91bFn5zq_Pcsh9ZAnOCO78Jilh0YNDVBp4IDwVQc8Byc7XcIcaVXac7F-DKfWDseh18PeylvZLlywkvAy5GvkGJj32XYrFUMcjKWk9Yz1AyHF6DDB9-HaoHldzGRnQaYueAjldxuEGLUhM9llDaWf4tAbNEjZDA', 0, '2018-07-17 15:51:52', '2018-07-18 10:32:33', NULL),
(110, 'sonla@ominext.com', 'eesFelchSmM:APA91bFk6c_K2rcbwSkJUwjzHkC7UjNHqfGAakzTQjh6yueJsgmXRNRbBskOcFImNhm-YfJXYv4ZXM8fnRRv9HqHh0Oe3vUxkm6DVczIW2XE-f7rpMOU1dmVahYRFCqXhzK_TSdpHQx8D25ahHA0mviDmWPWebn84Q', 0, '2018-07-17 16:17:20', '2018-07-17 16:17:20', NULL),
(113, 'admin@ominext.com', 'eBLs2PNwmNw:APA91bEudGwSNfKff-baFPguZuhMKDQYcFDqkMmxIZAWMvmTuB8Yo4EDXGSpOqbM22W2uG6IXvEGG5nYTPNZgC2dDQ7qqCwnEUyW_Ly4byAO6GbTZUbfk5PoYqempxBZOud0oWzjYrU2', 2, '2018-07-18 10:16:56', '2018-07-18 10:16:56', NULL),
(115, 'thanhnc@ominext.com', 'eV6PpylEpVM:APA91bFH52WOZKDgs7yiPUYs7e8Nu8zYVJAnzo1nyFlPCsJNiUOZOmggaeCY5jqP6AgiDKf9n5syAn_lpUHxOLTNuOqS__uh8km3rGRoxBKReiBnIDOlsEf0PXyB4Q5krIoB1V_wIB16XFDOrCIcRmwR1If3rlmjXA', 0, '2018-07-18 12:10:44', '2018-07-18 12:10:44', NULL),
(118, 'sonla@ominext.com', 'e2JfaMSw7Dw:APA91bFi_2FtUvg9h_o_1msF2cDVcFoXPnTdPyPWwAZhvIZIzWD9MkTJxOFrZJlhFOFNNkXnIXEjx4ZgmNLBp4KbRHErJusXmz7RjqIYVccKWJEKo4VRQQW7uq5b84m-6emeM73F5UlFdBXI2r7JiEbJXCBg_QLt7g', 0, '2018-07-18 14:50:33', '2018-07-18 14:50:33', NULL),
(120, 'sonla@ominext.com', 'c-zPMXS9iFM:APA91bFKFuxS3ryU3fvc7GtoYwAoN8iMPW1zlTMkM9YZ2C07i6_2AxD3j2K5z-bGi2ldQzKwZ_Mwmos62Z8BU9RwdXmCpHPnzHDnVIHebsBYHXuVwNP-OPLQj0RP5St-YUEGgIOzEcCKAs34ZoZRY7EJMJrI_y1OwQ', 0, '2018-07-18 15:17:02', '2018-07-18 15:17:02', NULL),
(125, 'admin@ominext.com', 'c_0GuLNauDE:APA91bFeJSzO4aR7HWbK3cdtuo9VAbEc1xRlV3-o5rpKqoFlhr9_HIaF9n-QZxrx1q2KcxGsnKCw-qqz-dNd5DTw63LTB4u6O6N1y9BO5MQmfblQjm9P_BLt8abzDpybdjbzvY60jtb9', 2, '2018-07-18 18:29:58', '2018-07-18 18:29:58', NULL),
(132, 'sonla@ominext.com', 'crO3rjCp8t0:APA91bGW4miZJMBUvB2DZG-y2Anet2n7xNOI69TSz9yTKmfGQ9uWJ6-j5NEVk9ThGlfOhW2HTvgMS_BVc0WcrIBrNbUY8uHGempJDp8ZAIVqs5v8b_YgMg_n0Vnu0TnwrrVmNAeeLPe9erFsWZ31LZeA-fPlSyaGHA', 1, '2018-07-19 10:05:05', '2018-07-19 10:05:05', NULL),
(133, 'admin@ominext.com', 'fouaD5sIqLI:APA91bGv7zczjLgQDkAQ4TRsLV786DCqDaOHYp2tzFp9t57sAcMgiNA4GxGjcdsiCmHPCQew2wiKmFGhEHPXotrOS84raNUeZ3GZcOLYGb-uANf4nJeJ7VnSJsSh6y2hxlVEaKZzsiF5', 2, '2018-07-19 10:43:00', '2018-07-19 10:43:00', NULL),
(162, 'phuongnv@ominext.com', 'cQK6uzMWRG4:APA91bECpAUfP9NADeM9qOusbhQ34VyBPAdjAmaC9p8MwmgQcPhHEzMAmJMe03sClxwr93xltlDPSIvgyezD5Wz5_OI_fBleYSb67y9U60wOlTnKfxEpF0Q6IPMZ-qyzWQwBv9vsBgxX', 2, '2018-08-23 09:48:38', '2018-08-23 09:48:38', NULL),
(163, 'admin@ominext.com', 'fQHxPFVcZQM:APA91bGIpE1XB2hr3LShFtFFg-fZW0xsasSFuPwFnSCp-Tti9SDqo-EtWQr3mjyhOQiTbNE3Otf0yyqsbvZY41F_1Kn0wt8gjhSk5hAlGNz0CZUY0sI6bWzmjVf2cdt7lx0PnoPD0D4r', 2, '2018-09-10 01:20:32', '2018-09-10 01:20:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` tinyint(4) DEFAULT NULL,
  `position_id` tinyint(4) DEFAULT NULL,
  `job_status_id` tinyint(4) DEFAULT NULL,
  `birth_day` date DEFAULT NULL,
  `identification_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_date` date DEFAULT NULL,
  `identification_place_of` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_code` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temporary_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chatwork_account` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_account` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_link` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `personal_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_status_id` int(11) DEFAULT NULL,
  `employee_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_code` int(11) DEFAULT NULL,
  `japanese_certificate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_off_accumulated_permit` float NOT NULL DEFAULT '0',
  `day_off_accumulated_ot` float NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `training_date` date DEFAULT NULL,
  `official_date` date DEFAULT NULL,
  `late_reason_id` int(11) DEFAULT NULL,
  `bank_user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_user` text COLLATE utf8mb4_unicode_ci,
  `distance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day_off_accumulated_permit_temp` float NOT NULL DEFAULT '0',
  `day_off_accumulated_ot_temp` float NOT NULL DEFAULT '0',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `direct_manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `full_name`, `department_id`, `position_id`, `job_status_id`, `birth_day`, `identification_number`, `identification_date`, `identification_place_of`, `tax_code`, `permanent_address`, `temporary_address`, `bank_number`, `bank_name`, `phone_number`, `chatwork_account`, `skype_account`, `facebook_link`, `created_at`, `updated_at`, `deleted_at`, `personal_email`, `avatar`, `working_status_id`, `employee_code`, `attendance_code`, `japanese_certificate`, `day_off_accumulated_permit`, `day_off_accumulated_ot`, `email`, `update_date`, `check_in_date`, `training_date`, `official_date`, `late_reason_id`, `bank_user_name`, `bank_branch`, `contact_user`, `distance`, `day_off_accumulated_permit_temp`, `day_off_accumulated_ot_temp`, `gender`, `direct_manager_id`) VALUES
(1, 19, 'Nguyễn Phi Hùng', 1, 1, 1, '1994-10-16', '123456789', '2018-07-03', 'Hải Dương CA', '123456789', NULL, NULL, NULL, NULL, '1101400000', NULL, NULL, NULL, '2018-07-03 01:42:13', '2018-07-17 16:46:07', NULL, NULL, 'img_5b3e685f921a28.434853291530816607.5984.png', 1, '9999', NULL, NULL, 0, 0, 'hungnp@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4),
(4, 1, 'Đặng Hữu Chính', 5, 1, 1, '1993-07-29', '123456789', NULL, 'Hưng Yên', '43557567345345', '130 Cầu Giấy', '130 Cầu Giấy', '123456789', 'acb', '123456789', 'eeeeee', 'eeeeee', 'eeeeeee', '2018-07-04 09:49:07', '2018-07-17 15:24:57', NULL, NULL, 'img_5b3f33429324d1.008441371530868546.6027.png', 1, '16', NULL, NULL, 0, 0, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, 'hưng yên', NULL, NULL, 0, 0, 0, NULL),
(5, 5, 'Đào Hồng Hạnh', 3, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'acb', '0123456789', NULL, NULL, NULL, '2018-07-04 20:31:36', '2018-07-13 14:54:33', NULL, NULL, NULL, 1, '4', NULL, NULL, 0, 0, 'hanhdh@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(6, 6, 'Nguyễn Chí Thanh', 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-04 20:33:03', '2018-07-18 10:19:46', NULL, NULL, NULL, 1, '3', NULL, NULL, 0, 0, 'thanhnc@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 5),
(7, 7, 'Nguyễn Văn Phượng', 4, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-04 20:34:02', '2018-07-13 14:54:33', NULL, NULL, NULL, 1, '6', NULL, NULL, 0, 0, 'phuongnv@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(8, 8, 'Nguyễn Huy An', 5, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-04 20:34:55', '2018-07-13 14:54:33', NULL, NULL, NULL, 1, '10', NULL, NULL, 0, 0, 'annh@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(9, 9, 'Nguyễn Minh Trang', 3, 13, 1, '2018-07-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0988777666', NULL, NULL, NULL, '2018-07-04 20:35:54', '2018-07-13 14:54:34', NULL, NULL, NULL, 1, '8', NULL, NULL, 0, 0, 'trangnm@ominext.com', '2018-07-04', '2018-06-04', '2018-03-13', '2018-05-14', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(10, 10, 'Lương Anh Sơn', 6, 11, 3, '2018-07-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '09876543221', NULL, NULL, NULL, '2018-07-04 20:36:52', '2018-07-18 16:55:29', NULL, NULL, 'img_5b4cdcebda9571.797858981531763947.8954.png', 1, '80', NULL, NULL, 0, 0, 'sonla@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'abcd', NULL, 0, 0, 0, NULL),
(11, 11, 'Nguyễn Khánh Linh', 6, 10, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-04 20:38:06', '2018-07-13 14:54:34', NULL, NULL, NULL, 1, '11', NULL, NULL, 0, 0, 'linhnk@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(12, 12, 'Nguyễn Quang Vinh', 7, 12, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-04 20:38:59', '2018-07-13 14:54:34', NULL, NULL, NULL, 1, '13', NULL, NULL, 0, 0, 'vinhnq@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(13, 13, 'Đào Minh Phượng', 3, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-05 09:02:38', '2018-07-13 14:54:34', NULL, NULL, NULL, 1, '1', NULL, NULL, 0, 0, 'phuongdaominh@ominext.com', '2018-07-05', '2018-07-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(14, 14, 'Dương Thị Bích Ngọc', 3, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '01234578', NULL, NULL, NULL, '2018-07-05 09:03:56', '2018-07-13 14:54:34', NULL, NULL, 'img_5b3e6a63529f21.537967051530817123.3384.png', 1, '17', NULL, NULL, 0, 0, 'ngocdtb@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(15, 15, 'Vũ Hương Liên', 6, 2, 1, '2018-07-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '132132132132', NULL, NULL, NULL, '2018-07-05 18:51:56', '2018-07-17 18:16:58', NULL, NULL, NULL, 1, '18', NULL, NULL, 0, 0, 'lienvh@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(16, 16, 'Đỗ Thị Mai', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0988123432', NULL, NULL, NULL, '2018-07-05 18:51:56', '2018-07-13 14:54:34', NULL, NULL, NULL, 2, '9', NULL, NULL, 0, 0, 'maidt@ominext.com', '2018-07-10', '2018-07-30', '2018-07-31', '2018-07-05', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL),
(17, 17, 'Đỗ Thị Thùy Vân', 9, 4, 1, '1988-01-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '915353978', NULL, NULL, NULL, '2018-07-10 11:38:17', '2018-07-13 14:54:34', NULL, NULL, NULL, NULL, '2', 2, NULL, 0, 0, 'vandtt@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL),
(18, 18, 'Vũ Văn Tình', 4, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0987654321', NULL, NULL, NULL, '2018-07-17 18:19:49', '2018-07-17 18:19:49', NULL, NULL, NULL, 1, '19', NULL, NULL, 0, 0, 'tinhvv@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees_attach_files`
--

CREATE TABLE `employees_attach_files` (
  `id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees_attach_files`
--

INSERT INTO `employees_attach_files` (`id`, `description`, `name`, `employee_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '[Omi]Logo+Slogan_5b3e67777fcfa.png', 9, '2018-07-05 18:46:15', '2018-07-05 18:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `employees_job_status_history`
--

CREATE TABLE `employees_job_status_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `job_status_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees_job_status_history`
--

INSERT INTO `employees_job_status_history` (`id`, `employee_id`, `job_status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 4, 1, '2018-07-04 09:49:07', '2018-07-04 09:49:07', NULL),
(4, 5, 1, '2018-07-04 20:31:36', '2018-07-04 20:31:36', NULL),
(5, 6, 1, '2018-07-04 20:33:03', '2018-07-04 20:33:03', NULL),
(6, 7, 1, '2018-07-04 20:34:02', '2018-07-04 20:34:02', NULL),
(7, 8, 1, '2018-07-04 20:34:55', '2018-07-04 20:34:55', NULL),
(8, 9, 1, '2018-07-04 20:35:54', '2018-07-04 20:35:54', NULL),
(9, 10, 1, '2018-07-04 20:36:52', '2018-07-04 20:36:52', NULL),
(10, 11, 1, '2018-07-04 20:38:06', '2018-07-04 20:38:06', NULL),
(11, 12, 1, '2018-07-04 20:38:59', '2018-07-04 20:38:59', NULL),
(12, 13, 1, '2018-07-05 09:02:38', '2018-07-05 09:02:38', NULL),
(13, 14, 1, '2018-07-05 09:03:56', '2018-07-05 09:03:56', NULL),
(14, 11, 2, '2018-07-05 10:25:49', '2018-07-05 10:25:49', NULL),
(15, 10, 3, '2018-07-05 10:26:26', '2018-07-05 10:26:26', NULL),
(16, 15, 1, '2018-07-05 18:51:56', '2018-07-05 18:51:56', NULL),
(17, 16, 1, '2018-07-05 18:51:56', '2018-07-05 18:51:56', NULL),
(18, 18, 1, '2018-07-17 18:19:49', '2018-07-17 18:19:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees_specialized_skills`
--

CREATE TABLE `employees_specialized_skills` (
  `employee_id` int(11) DEFAULT NULL,
  `specialized_skill_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees_specialized_skills`
--

INSERT INTO `employees_specialized_skills` (`employee_id`, `specialized_skill_id`, `created_at`, `updated_at`) VALUES
(16, 4, '2018-07-05 18:51:56', '2018-07-05 18:51:56'),
(16, 5, '2018-07-05 18:51:56', '2018-07-05 18:51:56'),
(15, 2, '2018-07-17 15:26:34', '2018-07-17 15:26:34');

-- --------------------------------------------------------

--
-- Table structure for table `employees_update_history`
--

CREATE TABLE `employees_update_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `full_name` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` tinyint(4) DEFAULT NULL,
  `position_id` tinyint(4) DEFAULT NULL,
  `job_status_id` tinyint(4) DEFAULT NULL,
  `birth_day` date DEFAULT NULL,
  `identification_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_date` date DEFAULT NULL,
  `identification_place_of` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_code` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temporary_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chatwork_account` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_account` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_link` char(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `personal_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_code` int(11) DEFAULT NULL,
  `working_status_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `training_date` date DEFAULT NULL,
  `official_date` date DEFAULT NULL,
  `late_reason_id` int(11) DEFAULT NULL,
  `bank_user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_user` text COLLATE utf8mb4_unicode_ci,
  `distance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees_update_history`
--

INSERT INTO `employees_update_history` (`id`, `employee_id`, `full_name`, `department_id`, `position_id`, `job_status_id`, `birth_day`, `identification_number`, `identification_date`, `identification_place_of`, `tax_code`, `permanent_address`, `temporary_address`, `bank_number`, `bank_name`, `phone_number`, `chatwork_account`, `skype_account`, `facebook_link`, `status`, `created_at`, `updated_at`, `deleted_at`, `personal_email`, `avatar`, `employee_code`, `attendance_code`, `working_status_id`, `approved`, `email`, `update_date`, `check_in_date`, `training_date`, `official_date`, `late_reason_id`, `bank_user_name`, `bank_branch`, `contact_user`, `distance`, `gender`) VALUES
(3, 4, 'Đặng Hữu Chính', 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2018-07-04 09:49:07', '2018-07-04 09:55:13', NULL, NULL, NULL, '16', NULL, 1, 1, 'chinhdh@ominext.com', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0),
(7, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-02-09', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', NULL, NULL, NULL, 2, '2018-07-04 10:31:15', '2018-07-04 10:31:47', NULL, NULL, NULL, '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 5, 'Đào Hồng Hạnh', 3, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'acb', '0123456789', NULL, NULL, NULL, 2, '2018-07-05 10:28:15', '2018-07-05 15:41:50', NULL, NULL, NULL, '4', NULL, 1, 1, 'hanhdh@ominext.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-02-09', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'qqqqqqq', 'qqqq', 'qqqqq', 2, '2018-07-05 15:54:36', '2018-07-05 15:55:34', NULL, NULL, NULL, '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(11, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-02-09', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'eeeeee', 'eeeeee', 'eeeeeee', 2, '2018-07-05 16:17:36', '2018-07-05 16:21:02', NULL, NULL, NULL, '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-07-29', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'eeeeee', 'eeeeee', 'eeeeeee', 2, '2018-07-06 09:13:34', '2018-07-06 09:14:09', NULL, NULL, NULL, '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(13, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-07-29', '123456789', NULL, 'Hưng Yên', '43557567345345', '130 Cầu Giấy', '130 Cầu Giấy', '123456789', 'acb', '123456789', 'eeeeee', 'eeeeee', 'eeeeeee', 2, '2018-07-06 09:15:46', '2018-07-17 11:57:20', NULL, NULL, 'img_5b3f33429324d1.008441371530868546.6027.png', '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(14, 4, 'Đặng Hữu Chính', 5, 1, 1, '1993-07-29', '123456789', NULL, 'Hưng Yên', '43557567345345', '130 Cầu Giấy', '130 Cầu Giấy', '123456789', 'acb', '123456789', 'eeeeee', 'eeeeee', 'eeeeeee', 2, '2018-07-17 15:16:47', '2018-07-17 15:24:57', NULL, NULL, 'img_5b3f33429324d1.008441371530868546.6027.png', '16', NULL, 1, 1, 'chinhdh@ominext.com', '2018-07-04', NULL, NULL, NULL, NULL, NULL, 'hưng yên', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_excel_data`
--

CREATE TABLE `employee_excel_data` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `job_status` text COLLATE utf8mb4_unicode_ci,
  `position` text COLLATE utf8mb4_unicode_ci,
  `birthday` text COLLATE utf8mb4_unicode_ci,
  `phone` text COLLATE utf8mb4_unicode_ci,
  `personal_email` text COLLATE utf8mb4_unicode_ci,
  `email` text COLLATE utf8mb4_unicode_ci,
  `skype` text COLLATE utf8mb4_unicode_ci,
  `facebook` text COLLATE utf8mb4_unicode_ci,
  `checkin_date` text COLLATE utf8mb4_unicode_ci,
  `training_start_date` text COLLATE utf8mb4_unicode_ci,
  `employee_start_date` text COLLATE utf8mb4_unicode_ci,
  `fingerprint_id` text COLLATE utf8mb4_unicode_ci,
  `identification_number` text COLLATE utf8mb4_unicode_ci,
  `identification_date` text COLLATE utf8mb4_unicode_ci,
  `identification_place` text COLLATE utf8mb4_unicode_ci,
  `tax_code` text COLLATE utf8mb4_unicode_ci,
  `permanent_address` text COLLATE utf8mb4_unicode_ci,
  `temporary_address` text COLLATE utf8mb4_unicode_ci,
  `bank_number` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `japanese_certificate` text COLLATE utf8mb4_unicode_ci,
  `employee_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_excel_file_id` int(11) DEFAULT NULL,
  `is_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `department` text COLLATE utf8mb4_unicode_ci,
  `is_duplicate` tinyint(1) NOT NULL DEFAULT '0',
  `bank_user_name` text COLLATE utf8mb4_unicode_ci,
  `bank_name` text COLLATE utf8mb4_unicode_ci,
  `bank_branch` text COLLATE utf8mb4_unicode_ci,
  `contact_user` text COLLATE utf8mb4_unicode_ci,
  `distance` text COLLATE utf8mb4_unicode_ci,
  `late_reason_detail` text COLLATE utf8mb4_unicode_ci,
  `gender` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_excel_data`
--

INSERT INTO `employee_excel_data` (`id`, `name`, `job_status`, `position`, `birthday`, `phone`, `personal_email`, `email`, `skype`, `facebook`, `checkin_date`, `training_start_date`, `employee_start_date`, `fingerprint_id`, `identification_number`, `identification_date`, `identification_place`, `tax_code`, `permanent_address`, `temporary_address`, `bank_number`, `note`, `japanese_certificate`, `employee_id`, `created_at`, `updated_at`, `employee_excel_file_id`, `is_accepted`, `department`, `is_duplicate`, `bank_user_name`, `bank_name`, `bank_branch`, `contact_user`, `distance`, `late_reason_detail`, `gender`) VALUES
(1, 'Đỗ Thị Thùy Vân', 'Chính thức', 'PM', '1988-01-23', '915353978', NULL, 'vandtt@ominext.com', NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-10 10:35:34', '2018-07-10 10:35:34', 6, 0, 'Ban Giám đốc', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Nam'),
(2, 'Nguyễn Chí Thanh', 'Chính thức', 'PM', '1975-01-23', '915353978', NULL, 'thanhnc@ominext.com', NULL, NULL, NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2018-07-10 10:35:34', '2018-07-10 10:35:34', 6, 0, 'Phòng BrSE & PM', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Nam'),
(3, 'Đào Minh Phượng', 'Chính thức', 'Trưởng phòng', '1991-01-23', '915353978', 'phuongdaominh@gmail.com', 'phuongdaominh@ominext.com', 'SmallAngel_MP91', 'https://www.facebook.com/phuongdaominh.mp91', '2013-01-01', '2013-01-01', '2013-03-01', '1', '123456789', '2008-01-01', 'Hà Nam', '987654321', '130 Cầu Giấy, Quan Hoa, Hà Nội', '130 Cầu Giấy, Quan Hoa, Hà Nội', '12345678910', NULL, NULL, 13, '2018-07-10 10:35:34', '2018-07-10 10:35:34', 6, 0, 'Phòng Hành chính Nhân sự', 0, NULL, 'Vietcombank', 'Hội sở chính', NULL, NULL, NULL, 'Nữ'),
(4, 'Đỗ Thị Thùy Vân', 'Chính thức', 'PM', '1988-01-23', '915353978', NULL, 'vandtt@ominext.com', NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-10 11:37:30', '2018-07-10 11:38:17', 7, 1, 'Ban Giám đốc', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Nam'),
(5, 'Nguyễn Chí Thanh', 'Chính thức', 'PM', '1975-01-23', '915353978', NULL, 'thanhnc@ominext.com', NULL, NULL, NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2018-07-10 11:37:30', '2018-07-10 11:37:30', 7, 0, 'Phòng BrSE & PM', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Nam'),
(6, 'Đào Minh Phượng', 'Chính thức', 'Trưởng phòng', '1991-01-23', '915353978', 'phuongdaominh@gmail.com', 'phuongdaominh@ominext.com', 'SmallAngel_MP91', 'https://www.facebook.com/phuongdaominh.mp91', '2013-01-01', '2013-01-01', '2013-03-01', '1', '123456789', '2008-01-01', 'Hà Nam', '987654321', '130 Cầu Giấy, Quan Hoa, Hà Nội', '130 Cầu Giấy, Quan Hoa, Hà Nội', '12345678910', NULL, NULL, 13, '2018-07-10 11:37:30', '2018-07-10 11:37:30', 7, 0, 'Phòng Hành chính Nhân sự', 0, NULL, 'Vietcombank', 'Hội sở chính', NULL, NULL, NULL, 'Nữ');

-- --------------------------------------------------------

--
-- Table structure for table `employee_excel_department`
--

CREATE TABLE `employee_excel_department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_excel_department`
--

INSERT INTO `employee_excel_department` (`id`, `name`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'Phòng Product', 1, NULL, NULL),
(2, 'Phòng BrSE & PM', 2, NULL, NULL),
(3, 'Phòng Hành chính Nhân sự', 3, NULL, NULL),
(4, 'Phòng Mobile', 4, NULL, NULL),
(5, 'Phòng System development', 5, NULL, NULL),
(6, 'Phòng QA & QC', 6, NULL, NULL),
(7, 'Team Comtor', 7, NULL, NULL),
(8, 'Phòng Business Promotion', 8, NULL, NULL),
(9, 'Ban Giám đốc', 9, NULL, '2018-07-03 19:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `employee_excel_file`
--

CREATE TABLE `employee_excel_file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_excel_file`
--

INSERT INTO `employee_excel_file` (`id`, `name`, `created_at`, `updated_at`, `user_id`, `status`) VALUES
(1, 'HCNS_Form_file quản lý thông tin nhân sự.V2_5b3bc4010da10.xlsx', '2018-07-03 18:44:17', '2018-07-03 18:44:17', 1, 2),
(2, 'HCNS_Form_file quản lý thông tin nhân sự.V2_5b3bc72a50bae.xlsx', '2018-07-03 18:57:46', '2018-07-03 18:57:46', 1, 2),
(3, 'HCNS_Form_file quản lý thông tin nhân sự.V2_5b3bca538284e.xlsx', '2018-07-03 19:11:15', '2018-07-03 19:11:15', 1, 2),
(4, 'HCNS_Form_file quản lý thông tin nhân sự.V2_5b3bcabe049ad.xlsx', '2018-07-03 19:13:02', '2018-07-03 19:13:02', 1, 2),
(5, 'form_dữ liệu nhân sự-edit_5b3cb1ab031bb.xlsx', '2018-07-04 11:38:19', '2018-07-04 11:38:19', 1, 2),
(6, 'form_dữ liệu nhân sự-edit_5b43b1e72b2bf.xlsx', '2018-07-09 19:05:11', '2018-07-10 10:35:34', 10, 1),
(7, 'form_dữ liệu nhân sự-edit_5b449a731f789.xlsx', '2018-07-10 11:37:23', '2018-07-10 11:37:30', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_excel_job_status`
--

CREATE TABLE `employee_excel_job_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_status_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_excel_job_status`
--

INSERT INTO `employee_excel_job_status` (`id`, `name`, `job_status_id`, `created_at`, `updated_at`) VALUES
(1, 'Chính thức', 1, NULL, NULL),
(2, 'Thử việc', 2, NULL, NULL),
(3, 'Thực tập sinh', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_excel_position`
--

CREATE TABLE `employee_excel_position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_excel_position`
--

INSERT INTO `employee_excel_position` (`id`, `name`, `position_id`, `created_at`, `updated_at`) VALUES
(1, 'Developer', 1, NULL, NULL),
(2, 'Trưởng phòng', 2, NULL, NULL),
(3, 'Nhân viên đào tạo', 3, NULL, NULL),
(4, 'PM', 4, NULL, NULL),
(5, 'Hành Chính', 5, NULL, NULL),
(6, 'Android', 6, NULL, NULL),
(7, 'JAVA', 7, NULL, NULL),
(8, 'C#', 8, NULL, NULL),
(9, 'PHP', 9, NULL, NULL),
(10, 'Tester', 10, NULL, NULL),
(11, 'JQC', 11, NULL, NULL),
(12, 'Comtor', 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_project_manager`
--

CREATE TABLE `employee_project_manager` (
  `employee_id` int(11) NOT NULL,
  `project_manager_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_project_manager`
--

INSERT INTO `employee_project_manager` (`employee_id`, `project_manager_id`, `created_at`, `updated_at`) VALUES
(9, 5, '2018-07-18 18:07:31', '2018-07-18 18:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(229, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";a:0:{}s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:10:{s:10:\\\"department\\\";s:6:\\\"Mobile\\\";s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"19-07-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"19-07-2018\\\";s:4:\\\"from\\\";s:8:\\\"16:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:12:\\\"shrtgghrtyht\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";}s:11:\\\"mailSubject\\\";s:83:\\\"[TIME_OFF] Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531984882, 1531984882),
(231, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";a:0:{}s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:10:{s:10:\\\"department\\\";s:6:\\\"Mobile\\\";s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"19-07-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"19-07-2018\\\";s:4:\\\"from\\\";s:8:\\\"08:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:7:\\\"dsvgvfv\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";}s:11:\\\"mailSubject\\\";s:83:\\\"[TIME_OFF] Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531985458, 1531985458),
(233, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"dsvgvfv\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531986130, 1531986130),
(235, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"16:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:12:\\\"shrtgghrtyht\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531986197, 1531986197),
(237, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"dsvgvfv\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531986894, 1531986894),
(239, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";a:0:{}s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:10:{s:10:\\\"department\\\";s:6:\\\"Mobile\\\";s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"19-07-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"19-07-2018\\\";s:4:\\\"from\\\";s:8:\\\"08:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:4:\\\"dfds\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";}s:11:\\\"mailSubject\\\";s:83:\\\"[TIME_OFF] Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531987524, 1531987524),
(241, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";a:0:{}s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:7:{s:10:\\\"department\\\";s:6:\\\"Mobile\\\";s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"19-07-2018\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:13:\\\"\\u0103erdfawedrfw\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";}s:11:\\\"mailSubject\\\";s:83:\\\"[TIME_OFF] Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:25:\\\"mails.mail_request_allday\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531987590, 1531987590),
(243, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:13:\\\"\\u0103erdfawedrfw\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531987638, 1531987638),
(244, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"tinhvv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:1:{s:13:\\\"verified_code\\\";s:8:\\\"ABEjT3fh\\\";}s:11:\\\"mailSubject\\\";s:19:\\\"[HRM]Password reset\\\";s:8:\\\"mailView\\\";s:25:\\\"mails.mail_password_reset\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1531994551, 1531994551),
(245, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:131:\\\"- Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\n- Tin nh\\u1eafn: \\n- Th\\u1eddi gian duy\\u1ec7t: 20:25:45 19-07-2018\\n- Tr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:160;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1532006745, 1532006745),
(246, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"dsvgvfv\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:96:\\\"[APPROVED] Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1532006746, 1532006746),
(247, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:3:{i:0;s:18:\\\"hanhdh@ominext.com\\\";i:1;s:19:\\\"thanhnc@ominext.com\\\";i:2;s:20:\\\"phuongnv@ominext.com\\\";}s:8:\\\"mailData\\\";a:7:{s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"project\\\";s:3:\\\"HRM\\\";s:10:\\\"department\\\";s:23:\\\"H\\u00e0nh ch\\u00ednh Nh\\u00e2n s\\u1ef1\\\";s:10:\\\"created_at\\\";s:10:\\\"26-07-2018\\\";s:16:\\\"number_of_person\\\";i:3;s:15:\\\"detail_contents\\\";a:3:{i:0;a:7:{s:5:\\\"order\\\";i:1;s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"content\\\";s:5:\\\"dsfve\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:1;a:7:{s:5:\\\"order\\\";i:2;s:4:\\\"name\\\";s:19:\\\"Nguy\\u1ec5n Ch\\u00ed Thanh\\\";s:5:\\\"email\\\";s:19:\\\"thanhnc@ominext.com\\\";s:7:\\\"content\\\";s:5:\\\"dfcvd\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:2;a:7:{s:5:\\\"order\\\";i:3;s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"content\\\";s:8:\\\"sdfcvdsf\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}}}s:11:\\\"mailSubject\\\";s:56:\\\"\\u0110\\u0103ng k\\u00fd OT - \\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh (hanhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:28:\\\"mails.mail_request_over_time\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1532566541, 1532566541),
(248, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"tinhvv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:9:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:10:\\\"start_date\\\";s:10:\\\"02-08-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"14-08-2018\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:11:\\\"sdcfdascfds\\\";s:13:\\\"backup_person\\\";s:18:\\\"tinhvv@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:31:\\\"mails.mail_request_multiple_day\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180243, 1533180243),
(249, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:162:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Ngh\\u1ec9 nhi\\u1ec1u ng\\u00e0y\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: sdcfdascfds\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:170;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180243, 1533180243),
(250, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:9:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:10:\\\"start_date\\\";s:10:\\\"14-08-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"23-08-2018\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:5:\\\"dsfds\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:31:\\\"mails.mail_request_multiple_day\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180358, 1533180358),
(251, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:156:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Ngh\\u1ec9 nhi\\u1ec1u ng\\u00e0y\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: dsfds\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:171;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180358, 1533180358),
(252, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"29-08-2018\\\";s:6:\\\"reason\\\";s:12:\\\"L\\u00fd do kh\\u00e1c\\\";s:13:\\\"detail_reason\\\";s:6:\\\"fdefas\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:25:\\\"mails.mail_request_allday\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180420, 1533180420),
(253, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:121:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Ngh\\u1ec9 c\\u1ea3 ng\\u00e0y\\nL\\u00fd do: L\\u00fd do kh\\u00e1c\\nL\\u00fd do c\\u1ee5 th\\u1ec3: fdefas\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:172;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180420, 1533180420),
(254, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:11:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"02-08-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"02-08-2018\\\";s:4:\\\"from\\\";s:8:\\\"08:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:7:\\\"rtyhnct\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180458, 1533180458),
(255, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:169:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: \\u0110i mu\\u1ed9n\\/v\\u1ec1 s\\u1edbm\\/ra ngo\\u00e0i\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: rtyhnct\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:173;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533180458, 1533180458),
(256, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:3:{i:0;s:18:\\\"hanhdh@ominext.com\\\";i:1;s:19:\\\"thanhnc@ominext.com\\\";i:2;s:20:\\\"phuongnv@ominext.com\\\";}s:8:\\\"mailData\\\";a:7:{s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"project\\\";s:3:\\\"HRM\\\";s:10:\\\"department\\\";s:23:\\\"H\\u00e0nh ch\\u00ednh Nh\\u00e2n s\\u1ef1\\\";s:10:\\\"created_at\\\";s:10:\\\"26-07-2018\\\";s:16:\\\"number_of_person\\\";i:3;s:15:\\\"detail_contents\\\";a:3:{i:0;a:7:{s:5:\\\"order\\\";i:1;s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"content\\\";s:10:\\\"dsfvedgtfd\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:1;a:7:{s:5:\\\"order\\\";i:2;s:4:\\\"name\\\";s:19:\\\"Nguy\\u1ec5n Ch\\u00ed Thanh\\\";s:5:\\\"email\\\";s:19:\\\"thanhnc@ominext.com\\\";s:7:\\\"content\\\";s:5:\\\"dfcvd\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:2;a:7:{s:5:\\\"order\\\";i:3;s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"content\\\";s:8:\\\"sdfcvdsf\\\";s:4:\\\"date\\\";s:10:\\\"26-07-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}}}s:11:\\\"mailSubject\\\";s:56:\\\"\\u0110\\u0103ng k\\u00fd OT - \\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh (hanhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:28:\\\"mails.mail_request_over_time\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533692970, 1533692970),
(257, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:11:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"09-08-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"09-08-2018\\\";s:4:\\\"from\\\";s:8:\\\"08:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:5:\\\"fgbfg\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783647, 1533783647),
(258, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:167:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: \\u0110i mu\\u1ed9n\\/v\\u1ec1 s\\u1edbm\\/ra ngo\\u00e0i\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: fgbfg\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:174;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783647, 1533783647),
(259, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:11:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"09-08-2018\\\";s:8:\\\"end_date\\\";s:10:\\\"09-08-2018\\\";s:4:\\\"from\\\";s:8:\\\"08:00:00\\\";s:2:\\\"to\\\";s:8:\\\"17:30:00\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:6:\\\"rtghre\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_request_il_le_lo\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783673, 1533783673),
(260, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:168:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: \\u0110i mu\\u1ed9n\\/v\\u1ec1 s\\u1edbm\\/ra ngo\\u00e0i\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: rtghre\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:175;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783673, 1533783673),
(261, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"09-08-2018\\\";s:6:\\\"reason\\\";s:45:\\\"\\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\\";s:13:\\\"detail_reason\\\";s:6:\\\"retyet\\\";s:13:\\\"backup_person\\\";s:18:\\\"hungnp@ominext.com\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:25:\\\"mails.mail_request_allday\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783714, 1533783714),
(262, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:154:\\\"Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Ngh\\u1ec9 c\\u1ea3 ng\\u00e0y\\nL\\u00fd do: \\u1ed0m (\\u1ed0m ho\\u1eb7c b\\u1ecb th\\u01b0\\u01a1ng) \\/ Sick leave\\nL\\u00fd do c\\u1ee5 th\\u1ec3: retyet\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:176;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783714, 1533783714),
(263, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:7:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"10-08-2018\\\";s:13:\\\"detail_reason\\\";s:6:\\\"retyet\\\";s:13:\\\"backup_person\\\";s:2:\\\"__\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:37:\\\"mails.mail_request_check_in_check_out\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783747, 1533783747),
(264, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:107:\\\"- Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Qu\\u00ean checkin\\/checkout\\nL\\u00fd do c\\u1ee5 th\\u1ec3: retyet\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:177;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783747, 1533783747),
(265, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"hungnp@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:7:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"16-08-2018\\\";s:13:\\\"detail_reason\\\";s:6:\\\"retyet\\\";s:13:\\\"backup_person\\\";s:2:\\\"__\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:37:\\\"mails.mail_request_check_in_check_out\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783771, 1533783771),
(266, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:107:\\\"- Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Qu\\u00ean checkin\\/checkout\\nL\\u00fd do c\\u1ee5 th\\u1ec3: retyet\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:178;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533783771, 1533783771),
(267, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";N;s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:7:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"17-08-2018\\\";s:13:\\\"detail_reason\\\";s:3:\\\"sdc\\\";s:13:\\\"backup_person\\\";s:2:\\\"__\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:37:\\\"mails.mail_request_check_in_check_out\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533784709, 1533784709),
(268, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:104:\\\"- Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Qu\\u00ean checkin\\/checkout\\nL\\u00fd do c\\u1ee5 th\\u1ec3: sdc\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:179;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1533784709, 1533784709),
(269, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:80:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:119:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: Admin\\nTin nh\\u1eafn: r\\u00e8arefw4f\\nTh\\u1eddi gian duy\\u1ec7t: 10:15:52 13-08-2018\\nTr\\u1ea1ng th\\u00e1i: B\\u1ecb t\\u1eeb ch\\u1ed1i\\\";s:4:\\\"type\\\";i:6;s:8:\\\"sourceId\\\";i:172;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534130152, 1534130152),
(270, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 29-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 29-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"fdefas\\\";s:8:\\\"approver\\\";s:5:\\\"Admin\\\";s:15:\\\"approved_reason\\\";s:10:\\\"r\\u00e8arefw4f\\\";s:8:\\\"approved\\\";i:2;}s:11:\\\"mailSubject\\\";s:80:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534130152, 1534130152),
(271, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:80:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:117:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: Admin\\nTin nh\\u1eafn: regrtgrt\\nTh\\u1eddi gian duy\\u1ec7t: 10:16:54 13-08-2018\\nTr\\u1ea1ng th\\u00e1i: B\\u1ecb t\\u1eeb ch\\u1ed1i\\\";s:4:\\\"type\\\";i:6;s:8:\\\"sourceId\\\";i:175;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534130214, 1534130214),
(272, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 09-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 09-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"rtghre\\\";s:8:\\\"approver\\\";s:5:\\\"Admin\\\";s:15:\\\"approved_reason\\\";s:8:\\\"regrtgrt\\\";s:8:\\\"approved\\\";i:2;}s:11:\\\"mailSubject\\\";s:80:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534130214, 1534130214),
(273, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";N;s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:7:{s:10:\\\"department\\\";s:6:\\\"System\\\";s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:13:\\\"detail_reason\\\";s:5:\\\"rtyhy\\\";s:13:\\\"backup_person\\\";s:2:\\\"__\\\";s:8:\\\"approver\\\";s:0:\\\"\\\";}s:11:\\\"mailSubject\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:37:\\\"mails.mail_request_check_in_check_out\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534133212, 1534133212),
(274, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:0:{}s:5:\\\"title\\\";s:66:\\\"Y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:4:\\\"body\\\";s:106:\\\"- Ng\\u01b0\\u1eddi y\\u00eau c\\u1ea7u: \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\nLo\\u1ea1i l\\u00fd do: Qu\\u00ean checkin\\/checkout\\nL\\u00fd do c\\u1ee5 th\\u1ec3: rtyhy\\\";s:4:\\\"type\\\";i:1;s:8:\\\"sourceId\\\";i:180;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534133212, 1534133212),
(275, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:17:\\\"admin@ominext.com\\\";s:7:\\\"ccMails\\\";a:2:{i:0;s:18:\\\"hanhdh@ominext.com\\\";i:1;s:19:\\\"thanhnc@ominext.com\\\";}s:8:\\\"mailData\\\";a:7:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:7:\\\"project\\\";s:3:\\\"HRM\\\";s:10:\\\"department\\\";s:6:\\\"System\\\";s:10:\\\"created_at\\\";s:10:\\\"13-08-2018\\\";s:16:\\\"number_of_person\\\";i:2;s:15:\\\"detail_contents\\\";a:2:{i:0;a:7:{s:5:\\\"order\\\";i:1;s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"content\\\";s:3:\\\"\\u1ec1\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:1;a:7:{s:5:\\\"order\\\";i:2;s:4:\\\"name\\\";s:19:\\\"Nguy\\u1ec5n Ch\\u00ed Thanh\\\";s:5:\\\"email\\\";s:19:\\\"thanhnc@ominext.com\\\";s:7:\\\"content\\\";s:3:\\\"\\u1ebb\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}}}s:11:\\\"mailSubject\\\";s:56:\\\"\\u0110\\u0103ng k\\u00fd OT - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:28:\\\"mails.mail_request_over_time\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534134649, 1534134649),
(276, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:17:\\\"admin@ominext.com\\\";s:7:\\\"ccMails\\\";a:2:{i:0;s:18:\\\"hanhdh@ominext.com\\\";i:1;s:19:\\\"thanhnc@ominext.com\\\";}s:8:\\\"mailData\\\";a:7:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:17:\\\"admin@ominext.com\\\";s:7:\\\"project\\\";s:3:\\\"HRM\\\";s:10:\\\"department\\\";s:6:\\\"System\\\";s:10:\\\"created_at\\\";s:10:\\\"13-08-2018\\\";s:16:\\\"number_of_person\\\";i:2;s:15:\\\"detail_contents\\\";a:2:{i:0;a:7:{s:5:\\\"order\\\";i:1;s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"content\\\";s:3:\\\"d\\u00e1\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"12:00\\\";}i:1;a:7:{s:5:\\\"order\\\";i:2;s:4:\\\"name\\\";s:19:\\\"Nguy\\u1ec5n Ch\\u00ed Thanh\\\";s:5:\\\"email\\\";s:19:\\\"thanhnc@ominext.com\\\";s:7:\\\"content\\\";s:4:\\\"\\u00e3ds\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}}}s:11:\\\"mailSubject\\\";s:56:\\\"\\u0110\\u0103ng k\\u00fd OT - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (admin@ominext.com)\\\";s:8:\\\"mailView\\\";s:28:\\\"mails.mail_request_over_time\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534146847, 1534146847),
(277, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:18:\\\"tinhvv@ominext.com\\\";s:7:\\\"ccMails\\\";a:2:{i:0;s:19:\\\"thanhnc@ominext.com\\\";i:1;s:18:\\\"hanhdh@ominext.com\\\";}s:8:\\\"mailData\\\";a:7:{s:4:\\\"name\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:5:\\\"email\\\";s:18:\\\"tinhvv@ominext.com\\\";s:7:\\\"project\\\";s:3:\\\"HRM\\\";s:10:\\\"department\\\";s:6:\\\"Mobile\\\";s:10:\\\"created_at\\\";s:10:\\\"13-08-2018\\\";s:16:\\\"number_of_person\\\";i:2;s:15:\\\"detail_contents\\\";a:2:{i:0;a:7:{s:5:\\\"order\\\";i:1;s:4:\\\"name\\\";s:19:\\\"Nguy\\u1ec5n Ch\\u00ed Thanh\\\";s:5:\\\"email\\\";s:19:\\\"thanhnc@ominext.com\\\";s:7:\\\"content\\\";s:3:\\\"gju\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}i:1;a:7:{s:5:\\\"order\\\";i:2;s:4:\\\"name\\\";s:19:\\\"\\u0110\\u00e0o H\\u1ed3ng H\\u1ea1nh\\\";s:5:\\\"email\\\";s:18:\\\"hanhdh@ominext.com\\\";s:7:\\\"content\\\";s:7:\\\"ygjuyuj\\\";s:4:\\\"date\\\";s:10:\\\"13-08-2018\\\";s:4:\\\"from\\\";s:5:\\\"18:30\\\";s:2:\\\"to\\\";s:5:\\\"19:00\\\";}}}s:11:\\\"mailSubject\\\";s:51:\\\"\\u0110\\u0103ng k\\u00fd OT - V\\u0169 V\\u0103n T\\u00ecnh (tinhvv@ominext.com)\\\";s:8:\\\"mailView\\\";s:28:\\\"mails.mail_request_over_time\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534149752, 1534149752);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(278, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 09:53:33 20-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:160;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534733614, 1534733614),
(279, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"dsvgvfv\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534733614, 1534733614),
(280, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:84:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:131:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: yud5u67u6ty7u\\nTh\\u1eddi gian duy\\u1ec7t: 09:54:02 20-08-2018\\nTr\\u1ea1ng th\\u00e1i: B\\u1ecb t\\u1eeb ch\\u1ed1i\\\";s:4:\\\"type\\\";i:6;s:8:\\\"sourceId\\\";i:160;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534733642, 1534733642),
(281, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"dsvgvfv\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:13:\\\"yud5u67u6ty7u\\\";s:8:\\\"approved\\\";i:2;}s:11:\\\"mailSubject\\\";s:84:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534733642, 1534733642),
(282, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:84:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:133:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\nTin nh\\u1eafn: dfyhug\\nTh\\u1eddi gian duy\\u1ec7t: 15:35:20 20-08-2018\\nTr\\u1ea1ng th\\u00e1i: B\\u1ecb t\\u1eeb ch\\u1ed1i\\\";s:4:\\\"type\\\";i:6;s:8:\\\"sourceId\\\";i:162;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534754120, 1534754120),
(283, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 19-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 19-07-2018\\\";s:15:\\\"detailed_reason\\\";s:13:\\\"\\u0103erdfawedrfw\\\";s:8:\\\"approver\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:15:\\\"approved_reason\\\";s:6:\\\"dfyhug\\\";s:8:\\\"approved\\\";i:2;}s:11:\\\"mailSubject\\\";s:84:\\\"T\\u1eeb ch\\u1ed1i y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1534754120, 1534754120),
(284, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:164;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(285, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 25-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 25-07-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"e6y6t\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(286, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:165;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(287, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 23-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 23-07-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"sdfsd\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(288, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:166;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(289, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"17:00 23-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 23-07-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"sdfsd\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(290, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:167;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(291, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"17:00 23-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 23-07-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"sdfsd\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(292, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:168;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(293, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 23-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 23-07-2018\\\";s:15:\\\"detailed_reason\\\";s:2:\\\"\\u0111\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(294, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:20:\\\"phuongnv@ominext.com\\\";}s:5:\\\"title\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:169;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(295, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:20:\\\"phuongnv@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:23:\\\"Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng\\\";s:5:\\\"email\\\";s:20:\\\"phuongnv@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 23-07-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 23-07-2018\\\";s:15:\\\"detailed_reason\\\";s:8:\\\"rtyhrtyh\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:85:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - Nguy\\u1ec5n V\\u0103n Ph\\u01b0\\u1ee3ng (phuongnv@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(296, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:170;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(297, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"00:00 02-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"00:00 14-08-2018\\\";s:15:\\\"detailed_reason\\\";s:11:\\\"sdcfdascfds\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(298, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:171;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(299, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"00:00 14-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"00:00 23-08-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"dsfds\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(300, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:49 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:173;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017549, 1535017549),
(301, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 02-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 02-08-2018\\\";s:15:\\\"detailed_reason\\\";s:7:\\\"rtyhnct\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(302, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:174;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(303, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 09-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 09-08-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"fgbfg\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(304, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:176;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(305, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 09-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 09-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"retyet\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(306, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:177;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(307, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 10-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 10-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"retyet\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(308, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:178;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(309, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 16-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 16-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"retyet\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(310, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:179;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(311, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 17-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 17-08-2018\\\";s:15:\\\"detailed_reason\\\";s:3:\\\"sdc\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(312, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:123:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: V\\u0169 V\\u0103n T\\u00ecnh\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:45:50 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:180;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(313, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 13-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 13-08-2018\\\";s:15:\\\"detailed_reason\\\";s:5:\\\"rtyhy\\\";s:8:\\\"approver\\\";s:14:\\\"V\\u0169 V\\u0103n T\\u00ecnh\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017550, 1535017550),
(314, 'sendNotification', '{\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\SendNotification\\\":9:{s:9:\\\"receivers\\\";a:1:{i:0;s:19:\\\"chinhdh@ominext.com\\\";}s:5:\\\"title\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:4:\\\"body\\\";s:114:\\\"Ng\\u01b0\\u1eddi duy\\u1ec7t: Admin\\nTin nh\\u1eafn: \\nTh\\u1eddi gian duy\\u1ec7t: 16:48:06 23-08-2018\\nTr\\u1ea1ng th\\u00e1i: \\u0110\\u00e3 \\u0111\\u01b0\\u1ee3c duy\\u1ec7t\\\";s:4:\\\"type\\\";i:5;s:8:\\\"sourceId\\\";i:175;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:16:\\\"sendNotification\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017686, 1535017686),
(315, 'sendMail', '{\"displayName\":\"App\\\\Jobs\\\\SendEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmail\",\"command\":\"O:18:\\\"App\\\\Jobs\\\\SendEmail\\\":9:{s:6:\\\"toMail\\\";s:19:\\\"chinhdh@ominext.com\\\";s:7:\\\"ccMails\\\";a:0:{}s:8:\\\"mailData\\\";a:8:{s:4:\\\"name\\\";s:20:\\\"\\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh\\\";s:5:\\\"email\\\";s:19:\\\"chinhdh@ominext.com\\\";s:14:\\\"start_datetime\\\";s:16:\\\"08:00 09-08-2018\\\";s:12:\\\"end_datetime\\\";s:16:\\\"17:30 09-08-2018\\\";s:15:\\\"detailed_reason\\\";s:6:\\\"rtghre\\\";s:8:\\\"approver\\\";s:5:\\\"Admin\\\";s:15:\\\"approved_reason\\\";s:2:\\\"__\\\";s:8:\\\"approved\\\";i:1;}s:11:\\\"mailSubject\\\";s:81:\\\"Ph\\u00ea duy\\u1ec7t y\\u00eau c\\u1ea7u ngh\\u1ec9 ph\\u00e9p - \\u0110\\u1eb7ng H\\u1eefu Ch\\u00ednh (chinhdh@ominext.com)\\\";s:8:\\\"mailView\\\";s:27:\\\"mails.mail_approve_time_off\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";s:8:\\\"sendMail\\\";s:5:\\\"delay\\\";N;}\"}}', 0, NULL, 1535017686, 1535017686);

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Lập trình viên PHP', 'Lập trình viên PHP', '2018-07-05 17:46:37', '2018-07-05 17:46:37', NULL),
(2, 'Chuyên viên Nhân sự', 'Chuyên viên Nhân sự', '2018-07-05 17:47:55', '2018-07-05 17:47:55', NULL),
(3, 'Lập trình viên Java', 'Lập trình viên Java', '2018-07-05 17:48:19', '2018-07-05 17:48:19', NULL),
(4, 'Chuyên viên Marketing', 'Chuyên viên Marketing', '2018-07-05 17:48:35', '2018-07-05 17:48:35', NULL),
(5, 'Chuyên viên Marketing', 'Chuyên viên Marketing', '2018-07-05 17:48:44', '2018-07-05 17:48:54', '2018-07-05 17:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `job_status`
--

CREATE TABLE `job_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_status`
--

INSERT INTO `job_status` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Chính thức', 'Chính thức', '2018-07-03 01:39:21', '2018-07-03 01:39:23', NULL),
(2, 'Thử việc', 'Thử việc', '2018-07-03 18:56:50', '2018-07-03 18:56:50', NULL),
(3, 'Thực tập sinh', 'Thực tập sinh', '2018-07-03 18:56:58', '2018-07-03 18:56:58', NULL),
(4, 'Nhân sự', 'Nhân sự', '2018-07-05 17:41:43', '2018-07-05 17:41:58', '2018-07-05 17:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `late_reasons`
--

CREATE TABLE `late_reasons` (
  `id` int(11) NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_morning` time NOT NULL,
  `end_morning` time NOT NULL,
  `start_afternoon` time NOT NULL,
  `end_afternoon` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `time_off_id` int(11) DEFAULT NULL,
  `over_time_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `training_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `body`, `action`, `email`, `type`, `read`, `time_off_id`, `over_time_id`, `course_id`, `training_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(15, '[REFUSED] Từ chối yêu cầu nghỉ phép - Lương Anh Sơn (sonla@ominext.com)', '- Người duyệt: Admin\n- Tin nhắn: a\n- Thời gian duyệt: 21:27:00 19-07-2018\n- Trạng thái: Bị từ chối', '', 'tinhvv@ominext.com', 1, 1, 168, NULL, NULL, NULL, '2018-07-19 14:27:00', '2018-08-23 08:59:33', NULL),
(16, '[APPROVED] Phê duyệt yêu cầu nghỉ phép - Lương Anh Sơn (sonla@ominext.com)', '- Người duyệt: Admin\n- Tin nhắn: \n- Thời gian duyệt: 21:27:13 19-07-2018\n- Trạng thái: Đã được duyệt', '', 'phuongnv@ominext.com', 1, 1, 158, NULL, NULL, NULL, '2018-07-19 14:27:16', '2018-08-22 03:30:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `official_holidays`
--

CREATE TABLE `official_holidays` (
  `id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `official_holidays`
--

INSERT INTO `official_holidays` (`id`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2018-01-01', '2018-01-01', 'Nghỉ tết dương lịch', '2018-07-04 20:41:22', '2018-07-04 20:41:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `other_categories`
--

CREATE TABLE `other_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `other_categories`
--

INSERT INTO `other_categories` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HRM', 'Human resource management', 'projects', '2018-07-04 09:24:59', '2018-07-04 09:24:59', NULL),
(2, 'Đào tạo hội nhập', 'Đào tạo hội nhập', 'categoryCourses', '2018-07-04 10:34:58', '2018-07-04 10:34:58', NULL),
(3, 'Sushi', 'tre', 'room', '2018-07-04 17:53:24', '2018-07-05 19:13:30', NULL),
(4, 'Nam', 'Nam', '3', '2018-07-05 18:14:45', '2018-07-05 18:14:45', NULL),
(5, 'Nữ', 'Nữ', '3', '2018-07-05 18:14:59', '2018-07-05 18:14:59', NULL),
(6, 'Hợp đồng chính thức', 'Hợp đồng chính thức', '4', '2018-07-05 18:15:31', '2018-07-05 18:15:31', NULL),
(7, 'Hợp đồng thử việc', 'Hợp đồng thử việc', '4', '2018-07-05 18:16:26', '2018-07-05 18:16:26', NULL),
(8, 'Hợp đồng dịch vụ', 'Hợp đồng dịch vụ', '4', '2018-07-05 18:17:35', '2018-07-05 18:17:35', NULL),
(9, 'Hợp đồng chính thức', 'Hợp đồng chính thức', '4', '2018-07-05 18:18:54', '2018-07-05 18:19:02', '2018-07-05 18:19:02'),
(10, 'Hà Nội', 'Hà Nội', '2', '2018-07-05 18:21:41', '2018-07-05 18:21:41', NULL),
(11, 'Hà Nam', 'Hà Nam', '2', '2018-07-05 18:21:55', '2018-07-05 18:21:55', NULL),
(12, 'Nam Định', 'Nam Định', '2', '2018-07-05 18:22:07', '2018-07-05 18:22:07', NULL),
(13, 'Nam Định', 'Nam Định', '2', '2018-07-05 18:23:19', '2018-07-05 18:23:27', '2018-07-05 18:23:27'),
(14, 'Tempura', 'Tempura', 'room', '2018-07-05 19:13:49', '2018-07-05 19:13:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `over_times`
--

CREATE TABLE `over_times` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_category_id` int(11) NOT NULL,
  `proposer` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  `approved_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `over_times`
--

INSERT INTO `over_times` (`id`, `project_category_id`, `proposer`, `approved`, `approved_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'admin@ominext.com', 1, NULL, '2018-07-04 17:51:39', '2018-07-06 12:10:06', NULL),
(2, 1, 'sonla@ominext.com', 1, NULL, '2018-07-10 16:44:15', '2018-07-10 16:44:15', NULL),
(3, 1, 'sonla@ominext.com', 1, NULL, '2018-07-10 16:44:23', '2018-07-10 16:44:23', NULL),
(4, 1, 'sonla@ominext.com', 1, NULL, '2018-07-10 16:44:40', '2018-07-10 16:44:40', NULL),
(5, 1, 'sonla@ominext.com', 1, NULL, '2018-07-10 16:44:44', '2018-07-10 16:44:44', NULL),
(6, 1, 'sonla@ominext.com', 1, NULL, '2018-07-12 09:30:17', '2018-07-12 09:30:17', NULL),
(7, 1, 'sonla@ominext.com', 1, NULL, '2018-07-12 09:30:38', '2018-07-12 09:30:38', NULL),
(8, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:33:22', '2018-07-12 09:33:22', NULL),
(9, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:34:11', '2018-07-12 09:34:11', NULL),
(10, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:38:31', '2018-07-12 09:38:31', NULL),
(11, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:38:45', '2018-07-12 09:38:45', NULL),
(12, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:41:47', '2018-07-12 09:41:47', NULL),
(13, 1, 'admin@ominext.com', 1, NULL, '2018-07-12 09:47:30', '2018-07-12 09:47:30', NULL),
(14, 1, 'admin@ominext.com', 1, NULL, '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(15, 1, 'phuongdaominh@ominext.com', 1, NULL, '2018-07-17 15:41:19', '2018-07-17 15:41:19', NULL),
(16, 1, 'admin@ominext.com', 1, NULL, '2018-07-17 16:07:12', '2018-07-17 16:07:12', NULL),
(17, 1, 'admin@ominext.com', 1, NULL, '2018-07-17 16:09:30', '2018-07-17 16:09:30', NULL),
(18, 1, 'admin@ominext.com', 1, NULL, '2018-07-17 16:27:40', '2018-07-17 16:27:40', NULL),
(19, 1, 'admin@ominext.com', 1, NULL, '2018-07-18 14:48:46', '2018-07-19 10:34:19', NULL),
(20, 1, 'hanhdh@ominext.com', 0, NULL, '2018-07-26 00:55:41', '2018-08-08 01:49:29', NULL),
(21, 1, 'admin@ominext.com', 1, NULL, '2018-08-13 04:30:49', '2018-08-13 04:30:49', NULL),
(22, 1, 'admin@ominext.com', 1, NULL, '2018-08-13 07:54:07', '2018-08-13 07:54:07', NULL),
(23, 1, 'tinhvv@ominext.com', 1, NULL, '2018-08-13 08:42:32', '2018-08-13 08:42:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `over_time_details`
--

CREATE TABLE `over_time_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `over_time_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `over_time_details`
--

INSERT INTO `over_time_details` (`id`, `over_time_id`, `user_id`, `start_datetime`, `end_datetime`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
(11, 1, 1, '2018-07-04 18:30:00', '2018-07-04 19:00:00', 'Noi dung cong viec', '2018-07-06 12:05:46', '2018-07-06 12:05:46', NULL),
(12, 1, 4, '2018-07-04 18:30:00', '2018-07-04 19:00:00', 'Noi dung cong viec', '2018-07-06 12:05:46', '2018-07-06 12:05:46', NULL),
(13, 1, 7, '2018-07-06 18:30:00', '2018-07-06 19:00:00', 'Noi dung cong viec', '2018-07-06 12:05:46', '2018-07-06 12:05:46', NULL),
(14, 2, 1, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:15', '2018-07-10 16:44:15', NULL),
(15, 2, 4, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:15', '2018-07-10 16:44:15', NULL),
(16, 3, 1, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:23', '2018-07-10 16:44:23', NULL),
(17, 3, 4, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:23', '2018-07-10 16:44:23', NULL),
(18, 4, 1, '2018-07-10 18:30:00', '2018-07-10 20:01:00', 'Noi dung', '2018-07-10 16:44:40', '2018-07-10 16:44:40', NULL),
(19, 4, 4, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:40', '2018-07-10 16:44:40', NULL),
(20, 5, 1, '2018-07-10 18:30:00', '2018-07-10 20:01:00', 'Noi dung', '2018-07-10 16:44:44', '2018-07-10 16:44:44', NULL),
(21, 5, 4, '2018-07-10 18:30:00', '2018-07-10 19:01:00', 'Noi dung', '2018-07-10 16:44:44', '2018-07-10 16:44:44', NULL),
(22, 6, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'Noi dung', '2018-07-12 09:30:17', '2018-07-12 09:30:17', NULL),
(23, 6, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'Noi dung', '2018-07-12 09:30:17', '2018-07-12 09:30:17', NULL),
(24, 7, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'Noi dung', '2018-07-12 09:30:38', '2018-07-12 09:30:38', NULL),
(25, 7, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'Noi dung', '2018-07-12 09:30:38', '2018-07-12 09:30:38', NULL),
(26, 8, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:33:22', '2018-07-12 09:33:22', NULL),
(27, 8, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'aa', '2018-07-12 09:33:22', '2018-07-12 09:33:22', NULL),
(28, 9, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:34:11', '2018-07-12 09:34:11', NULL),
(29, 9, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'aa', '2018-07-12 09:34:11', '2018-07-12 09:34:11', NULL),
(30, 10, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:38:31', '2018-07-12 09:38:31', NULL),
(31, 10, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'aa', '2018-07-12 09:38:31', '2018-07-12 09:38:31', NULL),
(32, 11, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:38:45', '2018-07-12 09:38:45', NULL),
(33, 11, 4, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'aa', '2018-07-12 09:38:45', '2018-07-12 09:38:45', NULL),
(34, 12, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:41:47', '2018-07-12 09:41:47', NULL),
(35, 13, 1, '2018-07-12 18:30:00', '2018-07-12 19:00:00', 'á', '2018-07-12 09:47:30', '2018-07-12 09:47:30', NULL),
(36, 14, 17, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(37, 14, 16, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(38, 14, 15, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(39, 14, 14, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(40, 14, 13, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(41, 14, 12, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(42, 14, 11, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(43, 14, 10, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(44, 14, 9, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(45, 14, 1, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(46, 14, 4, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(47, 14, 5, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(48, 14, 6, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(49, 14, 7, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(50, 14, 8, '2018-07-13 18:30:00', '2018-07-13 19:00:00', 'a', '2018-07-13 16:11:45', '2018-07-13 16:11:45', NULL),
(51, 15, 10, '2018-07-17 18:30:00', '2018-07-17 19:00:00', 'sbcd', '2018-07-17 15:41:19', '2018-07-17 15:41:19', NULL),
(52, 15, 9, '2018-07-17 18:30:00', '2018-07-17 19:00:00', 'sbcd', '2018-07-17 15:41:19', '2018-07-17 15:41:19', NULL),
(53, 16, 4, '2018-07-17 18:30:00', '2018-07-17 19:00:00', 'a', '2018-07-17 16:07:12', '2018-07-17 16:07:12', NULL),
(54, 17, 4, '2018-07-17 18:30:00', '2018-07-17 19:00:00', 'a', '2018-07-17 16:09:30', '2018-07-17 16:09:30', NULL),
(55, 18, 4, '2018-07-17 18:30:00', '2018-07-17 19:00:00', 'a', '2018-07-17 16:27:40', '2018-07-17 16:27:40', NULL),
(56, 19, 7, '2018-07-18 18:30:00', '2018-07-18 19:00:00', 'Phiếu đăng ký làm ngoài giờ gửi về Phòng Hành chính – Nhân sự tổng hợp trước 16h hàng ngày;', '2018-07-18 14:48:46', '2018-07-18 14:48:46', NULL),
(60, 20, 5, '2018-07-26 18:30:00', '2018-07-26 19:00:00', 'dsfvedgtfd', '2018-08-08 01:49:29', '2018-08-08 01:49:29', NULL),
(61, 20, 6, '2018-07-26 18:30:00', '2018-07-26 19:00:00', 'dfcvd', '2018-08-08 01:49:29', '2018-08-08 01:49:29', NULL),
(62, 20, 7, '2018-07-26 18:30:00', '2018-07-26 19:00:00', 'sdfcvdsf', '2018-08-08 01:49:29', '2018-08-08 01:49:29', NULL),
(63, 21, 5, '2018-08-13 18:30:00', '2018-08-13 19:00:00', 'ề', '2018-08-13 04:30:49', '2018-08-13 04:30:49', NULL),
(64, 21, 6, '2018-08-13 18:30:00', '2018-08-13 19:00:00', 'ẻ', '2018-08-13 04:30:49', '2018-08-13 04:30:49', NULL),
(65, 22, 5, '2018-08-13 18:30:00', '2018-08-14 12:00:00', 'dá', '2018-08-13 07:54:07', '2018-08-13 07:54:07', NULL),
(66, 22, 6, '2018-08-13 18:30:00', '2018-08-13 19:00:00', 'ãds', '2018-08-13 07:54:07', '2018-08-13 07:54:07', NULL),
(67, 23, 6, '2018-08-13 18:30:00', '2018-08-17 19:00:00', 'gju', '2018-08-13 08:42:32', '2018-08-13 08:42:32', NULL),
(68, 23, 5, '2018-08-13 18:30:00', '2018-08-13 19:00:00', 'ygjuyuj', '2018-08-13 08:42:32', '2018-08-13 08:42:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_forgets`
--

CREATE TABLE `password_forgets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_forgets`
--

INSERT INTO `password_forgets` (`email`, `verified_code`, `created_at`) VALUES
('phuongnv@ominext.com', 'abVDU9mA', '2018-07-17 14:07:26'),
('sonla@ominext.com', 'R1L7zJWU', '2018-07-19 11:08:26'),
('tinhvv@ominext.com', 'ABEjT3fh', '2018-07-19 10:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dev', 'Developer', 'Developer', '2018-07-03 01:40:19', '2018-07-04 18:51:31', NULL),
(2, 'Trưởng phòng', 'Trưởng phòng', 'Trưởng phòng', '2018-07-03 18:53:49', '2018-07-03 18:53:49', NULL),
(3, 'Training', 'Nhân viên đào tạo', 'Nhân viên đào tạo', '2018-07-03 18:54:02', '2018-07-03 18:54:02', NULL),
(4, 'PM', 'PM', 'PM', '2018-07-03 18:54:17', '2018-07-03 18:54:17', NULL),
(5, 'HCNS', 'Hành Chính', 'Hành Chính', '2018-07-03 18:54:30', '2018-07-03 18:54:30', NULL),
(6, 'Android', 'Android', 'Android', '2018-07-03 18:54:43', '2018-07-03 18:54:43', NULL),
(7, 'JAVA', 'JAVA', 'JAVA', '2018-07-03 18:54:53', '2018-07-03 18:54:53', NULL),
(8, 'C#', 'C#', 'C#', '2018-07-03 18:55:04', '2018-07-03 18:55:04', NULL),
(9, 'PHP', 'PHP', 'PHP', '2018-07-03 18:55:17', '2018-07-03 18:55:17', NULL),
(10, 'Tester', 'Tester', 'Tester', '2018-07-03 18:55:32', '2018-07-03 18:55:32', NULL),
(11, 'JQC', 'JQC', 'JQC', '2018-07-03 18:55:43', '2018-07-03 18:55:43', NULL),
(12, 'Comtor', 'Comtor', 'Comtor', '2018-07-03 18:56:01', '2018-07-03 18:56:01', NULL),
(13, 'Nhân sự', 'Nhân sự', 'Nhân sự', '2018-07-05 17:37:35', '2018-07-05 17:37:35', NULL),
(14, '1', 'Nhân sự', 'Nhân sự', '2018-07-05 17:37:56', '2018-07-05 17:38:13', '2018-07-05 17:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN', 'ADMIN', 'ADMIN', '2018-07-03 01:43:05', '2018-07-03 01:43:07'),
(2, 'STAFF', 'STAFF', 'STAFF', '2018-07-05 09:14:57', '2018-07-05 09:14:57'),
(3, 'Nhân sự', 'Nhân sự', 'Nhân sự', '2018-07-05 09:17:06', '2018-07-05 09:17:06'),
(4, 'Đào tạo', 'Đào tạo', 'Đào tạo', '2018-07-05 09:44:29', '2018-07-05 09:44:29'),
(5, 'team leader', NULL, NULL, NULL, NULL),
(6, 'project manager', NULL, NULL, NULL, NULL),
(7, 'bom', NULL, NULL, NULL, NULL),
(8, 'hi', 'hi', 'hi', '2018-07-26 06:22:02', '2018-07-26 06:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 6),
(4, 2),
(4, 6),
(5, 2),
(5, 4),
(5, 6),
(5, 8),
(6, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(9, 3),
(10, 1),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(13, 3),
(13, 5),
(14, 2),
(14, 3),
(15, 2),
(15, 5),
(16, 2),
(17, 2),
(18, 2),
(18, 5),
(19, 2);

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `screen_category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`id`, `name`, `display_name`, `description`, `screen_category_id`, `created_at`, `updated_at`, `url`, `deleted_at`) VALUES
(24, 'Quản trị', 'Quản trị', 'Quản trị', 1, '2018-05-21 04:28:12', '2018-06-15 06:55:34', '/quan-tri', '2018-06-15 06:55:34'),
(25, 'Quản trị => Quản lý phân quyền', 'Quản trị => Quản lý phân quyền', 'Quản trị', 1, '2018-05-21 04:28:48', '2018-05-21 04:28:48', '/quan-tri/quan-ly-phan-quyen', NULL),
(26, 'Quản trị => Thêm vai trò', 'Quản trị => Thêm vai trò', 'Quản trị', 1, '2018-05-21 04:29:09', '2018-05-21 04:29:09', '/quan-tri/them-vai-tro', NULL),
(27, 'Quản trị => Thiết lập quyền trong vai trò', 'Quản trị => Thiết lập quyền trong vai trò', 'Quản trị', 1, '2018-05-21 04:29:27', '2018-05-21 04:29:27', '/quan-tri/thiet-lap-quyen-trong-vai-tro', NULL),
(28, 'Quản trị => Cập nhật vai trò', 'Quản trị => Cập nhật vai trò', 'Quản trị', 1, '2018-05-21 04:29:43', '2018-05-21 04:29:43', '/quan-tri/cap-nhat-vai-tro', NULL),
(29, 'Quản trị => Thêm người dùng cho vai trò', 'Quản trị => Thêm người dùng cho vai trò', 'Quản trị', 1, '2018-05-21 04:30:01', '2018-05-21 04:30:01', '/quan-tri/them-nguoi-dung-trong-vai-tro', NULL),
(30, 'Quản trị => Xem người dùng trong vai trò', 'Quản trị => Xem người dùng trong vai trò', 'Quản trị', 1, '2018-05-21 04:30:15', '2018-05-21 04:30:15', '/quan-tri/xem-nguoi-dung-trong-vai-tro', NULL),
(31, 'Nhân sự', 'Nhân sự', 'Danh sách nhân sự', 2, '2018-05-21 05:03:48', '2018-06-15 06:55:39', '/danh-sach-nhan-su', '2018-06-15 06:55:39'),
(32, 'Nhân sự => Danh sách', 'Nhân sự => Danh sách', 'Danh sách nhân sự', 2, '2018-05-21 05:04:06', '2018-06-14 01:23:12', '/danh-sach-nhan-su/danh-sach', NULL),
(33, 'Nhân sự => Thông tin nhân sự', 'Nhân sự => Thông tin nhân sự', 'Danh sách nhân sự', 2, '2018-05-21 05:05:01', '2018-06-14 01:23:23', '/danh-sach-nhan-su/thong-tin-nhan-su', NULL),
(34, 'Nhân sự  => Thêm nhân sự', 'Nhân sự  => Thêm nhân sự', 'Danh sách nhân sự', 2, '2018-05-21 05:05:17', '2018-06-14 01:23:39', '/danh-sach-nhan-su/them-nhan-su', NULL),
(35, 'Nhân sự  => Quản lý thay đổi', 'Nhân sự  => Quản lý thay đổi', 'Danh sách nhân sự', 2, '2018-05-21 05:05:30', '2018-06-14 01:23:52', '/danh-sach-nhan-su/quan-ly-thay-doi', NULL),
(36, 'Nhân sự  => Import', 'Nhân sự  => Import', 'Danh sách nhân sự', 2, '2018-05-21 05:05:48', '2018-06-14 01:24:06', '/danh-sach-nhan-su/import', NULL),
(37, 'Nhân sự => Sửa thông tin nhân sự', 'Nhân sự => Sửa thông tin nhân sự', 'Danh sách nhân sự', 2, '2018-05-21 05:08:16', '2018-06-14 01:24:18', '/danh-sach-nhan-su/sua-thong-tin-nhan-su', NULL),
(38, 'Đào tạo', 'Đào tạo', 'Đào tạo', 6, '2018-05-21 06:02:55', '2018-06-15 06:55:44', '/dao-tao', '2018-06-15 06:55:44'),
(39, 'Đào tạo => Danh sách khóa học', 'Đào tạo => Danh sách khóa học', 'ủl5ye5rktmè', 6, '2018-05-21 06:03:21', '2018-06-13 09:16:54', '/dao-tao/danh-sach-khoa-hoc', NULL),
(40, 'Đào tạo => Quản lý khóa học', 'Đào tạo => Quản lý khóa học', 'Đào tạo', 6, '2018-05-21 06:05:35', '2018-05-21 06:05:35', '/dao-tao/quan-ly-khoa-hoc', NULL),
(41, 'Đào tạo => Tạo khóa học', 'Đào tạo => Tạo khóa học', 'Đào tạo', 6, '2018-05-21 06:05:48', '2018-05-21 06:05:48', '/dao-tao/tao-khoa-hoc', NULL),
(42, 'Đào tạo => Cập nhật khóa học', 'Đào tạo => Cập nhật khóa học', 'Đào tạo', 6, '2018-05-21 06:06:10', '2018-05-21 06:06:10', '/dao-tao/cap-nhat-khoa-hoc', NULL),
(43, 'Đào tạo => Tùy chỉh đối tượng đào tạo cho khóa học', 'Đào tạo => Tùy chỉh đối tượng đào tạo cho khóa học', 'Đào tạo', 6, '2018-05-21 06:06:43', '2018-05-21 06:06:43', '/dao-tao/tuy-chinh-doi-tuong-dao-tao-cho-khoa-hoc', NULL),
(44, 'Đào tạo => Quản lý nhân viên trong khóa học', 'Đào tạo => Quản lý nhân viên trong khóa học', 'Đào tạo', 6, '2018-05-21 06:07:03', '2018-05-21 06:07:03', '/dao-tao/quan-ly-nhan-vien-trong-khoa-hoc', NULL),
(45, 'OT/Nghỉ phép', 'OT/Nghỉ phép', 'Đào tạo', 8, '2018-05-21 06:08:35', '2018-06-15 06:55:50', '/lam-them-gio-va-nghi-phep', '2018-06-15 06:55:50'),
(46, 'OT/Nghỉ phép => Danh sách đi muộn về sớm', 'OT/Nghỉ phép => Danh sách đi muộn về sớm', 'Đào tạo', 8, '2018-05-21 06:09:12', '2018-05-21 06:09:12', '/lam-them-gio-va-nghi-phep/di-muon-ve-som', NULL),
(47, 'OT/Nghỉ phép => Tạo/ Chỉnh  sửa đơn văng mặt', 'OT/Nghỉ phép => Tạo/ Chỉnh  sửa đơn văng mặt', 'Đào tạo', 8, '2018-05-21 06:10:00', '2018-07-04 09:32:48', '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat', NULL),
(48, 'OT/Nghỉ phép => Danh sách nghỉ theo ngày', 'OT/Nghỉ phép => Danh sách nghỉ theo ngày', 'Đào tạo', 8, '2018-05-21 06:10:20', '2018-05-21 06:10:20', '/lam-them-gio-va-nghi-phep/nghi-theo-ngay', NULL),
(49, 'OT/Nghỉ phép => Sửa đơn đi muộn về sớm', 'OT/Nghỉ phép => Sửa đơn đi muộn về sớm', 'Đào tạo', 8, '2018-05-21 06:10:40', '2018-07-04 09:29:53', '/lam-them-gio-va-nghi-phep/sua-di-muon-ve-som', '2018-07-04 09:29:53'),
(50, 'OT/Nghỉ phép => Sửa nghỉ theo ngày', 'OT/Nghỉ phép => Sửa nghỉ theo ngày', 'Đào tạo', 8, '2018-05-21 06:10:53', '2018-07-04 09:29:58', '/lam-them-gio-va-nghi-phep/sua-nghi-theo-ngay', '2018-07-04 09:29:58'),
(51, 'OT/Nghỉ phép => Duyệt nghỉ phép', 'OT/Nghỉ phép => Duyệt nghỉ phép', 'Đào tạo', 8, '2018-05-21 06:11:11', '2018-05-21 06:11:11', '/lam-them-gio-va-nghi-phep/duyet-nghi-phep', NULL),
(52, 'OT/Nghỉ phép => Duyệt làm thêm giờ', 'OT/Nghỉ phép => Duyệt làm thêm giờ', 'Đào tạo', 8, '2018-05-21 06:11:24', '2018-07-04 09:33:13', '/lam-them-gio-va-nghi-phep/duyet-lam-them-gio', '2018-07-04 09:33:13'),
(53, 'OT/Nghỉ phép => Danh sách làm thêm giờ', 'OT/Nghỉ phép => Danh sách làm thêm giờ', 'Đào tạo', 8, '2018-05-21 06:11:54', '2018-05-21 06:11:54', '/lam-them-gio-va-nghi-phep/lam-them-gio', NULL),
(54, 'OT/Nghỉ phép => Tạo/ Chỉnh sửa đơn làm thêm giờ', 'OT/Nghỉ phép => Tạo/ Chỉnh sửa đơn làm thêm giờ', 'Đào tạo', 8, '2018-05-21 06:12:09', '2018-07-04 09:30:40', '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio', NULL),
(55, 'OT/Nghỉ phép => Cập nhật đơn làm thêm giờ', 'OT/Nghỉ phép => Cập nhật đơn làm thêm giờ', 'Đào tạo', 8, '2018-05-21 06:12:23', '2018-07-04 09:32:58', '/lam-them-gio-va-nghi-phep/cap-nhat-don-lam-them-gio', '2018-07-04 09:32:58'),
(56, 'OT/Nghỉ phép =>Duyệt đơn làm thêm giờ', 'OT/Nghỉ phép =>Duyệt đơn làm thêm giờ', 'Đào tạo', 8, '2018-05-21 06:12:54', '2018-05-21 06:12:54', '/lam-them-gio-va-nghi-phep/duyet-lam-them-gio', NULL),
(57, 'Chấm công', 'Chấm công', 'Đào tạo', 4, '2018-05-21 06:14:17', '2018-06-15 06:55:57', '/cham-cong', '2018-06-15 06:55:57'),
(58, 'Chấm công => Duyệt dữ liệu', 'Chấm công => Duyệt dữ liệu', 'Chấm công', 4, '2018-05-21 06:14:35', '2018-05-21 06:14:35', '/cham-cong/duyet-du-lieu', NULL),
(59, 'Chấm công => Bảng tổng', 'Chấm công => Bảng tổng', 'Chấm công', 4, '2018-05-21 06:14:52', '2018-05-21 06:14:52', '/cham-cong/bang-tong', NULL),
(60, 'Danh mục', 'Danh mục', 'Danh mục', 5, '2018-05-21 06:15:14', '2018-06-15 06:56:02', '/danh-muc', '2018-06-15 06:56:02'),
(61, 'Danh mục => Chức danh', 'Danh mục => Chức danh', 'Danh mục => Chức danh', 5, '2018-05-21 06:15:45', '2018-05-21 06:15:45', '/danh-muc/chuc-danh', NULL),
(62, 'Danh mục => Thêm chức danh', 'Danh mục => Thêm chức danh', 'Danh mục => Chức danh', 5, '2018-05-21 06:15:56', '2018-05-21 06:15:56', '/danh-muc/them-chuc-danh', NULL),
(63, 'Danh mục => Cập nhật chức danh', 'Danh mục => Cập nhật chức danh', 'Danh mục => Chức danh', 5, '2018-05-21 06:16:19', '2018-05-21 06:16:19', '/danh-muc/cap-nhat-chuc-danh', NULL),
(64, 'Chấm công => Dữ liệu chấm công', 'Chấm công => Dữ liệu chấm công', 'dữ liệu chấm công', 4, '2018-05-24 06:14:35', '2018-05-24 06:14:35', '/cham-cong/check-in-check-out', NULL),
(65, 'Danh mục => danh mục khác', 'Danh mục => danh mục khác', 'dfv', 5, '2018-06-06 08:14:13', '2018-06-06 08:14:13', '/danh-muc/danh-muc-khac', NULL),
(66, 'Danh mục => danh sách danh mục khác', 'Danh mục => danh sách danh mục khác', 'dfv', 5, '2018-06-06 08:14:28', '2018-06-06 08:14:28', '/danh-muc/danh-sach-trong-danh-muc-khac', NULL),
(67, 'Danh mục => thêm danh mục khác', 'Danh mục => thêm danh mục khác', 'dfv', 5, '2018-06-06 08:14:40', '2018-06-14 02:25:33', '/danh-muc/them-danh-muc-khac', NULL),
(68, 'Danh mục => abcxyz', 'Danh mục => abcxyz', 'a', 5, '2018-06-13 07:11:54', '2018-06-14 08:46:12', '/danh-muc/danh-muc-khac', '2018-06-14 08:46:12'),
(69, 'Nhân sự => Hồ sơ của tôi', 'Nhân sự => Hồ sơ của tôi', 'Xem hồ sơ của mình', 2, '2018-06-13 07:42:04', '2018-06-14 01:22:13', '/danh-sach-nhan-su/ho-so', NULL),
(70, 'Nhân sự => Sửa hồ sơ của tôi', 'Nhân sự => Sửa hồ sơ của tôi', 'Sửa hồ sơ của mình', 2, '2018-06-13 07:42:32', '2018-06-14 01:22:37', '/danh-sach-nhan-su/sua-ho-so', NULL),
(71, 'Chấm Công => Bảng chấm công của tôi', 'Chấm Công => Bảng chấm công của tôi', 'Xem bảng chấm công của tôi', 4, '2018-06-13 08:17:47', '2018-06-14 01:21:39', '/cham-cong/bang-ca-nhan', NULL),
(72, 'OT/Nghỉ phép => Thống kê', 'OT/Nghỉ phép => Thống kê', 'thống kê tất cả đơn xin phép', 8, '2018-06-14 01:26:04', '2018-06-14 01:26:04', '/lam-them-gio-va-nghi-phep/thong-ke', NULL),
(73, 'Danh mục => Cập nhật danh mục khác', 'Danh mục => Cập nhật danh mục khác', 'sdrf', 5, '2018-06-14 02:18:02', '2018-06-14 02:18:02', '/danh-muc/cap-nhat-danh-muc-khac', NULL),
(74, 'Danh mục => Màn hình', 'Danh mục => Màn hình', 'd', 5, '2018-06-14 02:19:35', '2018-06-14 02:23:16', '/danh-muc/man-hinh', NULL),
(75, 'Danh mục => Thêm màn hình', 'Danh mục => Thêm màn hình', 'd', 5, '2018-06-14 02:19:53', '2018-06-14 02:23:28', '/danh-muc/them-danh-muc-man-hinh', NULL),
(76, 'Danh mục => Cập nhật màn hình', 'Danh mục => Cập nhật màn hình', 'd', 5, '2018-06-14 02:20:18', '2018-06-14 02:20:18', '/danh-muc/cap-nhat-danh-muc-man-hinh', NULL),
(77, 'Danh mục => Phòng ban', 'Danh mục => Phòng ban', 'd', 5, '2018-06-14 02:20:35', '2018-06-14 02:20:35', '/danh-muc/phong-ban', NULL),
(78, 'Danh mục => Thêm phòng ban', 'Danh mục => Thêm phòng ban', 'd', 5, '2018-06-14 02:20:46', '2018-06-14 02:20:46', '/danh-muc/them-phong-ban', NULL),
(79, 'Danh mục =>Cập nhật phòng ban', 'Danh mục =>Cập nhật phòng ban', 'd', 5, '2018-06-14 02:21:08', '2018-06-14 02:21:08', '/danh-muc/cap-nhat-phong-ban', NULL),
(80, 'Danh mục => Trạng thái công việc', 'Danh mục => Trạng thái công việc', 'd', 5, '2018-06-14 02:21:44', '2018-06-14 02:21:44', '/danh-muc/trang-thai-cong-viec', NULL),
(81, 'Danh mục => Thêm trạng thái công việc', 'Danh mục => Thêm trạng thái công việc', 'd', 5, '2018-06-14 02:21:54', '2018-06-14 02:21:54', '/danh-muc/them-trang-thai-cong-viec', NULL),
(82, 'Danh mục => Cập nhật trạng thái công việc', 'Danh mục => Cập nhật trạng thái công việc', 'd', 5, '2018-06-14 02:22:04', '2018-06-14 02:22:04', '/danh-muc/cap-nhat-trang-thai-cong-viec', NULL),
(83, 'Danh mục => Các ngày nghỉ lễ', 'Danh mục => Các ngày nghỉ lễ', 'd', 5, '2018-06-14 02:22:31', '2018-06-14 02:22:31', '/danh-muc/cac-ngay-nghi-le', NULL),
(84, 'Danh mục => Cập nhật các ngày nghỉ lễ', 'Danh mục => Cập nhật các ngày nghỉ lễ', 'd', 5, '2018-06-14 02:22:42', '2018-06-14 02:22:42', '/danh-muc/cap-nhat-cac-ngay-nghi-le', NULL),
(85, 'Danh mục => Thêm các ngày nghỉ lễ', 'Danh mục => Thêm các ngày nghỉ lễ', 'd', 5, '2018-06-14 02:22:54', '2018-06-14 02:22:54', '/danh-muc/them-cac-ngay-nghi-le', NULL),
(86, 'Danh sách nhân sự => Đổi mật khẩu', 'Danh sách nhân sự => Đổi mật khẩu', 'Mô tả', 2, '2018-07-05 15:48:23', '2018-07-05 15:48:23', '/danh-sach-nhan-su/doi-mat-khau', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `screen_categories`
--

CREATE TABLE `screen_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `screen_categories`
--

INSERT INTO `screen_categories` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Quản trị', 'Quản trị', 'Quản trị', NULL, NULL, NULL),
(2, 'Danh sách nhân sự', 'Danh sách nhân sự', 'Danh sách nhân sự', NULL, NULL, NULL),
(4, 'Chấm công', 'Chấm công', 'Chấm công', NULL, NULL, NULL),
(5, 'Danh mục', 'Danh mục', 'Danh mục', NULL, NULL, NULL),
(6, 'Đào tạo', 'Đào tạo', 'Đào tạo', '2018-05-21 06:02:01', '2018-05-21 06:02:01', NULL),
(8, 'OT/Nghỉ phép', 'OT/Nghỉ phép', 'OT/Nghỉ phép', '2018-05-21 06:08:26', '2018-05-21 06:08:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `screen_role`
--

CREATE TABLE `screen_role` (
  `screen_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `screen_role`
--

INSERT INTO `screen_role` (`screen_id`, `role_id`) VALUES
(25, 8),
(26, 8),
(27, 8),
(28, 8),
(29, 8),
(30, 8),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 3),
(39, 2),
(39, 4),
(39, 5),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(46, 2),
(46, 5),
(47, 2),
(47, 5),
(48, 2),
(48, 5),
(49, 5),
(50, 5),
(51, 2),
(51, 5),
(52, 5),
(53, 3),
(53, 5),
(53, 6),
(54, 3),
(54, 5),
(54, 6),
(55, 5),
(56, 3),
(56, 5),
(58, 3),
(59, 3),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(65, 3),
(66, 3),
(66, 7),
(67, 3),
(67, 7),
(69, 2),
(69, 3),
(69, 4),
(69, 5),
(70, 2),
(70, 3),
(70, 4),
(70, 5),
(71, 2),
(71, 3),
(71, 5),
(72, 3),
(72, 6),
(73, 3),
(73, 7),
(74, 3),
(75, 3),
(76, 3),
(77, 3),
(78, 3),
(79, 3),
(80, 3),
(81, 3),
(82, 3),
(83, 3),
(84, 3),
(85, 3),
(86, 2),
(86, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `trainer` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supporter` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_category_id` int(11) NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `course_id`, `start_datetime`, `end_datetime`, `trainer`, `supporter`, `content`, `room_category_id`, `notified`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 1, '2018-07-04 16:53:00', '2018-07-05 16:53:00', 'phuongnv@ominext.com', 'phuongnv@ominext.com', 'x', 3, 0, NULL, '2018-07-05 19:14:46', '2018-07-05 19:14:46'),
(5, 2, '2018-07-04 16:54:00', '2018-07-05 16:54:00', 'phuongnv@ominext.com', 'phuongnv@ominext.com', 'ẻge', 3, 0, NULL, '2018-07-13 16:07:32', '2018-07-13 16:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `session_user`
--

CREATE TABLE `session_user` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `presence` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_user`
--

INSERT INTO `session_user` (`session_id`, `user_id`, `presence`) VALUES
(3, 1, 1),
(3, 4, 0),
(3, 8, 1),
(3, 10, 1),
(5, 1, 0),
(5, 4, 0),
(5, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `start_morning` time NOT NULL DEFAULT '08:00:00',
  `end_morning` time NOT NULL DEFAULT '11:30:00',
  `start_afternoon` time NOT NULL DEFAULT '13:00:00',
  `end_afternoon` time NOT NULL DEFAULT '17:30:00',
  `in_late_threshold` int(11) NOT NULL DEFAULT '15',
  `time_off_registration_threshold` int(11) NOT NULL DEFAULT '3',
  `hr_and_administration_mail` varchar(191) NOT NULL DEFAULT 'hr_admin@ominext.com',
  `bom_mail` varchar(191) NOT NULL DEFAULT 'bom_omi@ominext.com',
  `notification_frequency` int(11) NOT NULL DEFAULT '600'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `start_morning`, `end_morning`, `start_afternoon`, `end_afternoon`, `in_late_threshold`, `time_off_registration_threshold`, `hr_and_administration_mail`, `bom_mail`, `notification_frequency`) VALUES
(1, '08:00:00', '11:30:00', '13:00:00', '17:30:00', 15, 200, 'hr_admin@ominext.com', 'bom_omi@ominext.com', 600);

-- --------------------------------------------------------

--
-- Table structure for table `specialized_skills`
--

CREATE TABLE `specialized_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialized_skills`
--

INSERT INTO `specialized_skills` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Tiếng Anh', 'Tiếng Anh', '2018-07-04 10:34:24', '2018-07-04 10:34:24', NULL),
(2, 'Tiếng Nhật - N1', 'Tiếng Nhật - N1', '2018-07-05 17:42:33', '2018-07-05 17:42:33', NULL),
(3, 'Tiếng Nhật - N2', 'Tiếng Nhật - N2', '2018-07-05 17:42:50', '2018-07-05 17:42:50', NULL),
(4, 'PHP', 'PHP', '2018-07-05 17:43:15', '2018-07-05 17:43:15', NULL),
(5, 'Java', 'Java', '2018-07-05 17:43:30', '2018-07-05 17:43:30', NULL),
(6, 'Java', 'Java', '2018-07-05 17:43:40', '2018-07-05 17:46:04', '2018-07-05 17:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `time_off`
--

CREATE TABLE `time_off` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detailed_reason` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backup_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `flow_type` tinyint(1) DEFAULT NULL,
  `forget_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_off`
--

INSERT INTO `time_off` (`id`, `employee_id`, `start_datetime`, `end_datetime`, `status`, `approved`, `approved_reason`, `reason`, `detailed_reason`, `backup_person`, `file_id`, `created_at`, `updated_at`, `deleted_at`, `flow_type`, `forget_type`) VALUES
(158, 10, '2018-07-19 08:00:00', '2018-07-19 17:30:00', 1, 0, NULL, 'Lý do khác', 'aa', 'hanhdh@ominext.com', NULL, '2018-07-19 10:48:05', '2018-07-19 14:27:13', NULL, 0, 0),
(159, 7, '2018-07-19 16:00:00', '2018-07-19 17:30:00', 2, 0, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'shrtgghrtyht', 'hungnp@ominext.com', NULL, '2018-07-19 07:21:22', '2018-07-19 07:43:16', NULL, 0, 0),
(160, 7, '2018-07-19 08:00:00', '2018-07-19 17:30:00', 1, 2, 'yud5u67u6ty7u', 'Ốm (Ốm hoặc bị thương) / Sick leave', 'dsvgvfv', 'hungnp@ominext.com', NULL, '2018-07-19 07:30:58', '2018-08-20 02:54:02', NULL, 0, 0),
(161, 7, '2018-07-19 08:00:00', '2018-07-19 17:30:00', 1, 0, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'dfds', 'hungnp@ominext.com', NULL, '2018-07-19 08:05:24', '2018-07-19 08:05:24', NULL, 0, 0),
(162, 7, '2018-07-19 08:00:00', '2018-07-19 17:30:00', 5, 2, 'dfyhug', 'Ốm (Ốm hoặc bị thương) / Sick leave', 'ăerdfawedrfw', 'hungnp@ominext.com', NULL, '2018-07-19 08:06:30', '2018-08-20 08:37:12', '2018-08-20 08:37:12', 0, 0),
(163, 7, '2018-07-23 09:00:00', '2018-07-24 09:00:00', 6, 0, NULL, 'Lý do khác', 'e6y6t', 'hungnp@ominext.com', NULL, '2018-07-23 02:19:22', '2018-07-23 02:19:22', NULL, 0, 0),
(164, 7, '2018-07-25 08:00:00', '2018-07-25 17:30:00', 5, 1, NULL, 'Lý do khác', 'e6y6t', 'hungnp@ominext.com', NULL, '2018-07-23 02:19:29', '2018-08-23 09:45:48', NULL, 0, 0),
(165, 7, '2018-07-23 08:00:00', '2018-07-23 17:30:00', 1, 1, NULL, 'Lý do khác', 'sdfsd', 'hungnp@ominext.com', NULL, '2018-07-23 02:19:41', '2018-08-23 09:45:49', NULL, 0, 0),
(166, 7, '2018-07-23 17:00:00', '2018-07-23 17:30:00', 2, 1, NULL, 'Lý do khác', 'sdfsd', 'hungnp@ominext.com', NULL, '2018-07-23 02:19:50', '2018-08-23 09:45:49', NULL, 0, 0),
(167, 7, '2018-07-23 17:00:00', '2018-07-23 17:30:00', 3, 1, NULL, 'Lý do khác', 'sdfsd', 'hungnp@ominext.com', NULL, '2018-07-23 02:19:59', '2018-08-23 09:45:49', NULL, 0, 0),
(168, 7, '2018-07-23 08:00:00', '2018-07-23 17:30:00', 5, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'đ', 'hungnp@ominext.com', NULL, '2018-07-23 02:23:27', '2018-08-23 09:45:49', NULL, 0, 0),
(169, 7, '2018-07-23 08:00:00', '2018-07-23 17:30:00', 4, 1, NULL, 'Lý do khác', 'rtyhrtyh', NULL, NULL, '2018-07-23 02:26:31', '2018-08-23 09:45:49', NULL, 0, 0),
(170, 4, '2018-08-02 00:00:00', '2018-08-14 00:00:00', 6, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'sdcfdascfds', 'tinhvv@ominext.com', NULL, '2018-08-02 03:24:03', '2018-08-23 09:45:49', NULL, 0, 0),
(171, 4, '2018-08-14 00:00:00', '2018-08-23 00:00:00', 6, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'dsfds', 'hungnp@ominext.com', NULL, '2018-08-02 03:25:58', '2018-08-23 09:45:49', NULL, 0, 0),
(172, 4, '2018-08-29 08:00:00', '2018-08-29 17:30:00', 5, 2, 'rèarefw4f', 'Lý do khác', 'fdefas', 'hungnp@ominext.com', NULL, '2018-08-02 03:27:00', '2018-08-13 03:15:51', NULL, 0, 0),
(173, 4, '2018-08-02 08:00:00', '2018-08-02 17:30:00', 1, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'rtyhnct', 'hungnp@ominext.com', NULL, '2018-08-02 03:27:38', '2018-08-23 09:45:49', NULL, 0, 0),
(174, 4, '2018-08-09 08:00:00', '2018-08-09 17:30:00', 1, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'fgbfg', 'hungnp@ominext.com', NULL, '2018-08-09 03:00:47', '2018-08-23 09:45:49', NULL, 0, 0),
(175, 4, '2018-08-09 08:00:00', '2018-08-09 17:30:00', 2, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'rtghre', 'hungnp@ominext.com', NULL, '2018-08-09 03:01:13', '2018-08-23 09:48:06', NULL, 0, 0),
(176, 4, '2018-08-09 08:00:00', '2018-08-09 17:30:00', 5, 1, NULL, 'Ốm (Ốm hoặc bị thương) / Sick leave', 'retyet', 'hungnp@ominext.com', NULL, '2018-08-09 03:01:54', '2018-08-23 09:45:49', NULL, 0, 0),
(177, 4, '2018-08-10 08:00:00', '2018-08-10 17:30:00', 4, 1, NULL, 'Lý do khác', 'retyet', 'hungnp@ominext.com', NULL, '2018-08-09 03:02:27', '2018-08-23 09:45:49', NULL, 0, 0),
(178, 4, '2018-08-16 08:00:00', '2018-08-16 17:30:00', 4, 1, NULL, 'Lý do khác', 'retyet', 'hungnp@ominext.com', NULL, '2018-08-09 03:02:51', '2018-08-23 09:45:49', NULL, 0, 0),
(179, 4, '2018-08-17 08:00:00', '2018-08-17 17:30:00', 4, 1, NULL, 'Lý do khác', 'sdc', NULL, NULL, '2018-08-09 03:18:29', '2018-08-23 09:45:49', NULL, 0, 0),
(180, 4, '2018-08-13 08:00:00', '2018-08-13 17:30:00', 4, 1, NULL, 'Lý do khác', 'rtyhy', NULL, NULL, '2018-08-13 04:06:52', '2018-08-23 09:45:49', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `time_off_excel_files`
--

CREATE TABLE `time_off_excel_files` (
  `id` int(11) NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `time_on`
--

CREATE TABLE `time_on` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `day_off` int(11) DEFAULT NULL,
  `working_time` float DEFAULT NULL,
  `tc` float DEFAULT NULL,
  `hour` float DEFAULT NULL,
  `day_off_half_permit` int(11) NOT NULL DEFAULT '0',
  `day_off_full_permit` int(11) NOT NULL DEFAULT '0',
  `day_off_permit` int(11) NOT NULL DEFAULT '0',
  `work_online` int(11) NOT NULL DEFAULT '0',
  `day_off_late_permit` int(11) NOT NULL DEFAULT '0',
  `day_off_late_without_permit` int(11) NOT NULL DEFAULT '0',
  `day_off_go_out` int(11) NOT NULL DEFAULT '0',
  `day_off_leave_early_permit` int(11) NOT NULL DEFAULT '0',
  `day_off_holiday` int(11) NOT NULL DEFAULT '0',
  `day_off_late_ot` int(11) NOT NULL DEFAULT '0',
  `time_on_month_id` int(11) NOT NULL,
  `late` int(11) NOT NULL DEFAULT '0',
  `leave_early` int(11) NOT NULL DEFAULT '0',
  `day_off_leave_early_without_permit` int(11) NOT NULL DEFAULT '0',
  `is_updated` tinyint(1) NOT NULL DEFAULT '0',
  `day_off_without_permit` int(11) NOT NULL DEFAULT '0',
  `is_imported` tinyint(1) NOT NULL DEFAULT '0',
  `day_off_ot` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on`
--

INSERT INTO `time_on` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `status`, `created_at`, `updated_at`, `deleted_at`, `day_off`, `working_time`, `tc`, `hour`, `day_off_half_permit`, `day_off_full_permit`, `day_off_permit`, `work_online`, `day_off_late_permit`, `day_off_late_without_permit`, `day_off_go_out`, `day_off_leave_early_permit`, `day_off_holiday`, `day_off_late_ot`, `time_on_month_id`, `late`, `leave_early`, `day_off_leave_early_without_permit`, `is_updated`, `day_off_without_permit`, `is_imported`, `day_off_ot`) VALUES
(1, 6, '2018-01-01', '17:24:19', NULL, 0, '2018-07-04 20:39:28', '2018-07-09 18:24:26', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 183, 564, 0, 0, 1, 0, 1, 0),
(2, 6, '2018-01-02', '08:16:00', '17:33:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0.97, 0, 7.73, 0, 0, 0, 0, 0, 16, 0, 0, 0, 0, 183, 16, 0, 0, 0, 0, 1, 0),
(3, 6, '2018-01-03', '08:40:00', '17:00:00', 1, '2018-07-04 20:39:28', '2018-07-06 11:27:49', NULL, NULL, 0.85, 0.38, 6.83, 0, 0, 0, 0, 0, 0, 0, 270, 0, 0, 183, 40, 30, 0, 1, 0, 1, 0),
(4, 6, '2018-01-04', '08:00:00', '17:30:11', 0, '2018-07-04 20:39:28', '2018-07-05 14:37:23', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 1, 0, 1, 0),
(5, 6, '2018-01-05', '08:16:00', '17:33:00', 1, '2018-07-04 20:39:28', '2018-07-05 14:33:57', NULL, NULL, 0.97, 0, 7.73, 0, 0, 0, 0, 0, 16, 0, 0, 0, 0, 183, 16, 0, 0, 1, 0, 1, 0),
(6, 6, '2018-01-06', '10:27:04', '17:25:30', 0, '2018-07-04 20:39:28', '2018-07-09 18:27:13', NULL, NULL, 0.69, 0, 5.48, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 147, 4, 4, 1, 0, 1, 0),
(7, 6, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(8, 6, '2018-01-08', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 480, 1, 0),
(9, 6, '2018-01-09', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 480, 1, 0),
(10, 6, '2018-01-10', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 480, 1, 0),
(11, 6, '2018-01-11', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 480, 1, 0),
(12, 6, '2018-01-12', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 480, 1, 0),
(13, 6, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(14, 6, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(15, 6, '2018-01-15', '07:48:00', '18:07:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(16, 6, '2018-01-16', '08:15:00', '18:11:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0.97, 0, 7.75, 0, 0, 0, 0, 15, 0, 0, 0, 0, 0, 183, 15, 0, 0, 0, 0, 1, 0),
(17, 6, '2018-01-17', '07:56:00', '17:34:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(18, 6, '2018-01-18', '08:04:00', '18:35:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0.99, 0.08, 7.93, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 183, 4, 0, 0, 0, 0, 1, 0),
(19, 6, '2018-01-19', '08:09:00', '15:08:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0.69, 0, 5.48, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 183, 9, 142, 142, 0, 0, 1, 0),
(20, 6, '2018-01-20', '10:05:00', '11:43:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0.63, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 125, 347, 347, 0, 0, 1, 0),
(21, 6, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(22, 6, '2018-01-22', '08:11:00', '18:42:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0.98, 0.2, 7.82, 0, 0, 0, 0, 11, 0, 0, 0, 0, 0, 183, 11, 0, 0, 0, 0, 1, 0),
(23, 6, '2018-01-23', '07:55:00', '18:43:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0.22, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(24, 6, '2018-01-24', '07:55:00', '19:01:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0.52, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(25, 6, '2018-01-25', '07:57:00', '18:11:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(26, 6, '2018-01-26', '08:02:00', '18:09:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 1, 0, 7.97, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 183, 2, 0, 0, 0, 0, 1, 0),
(27, 6, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:28', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(28, 6, '2018-01-28', NULL, NULL, 0, '2018-07-04 20:39:28', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(29, 6, '2018-01-29', '07:53:00', '18:50:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:29', NULL, NULL, 1, 0.33, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(30, 6, '2018-01-30', '07:55:00', '18:51:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:29', NULL, NULL, 1, 0.35, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 0, 0, 0, 0, 0, 1, 0),
(31, 6, '2018-01-31', '08:30:00', '17:37:00', 1, '2018-07-04 20:39:28', '2018-07-04 20:39:29', NULL, NULL, 0.94, 0, 7.5, 0, 0, 0, 0, 0, 30, 0, 0, 0, 0, 183, 30, 0, 0, 0, 0, 1, 0),
(32, 7, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(33, 7, '2018-01-02', '08:00:00', '17:07:06', 0, '2018-07-04 20:39:29', '2018-07-10 19:25:16', NULL, NULL, 0.95, 0, 7.63, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 22, 22, 1, 0, 1, 0),
(34, 7, '2018-01-03', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(35, 7, '2018-01-04', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(36, 7, '2018-01-05', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(37, 7, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(38, 7, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(39, 7, '2018-01-08', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(40, 7, '2018-01-09', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(41, 7, '2018-01-10', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(42, 7, '2018-01-11', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(43, 7, '2018-01-12', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(44, 7, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(45, 7, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(46, 7, '2018-01-15', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(47, 7, '2018-01-16', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(48, 7, '2018-01-17', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(49, 7, '2018-01-18', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(50, 7, '2018-01-19', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(51, 7, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(52, 7, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(53, 7, '2018-01-22', '08:35:00', '18:55:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0.93, 0.42, 7.42, 0, 0, 0, 0, 0, 35, 0, 0, 0, 0, 184, 35, 0, 0, 0, 0, 1, 0),
(54, 7, '2018-01-23', '07:55:00', '18:34:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 1, 0.07, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(55, 7, '2018-01-24', '08:04:00', '10:48:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0.34, 0, 2.73, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 184, 4, 402, 402, 0, 0, 1, 0),
(56, 7, '2018-01-25', '07:53:00', '19:35:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 1, 1.08, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(57, 7, '2018-01-26', '08:19:00', '18:05:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0.96, 0, 7.68, 0, 0, 0, 0, 0, 19, 0, 0, 0, 0, 184, 19, 0, 0, 0, 0, 1, 0),
(58, 7, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(59, 7, '2018-01-28', '15:27:00', NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 447, 0, 0, 0, 0, 1, 0),
(60, 7, '2018-01-29', '07:54:00', '18:30:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 0, 1, 0),
(61, 7, '2018-01-30', '08:00:00', '11:20:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0.42, 0, 3.33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 370, 370, 0, 0, 1, 0),
(62, 7, '2018-01-31', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 0, 0, 0, 0, 480, 1, 0),
(63, 8, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(64, 8, '2018-01-02', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 480, 1, 0),
(65, 8, '2018-01-03', '08:00:00', '12:33:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 297, 297, 0, 0, 1, 0),
(66, 8, '2018-01-04', '07:56:00', '18:36:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 1, 0.1, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(67, 8, '2018-01-05', '07:46:00', '18:43:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 1, 0.22, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(68, 8, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:29', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(69, 8, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(70, 8, '2018-01-08', '07:46:00', '18:36:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.1, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(71, 8, '2018-01-09', '07:50:00', '18:59:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.48, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(72, 8, '2018-01-10', '07:48:00', '19:15:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.75, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(73, 8, '2018-01-11', '07:53:00', '16:43:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.9, 0, 7.22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 47, 47, 0, 0, 1, 0),
(74, 8, '2018-01-12', '07:45:00', '15:46:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.78, 0, 6.27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 104, 104, 0, 0, 1, 0),
(75, 8, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(76, 8, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(77, 8, '2018-01-15', '08:20:00', '18:26:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.96, 0, 7.67, 0, 0, 0, 0, 0, 20, 0, 0, 0, 0, 185, 20, 0, 0, 0, 0, 1, 0),
(78, 8, '2018-01-16', '07:46:00', '14:50:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.67, 0, 5.33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 160, 160, 0, 0, 1, 0),
(79, 8, '2018-01-17', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 480, 1, 0),
(80, 8, '2018-01-18', '09:47:00', '18:55:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.78, 0.42, 6.22, 0, 0, 0, 0, 0, 107, 0, 0, 0, 0, 185, 107, 0, 0, 0, 0, 1, 0),
(81, 8, '2018-01-19', '07:47:00', '19:02:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.53, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(82, 8, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(83, 8, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(84, 8, '2018-01-22', '07:54:00', '18:31:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.02, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(85, 8, '2018-01-23', '07:46:00', '15:50:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.79, 0, 6.33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 100, 100, 0, 0, 1, 0),
(86, 8, '2018-01-24', '07:43:00', '18:19:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(87, 8, '2018-01-25', '07:42:00', '18:48:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.3, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(88, 8, '2018-01-26', '07:51:00', '18:50:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.33, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(89, 8, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(90, 8, '2018-01-28', '14:38:00', '19:37:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0, 3.98, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 398, 0, 0, 0, 0, 1, 0),
(91, 8, '2018-01-29', '07:50:00', '18:51:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 1, 0.35, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 0, 0, 0, 0, 0, 1, 0),
(92, 8, '2018-01-30', '08:47:00', '18:52:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.9, 0.37, 7.22, 0, 0, 0, 0, 0, 47, 0, 0, 0, 0, 185, 47, 0, 0, 0, 0, 1, 0),
(93, 8, '2018-01-31', '08:52:00', '18:51:00', 1, '2018-07-04 20:39:29', '2018-07-04 20:39:30', NULL, NULL, 0.89, 0.35, 7.13, 0, 0, 0, 0, 0, 52, 0, 0, 0, 0, 185, 52, 0, 0, 0, 0, 1, 0),
(94, 9, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(95, 9, '2018-01-02', '07:52:00', '17:31:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(96, 9, '2018-01-03', '07:48:00', '18:30:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(97, 9, '2018-01-04', '08:12:00', '18:16:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0.98, 0, 7.8, 0, 0, 0, 0, 12, 0, 0, 0, 0, 0, 186, 12, 0, 0, 0, 0, 1, 0),
(98, 9, '2018-01-05', '07:47:00', '19:39:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 1, 1.15, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(99, 9, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(100, 9, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(101, 9, '2018-01-08', '07:52:00', '18:23:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(102, 9, '2018-01-09', '08:08:00', '18:19:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0.98, 0, 7.87, 0, 0, 0, 0, 8, 0, 0, 0, 0, 0, 186, 8, 0, 0, 0, 0, 1, 0),
(103, 9, '2018-01-10', '07:52:00', '12:13:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 317, 317, 0, 0, 1, 0),
(104, 9, '2018-01-11', '07:51:00', '15:39:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0.77, 0, 6.15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 111, 111, 0, 0, 1, 0),
(105, 9, '2018-01-12', '07:51:00', '15:47:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0.79, 0, 6.28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 103, 103, 0, 0, 1, 0),
(106, 9, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(107, 9, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(108, 9, '2018-01-15', '08:04:00', '19:32:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0.99, 1.03, 7.93, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 186, 4, 0, 0, 0, 0, 1, 0),
(109, 9, '2018-01-16', '07:53:00', '14:16:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0.6, 0, 4.77, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 194, 194, 0, 0, 1, 0),
(110, 9, '2018-01-17', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 480, 1, 0),
(111, 9, '2018-01-18', '09:57:00', '19:41:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0.76, 1.18, 6.05, 0, 0, 0, 0, 0, 117, 0, 0, 0, 0, 186, 117, 0, 0, 0, 0, 1, 0),
(112, 9, '2018-01-19', '07:54:00', '18:51:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0.35, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(113, 9, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(114, 9, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(115, 9, '2018-01-22', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 480, 1, 0),
(116, 9, '2018-01-23', '07:49:00', '18:31:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0.02, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(117, 9, '2018-01-24', '07:57:00', '19:07:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0.62, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(118, 9, '2018-01-25', '07:51:00', '18:36:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0.1, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(119, 9, '2018-01-26', '07:49:00', '18:46:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0.27, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(120, 9, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(121, 9, '2018-01-28', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(122, 9, '2018-01-29', '07:57:00', '17:32:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 0, 1, 0),
(123, 9, '2018-01-30', '08:09:00', '18:06:00', 1, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0.98, 0, 7.85, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 186, 9, 0, 0, 0, 0, 1, 0),
(124, 9, '2018-01-31', NULL, NULL, 0, '2018-07-04 20:39:30', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 0, 0, 0, 0, 480, 1, 0),
(125, 10, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(126, 10, '2018-01-02', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 480, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(127, 10, '2018-01-03', '07:54:00', '18:19:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 150, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(128, 10, '2018-01-04', '08:31:00', '18:10:00', 1, '2018-07-04 20:39:31', '2018-07-10 19:00:25', NULL, NULL, 0.94, 0, 7.48, 0, 0, 0, 0, 0, 31, 0, 0, 0, 0, 187, 31, 0, 0, 1, 0, 1, 0),
(129, 10, '2018-01-05', '07:56:00', '18:27:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(130, 10, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(131, 10, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(132, 10, '2018-01-08', '07:15:00', '20:07:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 1.62, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(133, 10, '2018-01-09', '07:55:00', '19:26:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 0.93, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(134, 10, '2018-01-10', '07:57:00', '19:43:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 1.22, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(135, 10, '2018-01-11', '07:14:00', '19:42:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 1.2, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(136, 10, '2018-01-12', '07:51:00', '15:49:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0.79, 0, 6.32, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 101, 101, 0, 0, 1, 0),
(137, 10, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(138, 10, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(139, 10, '2018-01-15', '07:21:00', '19:28:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 0.97, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(140, 10, '2018-01-16', '07:19:00', '15:38:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0.77, 0, 6.13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 112, 112, 0, 0, 1, 0),
(141, 10, '2018-01-17', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 480, 1, 0),
(142, 10, '2018-01-18', '09:25:00', '19:33:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0.82, 1.05, 6.58, 0, 0, 0, 0, 90, 0, 0, 0, 0, 0, 187, 85, 0, 0, 0, 0, 1, 0),
(143, 10, '2018-01-19', '07:31:00', '17:47:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(144, 10, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(145, 10, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:31', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(146, 10, '2018-01-22', '07:48:00', '17:14:00', 1, '2018-07-04 20:39:31', '2018-07-05 14:56:49', NULL, NULL, 0.97, 0, 7.73, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 16, 16, 1, 0, 1, 0),
(147, 10, '2018-01-23', '07:17:00', '18:24:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(148, 10, '2018-01-24', '12:34:00', '18:20:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 0.56, 0, 4.5, 0, 0, 0, 0, 0, 274, 0, 0, 0, 0, 187, 274, 0, 0, 0, 0, 1, 0),
(149, 10, '2018-01-25', '07:25:00', '19:58:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 1, 1.47, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(150, 10, '2018-01-26', '08:21:00', '18:33:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 0.96, 0.05, 7.65, 0, 0, 0, 0, 0, 21, 0, 0, 0, 0, 187, 21, 0, 0, 0, 0, 1, 0),
(151, 10, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(152, 10, '2018-01-28', '15:03:00', '17:28:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 0, 1.42, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 423, 2, 2, 0, 0, 1, 0),
(153, 10, '2018-01-29', '08:06:00', '19:09:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 0.99, 0.65, 7.9, 0, 0, 0, 0, 6, 0, 0, 0, 0, 0, 187, 6, 0, 0, 0, 0, 1, 0),
(154, 10, '2018-01-30', '08:00:00', '19:52:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 1, 1.37, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(155, 10, '2018-01-31', '06:59:00', '20:22:00', 1, '2018-07-04 20:39:31', '2018-07-04 20:39:32', NULL, NULL, 1, 1.87, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 0, 0, 0, 0, 0, 1, 0),
(156, 11, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(157, 11, '2018-01-02', '06:59:00', '17:57:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(158, 11, '2018-01-03', '07:41:00', '18:16:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(159, 11, '2018-01-04', '07:19:00', '18:20:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(160, 11, '2018-01-05', '07:09:00', '17:42:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(161, 11, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(162, 11, '2018-01-07', '18:57:00', '22:39:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 2.7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(163, 11, '2018-01-08', '07:07:00', '19:37:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 1.12, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(164, 11, '2018-01-09', '07:29:00', '18:08:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(165, 11, '2018-01-10', '07:35:00', '22:50:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 3, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(166, 11, '2018-01-11', '07:44:00', '23:01:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 3, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(167, 11, '2018-01-12', '07:51:00', '15:38:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0.77, 0, 6.13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 112, 112, 0, 0, 1, 0),
(168, 11, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(169, 11, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(170, 11, '2018-01-15', '07:58:00', '18:23:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(171, 11, '2018-01-16', '07:43:00', '12:52:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 278, 278, 0, 0, 1, 0),
(172, 11, '2018-01-17', '17:36:00', NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(173, 11, '2018-01-18', '09:56:00', '19:33:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0.76, 1.05, 6.07, 0, 0, 0, 0, 0, 116, 0, 0, 0, 0, 188, 116, 0, 0, 0, 0, 1, 0),
(174, 11, '2018-01-19', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 480, 1, 0),
(175, 11, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(176, 11, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(177, 11, '2018-01-22', '07:54:00', '18:55:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0.42, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(178, 11, '2018-01-23', '07:38:00', '18:16:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(179, 11, '2018-01-24', '07:29:00', '18:05:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(180, 11, '2018-01-25', '07:41:00', '22:44:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 3, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(181, 11, '2018-01-26', '07:55:00', '17:34:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:32', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(182, 11, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(183, 11, '2018-01-28', NULL, NULL, 0, '2018-07-04 20:39:32', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(184, 11, '2018-01-29', '07:39:00', '18:22:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(185, 11, '2018-01-30', '07:24:00', '18:25:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(186, 11, '2018-01-31', '07:55:00', '18:41:00', 1, '2018-07-04 20:39:32', '2018-07-04 20:39:33', NULL, NULL, 1, 0.18, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 0, 0, 0, 0, 0, 1, 0),
(187, 12, '2018-01-01', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(188, 12, '2018-01-02', '07:44:00', '17:42:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(189, 12, '2018-01-03', '07:36:00', '17:45:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(190, 12, '2018-01-04', '07:50:00', '17:47:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(191, 12, '2018-01-05', '07:33:00', '12:20:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 310, 310, 0, 0, 1, 0),
(192, 12, '2018-01-06', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(193, 12, '2018-01-07', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(194, 12, '2018-01-08', '07:45:00', '17:45:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(195, 12, '2018-01-09', '07:45:00', '17:38:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(196, 12, '2018-01-10', '07:42:00', '17:43:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(197, 12, '2018-01-11', '07:42:00', '17:51:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(198, 12, '2018-01-12', '07:33:00', '15:27:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0.74, 0, 5.95, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 123, 123, 0, 0, 1, 0),
(199, 12, '2018-01-13', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(200, 12, '2018-01-14', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(201, 12, '2018-01-15', '07:39:00', '17:42:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(202, 12, '2018-01-16', '07:47:00', '11:46:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 344, 344, 0, 0, 1, 0),
(203, 12, '2018-01-17', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 480, 1, 0),
(204, 12, '2018-01-18', '11:16:00', '17:42:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0.59, 0, 4.73, 0, 0, 0, 0, 0, 196, 0, 0, 0, 0, 189, 196, 0, 0, 0, 0, 1, 0),
(205, 12, '2018-01-19', '07:31:00', '17:54:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(206, 12, '2018-01-20', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(207, 12, '2018-01-21', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(208, 12, '2018-01-22', '07:39:00', '11:46:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0.44, 0, 3.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 344, 344, 0, 0, 1, 0),
(209, 12, '2018-01-23', '07:43:00', '17:32:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(210, 12, '2018-01-24', '07:44:00', '17:42:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(211, 12, '2018-01-25', '07:39:00', '17:49:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(212, 12, '2018-01-26', '07:42:00', '17:51:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(213, 12, '2018-01-27', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(214, 12, '2018-01-28', NULL, NULL, 0, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(215, 12, '2018-01-29', '07:34:00', '17:45:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(216, 12, '2018-01-30', '07:37:00', '17:48:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:33', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0),
(217, 12, '2018-01-31', '07:35:00', '17:45:00', 1, '2018-07-04 20:39:33', '2018-07-04 20:39:34', NULL, NULL, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_on_accumulated_year`
--

CREATE TABLE `time_on_accumulated_year` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `day_off_permit_in_year` float NOT NULL DEFAULT '0',
  `day_off_accumulated_permit` float NOT NULL DEFAULT '0',
  `day_off_accumulated_ot` float NOT NULL DEFAULT '0',
  `reset_time` date DEFAULT NULL,
  `day_off_remain_permit` float NOT NULL DEFAULT '0',
  `day_off_remain_ot` float NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_on_excel_data`
--

CREATE TABLE `time_on_excel_data` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `day_off` int(11) DEFAULT NULL,
  `working_time` float NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_duplicate` tinyint(1) NOT NULL DEFAULT '0',
  `is_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `time_on_excel_file_id` int(11) DEFAULT NULL,
  `tc1` float DEFAULT '0',
  `tc2` float DEFAULT '0',
  `tc3` float DEFAULT '0',
  `hour` float DEFAULT '0',
  `late` int(11) NOT NULL DEFAULT '0',
  `leave_early` int(11) NOT NULL DEFAULT '0',
  `is_imported` int(11) NOT NULL DEFAULT '0',
  `time_on_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on_excel_data`
--

INSERT INTO `time_on_excel_data` (`id`, `date`, `check_in`, `check_out`, `employee_id`, `day_off`, `working_time`, `created_at`, `updated_at`, `is_duplicate`, `is_accepted`, `time_on_excel_file_id`, `tc1`, `tc2`, `tc3`, `hour`, `late`, `leave_early`, `is_imported`, `time_on_id`) VALUES
(1, '2018-01-01', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(2, '2018-01-02', '08:16:00', '17:33:00', 6, 0, 0.97, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 7.73, 16, 0, 0, NULL),
(3, '2018-01-03', '08:40:00', '18:53:00', 6, 0, 0.92, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.38, 0, 0, 7.33, 40, 0, 0, NULL),
(4, '2018-01-04', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(5, '2018-01-05', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(6, '2018-01-06', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(7, '2018-01-07', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(8, '2018-01-08', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(9, '2018-01-09', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(10, '2018-01-10', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(11, '2018-01-11', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(12, '2018-01-12', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(13, '2018-01-13', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(14, '2018-01-14', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(15, '2018-01-15', '07:48:00', '18:07:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(16, '2018-01-16', '08:15:00', '18:11:00', 6, 0, 0.97, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 7.75, 15, 0, 0, NULL),
(17, '2018-01-17', '07:56:00', '17:34:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(18, '2018-01-18', '08:04:00', '18:35:00', 6, 0, 0.99, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.08, 0, 0, 7.93, 4, 0, 0, NULL),
(19, '2018-01-19', '08:09:00', '15:08:00', 6, 0, 0.69, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 5.48, 9, 142, 0, NULL),
(20, '2018-01-20', '10:05:00', '11:43:00', 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.63, 0, 0, 0, 0, 0, 0, NULL),
(21, '2018-01-21', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(22, '2018-01-22', '08:11:00', '18:42:00', 6, 0, 0.98, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.2, 0, 0, 7.82, 11, 0, 0, NULL),
(23, '2018-01-23', '07:55:00', '18:43:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.22, 0, 0, 8, 0, 0, 0, NULL),
(24, '2018-01-24', '07:55:00', '19:01:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0.52, 0, 0, 8, 0, 0, 0, NULL),
(25, '2018-01-25', '07:57:00', '18:11:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(26, '2018-01-26', '08:02:00', '18:09:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 7.97, 2, 0, 0, NULL),
(27, '2018-01-27', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:28', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(28, '2018-01-28', NULL, NULL, 6, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(29, '2018-01-29', '07:53:00', '18:50:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.33, 0, 0, 8, 0, 0, 0, NULL),
(30, '2018-01-30', '07:55:00', '18:51:00', 6, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(31, '2018-01-31', '08:30:00', '17:37:00', 6, 0, 0.94, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 7.5, 30, 0, 0, NULL),
(32, '2018-01-01', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(33, '2018-01-02', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(34, '2018-01-03', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(35, '2018-01-04', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(36, '2018-01-05', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(37, '2018-01-06', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(38, '2018-01-07', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(39, '2018-01-08', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(40, '2018-01-09', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(41, '2018-01-10', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(42, '2018-01-11', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(43, '2018-01-12', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(44, '2018-01-13', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(45, '2018-01-14', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(46, '2018-01-15', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(47, '2018-01-16', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(48, '2018-01-17', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(49, '2018-01-18', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(50, '2018-01-19', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(51, '2018-01-20', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(52, '2018-01-21', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(53, '2018-01-22', '08:35:00', '18:55:00', 7, 0, 0.93, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.42, 0, 0, 7.42, 35, 0, 0, NULL),
(54, '2018-01-23', '07:55:00', '18:34:00', 7, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.07, 0, 0, 8, 0, 0, 0, NULL),
(55, '2018-01-24', '08:04:00', '10:48:00', 7, 0, 0.34, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 2.73, 4, 312, 0, NULL),
(56, '2018-01-25', '07:53:00', '19:35:00', 7, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 1.08, 0, 0, 8, 0, 0, 0, NULL),
(57, '2018-01-26', '08:19:00', '18:05:00', 7, 0, 0.96, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 7.68, 19, 0, 0, NULL),
(58, '2018-01-27', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(59, '2018-01-28', '15:27:00', NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(60, '2018-01-29', '07:54:00', '18:30:00', 7, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(61, '2018-01-30', '08:00:00', '11:20:00', 7, 0, 0.42, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 3.33, 0, 280, 0, NULL),
(62, '2018-01-31', NULL, NULL, 7, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(63, '2018-01-01', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(64, '2018-01-02', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(65, '2018-01-03', '08:00:00', '12:33:00', 8, 0, 0.44, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(66, '2018-01-04', '07:56:00', '18:36:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(67, '2018-01-05', '07:46:00', '18:43:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0.22, 0, 0, 8, 0, 0, 0, NULL),
(68, '2018-01-06', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:29', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(69, '2018-01-07', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(70, '2018-01-08', '07:46:00', '18:36:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(71, '2018-01-09', '07:50:00', '18:59:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.48, 0, 0, 8, 0, 0, 0, NULL),
(72, '2018-01-10', '07:48:00', '19:15:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.75, 0, 0, 8, 0, 0, 0, NULL),
(73, '2018-01-11', '07:53:00', '16:43:00', 8, 0, 0.9, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 7.22, 0, 47, 0, NULL),
(74, '2018-01-12', '07:45:00', '15:46:00', 8, 0, 0.78, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 6.27, 0, 104, 0, NULL),
(75, '2018-01-13', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(76, '2018-01-14', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(77, '2018-01-15', '08:20:00', '18:26:00', 8, 0, 0.96, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 7.67, 20, 0, 0, NULL),
(78, '2018-01-16', '07:46:00', '14:50:00', 8, 0, 0.67, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 5.33, 0, 160, 0, NULL),
(79, '2018-01-17', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(80, '2018-01-18', '09:47:00', '18:55:00', 8, 0, 0.78, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.42, 0, 0, 6.22, 107, 0, 0, NULL),
(81, '2018-01-19', '07:47:00', '19:02:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.53, 0, 0, 8, 0, 0, 0, NULL),
(82, '2018-01-20', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(83, '2018-01-21', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(84, '2018-01-22', '07:54:00', '18:31:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.02, 0, 0, 8, 0, 0, 0, NULL),
(85, '2018-01-23', '07:46:00', '15:50:00', 8, 0, 0.79, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 6.33, 0, 100, 0, NULL),
(86, '2018-01-24', '07:43:00', '18:19:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(87, '2018-01-25', '07:42:00', '18:48:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.3, 0, 0, 8, 0, 0, 0, NULL),
(88, '2018-01-26', '07:51:00', '18:50:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.33, 0, 0, 8, 0, 0, 0, NULL),
(89, '2018-01-27', NULL, NULL, 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(90, '2018-01-28', '14:38:00', '19:37:00', 8, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 3.98, 0, 0, 0, 0, 0, 0, NULL),
(91, '2018-01-29', '07:50:00', '18:51:00', 8, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(92, '2018-01-30', '08:47:00', '18:52:00', 8, 0, 0.9, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.37, 0, 0, 7.22, 47, 0, 0, NULL),
(93, '2018-01-31', '08:52:00', '18:51:00', 8, 0, 0.89, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0.35, 0, 0, 7.13, 52, 0, 0, NULL),
(94, '2018-01-01', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(95, '2018-01-02', '07:52:00', '17:31:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(96, '2018-01-03', '07:48:00', '18:30:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(97, '2018-01-04', '08:12:00', '18:16:00', 9, 0, 0.98, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 7.8, 12, 0, 0, NULL),
(98, '2018-01-05', '07:47:00', '19:39:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 1.15, 0, 0, 8, 0, 0, 0, NULL),
(99, '2018-01-06', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(100, '2018-01-07', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(101, '2018-01-08', '07:52:00', '18:23:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(102, '2018-01-09', '08:08:00', '18:19:00', 9, 0, 0.98, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 7.87, 8, 0, 0, NULL),
(103, '2018-01-10', '07:52:00', '12:13:00', 9, 0, 0.44, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(104, '2018-01-11', '07:51:00', '15:39:00', 9, 0, 0.77, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 6.15, 0, 111, 0, NULL),
(105, '2018-01-12', '07:51:00', '15:47:00', 9, 0, 0.79, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 6.28, 0, 103, 0, NULL),
(106, '2018-01-13', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:30', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(107, '2018-01-14', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(108, '2018-01-15', '08:04:00', '19:32:00', 9, 0, 0.99, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.03, 0, 0, 7.93, 4, 0, 0, NULL),
(109, '2018-01-16', '07:53:00', '14:16:00', 9, 0, 0.6, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 4.77, 0, 194, 0, NULL),
(110, '2018-01-17', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(111, '2018-01-18', '09:57:00', '19:41:00', 9, 0, 0.76, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.18, 0, 0, 6.05, 117, 0, 0, NULL),
(112, '2018-01-19', '07:54:00', '18:51:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(113, '2018-01-20', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(114, '2018-01-21', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(115, '2018-01-22', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(116, '2018-01-23', '07:49:00', '18:31:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.02, 0, 0, 8, 0, 0, 0, NULL),
(117, '2018-01-24', '07:57:00', '19:07:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.62, 0, 0, 8, 0, 0, 0, NULL),
(118, '2018-01-25', '07:51:00', '18:36:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(119, '2018-01-26', '07:49:00', '18:46:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.27, 0, 0, 8, 0, 0, 0, NULL),
(120, '2018-01-27', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(121, '2018-01-28', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(122, '2018-01-29', '07:57:00', '17:32:00', 9, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(123, '2018-01-30', '08:09:00', '18:06:00', 9, 0, 0.98, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 7.85, 9, 0, 0, NULL),
(124, '2018-01-31', NULL, NULL, 9, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(125, '2018-01-01', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(126, '2018-01-02', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(127, '2018-01-03', '07:54:00', '18:19:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(128, '2018-01-04', '07:31:00', '18:10:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(129, '2018-01-05', '07:56:00', '18:27:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(130, '2018-01-06', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(131, '2018-01-07', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(132, '2018-01-08', '07:15:00', '20:07:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.62, 0, 0, 8, 0, 0, 0, NULL),
(133, '2018-01-09', '07:55:00', '19:26:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.93, 0, 0, 8, 0, 0, 0, NULL),
(134, '2018-01-10', '07:57:00', '19:43:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.22, 0, 0, 8, 0, 0, 0, NULL),
(135, '2018-01-11', '07:14:00', '19:42:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.2, 0, 0, 8, 0, 0, 0, NULL),
(136, '2018-01-12', '07:51:00', '15:49:00', 10, 0, 0.79, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 6.32, 0, 101, 0, NULL),
(137, '2018-01-13', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(138, '2018-01-14', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(139, '2018-01-15', '07:21:00', '19:28:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0.97, 0, 0, 8, 0, 0, 0, NULL),
(140, '2018-01-16', '07:19:00', '15:38:00', 10, 0, 0.77, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 6.13, 0, 112, 0, NULL),
(141, '2018-01-17', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(142, '2018-01-18', '09:25:00', '19:33:00', 10, 0, 0.82, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 1.05, 0, 0, 6.58, 85, 0, 0, NULL),
(143, '2018-01-19', '07:31:00', '17:47:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(144, '2018-01-20', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(145, '2018-01-21', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:31', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(146, '2018-01-22', '07:48:00', '18:14:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(147, '2018-01-23', '07:17:00', '18:24:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(148, '2018-01-24', '12:34:00', '18:20:00', 10, 0, 0.56, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 4.5, 210, 0, 0, NULL),
(149, '2018-01-25', '07:25:00', '19:58:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.47, 0, 0, 8, 0, 0, 0, NULL),
(150, '2018-01-26', '08:21:00', '18:33:00', 10, 0, 0.96, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0.05, 0, 0, 7.65, 21, 0, 0, NULL),
(151, '2018-01-27', NULL, NULL, 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(152, '2018-01-28', '15:03:00', '17:28:00', 10, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.42, 0, 0, 0, 0, 0, 0, NULL),
(153, '2018-01-29', '08:06:00', '19:09:00', 10, 0, 0.99, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0.65, 0, 0, 7.9, 6, 0, 0, NULL),
(154, '2018-01-30', '08:00:00', '19:52:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.37, 0, 0, 8, 0, 0, 0, NULL),
(155, '2018-01-31', '06:59:00', '20:22:00', 10, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.87, 0, 0, 8, 0, 0, 0, NULL),
(156, '2018-01-01', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(157, '2018-01-02', '06:59:00', '17:57:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(158, '2018-01-03', '07:41:00', '18:16:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(159, '2018-01-04', '07:19:00', '18:20:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(160, '2018-01-05', '07:09:00', '17:42:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(161, '2018-01-06', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(162, '2018-01-07', '18:57:00', '22:39:00', 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 2.7, 0, 0, 0, 0, 0, 0, NULL),
(163, '2018-01-08', '07:07:00', '19:37:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.12, 0, 0, 8, 0, 0, 0, NULL),
(164, '2018-01-09', '07:29:00', '18:08:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(165, '2018-01-10', '07:35:00', '22:50:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 3, 0, 0, 8, 0, 0, 0, NULL),
(166, '2018-01-11', '07:44:00', '23:01:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 3, 0, 0, 8, 0, 0, 0, NULL),
(167, '2018-01-12', '07:51:00', '15:38:00', 11, 0, 0.77, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 6.13, 0, 112, 0, NULL),
(168, '2018-01-13', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(169, '2018-01-14', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(170, '2018-01-15', '07:58:00', '18:23:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(171, '2018-01-16', '07:43:00', '12:52:00', 11, 0, 0.44, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(172, '2018-01-17', '17:36:00', NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 486, 0, 0, NULL),
(173, '2018-01-18', '09:56:00', '19:33:00', 11, 0, 0.76, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 1.05, 0, 0, 6.07, 116, 0, 0, NULL),
(174, '2018-01-19', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(175, '2018-01-20', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(176, '2018-01-21', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(177, '2018-01-22', '07:54:00', '18:55:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0.42, 0, 0, 8, 0, 0, 0, NULL),
(178, '2018-01-23', '07:38:00', '18:16:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(179, '2018-01-24', '07:29:00', '18:05:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(180, '2018-01-25', '07:41:00', '22:44:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 3, 0, 0, 8, 0, 0, 0, NULL),
(181, '2018-01-26', '07:55:00', '17:34:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:32', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(182, '2018-01-27', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(183, '2018-01-28', NULL, NULL, 11, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(184, '2018-01-29', '07:39:00', '18:22:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(185, '2018-01-30', '07:24:00', '18:25:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(186, '2018-01-31', '07:55:00', '18:41:00', 11, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0.18, 0, 0, 8, 0, 0, 0, NULL),
(187, '2018-01-01', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(188, '2018-01-02', '07:44:00', '17:42:00', 12, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(189, '2018-01-03', '07:36:00', '17:45:00', 12, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(190, '2018-01-04', '07:50:00', '17:47:00', 12, 0, 1, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(191, '2018-01-05', '07:33:00', '12:20:00', 12, 0, 0.44, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(192, '2018-01-06', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(193, '2018-01-07', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:20', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(194, '2018-01-08', '07:45:00', '17:45:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(195, '2018-01-09', '07:45:00', '17:38:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(196, '2018-01-10', '07:42:00', '17:43:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(197, '2018-01-11', '07:42:00', '17:51:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(198, '2018-01-12', '07:33:00', '15:27:00', 12, 0, 0.74, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 5.95, 0, 123, 0, NULL),
(199, '2018-01-13', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(200, '2018-01-14', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(201, '2018-01-15', '07:39:00', '17:42:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(202, '2018-01-16', '07:47:00', '11:46:00', 12, 0, 0.44, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(203, '2018-01-17', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(204, '2018-01-18', '11:16:00', '17:42:00', 12, 0, 0.59, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 4.73, 196, 0, 0, NULL),
(205, '2018-01-19', '07:31:00', '17:54:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(206, '2018-01-20', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(207, '2018-01-21', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(208, '2018-01-22', '07:39:00', '11:46:00', 12, 0, 0.44, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(209, '2018-01-23', '07:43:00', '17:32:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(210, '2018-01-24', '07:44:00', '17:42:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(211, '2018-01-25', '07:39:00', '17:49:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(212, '2018-01-26', '07:42:00', '17:51:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(213, '2018-01-27', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(214, '2018-01-28', NULL, NULL, 12, 0, 0, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, NULL),
(215, '2018-01-29', '07:34:00', '17:45:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(216, '2018-01-30', '07:37:00', '17:48:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:33', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(217, '2018-01-31', '07:35:00', '17:45:00', 12, 0, 1, '2018-07-04 20:39:21', '2018-07-04 20:39:34', 0, 1, 1, 0, 0, 0, 8, 0, 0, 0, NULL),
(218, '2018-01-01', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(219, '2018-01-02', '08:16:00', '17:33:00', 6, 0, 0.97, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 7.73, 16, 0, 0, NULL),
(220, '2018-01-03', '08:40:00', '18:53:00', 6, 0, 0.92, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0.38, 0, 0, 7.33, 40, 0, 0, NULL),
(221, '2018-01-04', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(222, '2018-01-05', '08:16:00', '17:33:00', 6, 0, 0.97, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 7.73, 16, 0, 0, NULL),
(223, '2018-01-06', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(224, '2018-01-07', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(225, '2018-01-08', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(226, '2018-01-09', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(227, '2018-01-10', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(228, '2018-01-11', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(229, '2018-01-12', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(230, '2018-01-13', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(231, '2018-01-14', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(232, '2018-01-15', '07:48:00', '18:07:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(233, '2018-01-16', '08:15:00', '18:11:00', 6, 0, 0.97, '2018-07-05 14:31:27', '2018-07-05 14:31:43', 0, 1, 2, 0, 0, 0, 7.75, 15, 0, 0, NULL),
(234, '2018-01-17', '07:56:00', '17:34:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(235, '2018-01-18', '08:04:00', '18:35:00', 6, 0, 0.99, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.08, 0, 0, 7.93, 4, 0, 0, NULL),
(236, '2018-01-19', '08:09:00', '15:08:00', 6, 0, 0.69, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 5.48, 9, 142, 0, NULL),
(237, '2018-01-20', '10:05:00', '11:43:00', 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.63, 0, 0, 0, 0, 0, 0, NULL),
(238, '2018-01-21', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(239, '2018-01-22', '08:11:00', '18:42:00', 6, 0, 0.98, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.2, 0, 0, 7.82, 11, 0, 0, NULL),
(240, '2018-01-23', '07:55:00', '18:43:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.22, 0, 0, 8, 0, 0, 0, NULL),
(241, '2018-01-24', '07:55:00', '19:01:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.52, 0, 0, 8, 0, 0, 0, NULL),
(242, '2018-01-25', '07:57:00', '18:11:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(243, '2018-01-26', '08:02:00', '18:09:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 7.97, 2, 0, 0, NULL),
(244, '2018-01-27', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(245, '2018-01-28', NULL, NULL, 6, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(246, '2018-01-29', '07:53:00', '18:50:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.33, 0, 0, 8, 0, 0, 0, NULL),
(247, '2018-01-30', '07:55:00', '18:51:00', 6, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(248, '2018-01-31', '08:30:00', '17:37:00', 6, 0, 0.94, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 7.5, 30, 0, 0, NULL),
(249, '2018-01-01', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(250, '2018-01-02', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(251, '2018-01-03', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(252, '2018-01-04', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(253, '2018-01-05', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(254, '2018-01-06', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(255, '2018-01-07', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(256, '2018-01-08', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(257, '2018-01-09', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(258, '2018-01-10', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(259, '2018-01-11', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(260, '2018-01-12', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(261, '2018-01-13', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(262, '2018-01-14', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(263, '2018-01-15', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(264, '2018-01-16', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(265, '2018-01-17', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(266, '2018-01-18', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(267, '2018-01-19', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(268, '2018-01-20', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(269, '2018-01-21', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(270, '2018-01-22', '08:35:00', '18:55:00', 7, 0, 0.93, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.42, 0, 0, 7.42, 35, 0, 0, NULL),
(271, '2018-01-23', '07:55:00', '18:34:00', 7, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0.07, 0, 0, 8, 0, 0, 0, NULL),
(272, '2018-01-24', '08:04:00', '10:48:00', 7, 0, 0.34, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 2.73, 4, 312, 0, NULL),
(273, '2018-01-25', '07:53:00', '19:35:00', 7, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 1.08, 0, 0, 8, 0, 0, 0, NULL),
(274, '2018-01-26', '08:19:00', '18:05:00', 7, 0, 0.96, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 7.68, 19, 0, 0, NULL),
(275, '2018-01-27', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:44', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(276, '2018-01-28', '15:27:00', NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(277, '2018-01-29', '07:54:00', '18:30:00', 7, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(278, '2018-01-30', '08:00:00', '11:20:00', 7, 0, 0.42, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 3.33, 0, 280, 0, NULL),
(279, '2018-01-31', NULL, NULL, 7, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(280, '2018-01-01', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(281, '2018-01-02', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(282, '2018-01-03', '08:00:00', '12:33:00', 8, 0, 0.44, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(283, '2018-01-04', '07:56:00', '18:36:00', 8, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(284, '2018-01-05', '07:46:00', '18:43:00', 8, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.22, 0, 0, 8, 0, 0, 0, NULL),
(285, '2018-01-06', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(286, '2018-01-07', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(287, '2018-01-08', '07:46:00', '18:36:00', 8, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(288, '2018-01-09', '07:50:00', '18:59:00', 8, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.48, 0, 0, 8, 0, 0, 0, NULL),
(289, '2018-01-10', '07:48:00', '19:15:00', 8, 0, 1, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.75, 0, 0, 8, 0, 0, 0, NULL),
(290, '2018-01-11', '07:53:00', '16:43:00', 8, 0, 0.9, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 7.22, 0, 47, 0, NULL),
(291, '2018-01-12', '07:45:00', '15:46:00', 8, 0, 0.78, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 6.27, 0, 104, 0, NULL),
(292, '2018-01-13', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(293, '2018-01-14', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(294, '2018-01-15', '08:20:00', '18:26:00', 8, 0, 0.96, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 7.67, 20, 0, 0, NULL),
(295, '2018-01-16', '07:46:00', '14:50:00', 8, 0, 0.67, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 5.33, 0, 160, 0, NULL),
(296, '2018-01-17', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(297, '2018-01-18', '09:47:00', '18:55:00', 8, 0, 0.78, '2018-07-05 14:31:27', '2018-07-05 14:31:45', 0, 1, 2, 0.42, 0, 0, 6.22, 107, 0, 0, NULL),
(298, '2018-01-19', '07:47:00', '19:02:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0.53, 0, 0, 8, 0, 0, 0, NULL),
(299, '2018-01-20', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(300, '2018-01-21', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(301, '2018-01-22', '07:54:00', '18:31:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0.02, 0, 0, 8, 0, 0, 0, NULL),
(302, '2018-01-23', '07:46:00', '15:50:00', 8, 0, 0.79, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 6.33, 0, 100, 0, NULL),
(303, '2018-01-24', '07:43:00', '18:19:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(304, '2018-01-25', '07:42:00', '18:48:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0.3, 0, 0, 8, 0, 0, 0, NULL),
(305, '2018-01-26', '07:51:00', '18:50:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0.33, 0, 0, 8, 0, 0, 0, NULL),
(306, '2018-01-27', NULL, NULL, 8, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(307, '2018-01-28', '14:38:00', '19:37:00', 8, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:45', 0, 1, 2, 3.98, 0, 0, 0, 0, 0, 0, NULL),
(308, '2018-01-29', '07:50:00', '18:51:00', 8, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(309, '2018-01-30', '08:47:00', '18:52:00', 8, 0, 0.9, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.37, 0, 0, 7.22, 47, 0, 0, NULL),
(310, '2018-01-31', '08:52:00', '18:51:00', 8, 0, 0.89, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.35, 0, 0, 7.13, 52, 0, 0, NULL),
(311, '2018-01-01', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(312, '2018-01-02', '07:52:00', '17:31:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(313, '2018-01-03', '07:48:00', '18:30:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(314, '2018-01-04', '08:12:00', '18:16:00', 9, 0, 0.98, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 7.8, 12, 0, 0, NULL),
(315, '2018-01-05', '07:47:00', '19:39:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 1.15, 0, 0, 8, 0, 0, 0, NULL),
(316, '2018-01-06', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(317, '2018-01-07', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(318, '2018-01-08', '07:52:00', '18:23:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(319, '2018-01-09', '08:08:00', '18:19:00', 9, 0, 0.98, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 7.87, 8, 0, 0, NULL),
(320, '2018-01-10', '07:52:00', '12:13:00', 9, 0, 0.44, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(321, '2018-01-11', '07:51:00', '15:39:00', 9, 0, 0.77, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 6.15, 0, 111, 0, NULL),
(322, '2018-01-12', '07:51:00', '15:47:00', 9, 0, 0.79, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 6.28, 0, 103, 0, NULL),
(323, '2018-01-13', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(324, '2018-01-14', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(325, '2018-01-15', '08:04:00', '19:32:00', 9, 0, 0.99, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 1.03, 0, 0, 7.93, 4, 0, 0, NULL),
(326, '2018-01-16', '07:53:00', '14:16:00', 9, 0, 0.6, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 4.77, 0, 194, 0, NULL),
(327, '2018-01-17', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(328, '2018-01-18', '09:57:00', '19:41:00', 9, 0, 0.76, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 1.18, 0, 0, 6.05, 117, 0, 0, NULL),
(329, '2018-01-19', '07:54:00', '18:51:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.35, 0, 0, 8, 0, 0, 0, NULL),
(330, '2018-01-20', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(331, '2018-01-21', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(332, '2018-01-22', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(333, '2018-01-23', '07:49:00', '18:31:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.02, 0, 0, 8, 0, 0, 0, NULL),
(334, '2018-01-24', '07:57:00', '19:07:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.62, 0, 0, 8, 0, 0, 0, NULL),
(335, '2018-01-25', '07:51:00', '18:36:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.1, 0, 0, 8, 0, 0, 0, NULL),
(336, '2018-01-26', '07:49:00', '18:46:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0.27, 0, 0, 8, 0, 0, 0, NULL),
(337, '2018-01-27', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(338, '2018-01-28', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(339, '2018-01-29', '07:57:00', '17:32:00', 9, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(340, '2018-01-30', '08:09:00', '18:06:00', 9, 0, 0.98, '2018-07-05 14:31:28', '2018-07-05 14:31:46', 0, 1, 2, 0, 0, 0, 7.85, 9, 0, 0, NULL),
(341, '2018-01-31', NULL, NULL, 9, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(342, '2018-01-01', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(343, '2018-01-02', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(344, '2018-01-03', '07:54:00', '18:19:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(345, '2018-01-04', '07:31:00', '18:10:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(346, '2018-01-05', '07:56:00', '18:27:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(347, '2018-01-06', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(348, '2018-01-07', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(349, '2018-01-08', '07:15:00', '20:07:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.62, 0, 0, 8, 0, 0, 0, NULL),
(350, '2018-01-09', '07:55:00', '19:26:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0.93, 0, 0, 8, 0, 0, 0, NULL),
(351, '2018-01-10', '07:57:00', '19:43:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.22, 0, 0, 8, 0, 0, 0, NULL),
(352, '2018-01-11', '07:14:00', '19:42:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.2, 0, 0, 8, 0, 0, 0, NULL),
(353, '2018-01-12', '07:51:00', '15:49:00', 10, 0, 0.79, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 6.32, 0, 101, 0, NULL),
(354, '2018-01-13', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(355, '2018-01-14', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(356, '2018-01-15', '07:21:00', '19:28:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0.97, 0, 0, 8, 0, 0, 0, NULL),
(357, '2018-01-16', '07:19:00', '15:38:00', 10, 0, 0.77, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 6.13, 0, 112, 0, NULL),
(358, '2018-01-17', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(359, '2018-01-18', '09:25:00', '19:33:00', 10, 0, 0.82, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.05, 0, 0, 6.58, 85, 0, 0, NULL),
(360, '2018-01-19', '07:31:00', '17:47:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(361, '2018-01-20', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(362, '2018-01-21', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(363, '2018-01-22', '07:48:00', '18:14:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(364, '2018-01-23', '07:17:00', '18:24:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(365, '2018-01-24', '12:34:00', '18:20:00', 10, 0, 0.56, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 4.5, 210, 0, 0, NULL),
(366, '2018-01-25', '07:25:00', '19:58:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.47, 0, 0, 8, 0, 0, 0, NULL),
(367, '2018-01-26', '08:21:00', '18:33:00', 10, 0, 0.96, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0.05, 0, 0, 7.65, 21, 0, 0, NULL),
(368, '2018-01-27', NULL, NULL, 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(369, '2018-01-28', '15:03:00', '17:28:00', 10, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.42, 0, 0, 0, 0, 0, 0, NULL),
(370, '2018-01-29', '08:06:00', '19:09:00', 10, 0, 0.99, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0.65, 0, 0, 7.9, 6, 0, 0, NULL),
(371, '2018-01-30', '08:00:00', '19:52:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.37, 0, 0, 8, 0, 0, 0, NULL),
(372, '2018-01-31', '06:59:00', '20:22:00', 10, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 1.87, 0, 0, 8, 0, 0, 0, NULL),
(373, '2018-01-01', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(374, '2018-01-02', '06:59:00', '17:57:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(375, '2018-01-03', '07:41:00', '18:16:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(376, '2018-01-04', '07:19:00', '18:20:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(377, '2018-01-05', '07:09:00', '17:42:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(378, '2018-01-06', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:47', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL);
INSERT INTO `time_on_excel_data` (`id`, `date`, `check_in`, `check_out`, `employee_id`, `day_off`, `working_time`, `created_at`, `updated_at`, `is_duplicate`, `is_accepted`, `time_on_excel_file_id`, `tc1`, `tc2`, `tc3`, `hour`, `late`, `leave_early`, `is_imported`, `time_on_id`) VALUES
(379, '2018-01-07', '18:57:00', '22:39:00', 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 2.7, 0, 0, 0, 0, 0, 0, NULL),
(380, '2018-01-08', '07:07:00', '19:37:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 1.12, 0, 0, 8, 0, 0, 0, NULL),
(381, '2018-01-09', '07:29:00', '18:08:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(382, '2018-01-10', '07:35:00', '22:50:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 3, 0, 0, 8, 0, 0, 0, NULL),
(383, '2018-01-11', '07:44:00', '23:01:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 3, 0, 0, 8, 0, 0, 0, NULL),
(384, '2018-01-12', '07:51:00', '15:38:00', 11, 0, 0.77, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 6.13, 0, 112, 0, NULL),
(385, '2018-01-13', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(386, '2018-01-14', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(387, '2018-01-15', '07:58:00', '18:23:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(388, '2018-01-16', '07:43:00', '12:52:00', 11, 0, 0.44, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(389, '2018-01-17', '17:36:00', NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 486, 0, 0, NULL),
(390, '2018-01-18', '09:56:00', '19:33:00', 11, 0, 0.76, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 1.05, 0, 0, 6.07, 116, 0, 0, NULL),
(391, '2018-01-19', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(392, '2018-01-20', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(393, '2018-01-21', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(394, '2018-01-22', '07:54:00', '18:55:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0.42, 0, 0, 8, 0, 0, 0, NULL),
(395, '2018-01-23', '07:38:00', '18:16:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(396, '2018-01-24', '07:29:00', '18:05:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(397, '2018-01-25', '07:41:00', '22:44:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 3, 0, 0, 8, 0, 0, 0, NULL),
(398, '2018-01-26', '07:55:00', '17:34:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(399, '2018-01-27', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(400, '2018-01-28', NULL, NULL, 11, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(401, '2018-01-29', '07:39:00', '18:22:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(402, '2018-01-30', '07:24:00', '18:25:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(403, '2018-01-31', '07:55:00', '18:41:00', 11, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0.18, 0, 0, 8, 0, 0, 0, NULL),
(404, '2018-01-01', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(405, '2018-01-02', '07:44:00', '17:42:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(406, '2018-01-03', '07:36:00', '17:45:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(407, '2018-01-04', '07:50:00', '17:47:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(408, '2018-01-05', '07:33:00', '12:20:00', 12, 0, 0.44, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(409, '2018-01-06', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(410, '2018-01-07', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(411, '2018-01-08', '07:45:00', '17:45:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(412, '2018-01-09', '07:45:00', '17:38:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(413, '2018-01-10', '07:42:00', '17:43:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(414, '2018-01-11', '07:42:00', '17:51:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(415, '2018-01-12', '07:33:00', '15:27:00', 12, 0, 0.74, '2018-07-05 14:31:28', '2018-07-05 14:31:48', 0, 1, 2, 0, 0, 0, 5.95, 0, 123, 0, NULL),
(416, '2018-01-13', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(417, '2018-01-14', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(418, '2018-01-15', '07:39:00', '17:42:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(419, '2018-01-16', '07:47:00', '11:46:00', 12, 0, 0.44, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(420, '2018-01-17', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(421, '2018-01-18', '11:16:00', '17:42:00', 12, 0, 0.59, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 4.73, 196, 0, 0, NULL),
(422, '2018-01-19', '07:31:00', '17:54:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(423, '2018-01-20', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(424, '2018-01-21', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(425, '2018-01-22', '07:39:00', '11:46:00', 12, 0, 0.44, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 3.5, 0, 270, 0, NULL),
(426, '2018-01-23', '07:43:00', '17:32:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(427, '2018-01-24', '07:44:00', '17:42:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(428, '2018-01-25', '07:39:00', '17:49:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(429, '2018-01-26', '07:42:00', '17:51:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(430, '2018-01-27', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(431, '2018-01-28', NULL, NULL, 12, 0, 0, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, NULL),
(432, '2018-01-29', '07:34:00', '17:45:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(433, '2018-01-30', '07:37:00', '17:48:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL),
(434, '2018-01-31', '07:35:00', '17:45:00', 12, 0, 1, '2018-07-05 14:31:28', '2018-07-05 14:31:49', 0, 1, 2, 0, 0, 0, 8, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_on_excel_file`
--

CREATE TABLE `time_on_excel_file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on_excel_file`
--

INSERT INTO `time_on_excel_file` (`id`, `name`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Chấm công mẫu.xls_5b3d3074a691a.xlsx', 1, 1, '2018-07-04 20:39:16', '2018-07-04 20:39:21'),
(2, 'Chấm công mẫu.xls_5b3e2bbbac430.xlsx', 9, 1, '2018-07-05 14:31:23', '2018-07-05 14:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `time_on_month`
--

CREATE TABLE `time_on_month` (
  `id` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `day_off` float NOT NULL DEFAULT '0',
  `day_off_with_pay_permit` float NOT NULL DEFAULT '0',
  `day_off_without_pay` float NOT NULL DEFAULT '0',
  `day_off_with_pay_ot` float NOT NULL DEFAULT '0',
  `day_off_remain_in_month_permit` float NOT NULL DEFAULT '0',
  `day_off_remain_in_month_ot` float NOT NULL DEFAULT '0',
  `day_off_accumulated_permit` float NOT NULL DEFAULT '0',
  `day_off_accumulated_ot` float NOT NULL DEFAULT '0',
  `day_off_subtract_salary` float NOT NULL DEFAULT '0',
  `day_off_remain_permit` float NOT NULL DEFAULT '0',
  `day_off_remain_ot` float NOT NULL DEFAULT '0',
  `absent_permit` int(11) NOT NULL DEFAULT '0',
  `absent_without_permit` int(11) NOT NULL DEFAULT '0',
  `diligence` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `work_day` float NOT NULL DEFAULT '0',
  `actual_work_day` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on_month`
--

INSERT INTO `time_on_month` (`id`, `month`, `year`, `day_off`, `day_off_with_pay_permit`, `day_off_without_pay`, `day_off_with_pay_ot`, `day_off_remain_in_month_permit`, `day_off_remain_in_month_ot`, `day_off_accumulated_permit`, `day_off_accumulated_ot`, `day_off_subtract_salary`, `day_off_remain_permit`, `day_off_remain_ot`, `absent_permit`, `absent_without_permit`, `diligence`, `created_at`, `updated_at`, `employee_id`, `is_approved`, `work_day`, `actual_work_day`) VALUES
(183, 1, 2018, 5.2, 0, 0, 0, -5.2, 0, 0, 0, -5.2, 0, 0, 6, 9, 10, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 6, 0, 0, 0),
(184, 1, 2018, 14.1, 0, 0, 0, -14.1, 0, 0, 0, -14.1, 0, 0, 1, 19, 19, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 7, 0, 0, 0),
(185, 1, 2018, 2.5, 0, 0, 0, -2.5, 0, 0, 0, -2.5, 0, 0, 0, 11, 11, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 8, 0, 0, 0),
(186, 1, 2018, 3.3, 0, 0, 0, -3.3, 0, 0, 0, -3.3, 0, 0, 4, 8, 8, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 9, 0, 0, 0),
(187, 1, 2018, 3.2, 0, 0, 0, -3.2, 0, 0, 0, -3.2, 0, 0, 4, 7, 7, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 10, 0, 0, 0),
(188, 1, 2018, 1.2, 0, 0, 0, -1.2, 0, 0, 0, -1.2, 0, 0, 0, 4, 4, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 11, 0, 0, 0),
(189, 1, 2018, 1.4, 0, 0, 0, -1.4, 0, 0, 0, -1.4, 0, 0, 0, 6, 6, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 12, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_on_official_holiday`
--

CREATE TABLE `time_on_official_holiday` (
  `time_on_id` int(11) NOT NULL,
  `official_holiday_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on_official_holiday`
--

INSERT INTO `time_on_official_holiday` (`time_on_id`, `official_holiday_id`, `created_at`, `updated_at`, `time`) VALUES
(1, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(32, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(63, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(94, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(125, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(156, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480),
(187, 1, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 480);

-- --------------------------------------------------------

--
-- Table structure for table `time_on_over_time`
--

CREATE TABLE `time_on_over_time` (
  `time_on_id` int(11) NOT NULL,
  `over_time_detail_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_on_time_off`
--

CREATE TABLE `time_on_time_off` (
  `time_on_id` int(11) NOT NULL,
  `time_off_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_on_time_off`
--

INSERT INTO `time_on_time_off` (`time_on_id`, `time_off_id`, `created_at`, `updated_at`, `time`) VALUES
(3, 132, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 90),
(126, 128, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 570),
(127, 60, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 150),
(142, 127, '2018-07-13 14:54:34', '2018-07-13 14:54:34', 90);

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` float DEFAULT NULL,
  `presence` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`id`, `course_id`, `user_id`, `score`, `presence`, `deleted_at`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 0.5, 0, NULL, '2018-07-05 19:14:46', '2018-07-05 19:15:19'),
(6, 1, 4, NULL, 0, NULL, '2018-07-05 19:14:46', '2018-07-05 19:14:46'),
(7, 1, 10, 6.5, 0, NULL, '2018-07-05 19:14:46', '2018-07-05 19:15:19'),
(8, 1, 8, 7.5, 0, NULL, '2018-07-05 19:14:46', '2018-07-05 19:15:39'),
(12, 2, 1, 10, 0, NULL, '2018-07-13 16:07:32', '2018-07-13 16:07:56'),
(13, 2, 4, 10, 0, NULL, '2018-07-13 16:07:32', '2018-07-13 16:07:56'),
(14, 2, 10, 10, 0, NULL, '2018-07-13 16:07:32', '2018-07-13 16:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `screen_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@ominext.com', '$2y$10$z4vwZOb5JdJa5t74IA5q.epYhgHe0qYxSHHY9kjjnH/.qRO/.02y6', NULL, '2018-07-03 01:37:39', '2018-07-03 01:37:41'),
(4, 'Đặng Hữu Chính', 'chinhdh@ominext.com', '$2y$10$8HlQnGHp.P9j1lkTJpTrYuQBlCOjIAjC3pkOYpvDNl5soYFbK.tdK', NULL, '2018-07-04 09:49:07', '2018-07-05 15:50:40'),
(5, 'Đào Hồng Hạnh', 'hanhdh@ominext.com', '$2y$10$zw1oGHMLfXfz0f6J4MCoYeVV5lRpNiLTcdv5n5m3175yl/AJopwtS', NULL, '2018-07-04 20:31:36', '2018-07-04 20:31:36'),
(6, 'Nguyễn Chí Thanh', 'thanhnc@ominext.com', '$2y$10$enmrsUEi22ZnSEEWkoNVJ.4zzt09rbpgR/jf1MBp/b6mqtuh/m1U6', NULL, '2018-07-04 20:33:03', '2018-07-04 20:33:03'),
(7, 'Nguyễn Văn Phượng', 'phuongnv@ominext.com', '$2y$10$z4vwZOb5JdJa5t74IA5q.epYhgHe0qYxSHHY9kjjnH/.qRO/.02y6', NULL, '2018-07-04 20:34:02', '2018-07-04 20:34:02'),
(8, 'Nguyễn Huy An', 'annh@ominext.com', '$2y$10$lijVIYSMmjwl0d4s.w9xO.LQuA7/MOyJW0oSo/pT/v4jqT9RmMhD2', NULL, '2018-07-04 20:34:55', '2018-07-04 20:34:55'),
(9, 'Nguyễn Minh Trang', 'trangnm@ominext.com', '$2y$10$k6Rz60m8IyOazRa9Y8ynmONW8MUtfMEi/..6eaQJwWR225zxeAtcW', NULL, '2018-07-04 20:35:54', '2018-07-04 20:35:54'),
(10, 'Lương Anh Sơn', 'sonla@ominext.com', '$2y$10$9RaCa9NQ8zWPNFgjbs4MvO4a2iChUsWqMTX0w8oFAsnQsWhCezi1C', NULL, '2018-07-04 20:36:52', '2018-07-17 15:29:23'),
(11, 'Nguyễn Khánh Linh', 'linhnk@ominext.com', '$2y$10$MR9rttCELHw4n9sK8Yy70uJwiNQJpB1HY1owvICAYHb6z0yioWdbu', NULL, '2018-07-04 20:38:06', '2018-07-04 20:38:06'),
(12, 'Nguyễn Quang Vinh', 'vinhnq@ominext.com', '$2y$10$P1pCLDPK1oL2F.b9EQDz5eyqscGOZiKTMgPklq/SjZFl/Xcnt5YzW', NULL, '2018-07-04 20:38:59', '2018-07-04 20:38:59'),
(13, 'Đào Minh Phượng', 'phuongdaominh@ominext.com', '$2y$10$bW.EBvvbuMGq5jNgHiJG2eMT1Dfxg13xvS7taNYNtD1GgToNPYPeO', NULL, '2018-07-05 09:02:38', '2018-07-05 09:02:38'),
(14, 'Dương Thị Bích Ngọc', 'ngocdtb@ominext.com', '$2y$10$Jjzs2vCiTzVkLDZk0QOwjuSHNzU2fUanzguaBQJrYKPK.ms4Aat2m', NULL, '2018-07-05 09:03:56', '2018-07-05 09:03:56'),
(15, 'dasdhjkljasdlkasd', 'sdasdass@ominext.com', '$2y$10$t6esVWxmVUJSYrtKo/jpn.I5IjxdLWD1TQJ5Oom4en1QD7vy0JMSu', NULL, '2018-07-05 18:51:56', '2018-07-05 18:51:56'),
(16, 'Đỗ Thị Mai', 'maidt@ominext.com', '$2y$10$k5FRTp6v./gH7FWNoXsVU.E7o6GegmrKFUA5rovm0Zg.yK0vVwMFG', NULL, '2018-07-05 18:51:56', '2018-07-05 18:51:56'),
(17, 'Đỗ Thị Thùy Vân', 'vandtt@ominext.com', '$2y$10$ayea1EsYX7Kuy896oRm/QuR19Waw8kNMn1rdKuk7.H0w.fSw7dwta', NULL, '2018-07-10 11:38:17', '2018-07-10 11:38:17'),
(18, 'Vũ Văn Tình', 'tinhvv@ominext.com', '$2y$10$z4vwZOb5JdJa5t74IA5q.epYhgHe0qYxSHHY9kjjnH/.qRO/.02y6', NULL, '2018-07-17 18:19:49', '2018-07-17 18:19:49'),
(19, 'Nguyễn Phi Hùng', 'hungnp@ominext.com', '$2y$10$z4vwZOb5JdJa5t74IA5q.epYhgHe0qYxSHHY9kjjnH/.qRO/.02y6', NULL, '2018-07-17 18:19:49', '2018-07-17 18:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `working_status`
--

CREATE TABLE `working_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `working_status`
--

INSERT INTO `working_status` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Đang làm việc', 'Đang làm việc', '2018-07-03 01:39:43', '2018-07-05 17:49:28', NULL),
(2, 'Đã nghỉ việc', 'Đã nghỉ việc', '2018-07-03 01:39:55', '2018-07-05 17:49:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_score_excel_files`
--
ALTER TABLE `course_score_excel_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_fcm_device_token_unique` (`fcm_device_token`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_attach_files`
--
ALTER TABLE `employees_attach_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_job_status_history`
--
ALTER TABLE `employees_job_status_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees_update_history`
--
ALTER TABLE `employees_update_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_excel_data`
--
ALTER TABLE `employee_excel_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_excel_department`
--
ALTER TABLE `employee_excel_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_excel_file`
--
ALTER TABLE `employee_excel_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_excel_job_status`
--
ALTER TABLE `employee_excel_job_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_excel_position`
--
ALTER TABLE `employee_excel_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_status`
--
ALTER TABLE `job_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `late_reasons`
--
ALTER TABLE `late_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `official_holidays`
--
ALTER TABLE `official_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_categories`
--
ALTER TABLE `other_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `over_times`
--
ALTER TABLE `over_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `over_time_details`
--
ALTER TABLE `over_time_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_forgets`
--
ALTER TABLE `password_forgets`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `verified_code` (`verified_code`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `screens_name_unique` (`name`);

--
-- Indexes for table `screen_categories`
--
ALTER TABLE `screen_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `screen_categories_name_unique` (`name`);

--
-- Indexes for table `screen_role`
--
ALTER TABLE `screen_role`
  ADD PRIMARY KEY (`screen_id`,`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_user`
--
ALTER TABLE `session_user`
  ADD PRIMARY KEY (`session_id`,`user_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialized_skills`
--
ALTER TABLE `specialized_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_off`
--
ALTER TABLE `time_off`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_off_excel_files`
--
ALTER TABLE `time_off_excel_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_on`
--
ALTER TABLE `time_on`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_on_accumulated_year`
--
ALTER TABLE `time_on_accumulated_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_on_excel_data`
--
ALTER TABLE `time_on_excel_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_on_excel_file`
--
ALTER TABLE `time_on_excel_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_on_month`
--
ALTER TABLE `time_on_month`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `working_status`
--
ALTER TABLE `working_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_score_excel_files`
--
ALTER TABLE `course_score_excel_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employees_attach_files`
--
ALTER TABLE `employees_attach_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees_job_status_history`
--
ALTER TABLE `employees_job_status_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employees_update_history`
--
ALTER TABLE `employees_update_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employee_excel_data`
--
ALTER TABLE `employee_excel_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_excel_department`
--
ALTER TABLE `employee_excel_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_excel_file`
--
ALTER TABLE `employee_excel_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_excel_job_status`
--
ALTER TABLE `employee_excel_job_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_excel_position`
--
ALTER TABLE `employee_excel_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_status`
--
ALTER TABLE `job_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `late_reasons`
--
ALTER TABLE `late_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `official_holidays`
--
ALTER TABLE `official_holidays`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `other_categories`
--
ALTER TABLE `other_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `over_times`
--
ALTER TABLE `over_times`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `over_time_details`
--
ALTER TABLE `over_time_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `screen_categories`
--
ALTER TABLE `screen_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `specialized_skills`
--
ALTER TABLE `specialized_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `time_off`
--
ALTER TABLE `time_off`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `time_off_excel_files`
--
ALTER TABLE `time_off_excel_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_on`
--
ALTER TABLE `time_on`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `time_on_accumulated_year`
--
ALTER TABLE `time_on_accumulated_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_on_excel_data`
--
ALTER TABLE `time_on_excel_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT for table `time_on_excel_file`
--
ALTER TABLE `time_on_excel_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `time_on_month`
--
ALTER TABLE `time_on_month`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `working_status`
--
ALTER TABLE `working_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
