<?php
   namespace App\Controller;
   use App\Controller\AppController;

   class TestsController extends AppController{
      public function index($arg1, $arg2){
        $this->set('argument1',$arg1);
        $this->set('argument2',$arg2);
      }
   }
?>
