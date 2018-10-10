START TRANSACTION;

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_welcome_done_1', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL),
(1, 'o_welcome_done_2', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL),
(1, 'o_welcome_done_3', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL),
(1, 'o_welcome_done_4', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL),
(1, 'o_welcome_done_5', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL),
(1, 'o_welcome_done_6', 99, '1|0::0', NULL, 'bool', NULL, 0, NULL);

COMMIT;