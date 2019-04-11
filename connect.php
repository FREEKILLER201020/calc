<?php
$file  = file_get_contents(realpath(dirname(__FILE__))."/../config.json");
$config = json_decode($file, true);
print_r($config);
$connection = new mysqli($config["hostname"].$config["port"], $config["username"], $config["password"]);
echo $config["hostname"].$config["port"]," ", $config["username"]," ", $config["password"];
// $connection = new mysqli("109.120.167.100:3306", "root", "root_pass123!");
if ($connection->connect_errno) {
    die("Unable to connect to MySQL server:".$connection->connect_errno.$connection->connect_error);
}
// Установка параметров соединения (не уверен, что это надо)
$connection->query("SET NAMES 'utf8'");
$connection->query("SET CHARACTER SET 'utf8'");
$connection->query("SET SESSION collation_connection = 'utf8_general_ci'");
if ($connection && $config["debug"]) {
    // echo("Connected to MySQL server.\n");
}
 ?>
