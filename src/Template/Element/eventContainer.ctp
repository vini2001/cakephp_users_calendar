<div id="ev_<?= $ev["id"]; ?>" class="event <?php if(isset($ev["invitedBy"]) && $ev["accepted"] != 1) echo "event-invited"; ?>">
  <?php
    if(isset($ev["invitedBy"]) && $ev["accepted"] != 1){
      ?>
        <i id="<?= $ev["id"]; ?>" class="fa fa-thumbs-o-up align-right-event accept-invite" style="margin-left:2px;"></i>
        <i id="<?= $ev["id"]; ?>" class="fa fa-close align-right-event reject-invite"></i>
      <?php
    }else{
      ?>
        <?php if(!isset($ev["invitedBy"])) {?>
            <i id="<?= $ev["id"]; ?>" class="fa fa-trash-o align-right-event delete"></i>
            <i id="<?= $ev["id"]; ?>" class="fa fa-user-o align-right-event invite" style="margin-right:2px;"></i>
        <?php }
    }
  ?>
  <div class="event-desc">
    <?= $ev["title"]; ?>
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

  if(isset($ev["invitedBy"])){
    ?>
    <div class="event-user">
      Invited by <?php echo $ev["invitedBy"]; ?>
    </div>
    <?php
  }
  ?>
</div>
