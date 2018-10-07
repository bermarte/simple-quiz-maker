-- SMALLINT UNSIGNED => max is 65535
-- 1 documento 50*25(1250)
-- 50 documents 1250*50 = 62500 max.
-- SMALLINT(5) UNSIGNED
-- max input for question: 600 characters
-- max title, user,: 50 characters
-- max 25 answers for a question
-- max 50 questions for 1 document

-- answers and questions: max 600 carachters
CREATE DATABASE IF NOT EXISTS simple_quiz;
USE simple_quiz;
DROP TABLE IF EXISTS simple_quiz.percentage;
DROP TABLE IF EXISTS simple_quiz.randomness;
DROP TABLE IF EXISTS simple_quiz.choices;
DROP TABLE IF EXISTS simple_quiz.questions;
DROP TABLE IF EXISTS simple_quiz.group;
DROP TABLE IF EXISTS simple_quiz.document;
DROP TABLE IF EXISTS simple_quiz.users;
-- Table structure for table users
-- DROP TABLE IF EXISTS simple_quiz.users; 
 CREATE TABLE IF NOT EXISTS users (  
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,  
  username varchar(100) NOT NULL,  
  password varchar(100) NOT NULL,
  email varchar(320) NOT NULL,
  PRIMARY KEY (id)  
 ) ENGINE=InnoDB CHARSET=utf8;
-- insert username and password for first access
INSERT INTO simple_quiz.users (username, password) VALUES  
 ('admin', 'password');
-- Hash password
UPDATE users
SET password = MD5(password)
WHERE id = 1;
-- create table document
-- this is the html document
-- DROP TABLE IF EXISTS simple_quiz.document;
CREATE TABLE IF NOT EXISTS document (  
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,  
  title varchar(50) NOT NULL,  
  coach varchar(50) NOT NULL,
  mail varchar(320) NOT NULL,
  PRIMARY KEY (id)  
 ) ENGINE=InnoDB CHARSET=utf8;
-- Dump data
-- example of INSERT
-- INSERT INTO simple_quiz.document (title,coach,mail) VALUES  
-- ('test','test','test');
-- create table group
-- contains category/subject
-- and numbers of questions
-- DROP TABLE IF EXISTS simple_quiz.group;
CREATE TABLE IF NOT EXISTS simple_quiz.group (
   id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
   category VARCHAR(50) NOT NULL,  
   numQuestions SMALLINT(5) UNSIGNED NOT NULL,
   id_document SMALLINT(5) UNSIGNED,
   PRIMARY KEY (id),
   FOREIGN KEY(id_document) REFERENCES document(id)
) ENGINE=InnoDB CHARSET=utf8;
-- create table questions
-- DROP TABLE IF EXISTS simple_quiz.questions;
CREATE TABLE IF NOT EXISTS simple_quiz.questions (
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  question VARCHAR(600) NOT NULL,
  numAnswers SMALLINT(5) UNSIGNED  NOT NULL,
  id_group SMALLINT(5) UNSIGNED,
  PRIMARY KEY (id),
  FOREIGN KEY(id_group) REFERENCES simple_quiz.group(id)
) ENGINE=InnoDB CHARSET=utf8;
-- create table answers
-- DROP TABLE IF EXISTS simple_quiz.choices;
CREATE TABLE IF NOT EXISTS simple_quiz.choices(
 id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
 answer VARCHAR(600) NOT NULL,
 id_questions SMALLINT(5) UNSIGNED,
 isRight BOOLEAN DEFAULT 0,
 PRIMARY KEY (id),
 FOREIGN KEY(id_questions) REFERENCES questions(id)
) ENGINE=InnoDB CHARSET=utf8;
-- create table randomness
-- DROP TABLE IF EXISTS simple_quiz.randomness;
CREATE TABLE IF NOT EXISTS simple_quiz.randomness (
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  isRandom BOOLEAN DEFAULT NULL,
  id_titleR SMALLINT(5) UNSIGNED,
  PRIMARY KEY (id),
  FOREIGN KEY(id_titleR) REFERENCES document(id)
) ENGINE=InnoDB CHARSET=utf8;
-- create table percentage
-- DROP TABLE IF EXISTS simple_quiz.percentage;
CREATE TABLE IF NOT EXISTS simple_quiz.percentage (
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  percent SMALLINT(5) UNSIGNED,-- max 100 for 100%
  id_titleP SMALLINT(5) UNSIGNED UNSIGNED,
  PRIMARY KEY (id),
  FOREIGN KEY(id_titleP) REFERENCES document(id)
) ENGINE=InnoDB CHARSET=utf8;
UPDATE users
SET email = "email";