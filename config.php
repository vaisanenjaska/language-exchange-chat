<?php
    define("DB_SERVER",  getenv('MYSQL_HOST'));
    define("DB_USER",    getenv('MYSQL_USER'));
    define("DB_PASSWORD",getenv('MYSQL_PASS'));
    define("DB_NAME",    getenv('MYSQL_DB'));
    $db = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
    if (!$db) {
        die('Databse connection error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }
    session_start();
?>
