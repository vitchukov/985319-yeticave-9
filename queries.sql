use yeticave;

insert into categories
  (name, code)
values ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

insert into lots
(dt_cr, name, descr, url, price, dt_end, step, user_id, user_win_id, cat_id)
values (20190426120000, '2014 Rossignol District Snowboard', 'Описание', 'img/lot-1.jpg', 10999, 20190626120000, 100, 1,
        2, 1),
       (20190426120000, 'DC Ply Mens 2016/2017 Snowboard', 'Описание', 'img/lot-2.jpg', 159999, 20190626120000, 100, 1,
        2, 1),
       (20190426120000, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Описание', 'img/lot-3.jpg', 8000,
        20190626120000,
        100, 1, 2, 2),
       (20190426120000, 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание', 'img/lot-4.jpg', 10999, 20190626120000,
        100,
        1, 2, 3),
       (20190426120000, 'Куртка для сноуборда DC Mutiny Charocal', 'Описание', 'img/lot-5.jpg', 7500, 20190626120000,
        100, 1,
        2, 4),
       (20190426120000, 'Маска Oakley Canopy', 'Описание', 'img/lot-6.jpg', 5400, 20190626120000, 100, 1, 2, 6);

insert into users
  (dt_reg, email, name, password, av_url, contacts)
values (20190420120000, 'vitchukov@mail.ru', 'Владимир', '123', 'img/1.gpg', 'г.Йошкар-Ола'),
       (20190420120000, 'ivan@hmail.ru', 'Иван', '123', 'img/1.gpg', 'г.Петяково');

insert into rates
  (dt_rate, sum, user_id, lot_id)
values (20190427120000, 11000, 2, 1),
       (20190427121000, 12000, 1, 1);

# получаем все категории
select id, name, code from categories;

# получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
select l.name, l.price, l.url, r.sum, c.name from lots l
inner join categories c on l.cat_id=c.id
inner join rates r on r.lot_id=l.id
where l.dt_cr BETWEEN 20190426120000 AND 20190427120000;

# показать лот по его id. Получите также название категории, к которой принадлежит лот
select l.name, c.name from lots l
join categories c on l.cat_id=c.id
where l.id=2;

# обновить название лота по его идентификатору;
update lots set name='Крепления Union Contact Pro 2015 года размер L/XL' where id=3;

# получить список самых свежих ставок для лота по его идентификатору
select r.sum, l.name from rates r
join lots l on r.lot_id=l.id
where l.id=3 and r.dt_rate > 20190427120000;
