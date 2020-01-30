<?php
  //echo $daysInTheMonth . "/" . $daysInotherMonth . "<br/>";
?>

<head>
  <?= $this->Html->css('calendar.css') ?>
</head>

<body>
  <table id="calendar">
  <caption>
    <?php echo $this->Html->link("‹",["action" => "/".($plusMonths-1).""], ['class' => 'previous round']); ?>
    <?php echo $today; ?>
    <?php echo $this->Html->link("›",["action" => "/".($plusMonths+1).""], ['class' => 'previous round']); ?>
  </caption>
  <tr class="weekdays">
    <th scope="col">Sunday</th>
    <th scope="col">Monday</th>
    <th scope="col">Tuesday</th>
    <th scope="col">Wednesday</th>
    <th scope="col">Thursday</th>
    <th scope="col">Friday</th>
    <th scope="col">Saturday</th>
  </tr>

  <?php
    foreach ($days as $key => $dayMap) {
      if($key % 7 == 0){
        if($key != 6) echo '</tr>';
        echo '<tr class="days">';
      }
      ?>
      <td class="day <?php if(isset($dayMap["otherMonth"])) echo "other-month"; ?>">
        <div class="date"><?php echo $dayMap["day"]; ?></div>
        <!--<div class="event">
          <div class="event-desc">
            Group Project meetup
          </div>
          <div class="event-time">
            6:00pm to 8:30pm
          </div>
        </div>-->
      </td>
      <?php
    }
    echo '</tr>';
  ?>
</table>

<br/><br/>
</body>
