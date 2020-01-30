<body>
  <?php echo $this->Form->create(null,array('url'=>'/users/edit/'.$id)); ?>
      <label>Username</label>
      <input type="text" name="username" value="<?php echo $username; ?>"/>
      <label>Password</label>
      <input type="password" name="password"/>
      <label>Name</label>
      <input type="text" name="name" value="<?php echo $name; ?>"/>
      <input type="submit" value="Edit"/>
  </form>
</body>
