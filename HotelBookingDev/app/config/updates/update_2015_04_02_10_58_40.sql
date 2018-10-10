
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_rooms_select', 'frontend', 'Frontend / Select number of rooms', 'script', '2015-04-02 06:58:49');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select number of rooms', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_rooms_accommodate_text', 'frontend', 'Frontend / Rooms accommodate', 'script', '2015-04-02 07:06:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You''ve selected room(s) for {XA} {ADULTS} and {XC} {CHILDREN}.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_status_hint', 'backend', 'Bookings / Status (Hint)', 'script', '2015-04-02 09:58:09');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking status depends on the deposit payment status for the reservation. If deposit is paid status become "Confirmed". New bookings which have not been paid instantly, have status "Pending" by default. The system will keep as reserved the rooms assigned to a pending booking. After pending reservation time expires, bookings which haven''t been paid yet become with a status "Not confirmed". Rooms assigned to a not confirmed booking are available to be booked by other customers. You can manage booking statuses at Options menu / Bookings Tab / Options sub-tab.', 'script');



COMMIT;