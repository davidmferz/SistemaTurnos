
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'dash_tab_arrivals', 'backend', 'Dashboard / Tab Arrivals', 'script', '2015-03-30 07:22:26');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrivals', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_tab_departures', 'backend', 'Dashboard / Tab Departures', 'script', '2015-03-30 07:22:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departures', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_tab_latest', 'backend', 'Dashboard / Tab Latest', 'script', '2015-03-30 07:23:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Latest', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_tab_upcoming', 'backend', 'Dashboard / Tab Upcoming Bookings', 'script', '2015-03-30 07:23:22');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Upcoming Bookings', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_tab_past', 'backend', 'Dashboard / Tab Past Bookings', 'script', '2015-03-30 07:23:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Past Bookings', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_no_arrivals', 'backend', 'Dashboard / No arrivals today', 'script', '2015-03-30 07:24:31');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No arrivals today.', 'script');



INSERT INTO `fields` VALUES (NULL, 'dash_no_departures', 'backend', 'Dashboard / No departures today', 'script', '2015-03-30 07:24:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No departures today.', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AU11', 'arrays', 'error_titles_ARRAY_AU11', 'script', '2015-03-30 08:40:16');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Users', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AU11', 'arrays', 'error_bodies_ARRAY_AU11', 'script', '2015-03-30 08:41:55');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can view system users. Under the Add user tab you can add a new user. To Edit a user just click on the edit icon next to it.', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AU12', 'arrays', 'error_titles_ARRAY_AU12', 'script', '2015-03-30 08:48:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add an User', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AU12', 'arrays', 'error_bodies_ARRAY_AU12', 'script', '2015-03-30 09:09:35');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Enter role (e.g. admin or editor), email and password for the user. You can also specify the name and phone of the user.', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AU13', 'arrays', 'error_titles_ARRAY_AU13', 'script', '2015-03-30 09:11:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update an User', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AU13', 'arrays', 'error_bodies_ARRAY_AU13', 'script', '2015-03-30 09:12:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update role (e.g. admin or editor), email, password and status of the user. You can also specify the name and phone of the user.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_rooms_extras', 'backend', 'Bookings / Rooms & Extras', 'script', '2015-03-30 09:55:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Rooms and extras', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_arrival_date_time', 'backend', 'Bookings / Arrival date & time', 'script', '2015-03-30 10:00:11');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival date / time', 'script');



INSERT INTO `fields` VALUES (NULL, 'rooms_price', 'backend', 'Rooms / Price', 'script', '2015-03-30 12:28:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Price', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_amount_tooltip', 'backend', 'Bookings / Amount tooltip', 'script', '2015-03-30 12:56:37');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '{AMOUNT} is the correct amount according to the lastest changes you have made to this reservation. If you wish to update the amount, please click on ''{BUTTON}'' button.', 'script');



COMMIT;