<head>
  <?= $this->Html->css('users.css') ?>
</head>


<?php
  //if(isset($errors)) echo var_dump($errors);

  $this->Form->setTemplates([
      'inputContainer' => '<div class="input {{type}}{{required}}">
          {{content}} <span class="form-error">{{help}}</span></div>'
  ]);

  echo $this->Form->create($user, array('url'=>'/users/add'));

  echo $this->Form->control("username", ["type" => "text", 'templateVars' => ['help' => $errors["username"] ?? ""]]);
  echo $this->Form->control("password", ["type" => "password", 'templateVars' => ['help' => $errors["password"] ?? ""]]);
  echo $this->Form->control("password_match", ["type" => "password", 'templateVars' => ['help' => $errors["password_match"] ?? ""]]);
  echo $this->Form->control("name", ["type" => "text", 'templateVars' => ['help' => $errors["name"] ?? ""]]);

  echo $this->Form->Button("Add User");
  echo $this->Form->end();
?>
