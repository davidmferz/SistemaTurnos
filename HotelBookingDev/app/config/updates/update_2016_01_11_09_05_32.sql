
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblNightsValidation', 'backend', 'Label / Max nights cannot be less than min nights.', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Max nights cannot be less than min nights.', 'script');

COMMIT;