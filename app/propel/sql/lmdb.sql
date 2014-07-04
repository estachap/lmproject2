
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- propelcommit
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `propelcommit`;

CREATE TABLE `propelcommit`
(
    `id` VARCHAR(255) NOT NULL,
    `title` VARCHAR(250) NOT NULL,
    `link` VARCHAR(255) NOT NULL,
    `content` TEXT,
    `update_date` DATETIME,
    `author_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `propelcommit_FI_1` (`author_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- author
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `author`;

CREATE TABLE `author`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(250) NOT NULL,
    `uri` VARCHAR(250),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- file
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(250) NOT NULL,
    `commit_id` VARCHAR(255) NOT NULL,
    `commit_status` VARCHAR(2),
    PRIMARY KEY (`id`),
    INDEX `file_FI_1` (`commit_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
