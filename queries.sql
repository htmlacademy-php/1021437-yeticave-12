-- Заполнение таблицы пользователей
INSERT INTO `users`
    (`registration_at`, `email`, `name`, `password`, `users_info`)
VALUES
(NOW(), 'ivanov1@gmail.com', 'Иванов Иван', '11111', 'Уверенный игрок на аукционах, возраст: 28 лет, мой телефон: 8-915-225-44-21'),
(NOW(), 'smirnovpetr@gmail.com', 'Смирнов Петр', '22222', 'Профессионалный игрок на аукционах, мой телефон: 8-999-225-22-44, возраст: 56 лет.'),
(NOW(), 'ivanov2001@gmail.com', 'Иванов Иван', '11111', 'Возраст: 28 лет, уверенный игрок на аукционах, мой телефон: 8-910-000-22-11'),
(NOW(), 'moiseenko@gmail.com', 'Смирнов Петр', '22222', 'Профессионалный игрок на аукционах, возраст: 56 лет, телефон для связи: 8-910-000-22-11'),
(NOW(), 'tarasov1990@gmail.com', 'Тарасов Семён', '33333', 'Новичок в этом деле, возраст: 33 года, контактный телефон: 8-914-333-44-44');

-- Заполнение списка категорий
INSERT INTO `categories`
    (`name`, `code`)
VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

-- Заполнение списка лотов
INSERT INTO `lots`
    (`created_at`, `name`, `description`, `image_link`, `price_start`, `ends_at`, `step_rate`, `author_id`, `user_winner_id`, `category_id`)
VALUES
(NOW(), '2014 Rossignol District Snowboard', 'Легкая, быстрая, стабильная, отлично выстреливающая вверх', 'img/lot-1.jpg', '10999', '2020-04-12 23:00:00', '100', '2', '4', '1'),
(NOW(), 'DC Ply Mens 2016/2017 Snowboard', 'Максимально заряженная на быстрое и агрессивное катания', 'img/lot-2.jpg', '159999', '2020-04-10 23:00:00', '2000', '4', '2', '1'),
(NOW(), 'Крепления Union Contact Pro 2015 года размер L/XL', 'Отличная модель от известного брэнда, который специализируется на креплениях', 'img/lot-3.jpg', '8000', '2020-04-11 23:00:00', '300', '2', '2', '2'),
(NOW(), 'Ботинки для сноуборда DC Mutiny Charocal', 'Удобный, технологичный и стильный вариант', 'img/lot-4.jpg', '10999', '2020-04-09 23:00:00', '700', '3', '3', '3'),
(NOW(), 'Куртка для сноуборда DC Mutiny Charocal', 'Стильный силуэт подчеркнет Ваш городской образ.', 'img/lot-5.jpg', '7500', '2020-04-13 23:00:00', '250', '5', '5', '4'),
(NOW(), 'Маска Oakley Canopy', 'Такая маска идеально подойдет опытным райдерам', 'img/lot-6.jpg', '5400', '2020-04-14 23:00:00', '400', '2', '4', '6');


-- Добавление ставок
INSERT INTO `bids`
    (`created_at`, `price`, `user_id`, `lot_id`)
VALUES
(NOW(), '8300', '3', '3'),
(NOW(), '8600', '4', '3'),
(NOW(), '8900', '5', '3'),
(NOW(), '161999', '4', '2'),
(NOW(), '11699', '2', '4'),
(NOW(), '9200', '4', '3'),
(NOW(), '12399', '3', '4');

-- получить все категории
SELECT `name` FROM `categories`;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории
SELECT lot.name, lot.price_start, lot.image_link, category.name, bid.price as current_price FROM `lots` as lot
    INNER JOIN `categories` as category ON category.id = lot.category_id
    INNER JOIN `bids` as bid ON bid.lot_id = lot.id
    WHERE lot.ends_at > NOW() ORDER BY lot.created_at;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот;
SELECT lot.name, lot.description, lot.price_start, category.name as categories_name FROM `lots` as lot
    INNER JOIN `categories` as category ON category.id = lot.category_id
    WHERE lot.id = 3;

-- обновить название лота по его идентификатору
UPDATE `lots` SET `name` = 'Маска Oakley Canopy 2020'  WHERE `id` = 6;

-- получить список ставок для лота по его идентификатору с сортировкой по дате (от меньшей к большей)
SELECT * FROM `bids` WHERE `lot_id` = 3 ORDER BY `created_at` ASC
