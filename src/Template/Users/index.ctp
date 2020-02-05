<?php
  echo $this->Html->link('Add User',
    ['controller' => 'Users', 'action' => 'add'],
    ['class' => 'btn']
  );
?>

<br/>
<br/>

<table>
  <?php
    echo $this->Html->tableHeaders(['ID', 'Name', 'Username', 'Edit', 'Delete']);
      foreach ($results as $row){

        $edit = $this->Html->link('Edit',["controller" => "Users","action" => "edit",$row->id]);
        $delete = $this->Html->link('Delete',["controller" => "Users","action" => "delete",$row->id]);

        echo $this->Html->tableCells([
          ["$row->id", $row->name, $row->username, $edit, $delete]
        ]);
      }
  ?>
</table>
