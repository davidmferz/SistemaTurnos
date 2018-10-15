
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_accommodation', 'backend', 'Frontend / Accommodation', 'script', '2015-05-13 13:18:47');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This room accommodates:', 'script');

COMMIT;