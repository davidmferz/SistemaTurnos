
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_guests', 'frontend', 'Label / Guests', 'script', '2017-04-21 02:58:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Guests', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_validate_guests', 'frontend', 'Frontend / Validate: Guests', 'script', '2017-04-21 02:59:22');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Guests is required', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_price_from', 'frontend', 'Label / Price from', 'script', '2017-04-21 03:30:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Price from', 'script');

COMMIT;