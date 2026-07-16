ALTER TABLE `users`
  ADD COLUMN `allowed_fy` varchar(100) DEFAULT NULL;

UPDATE `users`
SET `allowed_fy` = (SELECT `year` FROM `year` WHERE `current` = '1' LIMIT 1)
WHERE `userlevel` <> 'sadmin_df56fdg' AND (`allowed_fy` IS NULL OR `allowed_fy` = '');
