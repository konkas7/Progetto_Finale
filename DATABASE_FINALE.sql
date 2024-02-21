-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2024 at 09:30 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formula_one_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `circuiti`
--

CREATE TABLE `circuiti` (
  `circuito_id` int(11) NOT NULL,
  `nome_circuito` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `circuiti`
--

INSERT INTO `circuiti` (`circuito_id`, `nome_circuito`) VALUES
(1, 'Monaco Grand Prix'),
(2, 'Italian Grand Prix'),
(3, 'British Grand Prix'),
(4, 'Spanish Grand Prix'),
(5, 'Japanese Grand Prix'),
(6, 'Austrian Grand Prix'),
(7, 'Brazilian Grand Prix'),
(8, 'Russian Grand Prix'),
(9, 'Mexican Grand Prix'),
(10, 'Canadian Grand Prix'),
(11, 'United States Grand Prix'),
(12, 'Hungarian Grand Prix');

-- --------------------------------------------------------

--
-- Table structure for table `dati_circuito`
--

CREATE TABLE `dati_circuito` (
  `circuito_id` int(11) NOT NULL,
  `altitudine` int(11) NOT NULL,
  `lunghezza_record` decimal(5,2) NOT NULL,
  `record_tempo` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dati_circuito`
--

INSERT INTO `dati_circuito` (`circuito_id`, `altitudine`, `lunghezza_record`, `record_tempo`) VALUES
(1, 10, '3.34', '01:12:17'),
(2, 50, '5.79', '01:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `gare`
--

CREATE TABLE `gare` (
  `gara_id` int(11) NOT NULL,
  `circuito_id` int(11) NOT NULL,
  `data_gara` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gare`
--

INSERT INTO `gare` (`gara_id`, `circuito_id`, `data_gara`) VALUES
(1, 1, '2023-05-28'),
(2, 2, '2023-09-03'),
(3, 3, '2023-07-16'),
(4, 4, '2023-05-14'),
(5, 5, '2023-10-08'),
(6, 6, '2023-07-09'),
(7, 7, '2023-11-12'),
(8, 8, '2023-09-24'),
(9, 9, '2023-10-29'),
(10, 10, '2023-06-11'),
(11, 11, '2023-10-22'),
(12, 12, '2023-08-06');

-- --------------------------------------------------------

--
-- Table structure for table `gare_piloti`
--

CREATE TABLE `gare_piloti` (
  `gara_id` int(11) NOT NULL,
  `pilota_id` int(11) NOT NULL,
  `posizione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gare_piloti`
--

INSERT INTO `gare_piloti` (`gara_id`, `pilota_id`, `posizione`) VALUES
(3, 1, 2),
(3, 2, 3),
(3, 3, 1),
(4, 1, 3),
(4, 2, 1),
(4, 4, 2),
(5, 1, 1),
(5, 2, 2),
(5, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `piloti`
--

CREATE TABLE `piloti` (
  `pilota_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nazionalita` varchar(30) NOT NULL,
  `data_nascita` date NOT NULL,
  `punti_totali` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `piloti`
--

INSERT INTO `piloti` (`pilota_id`, `nome`, `cognome`, `nazionalita`, `data_nascita`, `punti_totali`) VALUES
(1, 'Lewis', 'Hamilton', 'British', '1985-01-07', 100),
(2, 'Max', 'Verstappen', 'Dutch', '1997-09-30', 90),
(3, 'Charles', 'Leclerc', 'Monegasque', '1997-10-16', 80),
(4, 'Valtteri', 'Bottas', 'Finnish', '1989-08-28', 70),
(5, 'Sebastian', 'Vettel', 'German', '1987-07-03', 60),
(6, 'Daniel', 'Ricciardo', 'Australian', '1989-07-01', 50),
(7, 'Fernando', 'Alonso', 'Spanish', '1981-07-29', 80),
(8, 'Lando', 'Norris', 'British', '1999-11-13', 65),
(9, 'Esteban', 'Ocon', 'French', '1996-09-17', 45),
(10, 'Kimi', 'Räikkönen', 'Finnish', '1979-10-17', 40),
(11, 'Nico', 'Hülkenberg', 'German', '1987-08-19', 30),
(12, 'Sergio', 'Pérez', 'Mexican', '1990-01-26', 55);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `nome_team` varchar(100) NOT NULL,
  `sede` varchar(100) NOT NULL,
  `anno_fondazione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `nome_team`, `sede`, `anno_fondazione`) VALUES
(1, 'Mercedes', 'Brackley, UK', 2010),
(2, 'Red Bull Racing', 'Milton Keynes, UK', 2005),
(3, 'Ferrari', 'Maranello, Italy', 1929),
(4, 'McLaren', 'Woking, UK', 1963),
(5, 'Alpine', 'Enstone, UK', 1977),
(6, 'AlphaTauri', 'Faenza, Italy', 2006),
(7, 'Williams', 'Grove, UK', 1977),
(8, 'Haas', 'Kannapolis, USA', 2016),
(9, 'Aston Martin', 'Silverstone, UK', 1960),
(10, 'Alpha Romeo Racing', 'Hinwil, Switzerland', 1950),
(11, 'Racing Point', 'Silverstone, UK', 2018),
(12, 'Scuderia Toro Rosso', 'Faenza, Italy', 2006);

-- --------------------------------------------------------

--
-- Table structure for table `team_piloti`
--

CREATE TABLE `team_piloti` (
  `team_id` int(11) NOT NULL,
  `pilota_id` int(11) NOT NULL,
  `data_ingresso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team_piloti`
--

INSERT INTO `team_piloti` (`team_id`, `pilota_id`, `data_ingresso`) VALUES
(1, 1, 2013),
(2, 2, 2016),
(3, 3, 2019),
(1, 4, 2017),
(2, 5, 2021),
(3, 6, 2018),
(4, 7, 2014),
(5, 8, 2019),
(6, 9, 2020),
(7, 10, 2019),
(8, 11, 2020),
(9, 12, 2018);

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `password`) VALUES
(1, 'username', 'password'),
(2, 'Thomas', 'Poiupoiu7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `circuiti`
--
ALTER TABLE `circuiti`
  ADD PRIMARY KEY (`circuito_id`);

--
-- Indexes for table `dati_circuito`
--
ALTER TABLE `dati_circuito`
  ADD PRIMARY KEY (`circuito_id`);

--
-- Indexes for table `gare`
--
ALTER TABLE `gare`
  ADD PRIMARY KEY (`gara_id`),
  ADD KEY `circuito_id` (`circuito_id`);

--
-- Indexes for table `gare_piloti`
--
ALTER TABLE `gare_piloti`
  ADD PRIMARY KEY (`gara_id`,`pilota_id`),
  ADD KEY `pilota_id` (`pilota_id`);

--
-- Indexes for table `piloti`
--
ALTER TABLE `piloti`
  ADD PRIMARY KEY (`pilota_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `team_piloti`
--
ALTER TABLE `team_piloti`
  ADD PRIMARY KEY (`team_id`,`pilota_id`,`data_ingresso`),
  ADD KEY `pilota_id` (`pilota_id`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `circuiti`
--
ALTER TABLE `circuiti`
  MODIFY `circuito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `gare`
--
ALTER TABLE `gare`
  MODIFY `gara_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `piloti`
--
ALTER TABLE `piloti`
  MODIFY `pilota_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `dati_circuito`
--
ALTER TABLE `dati_circuito`
  ADD CONSTRAINT `dati_circuito_ibfk_1` FOREIGN KEY (`circuito_id`) REFERENCES `circuiti` (`circuito_id`);

--
-- Constraints for table `gare`
--
ALTER TABLE `gare`
  ADD CONSTRAINT `gare_ibfk_1` FOREIGN KEY (`circuito_id`) REFERENCES `circuiti` (`circuito_id`);

--
-- Constraints for table `gare_piloti`
--
ALTER TABLE `gare_piloti`
  ADD CONSTRAINT `gare_piloti_ibfk_1` FOREIGN KEY (`gara_id`) REFERENCES `gare` (`gara_id`),
  ADD CONSTRAINT `gare_piloti_ibfk_2` FOREIGN KEY (`pilota_id`) REFERENCES `piloti` (`pilota_id`);

--
-- Constraints for table `team_piloti`
--
ALTER TABLE `team_piloti`
  ADD CONSTRAINT `team_piloti_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `team_piloti_ibfk_2` FOREIGN KEY (`pilota_id`) REFERENCES `piloti` (`pilota_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
