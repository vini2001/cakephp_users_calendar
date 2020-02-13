<?php
  date_default_timezone_set('Australia/Sydney');
  include "db.php";

  $date = new DateTime();
  $now = $date->format('Y-m-d H:i:s');

  $notify1 = [];
  $notify24 = [];

  $sql = "
    SELECT '$now' as now, ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) as diff , E.* FROM events E
    WHERE E.notified_1 <> 1
    and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) > 0.9166666
    and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) < 1.0833333
    ORDER BY id desc;
  ";

  $result = $db->query($sql);
  while($row = $result->fetch_assoc()){
    echo json_encode($row) . "</br>";
    $notify1[] = $row["id"];
  }

  $sql = "
    SELECT '$now' as now, ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) as diff , E.* FROM events E
    WHERE E.notified_24 <> 1
    and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) > 23.9166666
    and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) < 24.0833333
    ORDER BY id desc;
  ";

  $result = $db->query($sql);
  while($row = $result->fetch_assoc()){
    echo json_encode($row) . "</br>";
    $notify24[] = $row["id"];
  }


  /*** ***/
  $where = "";
  foreach ($notify1 as $key => $value) {
    if($key == 0) $where .= "id = $value";
    else $where .= " or id = $value";
  }

  $sql = "
    UPDATE events SET notified_1 = 1 WHERE $where;
  ";
  echo $sql . "</br>";
  $db->query($sql);
  /*** ***/


  /*** ***/
  $where = "";
  foreach ($notify24 as $key => $value) {
    if($key == 0) $where .= "id = $value";
    else $where .= " or id = $value";
  }

  $sql = "
    UPDATE events SET notified_24 = 1 WHERE $where;
  ";
  echo $sql . "</br>";
  $db->query($sql);
  /*** ***/

  echo json_encode($notify1);
  echo json_encode($notify24);

?>
