<div id="ev_<?= $ev["id"]; ?>" class="event">
  <i id="<?= $ev["id"]; ?>" class="fa fa-trash-o align-right-event delete"></i>
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
  ?>
</div>
