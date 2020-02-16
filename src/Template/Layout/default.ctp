<?php
  date_default_timezone_set('Australia/Sydney');
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

 $cakeDescription = 'SolarQuotes Calendar';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css">

    <?= $this->Html->css('milligram.min.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <?php echo $this->Html->script('app'); ?>
</head>
<body>

  <div id="snackbar-container">
    <div class="snackbar" align="center">
      <span id="snackbarText"> Unauthorized Request </span>
    </div>
  </div>

    <nav class="top-nav">
        <div class="top-nav-title">

            <div>
              <img src="<?= $this->request->webroot ?>img/logo.png" height="50px" width="auto"/>
              </br>
              <a> <?php if(isset($name)) echo $name; ?> </a>
            </div>


            <?php
              // $date = new DateTime();
              // $now = $date->format('Y, M d\t\h H:i:s');
              // echo $now;
            ?>
        </div>
        <div class="top-nav-menu">
            <?php
              if($loggedIn && $is_adm){
                echo $this->Html->link('Users',["controller" => "Users","action" => "index"]);
                echo $this->Html->link('Calendar',["controller" => "Calendar","action" => "index"]);
              }
            ?>
        </div>
        <div class="top-nav-links">
            <?php
              if(isset($isAtCalendar) && $isAtCalendar){
                echo $this->Html->link('Export Data', "#", ["id" => "exportDataLink"]);
              }
            ?>
            <a target="_blank" href="https://www.solarquotes.com.au/">Website</a>
            <a target="_blank" href="https://play.google.com/store/apps/details?id=solarquotes.com.solarquotes">App</a>
            <?php if($loggedIn) echo $this->element('logoutLink'); ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>



</body>


</html>
