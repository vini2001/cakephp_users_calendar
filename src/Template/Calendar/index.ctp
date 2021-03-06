<?php use Cake\Routing\Router; ?>

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= $this->Html->css('calendar.css') ?>
  <script>
    var rootURL = '<?php echo $this->Url->build("/"); ?>';
    var deleteURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "delete"]); ?>';
    var addURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "add"]); ?>';
    var inviteURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "invite"]); ?>';
    var acceptURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "acceptInvitation"]); ?>';
    var rejectURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "declineInvitation"]); ?>';
    var removeInviteUrl = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "removeInvite"]); ?>';
    var exportDataURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "exportCalendarData"]); ?>';
    var calendarElementURL = '<?php echo $this->Url->build(["controller" => "Calendar", "action" => "index"]);?>';
    var imagesURL = '<?= $this->request->getAttribute("webroot") ?>img/';
    var plusMonths = <?= $plusMonths ?>

    var csrfToken = '<?= $this->request->getParam('_csrfToken') ?>';
    var users = JSON.parse('<?= json_encode($users) ?>');
    var invitedEvents = JSON.parse('<?= json_encode($invitedEvents) ?>');
    var events = JSON.parse('<?= json_encode($events) ?>');
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <?php echo $this->Html->script('calendar'); ?>
</head>

<body>

  <div class="row">
    <div class="inputAddEvent"> <span> Title </span>  <?php echo $this->Form->control("title", ["type" => "text", "placeholder" => "Meeting with...", "id" => "edt_title", "label" => false]); ?> </div>
    <div class="inputAddEvent"> <span> Date </span> <input type="date" id="edt_date" placeholder="YYYY-MM-DD"/> </div>
    <div class="inputAddEvent"> <span> Time </span> <input type="time" id="edt_time" placeholder="HH:MM"/> </div>
  </div>
  <div class="row"> <?php echo $this->Form->Button("Add", ["id" => "addEvent"]); ?> </div>
  <br/>

  <div class="modal" id="boxusers">
    <div class="box-container">
      <span class="box-title"> Invite Users <span id="closeBoxUsersModal" class="close">&times;</span> </span>
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

  <div class="modal" id="exportDataModal">
    <div class="box-container">
      <span class="box-title"> Export Data <span id="closeExportDataModal" class="close">&times;</span> </span>
      <div class="box">
        <div style="margin:10px;">
          <div class=""> <span> From </span> <input type="date" id="start_export_date" placeholder="YYYY-MM-DD"/> </div>
          <div class=""> <input type="time" id="start_export_time" placeholder="HH:MM"/> </div>
          <div class=""> <span> Until </span> <input type="date" id="end_export_date" placeholder="YYYY-MM-DD"/> </div>
          <div class=""> <input type="time" id="end_export_time" placeholder="HH:MM"/> </div>
        </div>
      </div>
      <span id="btn_export_data" class="box-submit btn"> Export to CSV </span>
    </div>
  </div>


  <div id="calendarContainer">
    <?= $this->element('calendar', ['plusMonths' => $plusMonths, 'days' >= $days, 'events' => $events, 'invitedEvents' => $invitedEvents]) ?>
  </div>

  <br/><br/>

  <?php
    $date = new DateTime();
    $now = $date->format('Y, M d\t\h H:i');
  ?>

  <span id="txt_time"> <?= $now; ?></span>

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
