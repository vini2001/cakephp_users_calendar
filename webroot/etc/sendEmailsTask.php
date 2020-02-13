<?php
  require '../..' . '/vendor/autoload.php';
  date_default_timezone_set('Australia/Sydney');
  include "db.php";

  $date = new DateTime();
  $now = $date->format('Y-m-d H:i:s');

  $notify1 = [];
  $notify24 = [];
  $rows = [];

  $sql = "
    SELECT '$now' as now, ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) as diff , E.*, U.name, U.username, UI.username as email_guest, UI.name as name_guest FROM events E
	  JOIN users U ON U.id = E.user_id
	  LEFT JOIN invitation I ON I.id_event = E.id
	  LEFT JOIN users UI ON UI.id = I.id_user
    WHERE E.notified_1 <> 1
      and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) > 0.166666
      and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) < 1.0833333
    ORDER BY E.id desc;
  ";

  $result = $db->query($sql);
  while($row = $result->fetch_assoc()){
    echo json_encode($row) . "</br>";
    $rows[] = $row;
    $notify1[] = $row["id"];
  }

  $sql = "
    SELECT '$now' as now, ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) as diff , E.*, U.name, U.username, UI.username as email_guest, UI.name as name_guest FROM events E
	  JOIN users U ON U.id = E.user_id
	  LEFT JOIN invitation I ON I.id_event = E.id
	  LEFT JOIN users UI ON UI.id = I.id_user
    WHERE E.notified_24 <> 1
      and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) > 20.9166666
      and ((UNIX_TIMESTAMP(E.date) - UNIX_TIMESTAMP('$now'))/(60*60)) < 24.0833333
    ORDER BY E.id desc;
  ";

  $result = $db->query($sql);
  while($row = $result->fetch_assoc()){
    echo json_encode($row) . "</br>";
    $rows[] = $row;
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
  // echo $sql . "</br>";
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
  // echo $sql . "</br>";
  $db->query($sql);
  /*** ***/

  echo json_encode($notify1);
  echo json_encode($notify24);

  for($i = 0; $i < 20; $i++) {
    echo "\n</br>";
  }


  $lastEventId = -1;
  $lastUserId = -1;
  foreach ($rows as $key => $row) {
    $eventId = $row["id"];
    $title = $row["title"];
    $date = $row["date"];
    $time = $row["diff"] > 23 ? 24 : 1;

    $userId = $row["user_id"];
    $emailAddress = $row["username"];
    $name = $row["name"];

    $emailGuest = $row["email_guest"];
    $name_guest = $row["name_guest"];

    if($eventId != $lastEventId || $userId != $lastUserId){
      sendMail($emailAddress, $name, true, ["title" => $title, "date" => $date, "time" => $time]);
    }

    $lastEventId = $eventId;
    $lastUserId = $userId;

    if($emailGuest != "") sendMail($emailGuest, $name_guest, false, ["title" => $title, "date" => $date, "time" => $time, "invitedBy" => $name]);
  }

  function sendMail($email, $name, $isOwner, $event){
    include "config.php";
    $time = $event["time"];
    $title = $event["title"];

    $body = "Greetings, $name" . "\n</br>";
    $body .= "You have '$title' in $time " . ($time == 1 ? "hour!" : "hours!") . "\n</br>";
    if(!$isOwner){
      $body .= "You've been invited to this event by " . $event["invitedBy"] . "\n</br>";
    }
    $body .= "Date: " . $event["date"] . "\n</br>";

    echo "********* EMAIL **********" . "\n</br>";
    echo "To: $email " . "\n</br>";
    echo "Name: $name " . "\n</br>";
    echo $body;
    echo "**************************" . "\n</br>". "\n</br>". "\n</br>";


    $sendgridEmail = new \SendGrid\Mail\Mail();
    $sendgridEmail->setFrom("finn@solarquotes.com.au", "SolarQuotes");
    $sendgridEmail->setSubject("$title soon");
    $sendgridEmail->addTo($email, $name);
    $sendgridEmail->addContent(
        "text/plain", $body
    );
    $sendgridEmail->addContent(
        "text/html", $body
    );
    $sendgrid = new \SendGrid($SENDGRID_API_KEY);

    try {
        $response = $sendgrid->send($sendgridEmail);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

  }


?>
