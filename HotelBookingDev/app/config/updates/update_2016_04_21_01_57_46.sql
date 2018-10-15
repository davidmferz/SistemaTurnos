
START TRANSACTION;

INSERT INTO `calendars` (`id`, `user_id`) VALUES
(1, 1);

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_week_start', 1, '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 8, 1, NULL),
(1, 'o_date_format', 1, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d-m-Y', 'd.m.Y (25.09.2012)|m.d.Y (09.25.2012)|Y.m.d (2012.09.25)|j.n.Y (25.9.2012)|n.j.Y (9.25.2012)|Y.n.j (2012.9.25)|d/m/Y (25/09/2012)|m/d/Y (09/25/2012)|Y/m/d (2012/09/25)|j/n/Y (25/9/2012)|n/j/Y (9/25/2012)|Y/n/j (2012/9/25)|d-m-Y (25-09-2012)|m-d-Y (09-25-2012)|Y-m-d (2012-09-25)|j-n-Y (25-9-2012)|n-j-Y (9-25-2012)|Y-n-j (2012-9-25)', 'enum', 7, 1, NULL),
(1, 'o_max_nights', 1, '30', NULL, 'int', 6, 0, NULL),
(1, 'o_timezone', 1, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 10, 1, NULL),
(1, 'o_send_email', 1, 'mail|smtp::mail', 'PHP mail()|SMTP', 'enum', 11, 1, NULL),
(1, 'o_smtp_host', 1, NULL, NULL, 'string', 12, 1, NULL),
(1, 'o_smtp_pass', 1, NULL, NULL, 'string', 15, 1, NULL),
(1, 'o_smtp_port', 1, '25', NULL, 'int', 13, 1, NULL),
(1, 'o_smtp_user', 1, NULL, NULL, 'string', 14, 1, NULL),

(1, 'o_accept_bookings', 3, '1|0::1', NULL, 'bool', 1, 1, NULL),
(1, 'o_status_if_paid', 3, 'confirmed|pending|cancelled::confirmed', 'Confirmed|Pending|Cancelled', 'enum', 9, 1, NULL),
(1, 'o_status_if_not_paid', 3, 'confirmed|pending|cancelled::pending', 'Confirmed|Pending|Cancelled', 'enum', 10, 1, NULL),
(1, 'o_price_based_on', 3, 'days|nights::days', 'Days|Nights', 'enum', 11, 1, NULL),

(1, 'o_bf_title', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 1, 1, NULL),
(1, 'o_bf_fname', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 2, 1, NULL),
(1, 'o_bf_lname', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 3, 1, NULL),
(1, 'o_bf_email', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 4, 1, NULL),
(1, 'o_bf_phone', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 5, 1, NULL),
(1, 'o_bf_arrival', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 6, 1, NULL),
(1, 'o_bf_notes', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 7, 1, NULL),
(1, 'o_bf_company', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 10, 1, NULL),
(1, 'o_bf_address', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 11, 1, NULL),
(1, 'o_bf_city', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 12, 1, NULL),
(1, 'o_bf_state', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 13, 1, NULL),
(1, 'o_bf_zip', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 14, 1, NULL),
(1, 'o_bf_country', 4, '1|2|3::2', 'No|Yes|Yes (Required)', 'enum', 15, 1, NULL),
(1, 'o_bf_captcha', 4, '1|3::3', 'No|Yes (Required)', 'enum', 16, 1, NULL),
(1, 'o_bf_terms', 4, '1|3::3', 'No|Yes (Required)', 'enum', 17, 1, NULL),

(1, 'o_disable_payments', 7, '1|0::0', NULL, 'bool', 4, 1, NULL),
(1, 'o_currency', 7, 'AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EEK|EGP|ERN|ETB|EUR|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|STD|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYU|UZS|VEF|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XTS|XXX|YER|ZAR|ZMK|ZWL::EUR', NULL, 'enum', 6, 1, NULL),
(1, 'o_deposit', 7, '10', NULL, 'float', 12, 1, NULL),
(1, 'o_deposit_type', 7, 'amount|percent::percent', 'Amount|Percent', 'enum', NULL, 0, NULL),
(1, 'o_security', 7, '0', NULL, 'float', 13, 1, NULL),
(1, 'o_tax', 7, '0', NULL, 'float', 14, 1, NULL),
(1, 'o_require_all_within', 7, '0', NULL, 'int', 15, 1, NULL),
(1, 'o_allow_paypal', 7, '1|0::1', NULL, 'bool', 16, 1, NULL),
(1, 'o_paypal_address', 7, 'paypal_seller@example.com', NULL, 'string', 17, 1, NULL),
(1, 'o_allow_authorize', 7, '1|0::0', NULL, 'bool', 18, 1, NULL),
(1, 'o_authorize_mid', 7, '3D66fj6UN', NULL, 'string', 19, 1, NULL),
(1, 'o_authorize_key', 7, '9x6kQ948YUss263y', NULL, 'string', 20, 1, NULL),
(1, 'o_authorize_hash', 7, 'abcd', NULL, 'string', 21, 1, NULL),
(1, 'o_authorize_tz', 7, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 22, 1, NULL),
(1, 'o_allow_creditcard', 7, '1|0::0', NULL, 'bool', 23, 1, NULL),
(1, 'o_allow_bank', 7, '1|0::0', NULL, 'bool', 24, 1, NULL),
(1, 'o_bank_account', 7, 'Bank of America', NULL, 'text', 25, 1, NULL),
(1, 'o_allow_cash', 7, '1|0::0', NULL, 'bool', 26, 1, NULL),
(1, 'o_thankyou_page', 7, 'http://www.phpjabbers.com/', NULL, 'string', 27, 1, NULL),

(1, 'o_last_usage_time', 99, '1400000000', NULL, 'string', NULL, 0, NULL),
(1, 'o_multi_lang', 99, '1|0::1', NULL, 'bool', NULL, 0, NULL);

COMMIT;