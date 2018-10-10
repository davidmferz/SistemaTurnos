
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'booking_existing_client', 'backend', 'Label / Existing client', 'script', '2017-03-27 08:18:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Existing client', 'script');

INSERT INTO `fields` VALUES (NULL, 'booking_search_hint', 'backend', 'Label / Search by First name, Last name, Phone, and Email', 'script', '2017-03-27 08:20:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Search by First name, Last name, Phone, and Email', 'script');

COMMIT;