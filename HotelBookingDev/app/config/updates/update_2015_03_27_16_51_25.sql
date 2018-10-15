
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'preview_use_theme', 'backend', 'Preview / Use this theme', 'script', '2015-03-27 15:44:43');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use this theme', 'script');



INSERT INTO `fields` VALUES (NULL, 'preview_theme_current', 'backend', 'Preview / Currently in use', 'script', '2015-03-27 15:53:20');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Currently in use', 'script');



COMMIT;