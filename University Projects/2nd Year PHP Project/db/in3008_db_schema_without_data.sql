/*
Main MySQL file for the IN3008 - Electronic Commerce coursework
* 
* author Georgi Zhivankin
* Version 1.0
* Description
This file contains the necessary SQL statements to create and populate a MySQL database that is being used by the IN3008 - Electronic Commerce module coursework
*/

# DATABASE INITIALISATION

CREATE DATABASE in3008;

USE in3008;

DROP TABLE IF EXISTS in3008_products;
DROP TABLE IF EXISTS in3008_products2images;
DROP TABLE IF EXISTS in3008_categories;
DROP TABLE IF EXISTS in3008_products2categories;
DROP TABLE IF EXISTS in3008_users;
DROP TABLE IF EXISTS in3008_addresses;
DROP TABLE IF EXISTS in3008_payments;
DROP TABLE IF EXISTS in3008_paymentCards;
DROP TABLE IF EXISTS in3008_orders;
DROP TABLE IF EXISTS in3008_users2addresses;
DROP TABLE IF EXISTS in3008_products2orders;
DROP TABLE IF EXISTS in3008_countries;


/*
TABLES STRUCTURE
*/

-- STRUCTURE FOR TABLE products

CREATE TABLE in3008_products(
	productID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	productName VARCHAR(100) NOT NULL,
	productDescription TEXT,
	productPrice FLOAT NOT NULL,
	productQuantity INT(11),
	INDEX (productName)
	);

			-- STRUCTURE FOR TABLE users

CREATE TABLE in3008_users(
	userID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	userName VARCHAR(255) NOT NULL DEFAULT '' UNIQUE,
	password BLOB NOT NULL DEFAULT '',
	email VARCHAR(255) NOT NULL,
	gender ENUM('m', 'f'),
	phone VARCHAR(20),
	level ENUM('administrator', 'customer'),
index (userName, email)
	);

-- STRUCTURE FOR TABLE addresses

CREATE TABLE in3008_addresses(
	addressID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	addressName VARCHAR(100) NOT NULL DEFAULT '',
	addressLine1 VARCHAR(255) NOT NULL DEFAULT '',
	addressLine2 VARCHAR(255),
	addressLine3 VARCHAR(255),
	city VARCHAR(255) NOT NULL,
	State VARCHAR(255),
	postCode VARCHAR(20) NOT NULL,
	country VARCHAR(255) NOT NULL
	);

# STRUCTURE FOR TABLE payments

CREATE TABLE in3008_payments(
	paymentID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	paymentDate DATETIME NOT NULL,
	paymentAmount DOUBLE NOT NULL,
	paymentCardID SMALLINT NOT NULL REFERENCES paymentCards(cardID)
	);

-- STRUCTURE FOR TABLE paymentCards

CREATE TABLE in3008_paymentCards(
	cardID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	cardType ENUM('Visa', 'Mastercard', 'AMEX', 'Eurocard') NOT NULL,
	cardNumber INT(16) NOT NULL UNIQUE,
	cardIssuedDate DATE NOT NULL,
	cardExpiryDate DATE NOT NULL
	);

-- STRUCTURE FOR TABLE orders

CREATE TABLE in3008_orders(
	orderNumber SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	orderDate DATETIME NOT NULL,
	orderAmount SMALLINT NOT NULL,
	userID SMALLINT REFERENCES in3008_users(userID),
	shippingMethod VARCHAR(50),
	shippingName VARCHAR(255) NOT NULL,
	shippingAddress TEXT NOT NULL,
	shippingPostCode VARCHAR(10) NOT NULL,
	orderStatus ENUM('paid', 'processing', 'processed') NOT NULL,
	INDEX (orderNumber)
	);

-- STRUCTURE FOR TABLE users2Addresses

CREATE TABLE in3008_users2addresses(
	userID SMALLINT NOT NULL REFERENCES users(userID),
	addressID SMALLINT NOT NULL REFERENCES addresses(addressID)
	);

-- STRUCTURE FOR TABLE products2Orders

CREATE TABLE in3008_products2orders(
	productID SMALLINT NOT NULL REFERENCES products(productID),
	productQuantity SMALLINT NOT NULL,
	orderNumber SMALLINT NOT NULL REFERENCES orders(orderNumber)
	);

# STRUCTURE FOR TABLE products2images

CREATE TABLE in3008_products2images(
	productID SMALLINT REFERENCES in3008_products(productID),
	imageName VARCHAR(255),
	INDEX (imageName)
	);

# STRUCTURE FOR TABLE categories

CREATE TABLE in3008_categories(
	categoryID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
	categoryName VARCHAR(255) NOT NULL,
	categoryDescription VARCHAR(255),
	categoryParent INT(11),
	INDEX (categoryName, categoryDescription)
	);

# STRUCTURE FOR TABLE products2categories

CREATE TABLE in3008_products2categories(
	productID SMALLINT REFERENCES in3008_products(productID),
	categoryID SMALLINT REFERENCES in3008_categories(categoryID)
	);
