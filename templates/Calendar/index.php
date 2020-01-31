<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php


  function getEvents($day, &$events){
    if(sizeof($events) == 0) return;
    $ev = $events[0];
    while($day["day"] == $ev["day"] && $day["month"] == $ev["month"]  && $day["year"] == $ev["year"]){
      ?>
        <div class="event">
          <i id="<?php echo $ev["id"]; ?>" class="fa fa-trash-o align-right-event delete"></i>
          <div class="event-desc">
            <?php echo $ev["title"]; ?>
          </div>
          <div class="event-time">
            Time: <?php echo $ev["time"]; ?>
          </div>
          <?php
            if(isset($ev["user_name"])){
              ?>
              <div class="event-user">
                User: <?php echo $ev["user_name"]; ?>
              </div>
              <?php
            }
          ?>
        </div>
      <?php
      unset($events[0]);
      $events = array_values($events);
      if(sizeof($events) == 0) return;
      $ev = $events[0];
    }
  }

  function getTimestampFromDay($day){
    //return "2013-03-18T13:00";
    $d = $day["day"] >= 10 ? $day["day"] : ("0".$day["day"]);
    $month = $day["month"] >= 10 ? $day["month"] : ("0".$day["month"]);
    return $day["year"] . "-" . $month . "-" . $d . "T12:00";
  }

?>

<head>
  <?= $this->Html->css('calendar.css') ?>
</head>

<body>


  <?php echo $this->Form->create(null,array('url'=>'/calendar/add')); ?>
      <div class="row">
        <div style="margin-right: 20px;">
          <label>Title</label>
          <input placeholder="Meeting with..." type="text" name="title"/>
        </div>
        <div>
          <label>Date</label>
          <input id="edt_date" type="datetime-local" name="date"/>
        </div>
      </div>
      <div class="row"> <input type="submit" value="Add"/> </div>
  </form>
  <br/>

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
      <td id="<?php echo getTimestampFromDay($dayMap); ?>" class="day <?php if(isset($dayMap["otherMonth"])) echo "other-month"; ?>">
        <div class="date"><?php echo $dayMap["day"]; ?></div>
        <?php getEvents($dayMap, $events); ?>
      </td>
      <?php
    }
    echo '</tr>';
  ?>

</table>

<br/><br/>


</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    $('.day').on( "click", function() {
        $('#edt_date').val(this.id);
    });

    $('.delete').on( "click", function() {
        window.location="calendar/delete/"+this.id;
        return false;
    });
  });
</script>
