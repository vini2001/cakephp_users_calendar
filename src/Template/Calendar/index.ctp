<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php
  use Cake\Routing\Router;

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

  function getTimestampFromDay($day){
    //return "2013-03-18T13:00";
    $d = $day["day"] >= 10 ? $day["day"] : ("0".$day["day"]);
    $month = $day["month"] >= 10 ? $day["month"] : ("0".$day["month"]);
    return $day["year"] . "-" . $month . "-" . $d . "T12:00";
  }

?>

<head>
  <?= $this->Html->css('calendar.css') ?>
  <script>
    var deleteURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "delete"]); ?>';
    var addURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "add"]); ?>';
    var inviteURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "invite"]); ?>';
    var acceptURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "acceptInvitation"]); ?>';
    var rejectURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "declineInvitation"]); ?>';
    var removeInviteUrl = '<?php echo Router::url(["controller" => "Calendar", "action" => "removeInvite"]); ?>';
    var csrfToken = '<?= $this->request->getParam('_csrfToken') ?>';
    var users = JSON.parse('<?= json_encode($users) ?>');
    var invitedEvents = JSON.parse('<?= json_encode($invitedEvents) ?>');
    console.log(JSON.stringify(invitedEvents));
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <?php echo $this->Html->script('calendar'); ?>
</head>

<body>

  <div class="row">
    <div class="input"> <?php echo $this->Form->control("title", ["type" => "text", "placeholder" => "Meeting with...", "id" => "edt_title"]); ?> </div>
    <div class="input"> <?php echo $this->Form->control("date", ["type" => "datetime-local", "id" => "edt_date"]); ?> </div>
  </div>
  <div class="row"> <?php echo $this->Form->Button("Add", ["id" => "addEvent"]); ?> </div>
  <br/>

  <div class="modal" id="boxusers">
    <div class="box-container">
      <span class="box-title"> Invite Users <span class="close">&times;</span> </span>
      <div id="users_div" class="box">
        <ul>
          <?php
            foreach ($users as $key => $user) {
              ?>
              <li>
                <input id="user_invite_<?= $user["id"] ?>" class="checkbox-list" type="checkbox"/>
                <?= $user["name"] ?>
                <i id="remove_invite_user_<?= $user["id"] ?>" class="fa fa-close align-right-event remove-invite"></i>
              </li>
              <?php
            }
          ?>
        </ul>
      </div>
      <span id="btn_invite" class="box-submit btn"> Send invite </span>
    </div>
  </div>


  <table id="calendar">
    <caption>
      <?php echo $this->Html->link("‹",["action" => "/".($plusMonths-1).""], ['class' => 'previous round']); ?>
      <?php echo $today; ?>
      <?php echo $this->Html->link("›",["action" => "/".($plusMonths+1).""], ['class' => 'previous round']); ?>
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
  <br/><br/>

  <script type="text/template" id="template-inputs">
    <?php
      $ev = [
          "id" => ":::id",
          "title" => ":::title",
          "time" => ":::time"
      ];
      if($is_adm) $ev["user_name"] = ":::user_name";
      echo $this->element('eventContainer', ["ev" => $ev]);
    ?>
  </script>

  <script type="text/template" id="template-event-invited">
    <?php
      $ev = [
          "id" => ":::id",
          "title" => ":::title",
          "time" => ":::time",
          "invitedBy" => ":::invitedBy",
          "accepted" => "1"
      ];
      if($is_adm) $ev["user_name"] = ":::user_name";
      echo $this->element('eventContainer', ["ev" => $ev]);
    ?>
  </script>

</body>
