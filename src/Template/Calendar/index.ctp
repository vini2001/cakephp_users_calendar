<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php
  use Cake\Routing\Router;
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
    var exportDataURL = '<?php echo Router::url(["controller" => "Calendar", "action" => "exportCalendarData"]); ?>';
    var calendarElementURL = '<?php echo Router::url(["controller" => "Element", "action" => "calendar"]); ?>';

    var plusMonths = <?= $plusMonths ?>

    var csrfToken = '<?= $this->request->getParam('_csrfToken') ?>';
    var users = JSON.parse('<?= json_encode($users) ?>');
    var invitedEvents = JSON.parse('<?= json_encode($invitedEvents) ?>');
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <?php echo $this->Html->script('calendar'); ?>
</head>

<body>

  <div class="row">
    <div class="inputAddEvent"> <?php echo $this->Form->control("title", ["type" => "text", "placeholder" => "Meeting with...", "id" => "edt_title"]); ?> </div>
    <div class="inputAddEvent"> <?php echo $this->Form->control("date", ["type" => "datetime-local", "id" => "edt_date"]); ?> </div>
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
          <?= $this->Form->control("From", ["type" => "datetime-local", "id" => "start_export_date"]); ?>
          <?= $this->Form->control("Until", ["type" => "datetime-local", "id" => "end_export_date"]); ?>
        </div>
      </div>
      <span id="btn_export_data" class="box-submit btn"> Export to CSV </span>
    </div>
  </div>


  <div id="calendarContainer">
    <?= $this->element('calendar', ['plusMonths' => $plusMonths, 'days' >= $days, 'events' => $events, 'invitedEvents' => $invitedEvents]) ?>
  </div>

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
