<head>
  <?= $this->Html->css('users.css') ?>
</head>

<?php
  $this->Form->setTemplates([
      'inputContainer' => '<div class="input {{type}}{{required}}">
          {{content}} <span class="form-error">{{help}}</span></div>'
  ]);

  echo $this->Form->create($user, array('url'=>'/users/edit/'.$id));
  echo $this->Form->control("username", ["value" => $username, "type" => "text", 'templateVars' => ['help' => $errors["username"] ?? ""]]);
  echo $this->Form->control("name", ["value" => $name, "type" => "text", 'templateVars' => ['help' => $errors["name"] ?? ""]]);
  echo $this->Form->Button("Save changes");
  echo $this->Form->end();
?>
