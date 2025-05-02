-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th5 02, 2025 lúc 10:40 AM
-- Phiên bản máy phục vụ: 10.3.34-MariaDB
-- Phiên bản PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `steam_wonder`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_service`
--

CREATE TABLE `tb_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `education_program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `education_age_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_attendance` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Tính theo điểm danh hoặc Không theo điểm danh',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Dịch vụ mặc định cho lớp học',
  `service_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại dịch vụ ví dụ: Thu theo chu kỳ (năm/tháng), hoặc chỉ thu 1 lần...',
  `json_params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`json_params`)),
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `iorder` int(11) NOT NULL DEFAULT 0,
  `admin_created_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_updated_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tb_service`
--

INSERT INTO `tb_service` (`id`, `name`, `description`, `area_id`, `service_category_id`, `education_program_id`, `education_age_id`, `is_attendance`, `is_default`, `service_type`, `json_params`, `status`, `iorder`, `admin_created_id`, `admin_updated_id`, `created_at`, `updated_at`) VALUES
(1, 'Phí ghi danh', NULL, 1, 6, NULL, NULL, 0, 0, 'tu_huy_sau_khi_su_dung', NULL, 'active', 1, 1, 1, '2025-04-29 03:38:27', '2025-04-29 06:10:17'),
(2, 'Phí dịch vụ bán trú', NULL, 1, 2, NULL, NULL, 0, 0, 'binh_thuong', NULL, 'active', 2, 1, 1, '2025-04-29 03:40:31', '2025-04-29 07:45:07'),
(3, 'Học phí (Hệ TATC)', NULL, 1, 1, 1, NULL, 0, 0, 'binh_thuong', NULL, 'active', 0, 1, 1, '2025-04-29 07:44:04', '2025-04-29 08:02:14'),
(4, 'Học phí (Song ngữ)', NULL, 1, 1, 2, NULL, 0, 0, 'binh_thuong', NULL, 'active', 0, 1, NULL, '2025-04-29 07:46:06', '2025-04-29 07:46:06'),
(5, 'Tiền ăn', NULL, 1, 2, NULL, NULL, 1, 0, 'binh_thuong', NULL, 'active', 3, 1, 1, '2025-04-29 07:49:47', '2025-05-02 03:14:30'),
(6, 'Phí phát triển trường', NULL, 1, 4, NULL, NULL, 0, 0, 'dau_nam_hoc', NULL, 'active', 0, 1, NULL, '2025-04-29 07:56:02', '2025-04-29 07:56:02'),
(7, 'Phí học phẩm (Hệ TATC)', NULL, 1, 4, 1, NULL, 0, 0, 'dau_nam_hoc', NULL, 'active', 0, 1, NULL, '2025-04-29 08:00:23', '2025-04-29 08:00:23'),
(8, 'Phí học phẩm (Hệ Song ngữ)', NULL, 1, 4, 2, NULL, 0, 0, 'dau_nam_hoc', NULL, 'active', 0, 1, NULL, '2025-04-29 08:01:55', '2025-04-29 08:01:55'),
(9, 'Phí dã ngoại', NULL, 1, 3, NULL, NULL, 0, 0, 'dau_nam_hoc', NULL, 'active', 0, 1, NULL, '2025-04-29 08:04:11', '2025-04-29 08:04:11'),
(10, 'Phí đồng phục', NULL, 1, 7, NULL, NULL, 0, 0, 'tu_huy_sau_khi_su_dung', NULL, 'active', 0, 1, NULL, '2025-04-29 08:05:51', '2025-04-29 08:05:51'),
(11, 'Phí trông muộn', NULL, 1, 6, NULL, NULL, 1, 0, 'binh_thuong', NULL, 'active', 0, 1, NULL, '2025-04-29 08:08:47', '2025-04-29 08:08:47');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tb_service`
--
ALTER TABLE `tb_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_service_area_id_foreign` (`area_id`),
  ADD KEY `tb_service_service_category_id_foreign` (`service_category_id`),
  ADD KEY `tb_service_education_program_id_foreign` (`education_program_id`),
  ADD KEY `tb_service_education_age_id_foreign` (`education_age_id`),
  ADD KEY `tb_service_admin_created_id_foreign` (`admin_created_id`),
  ADD KEY `tb_service_admin_updated_id_foreign` (`admin_updated_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tb_service`
--
ALTER TABLE `tb_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tb_service`
--
ALTER TABLE `tb_service`
  ADD CONSTRAINT `tb_service_admin_created_id_foreign` FOREIGN KEY (`admin_created_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `tb_service_admin_updated_id_foreign` FOREIGN KEY (`admin_updated_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `tb_service_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_service_education_age_id_foreign` FOREIGN KEY (`education_age_id`) REFERENCES `tb_education_ages` (`id`),
  ADD CONSTRAINT `tb_service_education_program_id_foreign` FOREIGN KEY (`education_program_id`) REFERENCES `tb_education_programs` (`id`),
  ADD CONSTRAINT `tb_service_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `tb_service_category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
