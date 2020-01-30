<body>
  <?php echo $this->Form->create(null,array('url'=>'/users/add')); ?>
      <label>Username</label>
      <input type="text" name="username"/>
      <label>Password</label>
      <input type="password" name="password"/>
      <label>Name</label>
      <input type="text" name="name"/>
      <input type="submit" value="Add"/>
  </form>
</body>
