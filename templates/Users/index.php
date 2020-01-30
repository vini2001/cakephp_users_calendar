<?php
echo $this->Html->link('Add User',
  ['controller' => 'Users', 'action' => 'add'],
  ['class' => 'btn']
);
?>

<br/>
<br/>
<table>
   <tr>
      <td>ID</td>
      <td>Username</td>
      <td>Name</td>
      <td>Edit</td>
      <td>Delete</td>
   </tr>

   <?php
      foreach ($results as $row):
         echo "<tr><td>".$row->id."</td>";
         echo "<td>".$row->username."</td>";
         echo "<td>".$row->name."</td>";
         echo "<td><a href='".$this->Url->build(["controller" => "Users","action" => "edit",$row->id])."'>Edit</a></td>";
         echo "<td><a href='".$this->Url->build(["controller" => "Users","action" => "delete",$row->id])."'>Delete</a></td></tr>";
      endforeach;
   ?>
</table>
