<?php
require("class.php");
$file  = file_get_contents(realpath(dirname(__FILE__))."/../config.json");
$config = json_decode($file, true);

// $_POST["type"]="GetPurchases";
// $_POST["type"]="Total";
// $_POST["id"]=3;
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
$amm = $_POST["amm"];

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

if ($_POST["type"]=="Eat") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.AddEat_drink($idd,$idd2);\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
}

if ($_POST["type"]=="UnEat") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.DelEat_drink($idd,$idd2);\n";
  $result = $connection->query($query);
  $events=array();
  mysqli_close($connection);
}

if ($_POST["type"]=="GetPurchases") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetParticipations();\n";
  $result = $connection->query($query);
  $array=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          if ($row["event_id"]==$idd){
            $tmp= new Participation($row["event_id"],$row["member_id"]);
            array_push($array, $tmp);
          }
      }
  }
  // print_r($array);
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.GetPurchases($idd);\n";
  $result = $connection->query($query);
  $purch=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], WhoIs($row["buyer"],$config));
          $connection2=Connect($config);
          $query2 = "call {$config["base_database"]}.GetEat_drink({$row["id"]});\n";
          $result2 = $connection2->query($query2);
          $eat=array();
          mysqli_close($connection2);
          if ($result2->num_rows > 0) {
              while ($row2 = $result2->fetch_assoc()) {
                  // print_r($row);
                  // if ($row["event_id"]==$idd){
                    $eat2= new Eat($row2["purchase_id"],$row2["member_id"]);
                    array_push($eat, $eat2);
                  // }
              }
          }
          // print_r($eat);
          foreach ($array as $arr) {
            $was=0;
            foreach ($eat as $eat1) {
              // print_r($eat1);
              if ($arr->member_id==$eat1->member){
                $was=1;
              }
            }
            $a=WhoIs($arr->member_id,$config);
            $a2=$arr->member_id;
            if ($was==1){
              $tmp->$a="<input type=\"checkbox\" id=\"chechbox+{$row["id"]}+$a2\" checked=\"1\">";
              $tmp->$a.="<input type=\"button\" value=\"Не ел\" onclick=\"UnEat({$row["id"]},$a2)\">";

            }
            else{
              $tmp->$a="<input type=\"checkbox\" id=\"chechbox+{$row["id"]}+$a2\">";
              $tmp->$a.="<input type=\"button\" value=\"Ел\" onclick=\"Eat({$row["id"]},$a2)\">";
            }
          }
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
      // array_push($res,new Participation_add($member->name,"<input type=\"button\" value=\"Участвовать\" onclick=\"MoveIn($idd,$member->id)\">"));
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

if ($_POST["type"]=="SetPayed") {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.SetPayed($idd,$idd2,$amm);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  // if ($result->num_rows > 0) {
  //     while ($row = $result->fetch_assoc()) {
  //         // print_r($row);
  //         return $row["name"];
  //         // $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
  //         // array_push($purch, $tmp);
  //     }
  // }
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
          $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], WhoIs($row["buyer"],$config));
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
          $tmp->payed=$row["payed"];
          array_push($array, $tmp);
      }
  }
  $connection2=Connect($config);
  $query2 = "call {$config["base_database"]}.GetEat_drink_all();\n";
  $result2 = $connection2->query($query2);
  $eat=array();
  mysqli_close($connection2);
  if ($result2->num_rows > 0) {
      while ($row2 = $result2->fetch_assoc()) {
          // print_r($row);
          // if ($row["event_id"]==$idd){
            $tmp= new Eat($row2["purchase_id"],$row2["member_id"]);
            array_push($eat, $tmp);
          // }
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
  // print_r($eat);
  // print_r($members2);
  // print_r($purch);
  // !!!
  $res=array();
  $res2=array();
  $total_eated=array();
  $summ=0;
  $one=0;
  for($i=0;$i<count($purch);$i++){
    $summ+=$purch[$i]->total;
    $res[$purch[$i]->member]+=$purch[$i]->total;
    $res2[$purch[$i]->id]+=$purch[$i]->total;
  }
  foreach ($eat as $peac) {
    $total_eated[$peac->purch]++;
  }
  // print_r($total_eated);
  // print_r($res);
  // $eated
  $one=$summ/count($members2);
  foreach ($members2 as $member) {
    if (!isset($res[$member->id])){
      $res[$member->id]=0;
    }
    $member->total=$res[$member->name];
    foreach ($eat as $peac) {
      $eated=0;
      if ($peac->member==$member->id){
        $eated=1;
      }
      // echo $eated.PHP_EOL;
      if ($eated==1){
        // print_r($peac);
        // print_r($res);
        // print_r($purch);
        // print_r($total_eated);
        $member->cashback-=$res2[$peac->purch]/$total_eated[$peac->purch];
        // $member->cashback=$member->total-$one;
      }
    }
    foreach ($array as $arr) {
      if (($member->id==$arr->member_id)&&($idd==$arr->event_id)){
        $text="<input type=\"text\" value=\"$arr->payed\" id=\"pay_".$idd."_".$member->id."\">";
        $button="<input type=\"button\" value=\"Pay\" onclick=\"Pay($idd,$member->id)\">";
        // $member->payed=$arr->payed;
        $member->payed=$text.$button;
      }
    }
    $member->cashback+=$member->total;

    //   $member->total=$res[$member->id];
    //   // $member->cashback=$member->total-$one;
  }
  // !!!
  array_push($members2,new Member_total("-","Всего"));
  $members2[count($members2)-1]->total=$summ;
  $members2[count($members2)-1]->cashback="Avr spend: ".$one;
  // array_push($members2,new Purchase("-","Всего", "-", "-", $sum, "-"));
  echo json_encode($members2, JSON_UNESCAPED_UNICODE);
}

function WhoIs ($idd,$config) {
  $connection=Connect($config);
  $query = "call {$config["base_database"]}.WhoIs($idd);\n";
  // echo $query;
  $result = $connection->query($query);
  // $purch=array();
  mysqli_close($connection);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // print_r($row);
          return $row["name"];
          // $tmp= new Purchase($row["id"],$row["name"], $row["price"], $row["quantity"], $row["total"], $row["buyer"]);
          // array_push($purch, $tmp);
      }
  }
  // echo json_encode($purch, JSON_UNESCAPED_UNICODE);
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
