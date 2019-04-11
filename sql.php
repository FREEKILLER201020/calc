<?php
require("class.php");
$file  = file_get_contents(realpath(dirname(__FILE__))."/../config.json");
$config = json_decode($file, true);

// $_POST["type"]="AddPurchases";
// $_POST["type"]="Total";
// $_POST["id"]=4;
// $_POST["name"]="1";
// $_POST["price"]=1;
// $_POST["quantity"]=1;
// $_POST["member"]=1;



$date = $_POST["datee"];
$idd = $_POST["id"];
$idd2 = $_POST["id2"];
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

if ($_POST["type"]=="AddPurchase") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.AddPurchase($idd,\"$name\",$price,$quantity,$member);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         array_push($purch, $tmp);
  //     }
  // }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}
if ($_POST["type"]=="DelPurchase") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.DelPurchase($idd);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         array_push($purch, $tmp);
  //     }
  // }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="DelMember") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.DelMember($idd);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         array_push($purch, $tmp);
  //     }
  // }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="GetMembers") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetMembers();\n";
  $result = $connection->query($query);
  $array=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Member($row["id"],$row["name"]);
          array_push($array, $tmp);
      }
  }
  echo json_encode($array, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="AddMember") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.AddMember(\"$name\");\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
}

if ($_POST["type"]=="GetParticipations") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetParticipations();\n";
  $result = $connection->query($query);
  $array=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Participation($row["event_id"],$row["member_id"]);
          array_push($array, $tmp);
      }
  }
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetMembers();\n";
  $result = $connection->query($query);
  $members=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Member($row["id"],$row["name"]);
          array_push($members, $tmp);
      }
  }
  $res=array();
  foreach ($members as $member) {
    $was=0;
    foreach ($array as $row1) {
      if ($row1->event_id==$idd){
        if ($member->id==$row1->member_id){
          $was=1;
        }
      }
    }
    if ($was==0){
      array_push($res,new Participation_add($member->name,"<input type=\"button\" value=\"Участвовать\" onclick=\"MoveIn($idd,$member->id)\">"));
    }
    else{
      array_push($res,new Participation_add("<input type=\"button\" value=\"Выйти\" onclick=\"MoveOut($idd,$member->id)\">",$member->name));
    }
  }
  echo json_encode($res, JSON_UNESCAPED_UNICODE);
}
if ($_POST["type"]=="MoveIn") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.AddParticipation($idd,$idd2);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         array_push($purch, $tmp);
  //     }
  // }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="MoveOut") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.DellParticipation($idd,$idd2);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         array_push($purch, $tmp);
  //     }
  // }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="GetParticipations_event") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetParticipationsIn($idd);\n";
  $result = $connection->query($query);
  $array=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Member($row["id"],$row["name"]);
          array_push($array, $tmp);
      }
  }
  echo json_encode($array, JSON_UNESCAPED_UNICODE);
}

if ($_POST["type"]=="Total") {
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
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetMembers();\n";
  $result = $connection->query($query);
  $members=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Member_total($row["id"],$row["name"]);
          array_push($members, $tmp);
      }
  }
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetParticipations();\n";
  $result = $connection->query($query);
  $array=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Participation($row["event_id"],$row["member_id"]);
          array_push($array, $tmp);
      }
  }
  $members2=array();
  foreach ($members as $member) {
    $was=0;
    foreach ($array as $row1) {
      if ($row1->event_id==$idd){
        if ($member->id==$row1->member_id){
          $was=1;
        }
      }
    }
    if ($was==0){
      // array_push($members2,new Participation_add($member->name,"<input type=\"button\" value=\"Участвовать\" onclick=\"MoveIn($idd,$member->id)\">"));
    }
    else{
      array_push($members2,$member);
    }
  }
  $res=array();
  $summ=0;
  for($i=0;$i<count($purch);$i++){
    $summ+=$purch[$i]->total;
    $res[$purch[$i]->member]+=$purch[$i]->total;
  }
  foreach ($members2 as $member) {
    if (isset($res[$member->id])){
      $member->total=$res[$member->id];
    }
  }
  array_push($members2,new Member_total("-","Всего"));
  $members2[count($members2)-1]->total=$summ;
  // array_push($members2,new Purchase("-","Всего", "-", "-", $sum, "-"));
  echo json_encode($members2, JSON_UNESCAPED_UNICODE);
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
    $connection = new mysqli($config["hostname"].$config["port"], $config["username"], $config["password"]);
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
    return $connection;
}
 ?>
