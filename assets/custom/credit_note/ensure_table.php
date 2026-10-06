<?php

if (!function_exists('ensure_credit_note_table')) {
    function ensure_credit_note_table($db) {
        $sql = "CREATE TABLE IF NOT EXISTS `credit_note` (
            `id` int NOT NULL AUTO_INCREMENT,
            `client` varchar(255) NOT NULL DEFAULT '',
            `sales_invoice` varchar(255) NOT NULL DEFAULT '',
            `cn_no` varchar(100) NOT NULL DEFAULT '',
            `cn_date` date DEFAULT NULL,
            `state` varchar(100) NOT NULL DEFAULT '',
            `items` longtext,
            `addons` text,
            `total` varchar(32) NOT NULL DEFAULT '0',
            `tax` text,
            `status` varchar(20) NOT NULL DEFAULT '0',
            `log_user` varchar(100) NOT NULL DEFAULT '',
            `log_date` date DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `cn_no` (`cn_no`),
            KEY `cn_date` (`cn_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        return $db->query($sql) === true;
    }
}
