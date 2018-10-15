
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'install_language', 'backend', 'Lable / Language', 'script', '2017-03-27 09:22:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Language', 'script');

INSERT INTO `fields` VALUES (NULL, 'install_all_languages', 'backend', 'Lable / All languages', 'script', '2017-03-27 09:24:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'All languages', 'script');

INSERT INTO `fields` VALUES (NULL, 'install_hide_language', 'backend', 'Lable / Hide language selector', 'script', '2017-03-27 09:45:24');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Hide language selector', 'script');

COMMIT;