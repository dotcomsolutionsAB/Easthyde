<?php

if (!function_exists('ensure_debit_note_table')) {
    function ensure_debit_note_table($db) {
        $sql = "CREATE TABLE IF NOT EXISTS `debit_note` (
            `id` int NOT NULL AUTO_INCREMENT,
            `supplier` varchar(255) NOT NULL DEFAULT '',
            `purchase_invoice` varchar(255) NOT NULL DEFAULT '',
            `dn_pi_date` date DEFAULT NULL,
            `dn_no` varchar(100) NOT NULL DEFAULT '',
            `dn_date` date DEFAULT NULL,
            `state` varchar(100) NOT NULL DEFAULT '',
            `items` longtext,
            `addons` text,
            `total` varchar(32) NOT NULL DEFAULT '0',
            `tax` text,
            `status` varchar(20) NOT NULL DEFAULT '0',
            `log_user` varchar(100) NOT NULL DEFAULT '',
            `log_date` date DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `dn_no` (`dn_no`),
            KEY `dn_date` (`dn_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        return $db->query($sql) === true;
    }
}
