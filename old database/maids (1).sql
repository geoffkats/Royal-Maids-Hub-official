-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 09, 2025 at 09:39 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u622340404_royalmaids`
--

-- --------------------------------------------------------

--
-- Table structure for table `maids`
--

CREATE TABLE `maids` (
  `maid_id` int(11) NOT NULL,
  `maid_code` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `passport_number` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('available','in-training','booked','deployed') DEFAULT 'available',
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages`)),
  `experience_years` int(11) DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `education_level` varchar(50) DEFAULT NULL,
  `health_status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`health_status`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `emergency_contact` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emergency_contact`)),
  `preferred_work_location` text DEFAULT NULL,
  `salary_expectation` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `additional_notes` text DEFAULT NULL,
  `trainer_id` int(10) UNSIGNED DEFAULT NULL,
  `nin_number` varchar(50) NOT NULL DEFAULT '',
  `lc1_chairperson` text DEFAULT NULL,
  `mother_name_phone` varchar(255) NOT NULL DEFAULT '',
  `father_name_phone` varchar(255) NOT NULL DEFAULT '',
  `tribe` varchar(100) NOT NULL DEFAULT '',
  `village` varchar(100) NOT NULL DEFAULT '',
  `district` varchar(100) NOT NULL DEFAULT '',
  `mobile_number_2` varchar(20) DEFAULT NULL,
  `marital_status` enum('single','married') NOT NULL DEFAULT 'single',
  `number_of_children` int(11) NOT NULL DEFAULT 0,
  `mother_tongue` varchar(100) NOT NULL DEFAULT '',
  `english_proficiency` int(11) NOT NULL DEFAULT 1,
  `role` enum('housekeeper','house_manager','nanny','chef','elderly_caretaker','nakawere_caretaker') NOT NULL DEFAULT 'housekeeper',
  `previous_work` text DEFAULT NULL,
  `medical_status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medical_status`)),
  `work_status` enum('Brokerage','long-term','part-time','full-time') DEFAULT NULL,
  `secondary_status` enum('booked','available','deployed','on-leave','absconded','terminated') DEFAULT NULL,
  `hepatitis_b_result` varchar(20) DEFAULT NULL,
  `hepatitis_b_date` date DEFAULT NULL,
  `hiv_result` varchar(20) DEFAULT NULL,
  `hiv_date` date DEFAULT NULL,
  `urine_hcg_result` varchar(20) DEFAULT NULL,
  `urine_hcg_date` date DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `date_of_arrival` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maids`
--

INSERT INTO `maids` (`maid_id`, `maid_code`, `first_name`, `last_name`, `nationality`, `date_of_birth`, `passport_number`, `email`, `phone`, `address`, `profile_image`, `status`, `languages`, `experience_years`, `skills`, `education_level`, `health_status`, `documents`, `emergency_contact`, `preferred_work_location`, `salary_expectation`, `created_at`, `updated_at`, `additional_notes`, `trainer_id`, `nin_number`, `lc1_chairperson`, `mother_name_phone`, `father_name_phone`, `tribe`, `village`, `district`, `mobile_number_2`, `marital_status`, `number_of_children`, `mother_tongue`, `english_proficiency`, `role`, `previous_work`, `medical_status`, `work_status`, `secondary_status`, `hepatitis_b_result`, `hepatitis_b_date`, `hiv_result`, `hiv_date`, `urine_hcg_result`, `urine_hcg_date`, `medical_notes`, `date_of_arrival`) VALUES
(40, 'M005', 'Semmy', 'Adong', 'Uganda, Langi', '2003-06-23', NULL, 'Simmie.adong@rmh.com', '0787596159', NULL, 'uploads/maid_40_1755530471.jpeg', 'deployed', '[\"english\"]', 0, '[\"housekeeping\"]', 'P.7', NULL, NULL, NULL, NULL, NULL, '2025-08-06 11:34:44', '2025-10-01 09:07:03', 'Worked for 2 year as house maid in Namere\r\nBrother: Isaac Udiri\r\nReffered by Ambrose\r\nHas 5 children', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 2, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"\"},\"hiv\":{\"result\":\"negative\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"\"},\"notes\":\"\"}', 'Brokerage', 'deployed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11'),
(41, 'M006', 'Robina', 'Akao', 'Lira, Ugandan', '2002-06-03', NULL, 'Akao.Robina@rmh.com', '0780962044', NULL, 'uploads/maid_41_1755530447.jpeg', '', '[\"english\"]', 0, '[\"laundry\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-15 12:26:50', '2025-10-24 09:41:20', '', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', '', 'terminated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(42, 'M007', 'Pauline', 'Awori Mary ', 'Japadola, Ugandan', '1994-05-15', NULL, 'Awori.Mary@rmh.com', '0777764586', NULL, 'uploads/maid_42_1755530419.jpeg', 'deployed', '[\"english\"]', 0, '[\"housekeeping\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-18 18:16:07', '2025-10-22 08:16:48', 'Name :Awori Mary Pauline\r\nAge:31\r\nNumber of children:2 years 10 and 8years\r\nPlace of origin:Tororo\r\nLanguages :Japadola,english and a little luganda\r\nTel:0777764586\r\nDate of birth 15th/may/1994\r\nNext of kin:0768715539 Brother Godfred Othieno', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'house_manager', '', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04'),
(43, 'M008', 'Harriet', 'Asemo', 'Ugandan', '1996-02-23', NULL, 'Harriet.Asemo@rmh.com', '0773301995', NULL, 'uploads/harriet.jpeg', 'deployed', '[\"english\"]', 0, '[\"housekeeping\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-19 19:06:07', '2025-10-24 09:42:05', '', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', '', 'absconded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(44, 'M009', 'Concy', 'Ajok', 'Acholi, Uganda', '1997-09-04', NULL, 'concy.ajok@rmh.com', '0762797092', NULL, 'uploads/concy.jpeg', '', '[\"english\"]', 0, '[\"laundry\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-19 19:26:37', '2025-10-22 08:16:16', '', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 6, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"\"},\"hiv\":{\"result\":\"negative\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"\"},\"notes\":\"\"}', 'full-time', 'absconded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11'),
(45, 'M010', 'Flavia', 'Ajalo', 'Langi, Uganda', '1998-03-12', NULL, 'flavia.ajalo@rmh.com', '0771005433', NULL, 'uploads/flavia.jpeg', '', '[\"english\"]', 0, '[\"housekeeping\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-20 10:23:32', '2025-10-22 10:13:05', '', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', 'full-time', 'absconded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-01'),
(46, 'M011', 'Innocent', 'Lalam', 'Langi, Uganda', '2005-05-18', NULL, 'innocent.lalam@rmh.com', '0765054837', NULL, 'uploads/innocent.jpeg', '', '[\"english\"]', 0, '[\"housekeeping\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-08-20 10:25:06', '2025-10-10 17:08:48', '', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'housekeeper', '', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', '', 'absconded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(47, 'M012', 'Sandra', 'Ayot', 'Uganda', '2002-09-10', NULL, 'sandra.ayot@rmh.com', '0783204328', NULL, 'uploads/sandraayot1.jpeg', 'deployed', '[\"english\"]', 1, '[\"housekeeping\"]', 'Certificate', NULL, NULL, NULL, NULL, NULL, '2025-08-20 20:12:33', '2025-10-21 12:22:24', '', 0, 'CF02115100EX4K', 'No LC1 Letter.', 'Ayemo Lucy - 0779384919', 'Odong David - 0789159242', 'Acholi', 'Kalamomiya East, Paidwe, Bobi, Tochi, Omoro', 'Gulu', '0783432537', 'single', 0, 'Acholi', 5, 'housekeeper', 'Worked in Nabingo, HouseKeeper and taking care of children, 4 kids; 12yrs, 8 years, 5yrs, 1yr and 8 months\r\nName of former Boss: Praise ', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"2025-08-27\"},\"hiv\":{\"result\":\"negative\",\"date\":\"2025-08-27\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"2025-08-27\"},\"notes\":\"\"}', 'full-time', 'deployed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20'),
(48, 'M013', 'Daphine', 'Asero', 'Uganda', '2003-02-02', NULL, 'asero@rmh.com', '0768566849', NULL, '', 'deployed', '[\"english\"]', 0, '[\"childcare\"]', '', NULL, NULL, NULL, NULL, NULL, '2025-09-26 16:46:00', '2025-10-10 17:13:53', 'NIN: CF03129100XM4A\r\nP-Number: B00607700', 0, '', '', ' - ', ' - ', '', '', '', '', 'single', 0, '', 1, 'nanny', '', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"\"},\"hiv\":{\"result\":\"negative\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"\"},\"notes\":\"\"}', '', 'deployed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(51, 'M015', 'Mary', 'Awori', 'Uganda', '2003-06-08', NULL, NULL, '0760907910', NULL, 'uploads/mary awori1.jpeg', 'deployed', NULL, 1, NULL, 'P.7', NULL, NULL, NULL, NULL, NULL, '2025-09-29 16:56:32', '2025-10-10 17:02:48', '', NULL, '', '', 'Yekyo Agnes - 0764907828', 'John Bakari - ', 'Other', 'Pakoi - B, Peta, Tororo', 'Other', '0762861702', 'single', 1, 'Japadollah, Luganda', 4, 'housekeeper', 'Worked cooking for builder - 7 months', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"\"},\"hiv\":{\"result\":\"negative\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"\"},\"notes\":\"\"}', 'full-time', 'deployed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22'),
(52, 'M016', 'Fiona', 'Ocwee', 'Ugandan', '2003-04-18', NULL, NULL, '0771022125', NULL, 'uploads/fiona ocwee.jpg', 'in-training', NULL, 0, NULL, 'P.7', NULL, NULL, NULL, NULL, NULL, '2025-10-10 11:43:41', '2025-10-10 13:44:13', 'None', NULL, 'CF03115100JXGF', 'C/Man - Abua Selestino Omara - 0773659350', 'Adong Paska - 0774410337', 'Ocan Emmy (In-law) - 0762267294', 'Langi', 'Obalwat Village, Palwo, Aremo, Tochi, Omoro District', 'Omoro', '', 'single', 0, 'Langi', 4, 'housekeeper', 'Worked in Boutique for 3 months in Gulu.', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"2025-09-28\"},\"hiv\":{\"result\":\"negative\",\"date\":\"2025-09-28\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"2025-09-28\"},\"notes\":\"\"}', 'full-time', 'available', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02'),
(53, 'M017', 'Josephine', 'Nanyonga', 'Ugandan', '1986-10-11', NULL, NULL, '0742145824', NULL, 'uploads/josephine nanyonga.jpeg', '', NULL, 4, NULL, 'Certificate', NULL, NULL, NULL, NULL, NULL, '2025-10-10 12:08:28', '2025-10-13 12:29:38', '', NULL, 'CF860301057HWD', 'Nalya-Nansese Village\r\nLugyo Parish\r\nMuduuma\r\nMpigi District\r\n\r\nSelyaaya Fred - 0754371777', 'Nakitende Imelda - 0703534261', ' - ', 'Baganda', 'Bujuuko', 'Mpigi', '', 'single', 3, 'Luganda', 5, 'housekeeper', '1. Elderly care - Home care - 2 years in Entebbe.\r\n2. Home Care of own children - 2 years in Bujuuko', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"2025-10-06\"},\"hiv\":{\"result\":\"negative\",\"date\":\"2025-10-06\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"2025-10-06\"},\"notes\":\"\"}', 'full-time', 'absconded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06'),
(54, 'M018', 'Juliet', 'Athieno', 'Ugandan', '1994-12-17', NULL, NULL, '0702825092', NULL, 'uploads/Juliet.jpeg', 'in-training', NULL, 0, NULL, 'S.4', NULL, NULL, NULL, NULL, NULL, '2025-10-13 11:56:08', '2025-10-22 11:22:33', '', NULL, 'CF94039106E3CH', 'Katajula Village\r\nNagogera Sub County\r\nTororo - District', 'Acieng Jane - CF72039107N5DE - 0755409317', 'Othieno Richard - CM69039100FXRG - Owere Vincent ', 'Jopadhola', 'Katajula Village', 'Tororo', '0700181525', 'single', 1, 'Japadollah', 7, 'housekeeper', 'Worked in Ndejje for 5months,\r\nLeft: Lady mistreatment, abusive\r\n3 children - 8, 3, 3 years\r\nKeep outside the house after chores.\r\n', '{\"hepatitis_b\":{\"result\":\"\",\"date\":\"\"},\"hiv\":{\"result\":\"\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"\",\"date\":\"\"},\"notes\":\"\"}', 'full-time', 'available', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-11'),
(55, 'M019', 'Vicky ', 'Akello', 'Uganda', '1994-12-30', NULL, NULL, '0768457028', NULL, 'uploads/akello vicky.jpeg', 'booked', NULL, 9, NULL, 'Certificate', NULL, NULL, NULL, NULL, NULL, '2025-10-21 11:00:58', '2025-10-24 10:30:53', 'Husband in Mbarara - builder, with company - \r\nAngatu Isaac - 0777900636', NULL, 'CF94057102M9JG', 'Inomo Cell, Inomo Ward, Amolatar T/C\r\nLC1 Chairperson: Okwel Joel\r\nPhone: 0782007620\r\n\r\nDefence LC1\r\nOtiira Mike: 0773926160\r\n\r\n', 'Magrat Odala - ', 'Odala Patrick - 0763911564', 'Langi', 'Inomo Cell, Inomo Ward, Amolatar Town Council', 'Amolatar District', '0789240945', 'single', 2, 'Langi', 7, 'housekeeper', 'Amolatar TownView Hotel\r\n9 years,\r\nWorking in the Kitchen\r\nReplace after going to visit sick Mum (a month)\r\n', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"2025-10-11\"},\"hiv\":{\"result\":\"negative\",\"date\":\"2025-10-11\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"2025-10-11\"},\"notes\":\"\"}', 'full-time', 'deployed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13'),
(56, 'M020', 'Olivier', 'Nangobya', 'Uganda', '2000-02-14', NULL, NULL, '0709793091', NULL, 'uploads/olivier Nangobya.jpeg', 'in-training', NULL, 2, NULL, 'S.4', NULL, NULL, NULL, NULL, NULL, '2025-10-21 11:59:33', '2025-10-22 10:27:29', '', NULL, '', 'Ndeeba Village\r\nVvumba Parish\r\nBusiika Town Council\r\nBamunanika County\r\nLuweero District\r\n\r\nMusoma Ibrahim :  0750608321', 'Nabawanuka Betty - 0704801152', ' - ', 'Baganda', 'Ndeeba', 'Luweero', '', 'single', 2, 'Luganda', 7, 'housekeeper', 'Kajjansi - 1  year, - House Girl, 2019, House worker, 7 rooms, children 3, ages 2,3 and 6 months, Salary - 80K, late payment\r\nEntebbe Bwebajja - House Girl - 1 yrs, 4 bdrms, 2 children, 8 and 5 yrs, - Shifted to Nigeria, Salary - 60K\r\n\r\nHow much - 150K above\r\nPay on-time', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"2025-10-17\"},\"hiv\":{\"result\":\"negative\",\"date\":\"2025-10-17\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"2025-10-17\"},\"notes\":\"\"}', 'full-time', 'booked', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18'),
(57, 'M021', 'Phionah', 'Ainembabazi', 'Uganda', '1993-12-12', NULL, NULL, '0786919622', NULL, 'uploads/phionah ainembababzi.jpeg', 'in-training', NULL, 2, NULL, 'Below P.7', NULL, NULL, NULL, NULL, NULL, '2025-10-21 12:15:46', '2025-10-21 09:15:46', '', NULL, '', '', 'Kembabazi Milly -Auntie - 0781886019', 'Wilson Bagambisa - Jackson Uncle (Phone number) - 0703135559', 'Banyankole', 'Ntugamo', 'Ntungamo', '0748942005', 'single', 2, 'Luganda, Lunyakole', 3, 'housekeeper', 'Kajjansi - Loy, 7 months, 5 children, youngest 2 yrs, 12, 18, 15 yrs - 5 bedrooms, Salary - 80K little\r\nLusanja - 1 yrs, Rashida, 3 children, 0.5, 4, 9yrs, - 4 bedrooms, Salary - 100K reduced the money to 60K', '{\"hepatitis_b\":{\"result\":\"negative\",\"date\":\"\"},\"hiv\":{\"result\":\"negative\",\"date\":\"\"},\"urine_hcg\":{\"result\":\"negative\",\"date\":\"\"},\"notes\":\"Ulcers - Issues from school days\"}', 'full-time', 'available', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16');

--
-- Triggers `maids`
--
DELIMITER $$
CREATE TRIGGER `before_insert_maid` BEFORE INSERT ON `maids` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    SET next_id = (SELECT IFNULL(MAX(CAST(SUBSTRING(maid_code, 2) AS SIGNED)), 0) + 1 FROM maids);
    SET NEW.maid_code = CONCAT('M', LPAD(next_id, 3, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `maids`
--
ALTER TABLE `maids`
  ADD PRIMARY KEY (`maid_id`),
  ADD UNIQUE KEY `maid_code` (`maid_code`),
  ADD KEY `idx_maid_code` (`maid_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `idx_maids_hepatitis_b` (`hepatitis_b_result`),
  ADD KEY `idx_maids_hiv` (`hiv_result`),
  ADD KEY `idx_maids_urine_hcg` (`urine_hcg_result`),
  ADD KEY `idx_maids_date_of_arrival` (`date_of_arrival`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `maids`
--
ALTER TABLE `maids`
  MODIFY `maid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
