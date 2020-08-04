CREATE MODEL IF NOT EXISTS ngcp_db;
USE ngcp_db;
CREATE TABLE IF NOT EXISTS `radios` (
	`radio_id` INT(4) NOT NULL,
	`alias` VARCHAR(255) NOT NULL,
	`location` VARCHAR(255) NULL,
	PRIMARY KEY (`radio_id`),
	UNIQUE (`alias`)
) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `allowed_locations` (
	`radio_id` INT(4) NOT NULL,
	`location` VARCHAR(255) NOT NULL,
	UNIQUE(`radio_id`, `location`),
	FOREIGN KEY (`radio_id`) REFERENCES `radios`(`radio_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB;
GRANT ALL PRIVILEGES ON *.* TO 'antonio'@'%';
FLUSH PRIVILEGES;