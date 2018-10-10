
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'system_132', 'backend', 'Label / Reservations are based on nights', 'script', '2017-04-21 04:01:53');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Reservations are based on nights, so you cannot select the same date.', 'script');

COMMIT;