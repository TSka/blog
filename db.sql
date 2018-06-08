CREATE TABLE IF NOT EXISTS `articles` (
	`id` int(10) NOT NULL auto_increment,
	`title` varchar(255),
	`text` text,
	`created` datetime,
	`image` text,
	PRIMARY KEY( `id` )
);