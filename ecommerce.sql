-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 04:09 PM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `description`, `image`) VALUES
(3, 'spider man', 4.49, 'The Spider-Man game is an action-adventure title that allows players to experience the life of the superhero \"Spider-Man\" in New York City. Players can swing between buildings using web-slinging mechanics, confront enemies, and solve puzzles, all while engaging in a rich storyline featuring well-known characters from the Marvel universe.', 'uploads/68137a6aba212_3796.jpg'),
(4, 'Dragon Ball', 9.99, 'Dragon Ball is an action-packed anime and video game franchise that follows the adventures of Goku and his friends as they seek powerful Dragon Balls to summon a wish-granting dragon. Players engage in intense battles, explore vast worlds, and experience epic storylines featuring iconic characters and transformations.', 'uploads/68137aa22a52a_2486.png'),
(5, 'One Piece', 7.99, 'One Piece is an adventurous anime and manga series that follows Monkey D. Luffy and his crew of pirates as they search for the ultimate treasure known as the One Piece. Players embark on thrilling journeys across vast oceans, engage in epic battles, and form bonds with diverse characters while pursuing their dreams of becoming the Pirate King.', 'uploads/68137af6cf2f2_luffy-scar-one-piece-4k-wallpaper-uhdpaper.com-264@3@a.jpg'),
(6, 'Leage Of Legends', 12.99, 'League of Legends is a multiplayer online battle arena (MOBA) game where players assume the role of \"champions\" with unique abilities and skills. Teams of five compete to destroy the opposing team\'s Nexus while strategizing, coordinating, and utilizing teamwork to outsmart their opponents in fast-paced, tactical gameplay.', 'uploads/68137b79e5695_twitch-pool-party-skin-lol-splash-art-hd-wallpaper-uhdpaper.com-588@5@e.jpg'),
(7, 'Tokyo Ghol', 15.00, 'Tokyo Ghoul is a dark fantasy anime and manga series that follows Ken Kaneki, a young man who becomes a half-ghoul after a chance encounter with one. As he navigates the dangerous world of ghouls and humans, he struggles with his identity, grapples with moral dilemmas, and fights for survival in a society that fears and hunts his kind.', 'uploads/68137bb245f5d_930839.png'),
(8, 'Demon Slayer', 10.00, 'Demon Slayer (Kimetsu no Yaiba) is an anime and manga series that follows Tanjiro Kamado, a young boy who becomes a demon slayer after his family is slaughtered by demons and his sister is turned into one. As he embarks on a quest to avenge his family and find a cure for his sister, Tanjiro faces powerful foes, forms deep bonds with fellow demon slayers, and learns the art of swordsmanship and breathing techniques.', 'uploads/68137be00ecc5_1329828.jpeg'),
(9, 'Elden Ring', 29.99, 'Elden Ring is an action role-playing game set in a vast, open world filled with rich lore and challenging enemies. Players explore the Lands Between, battling formidable foes and uncovering secrets while customizing their character\'s abilities and equipment, all within a beautifully crafted environment that combines elements of fantasy and dark mythology.', 'uploads/68137c17bb8ba_596296.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$WoLCSIAsL2KGZu3qVvhg.eqG/fgr.oDjP3YFfTTXlAQ/kk3NynGD2', 'admin'),
(5, 'ahmad', 'ahmad@gmail.com', '$2y$10$NaWXQS6uQg3wfBxkale6uOeDhLU1OiaruEuYL8qvUlx/Wher.I5Ka', 'user'),
(6, 'abd', 'abd@gmail.com', '$2y$10$c0CigYibWgrfchlFgu3kkOgT4SZN8Ac89NdQqjAgxZiW.w90Yb4gq', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
