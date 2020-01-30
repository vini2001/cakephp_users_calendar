<?php
   echo $this->Form->create();
   ?>
   <label>Username</label>
   <input type="text" name="username"/>
   <label>Password</label>
   <input type="password" name="password"/>
  <?php
   echo $this->Form->button('Login');
   echo $this->Form->end();
?>
