create database yeticave
  default character set utf8
  default collate utf8_general_ci;

use yeticave;
CREATE TABLE categories
(
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name varchar(128) not null unique,
  code varchar(128) not null unique
);

CREATE TABLE lots
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  dt_cr       timestamp default current_timestamp,
  name        varchar(128) not null,
  descr       varchar(128),
  url         varchar(128),
  price       int,
  dt_end      DATETIME,
  step        int,
  user_id     int,
  user_win_id int,
  cat_id      int
);

CREATE TABLE rates
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  dt_rate timestamp default current_timestamp,
  sum     int not null,
  user_id int,
  lot_id  int
);

CREATE TABLE users
(
  id       INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg   timestamp default current_timestamp,
  email    varchar(128) not null unique,
  name     varchar(128) not null,
  password varchar(128) not null,
  av_url   varchar(128),
  contacts varchar(255)
);

