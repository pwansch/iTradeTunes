--     mysql -h localhost -u root -p < createdb.sql
DROP DATABASE IF EXISTS itradetunes_dev;
CREATE DATABASE itradetunes_dev;
GRANT ALL PRIVILEGES ON itradetunes_dev.* TO itradetunes_dev@localhost IDENTIFIED BY 'password';
USE itradetunes_dev;

CREATE TABLE album (
  id int(11) NOT NULL auto_increment,
  artist varchar(100) NOT NULL,
  title varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO album (artist, title)
    VALUES  ('The  Military  Wives',  'In  My  Dreams');
INSERT INTO album (artist, title)
    VALUES  ('Adele',  '21');
INSERT INTO album (artist, title)
    VALUES  ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)');
INSERT INTO album (artist, title)
    VALUES  ('Lana  Del  Rey',  'Born  To  Die');
INSERT INTO album (artist, title)
    VALUES  ('Gotye',  'Making  Mirrors');
