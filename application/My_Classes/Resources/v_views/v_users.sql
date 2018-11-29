create view v_users as
select `users`.`user_id`     AS `user_id`,
       `users`.`user_name`   AS `user_name`,
       `users`.`email`       AS `email`,
       `users`.`password`    AS `password`,
       `roles`.`role`        AS `role`,
       `roles`.`id`          AS `role_id`,
       `users`.`auth_token`  AS `auth_token`,
       `users`.`device_id`   AS `device_id`,
       `users`.`device_type` AS `device_type`,
       `users`.`is_active`   AS `is_active`,
       `users`.`created_at`  AS `created_at`,
       `users`.`updated_at`  AS `updated_at`
from (`roles`
       join `users` on ((`users`.`role_id` = `roles`.`id`)));

