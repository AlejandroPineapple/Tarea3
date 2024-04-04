<?php

$db_host = 'localhost';
$db_username = 'admin';
$db_password = '123';
$db_database = 'autos';

$db = new mysqli($db_host, $db_username, $db_password, $db_database);
mysqli_query($db,"SET NAMES 'utf8'");

if ($db->connect_errno > 0 )
{
    die('No es posibleconectarse a la base de datos: ' . $db->connect_errno);
}
?>