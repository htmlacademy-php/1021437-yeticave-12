-- Создали БД
CREATE DATABASE `1021437-yeticave-12` DEFAULT CHARACTER SET `utf8mb4`;

-- Выбор БД;
USE `1021437-yeticave-12`;

-- Создание таблицы с пользователями
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время регистрации',
    `email` VARCHAR(254) NOT NULL UNIQUE COMMENT 'email указанный при регистрации',
    `name` VARCHAR(128) NOT NUll COMMENT 'имя пользователя',
    `password` VARCHAR(256) NOT NUll COMMENT 'зашифрованный пароль',
    `users_info` TEXT NOT NUll COMMENT 'контактная информация'
);

-- Создание таблицы с категориями
CREATE TABLE `categories` (
     `id` INT AUTO_INCREMENT PRIMARY KEY,
     `name` VARCHAR(255) NOT NULL UNIQUE,
     `code` VARCHAR(255) NOT NUll
);

-- Создание таблицы с лотами
CREATE TABLE `lots` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания лота',
    `name` VARCHAR(255) NOT NULL COMMENT 'наименование лота',
    `description` TEXT COMMENT 'описание лота',
    `image_link` VARCHAR(2048) NOT NULL COMMENT 'ссылка на изображение',
    `price_start` DECIMAL(16,2) UNSIGNED NOT NULL COMMENT 'начальная цена',
    `ends_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время конца лота',
    `step_rate` DECIMAL(16,2) UNSIGNED NOT NULL COMMENT 'шаг ставки',
    `author_id` INT NOT NULL COMMENT 'id пользователя',
    `user_winner_id` INT NOT NULL COMMENT 'id победителя',
    `category_id` INT NOT NULL COMMENT 'id категории объявления'
);

-- Создал составной индекс для поиска по автору и категории
CREATE INDEX user_category ON lots(author_id, category_id);
-- Добавил индекс для полнотекстового поиска
CREATE FULLTEXT INDEX search_lot ON `lots`(name, description);

-- Создание таблицы со ставками
CREATE TABLE `bids` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время размешения ставки',
    `price` DECIMAL(16,2) UNSIGNED NOT NULL COMMENT 'цена ставки',
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `lot_id` INT NOT NULL COMMENT 'id лота'
);
-- Создал составной индекс для поиска по автору и сумме
CREATE INDEX user_price ON bids(user_id, price);
