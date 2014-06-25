/*
* SmartDir MySQL Schema file
*
* This file contains the schema for the MySQL database used by the Smartdir script
*/

# Main DB Operations

CREATE DATABASE smartdir;
USE smartdir;

-- SCHEMA FOR TABLES

# TABLE STRUCTURE FOR users

CREATE TABLE smartdir_users(
	userID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL,
	password BLOB NOT NULL,
	level ENUM('user', 'admin') NOT NULL,
	homeDir VARCHAR(255),
	INDEX (username)
	);

# TABLE STRUCTURE FOR directories

CREATE TABLE smartdir_directories(
	dirID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	dirName VARCHAR(255) NOT NULL,
	dirPath VARCHAR(255) NOT NULL,
	INDEX (dirName)
	);

# STRUCTURE FOR TABLE users2Directories

CREATE TABLE smartdir_users2directories(
	userID SMALLINT REFERENCES smartdir_users(userID),
	dirPath VARCHAR(255) REFERENCES smartdir_directories(dirPath),
	INDEX (dirPath)
	);

# SAMPLE DATA FOR TABLE users

INSERT INTO smartdir_users (userID, username, password, level, homedir) VALUES (1, 'administrator', AES_ENCRYPT('administrator', 'thisisthesecretkeythathelpstoregisternewuserstothesystem'), 'admin', 'clients');

