-- Database script to load test data into the iTradeTunes database.
--
-- @copyright  2012 iTradeTunes Inc.
-- @version    $Id$
-- @since      File available since release 1.0.0.0

--
-- To load the test data, uncomment the statements for the desired environment and run the
-- script using the following command:
--     mysql -h localhost -u root -p < loadtestdb.sql
--
-- Development
--
USE itradetunes_dev;

-- Delete all loaded tables
DELETE FROM member;
DELETE FROM album;

-- Insert into member table
INSERT INTO member (id, first_name, last_name, email_address, email_address_private, email_address_verified, password_encrypted, image, thumbnail_image, about, interests, status, date_of_last_logon, number_of_logons, number_of_unsuccessful_logons, date_created, date_updated) VALUES
  (1, 'Sonja', 'Fenwick', 'sfenwick@itradetunes.com', 1, 2, '7c6a180b36896a0a8c02787eeafb0e4c', NULL, NULL, 'I am a regular kind of a gal.', 'I love classical music.', 1, '2012-11-30 03:15:01', 1, 0, '2012-10-30 18:03:38', '2012-11-10 14:08:28');

  -- Insert into album table
INSERT INTO album (artist, title) VALUES
    ('The  Military  Wives',  'In  My  Dreams'),
    ('Adele',  '21'),
    ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)'),
    ('Lana  Del  Rey',  'Born  To  Die'),
    ('Gotye',  'Making  Mirrors');
    
-- End of loadtestdb.sql    
