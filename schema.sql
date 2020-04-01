-- Создали БД
CREATE DATABASE `1021437-yeticave-12` DEFAULT CHARACTER SET `utf8mb4`;

-- Выбор БД;
USE `1021437-yeticave-12`;

-- Создание таблицы с пользователями
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_registration` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время регистрации',
    `email` VARCHAR(128) NOT NULL UNIQUE COMMENT 'email указанный при регистрации',
    `name` VARCHAR(128) NOT NUll COMMENT 'имя пользователя',
    `password` VARCHAR(256) NOT NUll COMMENT 'зашифрованный пароль',
    `users_info` TEXT NOT NUll COMMENT 'контактная информация'
);
-- Создаем уникальный индекс для поля email в таблице users
CREATE UNIQUE INDEX `e_mail` ON users(email);

-- Создание таблицы с категориями
CREATE TABLE `categories` (
     `id` INT AUTO_INCREMENT PRIMARY KEY,
     `name_category` VARCHAR(60) NOT NULL UNIQUE,
     `code` VARCHAR(128) NOT NUll
);
-- Создаем уникальный индекс для поля name_category в таблице categories
CREATE UNIQUE INDEX `category` ON categories(name_category);

-- Создание таблицы с лотами
CREATE TABLE `lots` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `create_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания лота',
    `name` VARCHAR(120) NOT NULL COMMENT 'наименование лота',
    `description` TEXT COMMENT 'описание лота',
    `image_link` VARCHAR(150) NOT NULL COMMENT 'ссылка на изображение',
    `price_start` VARCHAR(150) NOT NULL COMMENT 'начальная цена',
    `date_end` TIMESTAMP COMMENT 'дата и время создания лота',
    `step_rate` VARCHAR(150) NOT NULL COMMENT 'шаг ставки',
    `author_id` INT NOT NULL COMMENT 'id пользователя',
    `user_winner_id` INT NOT NULL COMMENT 'id победителя',
    `category_id` INT NOT NULL COMMENT 'id категории объявления'
);
-- Создал составной индекс для поиска по автору и категории
CREATE INDEX user_category ON lots(author_id, category_id);

-- Создание таблицы со ставками
CREATE TABLE `bids` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `create_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время размешения ставки',
    `price` VARCHAR(10) NOT NULL COMMENT 'цена ставки',
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `lot_id` INT NOT NULL COMMENT 'id лота'
);
-- Создал составной индекс для поиска по автору и сумме
CREATE INDEX user_price ON bids(user_id, price);
