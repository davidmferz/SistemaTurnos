
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblFieldRequired', 'backend', 'Label / This field is required.', 'script', '2015-08-14 02:33:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This field is required.', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblEmailInvalid', 'backend', 'Label / Email is invalid.', 'script', '2015-08-14 02:42:59');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email is invalid.', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblPreviousWeek', 'backend', 'Label / Previous week', 'script', '2015-08-14 03:03:52');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous week', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblPreviousDate', 'backend', 'Label / Previous date', 'script', '2015-08-14 03:04:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Previous date', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblNextDate', 'backend', 'Label / Next date', 'script', '2015-08-14 03:04:51');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next date', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblNextWeek', 'backend', 'Label / Next week', 'script', '2015-08-14 03:05:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Next week', 'script');



INSERT INTO `fields` VALUES (NULL, 'gridActionEmpty', 'backend', 'Grid / No records selected', 'script', '2015-08-14 03:33:42');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No records selected', 'script');



INSERT INTO `fields` VALUES (NULL, 'gridActionEmptyBody', 'backend', 'Grid / You need to select at least a single record', 'script', '2015-08-14 03:34:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to select at least a single record.', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblCheckAll', 'backend', 'Label / Check all', 'script', '2015-08-14 03:45:09');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Check all', 'script');



INSERT INTO `fields` VALUES (NULL, 'lblUnCheckAll', 'backend', 'Label / Uncheck all', 'script', '2015-08-14 03:45:35');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Uncheck all', 'script');



COMMIT;