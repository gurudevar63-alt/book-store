-- Orders schema migration
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(32) NOT NULL DEFAULT 'PLACED',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  book_id INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  INDEX (order_id),
  INDEX (book_id)
);

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2023 at 06:59 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `books`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) NOT NULL,
  `booktitle` varchar(300) NOT NULL,
  `bookauthor` varchar(300) NOT NULL,
  `bookprice` int(10) NOT NULL,
  `bookimagelocation` varchar(3000) NOT NULL,
  `discountbookprice` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `booktitle`, `bookauthor`, `bookprice`, `bookimagelocation`, `discountbookprice`) VALUES
(1, 'Pride and Prejudice', 'Jane Austen', 1300, 'images/1.jpg', '600'),
(2, 'To Kill a Mockingbird', 'Harper Lee', 1500, 'images/2.jpg', '765'),
(3, 'The Great Gatsby', 'F. Scott Fitzgerald', 1499, 'images/3.jpg', '799'),
(4, 'One Hundred Years of Solitude', 'Gabriel García Márquez', 1299, 'images/4.jpg', '899'),
(5, 'In Cold Blood', 'Truman Capote', 1899, 'images/6.jpg', '789'),
(6, 'Wide Sargasso Sea', 'Jean Rhys', 999, 'images/7.jpg', '699'),
(7, 'Brave New World', 'Aldous Huxley', 1566, 'images/8.jpg', '1299'),
(8, 'I Capture The Castle', 'Dodie Smith', 1299, 'images/9.jpg', '899'),
(9, 'Jane Eyre', 'Charlotte Bronte', 1899, 'images/10.jpg', '899'),
(10, 'Crime and Punishment', 'Fyodor Dostoevsky', 999, 'images/11.jpg', '600'),
(11, 'The Secret History', 'Donna Tartt', 1499, 'images/12.jpg', '899'),
(12, 'The Call of the Wild', 'Jack London', 999, 'images/13.jpg', '765'),
(13, 'The Chrysalids', 'John Wyndham', 1300, 'images/14.jpg', '699'),
(14, 'Persuasion', 'Jane Austen', 2999, 'images/15.jpg', '1299'),
(15, 'Moby-Dick', 'Herman Melville', 5999, 'images/16.jpg', '999'),
(16, 'The Lion, the Witch and the Wardrobe', 'C.S. Lewis', 2599, 'images/17.jpg', '1599'),
(17, 'To the Lighthouse', 'Virginia Woolf', 3299, 'images/18.jpg', '1599'),
(18, 'The Death of the Heart', 'Elizabeth Bowen', 999, 'images/19.jpg', '599'),
(19, 'Tess of the d Urbervilles', 'Thomas Hardy', 799, 'images/20.jpg', '699'),
(20, 'Frankenstein', 'Mary Shelley', 1299, 'images/5.jpg', '799'),
(21, 'Durga', 'Kevin Missal', 999, 'images/21.jpg', '699');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `email`, `password`) VALUES
(1, 'rahul', 'erahul096@gmail.com', '1234'),
(2, 'dell', 'dell@dell.com', '1234'),
(3, 'admin', 'admin@admin.com', 'admin'),
(4, 'ranjith', 'ranjith@gmail.com', 'ranjith'),
(5, 'billgates', 'billgates@gmail.com', 'billgates');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
