-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2020 at 02:21 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodshala`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `menuitem_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `final_cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isVeg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `password`, `name`, `email`, `address`, `isVeg`) VALUES
(1, 'tushar', 'pass123', 'Tushar Rajdev', 'tushar.rajdev@gmail.com', 'Bhardawadi, Andheri West', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menuitem`
--

CREATE TABLE `menuitem` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isVeg` tinyint(1) NOT NULL,
  `cost` float NOT NULL,
  `gst` float NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menuitem`
--

INSERT INTO `menuitem` (`id`, `restaurant_id`, `name`, `description`, `isVeg`, `cost`, `gst`) VALUES
(1, 1, 'Plain Cheese Pizza', 'Basic Cheese Pizza', 1, 240, 5),
(2, 1, 'Garden Fresh Pizza', 'Capsicum, Onion & Corn', 1, 340, 5),
(3, 1, 'Simply Veg Pizza', 'Capsicum, Onion & Diced Tomato', 1, 340, 5),
(4, 1, 'Chicken Tikka Pizza', 'Chicken Tikka, Onion & Corriander', 0, 470, 5),
(5, 1, 'Butter Chicken Pizza', 'Butter Chic., Onion & Corriander with Tandoori Sauce', 0, 470, 5),
(6, 1, 'Sezchuan Chicken Pizza', 'Sezchuan Chicken, Spring Onion & Capsicum', 0, 470, 5),
(7, 1, 'Hawaiian Pizza', 'Capsicum, Corn, Pineapple & Paneer', 1, 370, 5),
(8, 1, 'Popeye Special Pizza', 'Spinach, Baby Corn & Paneer Tikka', 1, 370, 5),
(9, 1, 'Joey\'s Special Pizza', 'Chicken Salami, BBQ Chicken, Roast Chicken & Onion', 0, 520, 5),
(10, 1, 'Chicken Mania', 'Chic Sausage, Chic Salami, Plain Chicken & Onion', 0, 520, 5),
(11, 1, 'Chocolate Mousse', 'Eggless', 1, 90, 5),
(12, 1, 'Chocolate Lava Cake', '-', 1, 90, 5),
(13, 2, 'BBQ Paneer & Bell Peppers', 'House spiced grilled Paneer Steak - Jalapeno - Caramelised Onions', 1, 189, 5),
(14, 2, 'Classic Veg', 'Fried Potato & Corn Based mixed Veggie Patty - Onion - Shredded Cucumber, Carrot & Mint Salad', 1, 219, 5),
(15, 2, 'Peri Peri Paneer & Bell Peppers', 'House spiced grilled Paneer Steak - Caramelised Onions - Crispy Potato Sticks', 1, 209, 5),
(16, 2, 'Classic Chicken', 'Chicken - BBQ Sauce - Cheese Slice', 0, 179, 5),
(17, 2, 'Buffalo Tenderloin', 'Buffalo Tenderloin - BBQ Sauce - Cheese Slice', 0, 219, 5),
(18, 2, 'Pork', 'Pork - BBQ Sauce - Cheese Slice', 0, 229, 5),
(19, 3, 'McVeggie Burger', 'A delectable patty filled with potatoes, peas, carrots and tasty Indian spices. Topped with crispy lettuce, mayonnaise, and packed into toasted sesame buns.', 1, 109, 5),
(20, 3, 'McChicken Burger', 'Tender and juicy chicken patty cooked to perfection, with creamy mayonnaise and crunchy lettuce adding flavour to each bite.', 0, 124, 5),
(21, 3, 'McSpicy Chicken Burger', 'Tender and juicy chicken patty coated in spicy, crispy batter topped with a creamy sauce and crispy shredded lettuce will have you craving for more.', 0, 172, 5),
(22, 3, 'McSpicy Paneer Burger', 'Rich and filling cottage cheese patty coated in spicy, crispy batter topped with a creamy sauce and crispy shredded lettuce will have you craving for more.', 1, 167, 5),
(23, 3, 'Big Spicy Paneer Wrap', 'Rich and filling cottage cheese patty coated in spicy, crispy batter, topped with a creamy sauce wrapped with lettuce, onions, tomatoes & seasoning and cheese. A BIG indulgence.', 1, 204, 5),
(24, 3, 'Big Spicy Chicken Wrap', 'Tender and juicy chicken patty coated in spicy, crispy batter, topped with a creamy sauce, wrapped with lettuce, onions, tomatoes & seasoning and cheese. A BIG indulgence.', 0, 215, 5),
(25, 3, 'Filet-O-Fish Burger', 'Signature fish filet patty, perfectly balanced with a sharp flavour of tartar sauce, a thin slice of cheese served between steamed buns.', 0, 153, 5),
(27, 3, 'Mcflurry Choco Crunch Medium', 'Our signature soft serve topped with delicious chunks of chocolate, a delight for chocolate lovers, have it whole or share with a loved one.', 1, 112, 5),
(28, 3, 'McFlurry Oreo Medium', 'Delicious soft serve meets crumbled oreo cookies, a match made in dessert heaven. Share it, if you can.', 1, 112, 5),
(29, 3, 'Choco Marble Slice Cake', 'A snack-time cake with a perfect balance of vanilla and chocolate', 0, 121, 5),
(30, 4, 'Veg Hawaiian Delight', 'Golden Corn, Pineapple & Jalapeno ', 1, 340, 5),
(31, 4, 'Spicy Triangle Tango', 'Golden Corn, Gherkini & Red Paprika', 1, 340, 5),
(32, 4, 'Chicken Salami Special', 'Cheese & Exotic Chicken Salami', 0, 340, 5),
(33, 4, 'Veggie Paradise', 'Babycorn, Black Olives, Crisp Capsicum & Red Paprika', 1, 420, 5),
(34, 4, 'Chicken Hawaiian Twist', 'Exotic Chicken Salami, Pineapple & Jalapeno', 0, 420, 5),
(35, 4, 'Chef\'s Chicken Choice', 'Double Smoked Chicken, Black Olives, Crisp Capsicum & Red Paprika', 0, 475, 5),
(36, 5, 'Vietnamese Noodle Clear Soup', 'Flavoured with Ginger & Spring Onion', 1, 255, 5),
(37, 5, 'Lung Fung Soup', 'Ginger and Coriander Flavoured Thick Soup with Carrot and Tofu', 1, 255, 5),
(38, 5, 'Chicken Hot & Sour Soup', 'Hot Soup Livoned with Soy and Crushed White Peppercorn', 0, 275, 5),
(39, 5, 'Lemon Coriander Soup', 'Chicken / Prawn', 0, 275, 5),
(40, 5, 'Crispy Corn Cubes', 'Piping hot cubes of creamy, crunchy lightly spiced corn', 1, 390, 5),
(41, 5, 'Crackling Spinach', 'Shredded spinach, fried crisp, tossed with almonds silvers sesame seeds', 1, 435, 5),
(42, 5, 'Asparagus Tempura Rolls', 'Asparagus Tempura, Avocado, Chef\'s Special Sauce', 0, 415, 5),
(43, 5, 'Prawn Tempura Sushi', 'Prawn Tempura, Crab Stick, Mixed Sauce', 0, 445, 5);

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menuitem_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `final_cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`id`, `order_id`, `menuitem_id`, `quantity`, `final_cost`) VALUES
(1, 1, 13, 1, 189),
(2, 1, 15, 1, 209),
(3, 1, 16, 1, 179),
(4, 2, 1, 4, 960),
(5, 3, 7, 2, 740),
(6, 4, 22, 3, 501),
(7, 4, 25, 3, 459);

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `final_cost` float DEFAULT NULL,
  `date` date NOT NULL,
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`id`, `restaurant_id`, `customer_id`, `quantity`, `final_cost`, `date`, `comments`) VALUES
(1, 2, 1, 3, 605.85, '2020-06-30', ''),
(2, 1, 1, 4, 1008, '2020-06-30', ''),
(3, 1, 1, 2, 777, '2020-06-30', ''),
(4, 3, 1, 6, 1008, '2020-06-30', '');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cuisine` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`id`, `username`, `password`, `name`, `address`, `cuisine`) VALUES
(1, 'joey', '12345', 'Joey\'s Pizza', 'Azad Nagar, Andheri West', 'Casual Dining, Pizza, Fast Food'),
(2, 'jimi', '12345', 'Jimis Burger', 'Lokhandwala, Andheri West', 'Quick Bites, Burger, Fast Food'),
(3, 'mcdonald', '12345', 'McDonald\'s ', 'Lokhandwala, Andheri West', 'Quick Bites, Burger, Fast Food'),
(4, 'dominos', '12345', 'Domino\'s Pizza', 'Lokhandwala, Andheri West', 'Quick Bites, Pizza, Fast Food'),
(5, 'mainchina', '12345', 'Mainland China', 'Veera Desai, Andheri West', 'Casual Dining, Chinese, Asian, Sushi, Japanese, Thai'),
(6, 'radhakrishna', '12345', 'Radha Krishna', 'Near Andheri West Station', 'South Indian, Chinese, Street Food, Desserts, Beverages'),
(7, 'urbantadka', '12345', 'Urban Tadka', '7 Bungalows, Andheri West', 'North Indian, Mughlai, Goan, Biryani, Beverages'),
(8, 'kfc', '12345', 'KFC', 'Infiniti Mall, Lokhandwala, Andheri West', 'Burger, Fast Food, Beverages'),
(9, 'luckybir', '12345', 'Lucky Biryani', 'Oshiwara, Andheri West', 'Biryani, North Indian, Chinese'),
(10, 'burgerking', '12345', 'Burger King', 'Infiniti Mall, Lokhandwala, Andheri West', 'Burger, Fast Food, Beverages');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `menuitem_id` (`menuitem_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `menuitem`
--
ALTER TABLE `menuitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menuitem_id` (`menuitem_id`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menuitem`
--
ALTER TABLE `menuitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`menuitem_id`) REFERENCES `menuitem` (`id`);

--
-- Constraints for table `menuitem`
--
ALTER TABLE `menuitem`
  ADD CONSTRAINT `menuitem_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orderlist` (`id`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`menuitem_id`) REFERENCES `menuitem` (`id`);

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
