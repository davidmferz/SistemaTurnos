
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblNoData', 'backend', 'Label / There is no data.', 'script', '2015-08-12 09:18:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no data.', 'script');

COMMIT;