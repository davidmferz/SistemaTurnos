
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'limit_update', 'backend', 'Label / Update limit', 'script', '2015-10-15 07:03:18');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update limit', 'script');

COMMIT;