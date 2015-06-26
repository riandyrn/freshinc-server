/*
Navicat MySQL Data Transfer

Source Server         : localIP
Source Server Version : 50620
Source Host           : 127.0.0.1:3306
Source Database       : db_freshinc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2015-06-26 09:30:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of carts
-- ----------------------------

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `goods` text NOT NULL,
  `start_balance` int(255) NOT NULL,
  `end_balance` int(255) NOT NULL,
  `address` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of logs
-- ----------------------------

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `type` enum('grocery','fruit','fish_meat','vegetable') NOT NULL DEFAULT 'grocery',
  `divider` int(255) NOT NULL DEFAULT '1',
  `unit` varchar(255) NOT NULL,
  `price_per_divider` int(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'brokoli super', 'vegetable', '100', 'gr', '150', 'brokoli.jpg', '100');
INSERT INTO `products` VALUES ('2', 'kubis lembang', 'vegetable', '100', 'gr', '1000', 'kubis.jpg', '100');
INSERT INTO `products` VALUES ('3', 'bayam air', 'vegetable', '100', 'gr', '150', 'bayam.jpg', '100');
INSERT INTO `products` VALUES ('4', 'mentimun madu', 'vegetable', '50', 'gr', '1000', 'mentimun.jpg', '100');
INSERT INTO `products` VALUES ('5', 'kacang panjang super', 'vegetable', '100', 'gr', '200', 'kacang_panjang.jpg', '100');
INSERT INTO `products` VALUES ('6', 'apel malang brondi', 'fruit', '200', 'gr', '2500', 'apel.jpg', '100');
INSERT INTO `products` VALUES ('7', 'kurma madinah', 'fruit', '100', 'gr', '2500', 'kurma.jpg', '100');
INSERT INTO `products` VALUES ('8', 'wortel lembang', 'vegetable', '100', 'gr', '200', 'wortel.jpg', '100');
INSERT INTO `products` VALUES ('9', 'tomat alpine', 'vegetable', '100', 'gr', '500', 'tomat.jpg', '100');
INSERT INTO `products` VALUES ('10', 'parsley cianjur', 'vegetable', '100', 'gr', '150', 'parsley.jpg', '100');
INSERT INTO `products` VALUES ('11', 'salmon super', 'fish_meat', '100', 'gr', '24000', 'salmon.jpg', '100');
INSERT INTO `products` VALUES ('12', 'tuna sirip biru', 'fish_meat', '100', 'gr', '7000', 'tuna.jpg', '100');
INSERT INTO `products` VALUES ('13', 'wagyu tenderloin', 'fish_meat', '100', 'gr', '15000', 'wagyu.jpg', '100');
INSERT INTO `products` VALUES ('14', 'beras cianjur', 'grocery', '1', 'kg', '10000', 'beras_cianjur.jpg', '100');
INSERT INTO `products` VALUES ('15', 'telur premium', 'grocery', '1', 'kg', '14000', 'telur_premium.jpg', '100');
INSERT INTO `products` VALUES ('16', 'tepung bumbu masako', 'grocery', '1', 'sachet', '4000', 'masako.jpg', '100');
INSERT INTO `products` VALUES ('17', 'susu ultra', 'grocery', '1', 'pack', '16000', 'susu_ultra.jpg', '100');
INSERT INTO `products` VALUES ('18', 'bison amerika', 'fish_meat', '1', 'kg', '50000', 'daging_bison.jpg', '100');
INSERT INTO `products` VALUES ('19', 'anggur afrika', 'fruit', '1', 'pack', '10000', 'anggur_afrika', '100');
INSERT INTO `products` VALUES ('20', 'pisang bogor', 'fruit', '100', 'gr', '500', 'pisang_bogor.jpg', '100');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `balance` int(255) NOT NULL DEFAULT '0',
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'customer', '4d52e10020c05d96c7f36551dddd980aef7af1bbd871d675d1699468f659c2df29212f8158d5554b91e4f5863ab2fda7a434e464d0859d45125aabee0624d82d', 'inisalt', '1000000', 'Komp. Graha Alam Raya W2/4, Margaluyu, Buah Batu, Bandung');
