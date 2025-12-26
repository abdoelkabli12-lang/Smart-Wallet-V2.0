-- Active: 1764858635456@@127.0.0.1@3306@tracker
use tracker;


CREATE DATABASE Smart_Wallet;

use smart_wallet;




TRUNCATE TABLE Expences_tracker;

ALTER TABLE user1 MODIFY COLUMN Password VARCHAR(255) NOT NULL;

ALTER TABLE user1 ADD UNIQUE (email);


CREATE TABLE IF NOT EXISTS users(
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  UserName VARCHAR(30) NOT NULL,
  Email VARCHAR(60) NOT NULL,
  Password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Transactions(
  trans_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  user_id INT,
  card_id INT,
  CONSTRAINT FK_userT_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT FK_cardT_id FOREIGN KEY(card_id) REFERENCES cards(Card_id) ON DELETE CASCADE,
  amount DECIMAL(6.2) NOT NULL,
  beneficary VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Expences_tracker(
  exp_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  user_id INT,
  -- card_id INT,
  CONSTRAINT FK_userE_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  -- CONSTRAINT FK_cardE_id FOREIGN KEY(card_id) REFERENCES cards(Card_id) ON DELETE CASCADE,
  Expences DECIMAL(6.2) NOT NULL,
  Date DATE NOT NULL,
  description VARCHAR(255),
  amountexp DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS Incomes_tracker(
  inc_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  user_id INT,
  -- card_id INT,
  CONSTRAINT FK_userI_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  -- CONSTRAINT FK_cardI_id FOREIGN KEY(card_id) REFERENCES cards(Card_id) ON DELETE CASCADE,
  Incomes DECIMAL(6.2) NOT NULL,
  Date DATE NOT NULL,
  description VARCHAR(255),
  amountexp DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS Cards(
  Card_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  Card_type VARCHAR(30) NOT NULL,
  serial_num CHAR(15) NOT NULL,
  CVV CHAR(3) NOT NULL,
  CONSTRAINT FK_user_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Transactions(
  trans_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  card_id INT,
  amount DECIMAL(10.2) NOT NULL,
  beneficiary INT CHECK (beneficiary >= 15),
  CONSTRAINT FK_userT_id FOREIGN KEY(user_id) REFERENCES user1(id) ON DELETE CASCADE,
  CONSTRAINT FK_card_id FOREIGN KEY(card_id) REFERENCES Cards(Card_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Authontification(
  auth_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  card_id INT,
  Token INT,
  CONSTRAINT FK_userAU_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT FK_cardAU_id FOREIGN KEY(card_id) REFERENCES Cards(Card_id) ON DELETE CASCADE
);

CREATE TABLE category_limits (
    limit_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_name VARCHAR(50) NOT NULL,
    monthly_limit DECIMAL(10,2) NOT NULL,
    CONSTRAINT FK_userCAT_id
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS Transferer(
  transf_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  card_id INT,
  user2_email VARCHAR(50) NOT NULL,
  user2_id INT NOT NULL,
  CONSTRAINT FK_usertr_id FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT FK_cardtr_id FOREIGN KEY(card_id) REFERENCES Cards(Card_id) ON DELETE CASCADE
);

ALTER TABLE cards MODIFY serial_num CHAR(19) NOT NULL;

ALTER TABLE cards ADD COLUMN placeholder VARCHAR(50) NOT NULL;




SELECT * FROM cards;

DELETE FROM cards;



USE smart_wallet;

SELECT id, Password FROM users WHERE Email = "abdo.el.kabli12@gmail.com";

ALTER TABLE cards
ADD card_color VARCHAR(50) DEFAULT 'indigo-purple';
ALTER TABLE Incomes_tracker RENAME COLUMN amountexp TO amountinc;


DESCRIBE Incomes_tracker;

INSERT INTO cards (card_color) VALUES ('test');


use smart_wallet;

ALTER TABLE transferer
ADD COLUMN(amount INT NOT NULL, description VARCHAR(100) NOT NULL);


ALTER TABLE cards ADD balance DECIMAL(10,2);


DESCRIBE cards;

ALTER TABLE cards ADD is_main TINYINT(1) DEFAULT 0;

UPDATE cards
SET is_main = 0
WHERE user_id = 2;

UPDATE cards
SET is_main = 2
WHERE Card_id = 17 AND user_id = 1;

ALTER TABLE expences_tracker
ADD category VARCHAR(50) NOT NULL AFTER Expences;


ALTER TABLE incomes_tracker
ADD category VARCHAR(50) NOT NULL AFTER Incomes;



ALTER TABLE incomes_tracker
ADD category_id INT NOT NULL AFTER category;

ALTER TABLE expences_tracker
ADD category_id INT NOT NULL AFTER category;