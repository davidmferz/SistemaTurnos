
START TRANSACTION;

UPDATE `options` SET `value`='0|1|2|3|4|5|6|7|8|9|10::1' WHERE `key`='o_theme';

COMMIT;