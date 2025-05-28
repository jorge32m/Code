-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2025 at 04:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `24198_Loja`
--

-- --------------------------------------------------------

--
-- Table structure for table `Carrinho`
--

CREATE TABLE `Carrinho` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `produtoId` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Carrinho`
--

INSERT INTO `Carrinho` (`id`, `userId`, `produtoId`, `quantidade`) VALUES
(1, 26, 5, 4),
(2, 26, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produto`
--
INSERT INTO `produto` (`id`, `nome`, `descricao`, `preco`, `imagem`) VALUES
(1, 'Produto 1', 'Descrição do Produto 1', 10.00, NULL),
(2, 'Produto 2', 'Descrição do Produto 2', 20.00, NULL),
(3, 'Produto 3', 'Descrição do Produto 3', 30.00, NULL),
(4, 'Produto 4', 'Descrição do Produto 4', 40.00, NULL),
(5, 'Produto 5', 'Descrição do Produto 5', 50.00, NULL),
(6, 'Produto 6', 'Descrição do Produto 6', 60.00, NULL),
(7, 'Produto 7', 'Descrição do Produto 7', 70.00, NULL);

--
-- Table structure for table `Role`
--

CREATE TABLE `Role` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`id`, `description`, `createdAt`, `updatedAt`) VALUES
(1, 'ADMIN', '2025-05-20 14:16:12', '2025-05-20 14:16:12'),
(2, 'CLIENT', '2025-05-20 14:16:12', '2025-05-20 14:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `Utilizador`
--

CREATE TABLE `Utilizador` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telemovel` varchar(9) DEFAULT NULL,
  `nif` varchar(9) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `RoleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Utilizador`
--

INSERT INTO `Utilizador` (`id`, `username`, `email`, `password`, `telemovel`, `nif`, `token`, `active`, `created_at`, `updated_at`, `RoleID`) VALUES
(26, 'Joao', 'joao.monge13@gmail.com', '$2y$10$bQ19waAOO4pj3WNsMQfIt.NMyPJfK0hbEBVj804K7PX1A5EuK4cSy', '213123123', '123123142', '6215c5ba071ef66337eb71c07ef54a50', 1, '2025-05-13 15:47:19', '2025-05-13 15:47:19', 1),
(27, 'Manuel Cliente', 'jpdme@iscte.pt', '$2y$10$xHolMC3VHEuSYBH1.VohnOw55h9SZArzA6jrLVw5wTO9wWj6/AC/G', '912312312', '231213123', 'cd79d6d34bd9e2880fb6f7944baf178d', 1, '2025-05-20 14:33:25', '2025-05-20 14:33:25', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Carrinho`
--
ALTER TABLE `Carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `produtoId` (`produtoId`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `Utilizador`
--
ALTER TABLE `Utilizador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nif` (`nif`),
  ADD KEY `RoleID` (`RoleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Carrinho`
--
ALTER TABLE `Carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Utilizador`
--
ALTER TABLE `Utilizador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Carrinho`
--
ALTER TABLE `Carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Utilizador` (`id`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produtoId`) REFERENCES `produto` (`id`);

--
-- Constraints for table `Utilizador`
--
ALTER TABLE `Utilizador`
  ADD CONSTRAINT `utilizador_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `Role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
