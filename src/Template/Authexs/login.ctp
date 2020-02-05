<?php  
   echo $this->Form->create();
   echo $this->Form->control('username', ['type' => 'text', 'required' => true]);
   echo $this->Form->control('password', ['type' => 'password', 'required' => true]);
   echo $this->Form->button('Login');
   echo $this->Form->end();
?>
