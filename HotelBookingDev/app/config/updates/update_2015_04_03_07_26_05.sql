
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_btn_close', 'frontend', 'Search / Button close', 'script', '2015-04-03 06:42:59');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Close', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_terms_label', 'frontend', 'Frontend / Terms', 'script', '2015-04-03 06:44:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Terms', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_title', 'frontend', 'Frontend / Validate: Title', 'script', '2015-04-03 06:53:36');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_fname', 'frontend', 'Frontend / Validate: First Name', 'script', '2015-04-03 06:55:47');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'First Name is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_lname', 'frontend', 'Frontend / Validate: Last Name', 'script', '2015-04-03 06:55:38');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last Name is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_phone', 'frontend', 'Frontend / Validate: Phone', 'script', '2015-04-03 06:55:28');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Phone is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_email', 'frontend', 'Frontend / Validate: Email', 'script', '2015-04-03 06:55:18');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_notes', 'frontend', 'Frontend / Validate: Requirements', 'script', '2015-04-03 06:56:30');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Additional requirements are required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_company', 'frontend', 'Frontend / Validate: Company', 'script', '2015-04-03 07:01:13');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Company name is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_address', 'frontend', 'Frontend / Validate: Address', 'script', '2015-04-03 07:01:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Address is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_city', 'frontend', 'Frontend / Validate: City', 'script', '2015-04-03 07:01:47');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'City is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_state', 'frontend', 'Frontend / Validate: State', 'script', '2015-04-03 07:02:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'County/Region/State is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_zip', 'frontend', 'Frontend / Validate: Zip', 'script', '2015-04-03 07:02:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Postcode/Zip is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_country', 'frontend', 'Frontend / Validate: Country', 'script', '2015-04-03 07:02:56');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Country is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_email_invalid', 'frontend', 'Frontend / Validate: Email invalid', 'script', '2015-04-03 07:03:49');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Valid email is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_captcha', 'frontend', 'Frontend / Validate: Captcha', 'script', '2015-04-03 07:04:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_captcha_remote', 'frontend', 'Frontend / Validate: Captcha remote', 'script', '2015-04-03 07:04:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha doesn''t match', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_terms', 'frontend', 'Frontend / Validate: Terms', 'script', '2015-04-03 07:15:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please confirm that you''ve read and agree to the Booking Conditions', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_arrival', 'frontend', 'Frontend / Validate: Arrival Time', 'script', '2015-04-03 07:06:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival Time is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_payment', 'frontend', 'Frontend / Validate: Payment method', 'script', '2015-04-03 07:08:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Payment method is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_cc_num', 'frontend', 'Frontend / Validate: CC Number', 'script', '2015-04-03 07:10:30');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Number is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_cc_code', 'frontend', 'Frontend / Validate: CC Code', 'script', '2015-04-03 07:10:46');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Code is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_cc_type', 'frontend', 'Frontend / Validate: CC Type', 'script', '2015-04-03 07:11:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Type is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_cc_exp_month', 'frontend', 'Frontend / Validate: CC Exp. month', 'script', '2015-04-03 07:11:36');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Expire month is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_cc_exp_year', 'frontend', 'Frontend / Validate: CC Exp. year', 'script', '2015-04-03 07:11:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CC Expire year is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_promo', 'frontend', 'Frontend / Validate: Promo code', 'script', '2015-04-03 07:16:37');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Promo code is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_date_from', 'frontend', 'Frontend / Validate: Check-in date', 'script', '2015-04-03 07:18:37');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Check-in date is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_date_to', 'frontend', 'Frontend / Validate: Check-out date', 'script', '2015-04-03 07:18:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Check-out date is required', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_validate_adults', 'frontend', 'Frontend / Validate: Adults', 'script', '2015-04-03 07:19:21');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Adults is required', 'script');



COMMIT;