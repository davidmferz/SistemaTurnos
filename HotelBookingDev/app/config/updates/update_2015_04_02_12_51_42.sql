
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'dash_view_all_arrivals', 'backend', 'Dashboard / View All Arrivals', 'script', '2015-04-02 11:28:28');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'View All Arrivals', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_view_all_departures', 'backend', 'Dashboard / View All Departures', 'script', '2015-04-02 11:28:43');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'View All Departures', 'script');



COMMIT;