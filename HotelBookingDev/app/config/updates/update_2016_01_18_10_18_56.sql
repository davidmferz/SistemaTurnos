
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_min_booking_nights', 'frontend', 'Label / Minimum booking nights', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Minimum booking nights are %u.', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_max_booking_nights', 'frontend', 'Label / Maximum booking nights', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Maximum booking nights are %u.', 'script');

COMMIT;