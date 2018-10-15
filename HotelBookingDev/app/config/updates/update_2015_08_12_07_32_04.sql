
START TRANSACTION;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "error_bodies_ARRAY_ART10");
UPDATE `multi_lang` SET `content` = 'Here you can set selected rooms as unavailable for a predefined period of time. There are 3 different statuses that you can choose from to mark the reason for a room being unavailable. "External booking" - set a room with that status if it''s booked via another channel (OTA). "Stop from web" - rooms with this status will not be available for booking via the hotel booking engine front end. You, being administrator, will still be able to make bookings via the back-end. "Unavailable" - set this status if room is unavailable for any other reason - for example renovation or maintenance. Rooms with "Unavailable" status won''t be available for booking both through the front-end and the back-end.' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

COMMIT;