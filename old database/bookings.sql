-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 09, 2025 at 09:36 AM
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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `full_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `parish` varchar(100) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `home_type` varchar(50) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `outdoor_responsibilities` text DEFAULT NULL,
  `appliances` text DEFAULT NULL,
  `adults` int(11) DEFAULT NULL,
  `has_children` enum('Yes','No') DEFAULT NULL,
  `children_ages` varchar(100) DEFAULT NULL,
  `has_elderly` enum('Yes','No') DEFAULT NULL,
  `pets` varchar(50) DEFAULT NULL,
  `pet_kind` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `language_other` varchar(100) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `service_tier` varchar(50) DEFAULT NULL,
  `service_mode` enum('Live-in','Live-out') DEFAULT NULL,
  `work_days` text DEFAULT NULL,
  `working_hours` varchar(50) DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `cuisine_type` varchar(50) DEFAULT NULL,
  `atmosphere` varchar(50) DEFAULT NULL,
  `manage_tasks` varchar(50) DEFAULT NULL,
  `unspoken_rules` text DEFAULT NULL,
  `anything_else` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `status`, `full_name`, `phone`, `email`, `country`, `city`, `division`, `parish`, `national_id`, `home_type`, `bedrooms`, `bathrooms`, `outdoor_responsibilities`, `appliances`, `adults`, `has_children`, `children_ages`, `has_elderly`, `pets`, `pet_kind`, `language`, `language_other`, `service_type`, `service_tier`, `service_mode`, `work_days`, `working_hours`, `responsibilities`, `cuisine_type`, `atmosphere`, `manage_tasks`, `unspoken_rules`, `anything_else`, `created_at`, `updated_at`) VALUES
(4, 'approved', 'kato Geoffrey', '0751801685', 'katogeoffreyg@gmail.com', 'Uganda', 'Kampala', 'gre', 'test', 'uploads/id_documents/nid_6881fa288af855.86304017.jpeg', 'Bungalow', 2, 2, 'None', 'Blender', 2, 'Yes', '2, 6', 'Yes', 'Yes, no duties', '2', 'English', '', 'Maid', 'Silver', 'Live-in', 'Thursday', '2', 'General House Cleaning', 'Mixed', 'Quiet', 'Worker takes initiative', '2', '2', '2025-07-24 09:17:28', '2025-07-24 09:41:57'),
(6, 'rejected', 'Lilian Arinda', '+256778286164', 'arindalilian22@gmail.com', 'Uganda', 'Kampala', 'Nakawa', 'Kiwatule ', '', 'Apartment', 2, 1, 'None', 'Blender', 2, 'Yes', '3 and 5months', 'No', 'No pets', '', 'English', '', 'Maid', 'Platinum', 'Live-in', '', '', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Written list', 'Respect boundaries \r\nHygiene \r\nDress appropriately ', 'The above', '2025-07-27 09:07:14', '2025-08-20 17:49:02'),
(7, 'pending', 'Rehema Nakirya Ssemyalo', '0772506802', 'rehema.nakirya@gmail.com', 'Uganda', 'Kampala', 'Kira', 'Kira', 'uploads/id_documents/nid_688624d17b1385.59246772.pdf', 'Maisonette', 5, 4, 'Gardening', 'Airfryer', 4, 'Yes', '10, 6 and 3', 'No', 'Yes, with duties', '2 dogs', 'A mix of English and Luganda', '', 'House Manager', 'Gold', 'Live-in', 'Sunday', 'N/A', 'Grocery Shopping', 'Mixed', 'Busy', 'Worker takes initiative', 'We don\'t particularly have unspoken family rules. But I expect the person to understand that she\'s here on a job and the boundaries in a home', 'I am looking for a mix of a house manager + chef. It is important that the person has qualification and experience in catering. I am looking for someone with experience, self motivated and self driven. I also prefe an older mature lady to younger girls', '2025-07-27 13:08:33', '2025-07-27 13:08:33'),
(8, 'rejected', 'Karen Hasahya', '0762936536', 'karen.hasahya@gmail.com', 'Uganda', 'Kampala', 'Nakawa', 'Bukoto II', 'uploads/id_documents/nid_6887eb4d5d3c63.47225454.jpeg', 'Apartment', 3, 2, '', 'None', 1, 'Yes', '2', 'No', 'No pets', '', 'English', '', 'Maid', 'Silver', 'Live-out', 'Saturday', '8:00 - 4:00pm  though mught be less hours. ', 'Grocery Shopping', 'Mixed', 'Quiet', 'Worker takes initiative', 'We collaborate and create a system that works for all. \r\nI have some written ideas to support our system. ', 'I have 2 sons soon 14 and 11, I am 42 years old myself. I think this information might help in guiding the process. \r\n\r\nI also work from home a lot but I’m very introverted so I keep to myself a lot and I really love peace and quiet. ', '2025-07-28 21:27:41', '2025-08-20 17:48:32'),
(9, 'pending', 'Victoria ', '0777800646', 'akankundavickie6@gmail.com', 'Uganda ', 'Kampala', 'Nakawa', 'Nakawa', '', 'Apartment', 2, 2, 'None', 'Airfryer', 4, 'Yes', '3 & 1 month', 'No', 'Yes, with duties', '1 dog', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', 'Minding our business \r\nRespect for one another \r\n', '', '2025-07-31 08:54:20', '2025-07-31 08:54:20'),
(10, 'pending', 'Naume Musiimenta ', '0785 198235 ', 'odalinconsult2023@gmail.com', 'Uganda ', 'Kampala ', 'Kiira', 'Kungu', '', '', 0, 0, 'Gardening', 'None', 2, 'Yes', '13, 10, 4', 'No', 'Yes, with duties', '1 dog', 'English', '', 'House Helper', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Verbal instructions', 'Discipline,  hardworking, Cleanliness and God fearing,', 'My past experience  , maids are very dirty and they don\'t want to work', '2025-07-31 15:23:22', '2025-07-31 15:23:22'),
(11, 'rejected', 'Victoria ', '0777800646', 'akankundavickie6@gmail.com', 'Uganda ', 'Kampala', 'Nakawa', 'Nakawa', '', 'Apartment', 2, 2, 'None', 'Airfryer', 4, 'Yes', '3 & 1 month', 'No', 'Yes, with duties', '1 dog', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', 'Minding our business \r\nRespect for one another \r\n', '', '2025-08-01 07:02:55', '2025-10-02 05:28:59'),
(12, 'approved', 'Victoria ', '0777800646', 'akankundavickie6@gmail.com', 'Uganda ', 'Kampala', 'Nakawa', 'Nakawa', '', 'Apartment', 2, 2, 'None', 'Airfryer', 4, 'Yes', '3 & 1 month', 'No', 'Yes, with duties', '1 dog', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', 'Minding our business \r\nRespect for one another \r\n', '', '2025-08-02 05:22:11', '2025-08-12 08:32:13'),
(13, 'rejected', 'Ellah Luwaga Kagumire', '0752720993', 'luwagaellah@gmail.com', 'Uganda', 'Kampala ', 'Kungu ', 'Kungu', '', '', 0, 0, 'Gardening', 'None', 3, 'Yes', '4 and 6months old ', 'No', 'No pets', '', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Worker takes initiative', '1.praying \r\n2.No noise whatsoever \r\n3.Respect\r\n4.Greeting \r\n5.Showering \r\n6.putting food at the table for meal time\r\n7.Kindness', 'I need someone with no children\r\nI need a kind person one who won’t be screaming at my children\r\nI need a christian \r\nI need a healthy person(had one with Asthma it was hard to deal with )', '2025-08-04 18:46:48', '2025-09-23 14:14:31'),
(14, 'rejected', 'MUKASHYAKA IRENE', '0759402530', 'mukashaka12@gmail.com', 'Uganda', 'Kampala', 'Complex Hall', 'Muluka 3', '', 'Bungalow', 2, 2, 'Sweeping', 'None', 3, 'Yes', '0 ,2.5', 'No', 'No pets', '', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Worker takes initiative', '', '- We prefer younger 18-25 ,Respectful and Honest\r\n- English speaking for proper communication\r\n-Christian', '2025-08-14 12:36:21', '2025-08-20 17:47:59'),
(15, 'pending', 'Loretta Tukahirwa', '778040140', 'loretta.tukahirwa@icloud.com', 'Uganda', 'Kampala', 'Kungu', 'Kungu', '', 'Apartment', 3, 4, 'None', 'Blender', 4, 'Yes', '11 weeks', 'Yes', 'No pets', '', 'English', '', 'Nanny', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Worker takes initiative', 'Limited screen time for the baby. Very clean person in terms of hygiene, responsive to feedback ', 'We are Christian’s and would like someone who can pray with us and share the same values. The main chore is looking after the baby and minding him. I expect someone that speaks English and can read him books now n then. Someone who won’t expose him to screens as a pastime. Someone that loves children naturally ', '2025-09-04 06:44:09', '2025-09-04 06:44:09'),
(16, 'pending', 'Rhoda Nalugya', '0773630541', 'rhodahaleynalugya@gmail.com', 'Uganda', 'Kampala', 'Nakawa', 'Kyambogo', 'uploads/id_documents/nid_68c183e72f19c5.54633753.pdf', 'Apartment', 2, 2, 'None', 'Airfryer', 2, 'Yes', '1.5, 3', 'No', 'No pets', '', 'English', '', 'Maid', 'Gold', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Busy', 'Worker takes initiative', 'Respecting the values of the home.\r\nPhones are used after work.\r\nChildren not beaten by maids.', 'I do not want lazy maids. My preference is inclined more to Baganda and maybe Basoga. Howver I am open to another tribe with their manners are good and they can speak English.', '2025-09-10 13:57:59', '2025-09-10 13:57:59'),
(17, 'pending', 'Rhoda Nalugya', '0773630541', 'rhodahaleynalugya@gmail.com', 'Uganda', 'Kampala', 'Nakawa', 'Kyambogo', 'uploads/id_documents/nid_68c185ead5fe58.37387606.pdf', 'Apartment', 2, 2, 'Gardening', 'Airfryer', 2, 'Yes', '1.5, 3', 'No', 'No pets', '', 'A mix of English and Luganda', '', 'Nanny', 'Gold', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Local', 'Busy', 'Worker takes initiative', 'My Children are not disciplined by Maids/Nannies', 'I do not want a lazy person. My preference is inclined to people from Central region. Baganda/Basoga etc', '2025-09-10 14:06:34', '2025-09-10 14:06:34'),
(18, 'pending', 'Shirley Nafula', '0787490734', 'nafulasharon193@gmail.com', 'UGANDA ', 'KAMPALA ', 'Kira', 'Nsasa', 'uploads/id_documents/nid_68c2d435d79356.16826129.pdf', 'Maisonette', 6, 6, 'None', 'Airfryer', 3, 'Yes', '1year boy,1month girl', 'No', 'Yes, no duties', '2 dogs', 'English', '', 'Nanny', 'Platinum', 'Live-in', 'Sunday', '5am to 9pm', 'Childcare / Nanny duties', 'Local', 'Quiet', 'Written list', '', '1.Time keeping / Managing time is key\r\n2.I don\'t allow maids to move out of the house as everything is made available in the house\r\n3.Church / Prayer  is a Must\r\n4.The 2 children as you may imagine are both in delicate stages & require maximum attention so the person should be very patient ,accomodative &  does work out of love \r\n5.i don\'t want a moselm, No muganda \r\n6.Hygiene is key\r\n7.Respect both ways is important \r\n', '2025-09-11 13:52:53', '2025-09-11 13:52:53'),
(19, 'approved', 'Josephine Nalugo ', '0773375900', 'joseynals@gmail.com', 'Uganda ', 'Kampala ', 'Kiira', 'Seta Kasangati ', 'uploads/id_documents/nid_68ca7295d091a1.63592835.jpeg', 'Bungalow', 3, 2, 'Gardening', 'Airfryer', 2, 'Yes', '2.5months, 2yrs, 9yrs', 'No', 'No pets', '', 'English', '', 'Nanny', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Written list', 'Professionalism, confidentiality, love for the children and high levels of hygiene.', 'The Nanny should be a Christian ', '2025-09-17 08:34:29', '2025-09-23 14:13:25'),
(20, 'approved', 'Precious Turinawe', '0776016303', 'preciousturi@gmail.com', 'Uganda', 'Entebbe', 'A', 'Entebbe', 'uploads/id_documents/nid_68cb8842c9e112.10355982.jpg', 'Bungalow', 4, 4, 'Gardening', '', 1, 'No', '', 'No', 'No pets', '', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', 'always at the house', 'Grocery Shopping', 'Local', 'Quiet', 'Worker takes initiative', 'No drinking ,smoking or having guests ', 'yes', '2025-09-18 04:19:14', '2025-09-23 14:13:18'),
(28, 'pending', 'Immaculate Mukuye ', '0755555581', 'immacnkm@gmail.com', 'Uganda ', 'Kampala ', 'Rubaga', 'Lungujja', 'uploads/id_documents/nid_68f517eb64a752.55363741.jpg', 'Bungalow', 0, 2, 'Gardening', 'Blender', 2, 'Yes', '9, 6, 5', 'No', 'No pets', '', 'Luganda', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', '* No beating only parents beat. Bad manners must be reported.\r\n*No foul language and verbal abusive language \r\n*Family and Sunday prayers are a must no one is left behind\r\n*Every member is treated fairly with dignity.\r\n', '', '2025-10-19 16:55:07', '2025-10-19 16:55:07'),
(29, 'pending', 'Immaculate Mukuye ', '0755555581', 'immacnkm@gmail.com', 'Uganda ', 'Kampala ', 'Rubaga', 'Lungujja', 'uploads/id_documents/nid_68f517eba80118.90437972.jpg', 'Bungalow', 0, 2, 'Gardening', 'Blender', 2, 'Yes', '9, 6, 5', 'No', 'No pets', '', 'Luganda', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', '* No beating only parents beat. Bad manners must be reported.\r\n*No foul language and verbal abusive language \r\n*Family and Sunday prayers are a must no one is left behind\r\n*Every member is treated fairly with dignity.\r\n', '', '2025-10-19 16:55:07', '2025-10-19 16:55:07'),
(30, 'pending', 'Josephine Nalugo', '0773375900', 'joseynals@gmail.com', 'Uganda', 'Kampala', 'Kiira', 'Seta Buwate', 'uploads/id_documents/nid_68f89bf00fa717.57802938.jpeg', 'Apartment', 3, 2, 'Gardening', 'Airfryer', 2, 'Yes', '3m, 2yrs,9yrs', 'No', 'No pets', '', 'English', '', 'House Helper', 'Silver', 'Live-in', 'Sunday', '', 'Cooking', 'Mixed', 'Quiet', 'Worker takes initiative', 'Excellent hygiene, respect , self drive ', 'I need a stable person for continuity.', '2025-10-22 08:55:12', '2025-10-22 08:55:12'),
(31, 'approved', 'JUSTINE NAMULI', '0772700862', 'nmljustine1@gmail.com', 'Uganda', 'MPIGI', 'MAWOKOTA', 'KISAMULA', 'uploads/id_documents/nid_68f8a429da0a40.91368247.pdf', 'Bungalow', 3, 3, 'Sweeping', 'Blender', 2, 'Yes', '5, 10, 12', 'No', 'Yes, no duties', '2 dogs', 'English', '', 'Maid', 'Silver', 'Live-in', 'Sunday', '', 'Childcare / Nanny duties', 'Mixed', 'Quiet', 'Worker takes initiative', 'Respect and care for one another', 'Cleanliness and hygiene are very important.\r\npro activeness and self drive make the relationship strong\r\nwould like someone who is open, sincere and ready to belong ', '2025-10-22 09:30:17', '2025-10-25 14:31:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
