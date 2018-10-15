
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'system_130', 'backend', 'Label / Restriction has not been added.', 'script', '2015-08-24 10:49:22');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Restriction has not been added because there are some existing booking.', 'script');



INSERT INTO `fields` VALUES (NULL, 'system_131', 'backend', 'Label / Restriction has not been updated.', 'script', '2015-08-24 10:50:30');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Restriction has not been updated because there are some existing booking.', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblRestrictionNotAdded', 'backend', 'Label / Restriction not added', 'script', '2015-08-24 10:52:56');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Restriction not added', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblRestrictionNotUpdated', 'backend', 'Label / Restriction not updated', 'script', '2015-08-24 10:53:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Restriction not updated', 'script');



COMMIT;