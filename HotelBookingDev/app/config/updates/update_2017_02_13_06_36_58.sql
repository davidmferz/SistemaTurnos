
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'enum_arr_ARRAY_days', 'arrays', 'enum_arr_ARRAY_days', 'script', '2017-02-13 06:36:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Days', 'script');

INSERT INTO `fields` VALUES (NULL, 'enum_arr_ARRAY_nights', 'arrays', 'enum_arr_ARRAY_nights', 'script', '2017-02-13 06:36:42');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Nights', 'script');

COMMIT;