
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'booking_no_rooms', 'backend', 'Bookings / No room available', 'script', '2015-05-15 09:04:43');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No room available', 'script');

COMMIT;