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
DELETE FROM album;

-- Insert into album table
INSERT INTO album (artist, title) VALUES
    ('The  Military  Wives',  'In  My  Dreams'),
    ('Adele',  '21'),
    ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)'),
    ('Lana  Del  Rey',  'Born  To  Die'),
    ('Gotye',  'Making  Mirrors');
    
-- End of loadtestdb.sql    
