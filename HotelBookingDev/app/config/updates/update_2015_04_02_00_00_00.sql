
START TRANSACTION;

DELETE FROM `options` WHERE `key` = 'o_show_week_numbers';

COMMIT;