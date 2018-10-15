
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_start_new_booking', 'backend', 'Frontend / Start new reservation', 'script', '2015-04-03 14:42:26');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'start new reservation', 'script');

COMMIT;