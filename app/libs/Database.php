<?php

/**
 * Class kết nối CSDL
 */
class Database extends PDO {
    function __construct()
    {
        /**
         * Kết nối đến CSDL sử dụng PDO
         */
        parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_TABLE, DB_USER, DB_PASS, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        ));
    }
}