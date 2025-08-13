DROP TABLE IF EXISTS `computer_history`;
CREATE TABLE `computer_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) DEFAULT NULL,
  `updated_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` varchar(50) DEFAULT NULL,
  `orig_json` text DEFAULT NULL,
  `updated_json` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

ALTER TABLE commands 
ADD COLUMN slug VARCHAR(45) AFTER id;

UPDATE commands SET slug = LOWER(REPLACE(name,' ','_'));

update schedules set parameters = REPLACE(REPLACE(REPLACE(REPLACE(parameters,')','}'),'array(','{'),"=>",":"),'\'','"');
