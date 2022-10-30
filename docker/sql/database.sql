CREATE TABLE IF NOT EXISTS udacity_sl_automation.onlineresource (
    `id` INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT UNIQUE NOT NULL,
    `description` TEXT NOT NULL,
    `name` TEXT NOT NULL,
    `url` TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS udacity_sl_automation.sessionlead (
    `id` INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT UNIQUE NOT NULL,
    `email` VARCHAR(320) NOT NULL UNIQUE,
    `first_name` TEXT NOT NULL,
    `google_app_password` TEXT NOT NULL,
    `user_passphrase` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS udacity_sl_automation.student (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT UNIQUE NOT NULL,
    `email` TEXT NOT NULL UNIQUE,
    `first_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `on_track_status` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS udacity_sl_automation.emails (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT UNIQUE NOT NULL,
    `sessReportCsv` TEXT NOT NULL UNIQUE
);