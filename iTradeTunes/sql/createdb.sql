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
-- Album table
--
CREATE TABLE album (
  id int(11) NOT NULL auto_increment,
  artist varchar(100) NOT NULL,
  title varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- End of createdb.sql