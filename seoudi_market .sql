-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2026 at 11:46 PM
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
-- Database: `seoudi_market`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`) VALUES
(1, 6),
(2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `cart_product`
--

CREATE TABLE `cart_product` (
  `cart_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_product`
--

INSERT INTO `cart_product` (`cart_product_id`, `product_id`, `quantity`, `created_at`, `cart_id`) VALUES
(1, 8, 1, '2026-05-18 20:33:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `image`) VALUES
(1, 'Dairy', 'dairy.jpg'),
(2, 'Beverages', 'beverages.jpg'),
(3, 'Snacks', 'snacks.jpg'),
(4, 'Bakery', 'bakery.jpg'),
(5, 'Frozen Food', 'frozen.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_message`
--

CREATE TABLE `contact_message` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_message`
--

INSERT INTO `contact_message` (`message_id`, `name`, `email`, `message`, `date`, `phone`) VALUES
(1, 'Mona Adel', 'mona@gmail.com', 'When will my order arrive?', '2026-05-18 18:23:43', '01055667788'),
(2, 'Karim Tarek', 'karim@gmail.com', 'Do you have more frozen products?', '2026-05-18 18:23:43', '01199887766'),
(3, 'Noureldin Sayed Hassan Abdou Hassan', 'guest@seoudi.com', 'mgcdhfsjhg', '2026-05-18 18:29:31', '01070394404');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `delivery_method` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `order_id`, `delivery_method`, `address`, `pickup_date`, `customer_name`, `phone`, `delivery_status`) VALUES
(1, 1, 'Delivery', 'Nasr City, Cairo', NULL, 'Ahmed Ali', '01012345678', 'pending'),
(2, 2, 'Pickup', NULL, '2026-05-20', 'Sara Mohamed', '01098765432', 'pending'),
(3, 3, 'Delivery', 'Maadi, Cairo', NULL, 'Omar Hassan', '01122334455', 'pending'),
(4, 10, 'home', 'mohamad hegazy street', NULL, NULL, NULL, 'preparing'),
(5, 12, 'pickup', NULL, NULL, NULL, NULL, 'preparing');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`favorite_id`, `user_id`, `product_id`) VALUES
(1, 1, 3),
(3, 2, 1),
(4, 2, 7),
(5, 3, 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','processing','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `payment_method`, `delivery_method`, `status`, `created_at`) VALUES
(1, 1, 180.00, 'Visa', 'Delivery', 'delivered', '2026-05-18 18:23:43'),
(2, 2, 75.00, 'Cash', 'Pickup', 'processing', '2026-05-18 18:23:43'),
(3, 3, 245.00, 'Visa', 'Delivery', 'pending', '2026-05-18 18:23:43'),
(4, 1, 15.00, 'cash', 'pickup', 'pending', '2026-05-18 19:38:25'),
(5, 1, 15.00, 'cash', 'pickup', 'pending', '2026-05-18 19:57:47'),
(6, 1, 15.00, 'cash', 'home', 'pending', '2026-05-18 19:58:37'),
(7, 1, 15.00, 'cash', 'home', 'pending', '2026-05-18 20:02:08'),
(8, 1, 15.00, 'cash', 'home', 'pending', '2026-05-18 20:03:33'),
(9, 1, 15.00, 'cash', 'home', 'pending', '2026-05-18 20:03:45'),
(10, 1, 15.00, 'cash', 'home', 'pending', '2026-05-18 20:04:35'),
(11, 1, 22.00, 'visa', 'pickup', 'pending', '2026-05-18 20:08:32'),
(12, 1, 22.00, 'Visa', 'Pickup', 'pending', '2026-05-18 20:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`order_product_id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 6, 5, 1, 15.00, '2026-05-18 19:58:37'),
(2, 7, 5, 1, 15.00, '2026-05-18 20:02:08'),
(3, 8, 5, 1, 15.00, '2026-05-18 20:03:33'),
(4, 9, 5, 1, 15.00, '2026-05-18 20:03:45'),
(5, 10, 5, 1, 15.00, '2026-05-18 20:04:35'),
(6, 11, 8, 1, 22.00, '2026-05-18 20:08:32'),
(7, 12, 8, 1, 22.00, '2026-05-18 20:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Cash','Visa') DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `amount`, `payment_date`, `payment_method`, `payment_status`) VALUES
(1, 1, 180.00, '2026-05-18 18:23:43', 'Visa', 'pending'),
(2, 2, 75.00, '2026-05-18 18:23:43', 'Cash', 'pending'),
(3, 3, 245.00, '2026-05-18 18:23:43', 'Visa', 'pending'),
(4, 8, 15.00, '2026-05-18 20:03:33', 'Cash', 'pending'),
(5, 9, 15.00, '2026-05-18 20:03:45', 'Cash', 'pending'),
(6, 10, 15.00, '2026-05-18 20:04:35', 'Cash', 'pending'),
(7, 12, 22.00, '2026-05-18 20:10:24', 'Cash', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `image`, `expiry_date`) VALUES
(1, 1, 'Milk 1L', 'Fresh full cream milk', 35.00, 50, 'milk.jpg', '2026-08-15'),
(2, 1, 'Cheddar Cheese', 'Premium cheddar cheese', 120.00, 20, 'cheese.jpg', '2026-10-01'),
(3, 2, 'Coca Cola', 'Soft drink 1.5L', 25.00, 100, 'cola.jpg', '2027-01-01'),
(4, 2, 'Orange Juice', 'Natural orange juice', 40.00, 35, 'juice.jpg', '2026-09-20'),
(5, 3, 'Potato Chips', 'Salted potato chips', 15.00, 80, 'chips.jpg', '2026-12-01'),
(6, 3, 'Chocolate Bar', 'Milk chocolate', 20.00, 60, 'chocolate.jpg', '2027-02-10'),
(7, 4, 'White Bread', 'Fresh bakery bread', 18.00, 40, 'bread.jpg', '2026-06-01'),
(8, 4, 'Croissant', 'Butter croissant', 22.00, 25, 'croissant.jpg', '2026-06-03'),
(9, 5, 'Frozen Pizza', 'Pepperoni frozen pizza', 150.00, 15, 'pizza.jpg', '2027-03-15'),
(10, 5, 'Frozen Nuggets', 'Chicken nuggets', 95.00, 30, 'nuggets.jpg', '2027-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `phone`, `role`) VALUES
(1, 'Ahmed Ali', 'ahmed@gmail.com', '123456', '01012345678', 'customer'),
(2, 'Sara Mohamed', 'sara@gmail.com', '123456', '01098765432', 'customer'),
(3, 'Omar Hassan', 'omar@gmail.com', '123456', '01122334455', 'customer'),
(4, 'Admin User', 'admin@seoudi.com', 'admin123', '01234567890', 'admin'),
(5, 'Noureldin Sayed Hassan Abdou Hassan', 'noureldinsayed@gmail.com', '123456', '01070394404', 'customer'),
(6, 'Noureldin Sayed Hassan Abdou Hassan', 'noureldin@gmail.com', '123456', '01070394404', 'customer'),
(7, 'Noureldin Sayed Hassan Abdou Hassan', 'hassan@gmail.com', '123456', '01070394404', 'customer'),
(8, 'hana', 'hana@gmail.com', '12345', '01070394404', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD PRIMARY KEY (`cart_product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_cart` (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact_message`
--
ALTER TABLE `contact_message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_product`
--
ALTER TABLE `cart_product`
  MODIFY `cart_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_message`
--
ALTER TABLE `contact_message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD CONSTRAINT `cart_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
