CREATE DATABASE `demo`;

CREATE TABLE `demo`.`demo` (
`id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`unique_key` VARCHAR( 255 ) NOT NULL ,
`name` VARCHAR( 255 ) NULL DEFAULT NULL ,
`summary` TEXT NULL DEFAULT NULL ,
`date` DATETIME NULL DEFAULT NULL ,
UNIQUE (`unique_key`));

INSERT INTO `demo`.`demo` 
(`id`, `unique_key`, `name`, `summary`, `date`)
VALUES (NULL, 'foo', 'foo', 'foo bar test', '2008-11-05 00:00:00'), 
(NULL , 'bar', 'bar', 'test foo bar', '2009-11-05 00:00:00');