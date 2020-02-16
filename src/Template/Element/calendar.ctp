<?php

  function getTimestampFromDay($day){
    $d = $day["day"] >= 10 ? $day["day"] : ("0".$day["day"]);
    $month = $day["month"] >= 10 ? $day["month"] : ("0".$day["month"]);
    return $day["year"] . "-" . $month . "-" . $d . "T12:00";
  }

  function getEvents($context, $day, &$events){
    if(sizeof($events) == 0) return;
    $ev = $events[0];
    while($day["day"] == $ev["day"] && $day["month"] == $ev["month"]  && $day["year"] == $ev["year"]){
      echo $context->element('eventContainer', ["ev" => $ev]);
      unset($events[0]);
      $events = array_values($events);
      if(sizeof($events) == 0) return;
      $ev = $events[0];
    }
  }
?>

<head>
  <?= $this->Html->css('calendar.css') ?>
</head>
<table id="calendar">
  <caption>
    <?php echo $this->Html->link("‹", "#", ['class' => 'previous round', 'onclick' => 'previousMonth()']); ?>
    <?php echo $today; ?>
    <?php echo $this->Html->link("›", "#", ['class' => 'previous round', 'onclick' => 'nextMonth()']); ?>
  </caption>

  <?php
    echo $this->Html->tableHeaders(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],[
      "class" => "weekdays"
    ]);

    foreach ($days as $key => $dayMap) {
      if($key % 7 == 0){
        if($key != 6) echo '</tr>';
        echo '<tr class="days">';
      }
      ?>
      <td id="<?php echo getTimestampFromDay($dayMap); ?>" class="day <?php if(isset($dayMap["otherMonth"])) echo "other-month"; ?>">
        <div class="date"><?php echo $dayMap["day"]; ?></div>
        <div id="div_ev_<?= $dayMap["day"] ?>_<?= $dayMap["month"] ?>">
          <?php getEvents($this, $dayMap, $events); ?>
          <?php getEvents($this, $dayMap, $invitedEvents); ?>
        </div>
      </td>
      <?php
    }
    echo '</tr>';
  ?>
</table>
