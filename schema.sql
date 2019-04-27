create database yeticave
  default character set utf8
  default collate utf8_general_ci;

use yeticave;
CREATE TABLE categories
(
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name char(128) not null unique,
  code char(128) not null unique
);

use yeticave;
CREATE TABLE lots
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  dt_cr       timestamp default current_timestamp,
  name_l        char(128) not null,
  descr       char(128),
  url         char(128),
  price       int,
  dt_end      DATETIME,
  step        int,
  user_id     int,
  user_win_id int,
  cat_id      int
);

use yeticave;
CREATE TABLE rates
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  dt_rate timestamp default current_timestamp,
  sum     int not null,
  user_id int,
  lot_id  int
);

use yeticave;
CREATE TABLE users
(
  id       INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg   timestamp default current_timestamp,
  email    char(128) not null unique,
  name     char(128) not null,
  password char(128) not null,
  av_url   char(128),
  contacts char(255)
);

