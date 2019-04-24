create database yeticave
  default character set utf8
  default collate utf8_general_ci;

CREATE TABLE categories
(
  id  INT AUTO_INCREMENT PRIMARY KEY,
  name char(128) not null unique
);

CREATE TABLE lots
(
  id  INT AUTO_INCREMENT PRIMARY KEY,
  dt_cr timestamp default current_timestamp,
  name char(128) not null ,
  descr char(128),
  url char(128),
  price int,
  dt_end DATETIME,
  step int,
  user_id int,
  user_win_id int
);

CREATE TABLE rates
(
  id  INT AUTO_INCREMENT PRIMARY KEY,
  dt_rate timestamp default current_timestamp,
  sum int not null ,
  user_id int,
  lot_id int
);

CREATE TABLE users
(
  id  INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg timestamp default current_timestamp,
  email char(128) not null unique ,
  name char(128) not null ,
  password char(128) not null ,
  av_url char(128),
  contacts char(255),
  lot_id int,
  rate_id int
);
