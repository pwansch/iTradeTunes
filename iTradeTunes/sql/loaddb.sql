-- Database script to load into and update data in the iTradeTunes database.
--
-- @copyright  2012 iTradeTunes Inc.
-- @version    $Id$
-- @since      File available since release 1.0.0.0

--
-- To load the data, uncomment the statements for the desired environment and run the
-- script using the following command:
--     mysql -h localhost -u root -p < loaddb.sql
--
-- Development
--
USE itradetunes_dev;

-- Insert mail templates
INSERT INTO mail_template VALUES (1, 1, 'member_verify', 0, 'Verify your iTradeTunes registration', 'Dear {$first_name},

We are excited that you are joining iTradeTunes!

Click on the following link:
{confirmation_url}
and begin browsing your friend\'s music.

If the link does not work, paste it into your browser.

If this email reached you in error, you can ignore it.

Regards,
Your team at iTradeTunes
support@itradetunes.com
', NULL, 0);
    
--
-- Rebuild tables
--
-- Tables with high number of inserts, updates and deletes
ALTER TABLE log ENGINE=InnoDB;
ALTER TABLE member ENGINE=InnoDB;
ALTER TABLE album ENGINE=InnoDB;
ALTER TABLE notification ENGINE=InnoDB;

-- Tables with low number of inserts, updates and deletes

-- Tables without of inserts, updates and deletes (only during code deployment)
ALTER TABLE language ENGINE=InnoDB;
ALTER TABLE mail_template ENGINE=InnoDB;

-- End of loaddb.sql    
