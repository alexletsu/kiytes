# DROP DATABASE `kytes`;
CREATE DATABASE `kytes` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

# CREATE USER 'kytesuser'@'localhost' IDENTIFIED BY 'kyp(XN&QP(W*&NXanyxwouiynex';
# GRANT ALL PRIVILEGES ON kytes.* TO 'kytesuser'@'localhost' WITH GRANT OPTION;

# DROP TABLE `addresses`;
CREATE TABLE `addresses` (
    `id`                INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `address`           VARCHAR(200) UNIQUE NOT NULL
);

# DROP TABLE `rel_user_address`;
CREATE TABLE `rel_user_address` (
    `id`                INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `address_id`        INT NOT NULL REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `user_id`           INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

# DROP TABLE `users`;
CREATE TABLE `users` (
    `id`                INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

    `user_type`         TINYINT(1) UNSIGNED DEFAULT 0 COMMENT '0 - customer, 1 - driver',

    `first_name`        VARCHAR(200) NOT NULL,
    `last_name`         VARCHAR(200) NOT NULL,

    `email`             VARCHAR(200) NOT NULL UNIQUE,
    `email_verified`    TINYINT(1) UNSIGNED DEFAULT 0,
    `email_acttoken`    VARCHAR(200) UNIQUE,

    `phone`             VARCHAR(200) NOT NULL UNIQUE,
    `phone_verified`    TINYINT(1) UNSIGNED DEFAULT 0,
    `phone_acttoken`    VARCHAR(200) UNIQUE,

    `password`          VARCHAR(200) NOT NULL,

    `is_complete`       TINYINT(1) UNSIGNED DEFAULT 0 COMMENT '0 - profile incomplete, 1 - profile complete',

    `photo`             VARCHAR(200) UNIQUE DEFAULT NULL,
    `license_photo`     VARCHAR(200) UNIQUE DEFAULT NULL,
    `insurance_photo`   VARCHAR(200) UNIQUE DEFAULT NULL,

    `ccard_info`        VARCHAR(200) DEFAULT NULL,

    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# DROP TABLE `cars`;
CREATE TABLE `cars` (
    `id`                INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

    `make`              VARCHAR(200) DEFAULT NULL,
    `model`             VARCHAR(200) DEFAULT NULL,

    `year`              INTEGER(4) UNSIGNED DEFAULT NULL,

    `license_plate`     VARCHAR(200) UNIQUE DEFAULT NULL,
    `car_photo`         VARCHAR(200) UNIQUE DEFAULT NULL,

    `price_mile`        FLOAT(8,2) DEFAULT NULL,

    `driver_id`         INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

# DROP TABLE `rides`;
CREATE TABLE `rides` (
    `id`                INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `client_id`         INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    `driver_id`         INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 

    `status`            INT(1) NOT NULL COMMENT 'Ride status : 0 - invitation for driver, 1 - pending, 2 - driver declines invitation, 3 - driver accepts invitation, 4 - completed', 
    `message`           VARCHAR(200), 

    `ride_token`        VARCHAR(200) UNIQUE,

    `time_start`        TIMESTAMP DEFAULT 0,
    `address_start`     INT NOT NULL REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    `address_end`       INT NOT NULL REFERENCES `addresses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 

    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# DROP TABLE `rates`;
CREATE TABLE `rates` (
    `id`               INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `rater_id`         INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    `rated_id`         INT NOT NULL REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    `rate`             FLOAT(3,2) NOT NULL,
    UNIQUE KEY `rate_unique` (`rater_id`, `rated_id`)
);
