<?php
require("class.php");
$file  = file_get_contents(realpath(dirname(__FILE__))."/../config.json");
$config = json_decode($file, true);

// $_POST["type"]="GetPurchases";
// $_POST["type"]="GetEvents";
// $_POST["id"]=1;


$date = $_POST["datee"];
$idd = $_POST["id"];
$name = $_POST["name"];
$price = $_POST["price"];
$quantity = $_POST["quantity"];
$member = $_POST["member"];

$d=explode("/", $date);
$date=$d[2]."-".$d[0]."-".$d[1];
if ($_POST["type"]=="GetEvents") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetEvents();\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Event($row["id"],$row["data"], $row["name"]);
          array_push($events, $tmp);
      }
  }
  echo json_encode($events, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="AddEvents") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.AddEvent(\"$date\",\"$name\");\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
}

if ($_POST["type"]=="DelEvents") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.DelEvent($idd);\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
}

if ($_POST["type"]=="GetPurchases") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetPurchases($idd);\n";
  $result = $connection->query($query);
  $purch=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
          array_push($purch, $tmp);
      }
  }
  echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}


function console_log($data)
{
    echo "<script>";
    echo "console.log(\"$data\")";
    echo "</script>";
}

function Connect($config) // Функция подключения к БД
{
  // print_r($config);
    // $connection = new mysqli($config["hostname"].$config["port"], $config["username"], $config["password"]);
    $connection = new mysqli("109.120.167.100:3306", "root", "root_pass123!");
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
    return $connection;
}
 ?>
