<?php

require('database.php');

$dbname = 'camagru';


try {

    $db = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $db->exec("CREATE DATABASE $dbname");
    $db = null;
    $db = new PDO("mysql:host=$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
    $sql_table = "CREATE TABLE users(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				login VARCHAR(255) NOT NULL UNIQUE,
				password VARCHAR(255) NOT NULL,
				email VARCHAR(255) NOT NULL UNIQUE,
				name VARCHAR(255) NOT NULL,
				role VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                sendemail TINYINT(1) NOT NULL DEFAULT '1',
                status ENUM('active','deactive') NOT NULL DEFAULT 'deactive')";
    if(!$db->query($sql_table)) {
        return false;
    }
    $sql_table = "CREATE TABLE IF NOT EXISTS screens(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                img_path VARCHAR(255) NOT NULL,
                login VARCHAR(255) NOT NULL,
                user_id INT(6) UNSIGNED NOT NULL)
                ";
    $db->exec($sql_table);
    $sql_table = "CREATE TABLE IF NOT EXISTS likes(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                screen_id INT(6) UNSIGNED NOT NULL,
                user_id INT(6) UNSIGNED NOT NULL,
                status TINYINT(1) NOT NULL DEFAULT '1',
                FOREIGN KEY(user_id) REFERENCES users(id))";
    $db->exec($sql_table);
    $sql_table = "CREATE TABLE IF NOT EXISTS comments(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(6) UNSIGNED NOT NULL ,
                screen_id INT(6) UNSIGNED NOT NULL,
                massage VARCHAR(255) NOT NULL,
                date_massage TIMESTAMP NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id))";
    $db->exec($sql_table);
} catch (PDOExeption $e){
    echo $sql_table . $e->getMessage();
}
?>