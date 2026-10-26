CREATE DATABASE `book_library`;
CREATE TABLE `authors` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`full_name` TEXT NOT NULL,
	`birth_date` DATE NOT NULL,
	`address` TEXT NOT NULL,
	`phone` TEXT NOT NULL,
	`email` TEXT NOT NULL,
	`language` TEXT NOT NULL,
	`country` TEXT NOT NULL,
	`janres` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='cp1251_general_ci';
CREATE TABLE `books` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`description` TEXT NOT NULL,
	`year` INT NOT NULL DEFAULT 0,
	`janre` TEXT NOT NULL,
	`author_id` BIGINT NOT NULL DEFAULT 0,
	`free_count` INT NOT NULL DEFAULT 0,
	`on_hand_count` INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
)
COLLATE='cp1251_general_ci';
CREATE TABLE `readers` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`full_name` TEXT NOT NULL,
	`birth_date` DATE NOT NULL,
	`address` TEXT NOT NULL,
	`phone` TEXT NOT NULL,
	`email` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='cp1251_general_ci';
CREATE TABLE `books_to_readers` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`reader_id` BIGINT(20) NOT NULL,
	`book_id` BIGINT(20) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK__books` (`book_id`) USING BTREE,
	INDEX `FK_readers` (`reader_id`) USING BTREE,
	CONSTRAINT `FK__books` FOREIGN KEY (`book_id`) REFERENCES `book_library`.`books` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `FK_readers` FOREIGN KEY (`reader_id`) REFERENCES `book_library`.`readers` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='cp1251_general_ci';


