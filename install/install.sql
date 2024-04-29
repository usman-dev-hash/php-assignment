-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema php_assignment
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema php_assignment
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `php_assignment` DEFAULT CHARACTER SET utf8mb4 ;
USE `php_assignment` ;

-- -----------------------------------------------------
-- Table `posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `posts` ;

CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `content` VARCHAR(255) NULL DEFAULT NULL,
    `title` VARCHAR(255) NULL DEFAULT NULL,
    `author` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `movies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `movies`;

CREATE TABLE IF NOT EXISTS `movies`
(
    `id`           INT(11)      NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(255) NULL     DEFAULT NULL,
    `casts`        VARCHAR(255) NULL     DEFAULT NULL,
    `release_date` DATE         NOT NULL,
    `director`     VARCHAR(255) NULL     DEFAULT NULL,
    `imdb`      DECIMAL(3,1) NOT NULL DEFAULT NULL,
    `rotten_tomatto`      DECIMAL(3,1) NOT NULL DEFAULT NULL,
    `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8mb4;
