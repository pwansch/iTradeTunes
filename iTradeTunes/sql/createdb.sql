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
-- Member table
--
CREATE TABLE member (
  id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(32) NOT NULL,
  last_name varchar(32) NOT NULL,
  email_address varchar(96) NOT NULL,
  email_address_private int(1) DEFAULT 1 NOT NULL, -- 0 not private, 1 private
  email_address_verified int(1) DEFAULT 0 NOT NULL, -- 0 not verified, 1 verification pending, 2 verified
  password_encrypted varchar(64) NOT NULL,
  image varchar(64) DEFAULT NULL, -- member provided
  thumbnail_image varchar(64) DEFAULT NULL, -- member provided
  about varchar(255) DEFAULT NULL,
  interests varchar(255) DEFAULT NULL,
  goto varchar(255) DEFAULT NULL,
  status int(1) DEFAULT 1 NOT NULL, -- 1 active, 0 locked
  date_of_last_logon datetime DEFAULT NULL,
  number_of_logons int(5) DEFAULT 0,
  number_of_unsuccessful_logons int(5) DEFAULT 0,
  date_created datetime NOT NULL,
  date_updated datetime DEFAULT NULL,
  PRIMARY KEY (id),
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

-- End of createdb.sql