-- Database script to create the iTradeTunes database and load domain data.
--
-- @copyright  2012 iTradeTunes Inc.
-- @version    $Id$
-- @since      File available since release 1.0.0.0

--
-- To create the itradetunes schema and database user, uncomment the statements for the
-- desired environment and run the script using the following command:
--     mysql -h localhost -u root -p < createdb.sql
--
-- Development
--
DROP DATABASE IF EXISTS itradetunes_dev;
CREATE DATABASE itradetunes_dev;
GRANT ALL PRIVILEGES ON itradetunes_dev.* TO itradetunes_dev@localhost IDENTIFIED BY 'password';
USE itradetunes_dev;

--
-- Language table
--
CREATE TABLE language (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(32)  NOT NULL,
  code char(2) NOT NULL,
  image varchar(64) DEFAULT NULL, -- stock image
  PRIMARY KEY (id),
  KEY idx_language_1 (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert domain data
INSERT INTO language VALUES (1, 'English', 'en', NULL);

--
-- Member table
--
CREATE TABLE member (
  id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(32) NOT NULL,
  last_name varchar(32) NOT NULL,
  language_id int DEFAULT 1 NOT NULL,
  email_address varchar(96) NOT NULL,
  email_address_private int(1) DEFAULT 1 NOT NULL, -- 0 not private, 1 private
  email_address_verified int(1) DEFAULT 0 NOT NULL, -- 0 not verified, 1 verification pending, 2 verified
  password_encrypted varchar(64) NOT NULL,
  image varchar(64) DEFAULT NULL, -- member provided
  thumbnail_image varchar(64) DEFAULT NULL, -- member provided
  about varchar(255) DEFAULT NULL,
  interests varchar(255) DEFAULT NULL,
  status int(1) DEFAULT 1 NOT NULL, -- 1 active, 0 locked
  date_of_last_logon datetime DEFAULT NULL,
  number_of_logons int(5) DEFAULT 0,
  number_of_unsuccessful_logons int(5) DEFAULT 0,
  date_created datetime NOT NULL,
  date_updated datetime DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (language_id) REFERENCES language(id) ON DELETE RESTRICT,
  CONSTRAINT uk_member_1 unique(email_address),
  KEY idx_member_1 (id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Album table
--
CREATE TABLE album (
  id int(11) NOT NULL AUTO_INCREMENT,
  artist varchar(100) NOT NULL,
  title varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Log table
--
CREATE TABLE log (
  id int(11) NOT NULL AUTO_INCREMENT,
  timestamp datetime NOT NULL,
  level varchar(6) NOT NULL,
  message varchar(8192) DEFAULT NULL,
  code int(1) NOT NULL, -- 0 default, 1 account locked, 2 mail
  email_address varchar(96) NOT NULL,
  ip_address varchar(39) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_log_1 (level, timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- All outgoing emails are inserted into the notification table and are sent by a cron-triggered batch
-- program. Outgoing email that has been sent successfully is pruned after a number of days.
--
CREATE TABLE notification (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(65) NOT NULL,
  email_address varchar(96) NOT NULL,
  subject varchar(128) DEFAULT '' NOT NULL,
  format int(1) DEFAULT 0 NOT NULL, -- 0 text, 1 html and text
  text mediumtext NOT NULL,
  html text DEFAULT NULL,
  status int(1) DEFAULT 1 NOT NULL, -- 1 unsent, 0 sent, -1 error
  retries int(1) DEFAULT 0 NOT NULL,
  message varchar(512) DEFAULT NULL,
  date_created datetime NOT NULL,
  date_updated datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_notification_1 (status, date_created),
  KEY idx_notification_2 (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Mail template table
--
CREATE TABLE mail_template (
  id int(11) NOT NULL AUTO_INCREMENT,
  language_id int DEFAULT 1 NOT NULL,
  title varchar(32) NOT NULL,
  format int(1) DEFAULT 0 NOT NULL, -- 0 text, 1 html and text
  subject varchar(128) DEFAULT '' NOT NULL,
  text text NOT NULL,
  html text DEFAULT NULL,
  log int(1) DEFAULT 0 NOT NULL, -- 0 no, 1 yes
  PRIMARY KEY (id),
  FOREIGN KEY (language_id) REFERENCES language(id) ON DELETE RESTRICT,
  CONSTRAINT uk_mail_template_1 unique(language_id, title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- End of createdb.sql