<?php
  echo $this->Form->create($user, array('url'=>'/users/edit/'.$id));
  echo $this->Form->control("username", ["value" => $username, "type" => "text"]);
  echo $this->Form->control("password", ["type" => "password"]);
  echo $this->Form->control("name", ["value" => $name, "type" => "text"]);
  echo $this->Form->Button("Save changes");
  echo $this->Form->end();
?>
