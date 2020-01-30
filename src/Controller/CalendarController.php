<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Auth\DefaultPasswordHasher;

   class CalendarController extends AppController{

      public function index($plusMonths = 0){
        $this->loadComponent('Calendar');





        $days = $this->Calendar->getDaysArray($plusMonths);

        $today = $this->Calendar->getMonthStr($plusMonths);
        $this->set('plusMonths',$plusMonths);
        $this->set('days',$days);
        $this->set('today', $today);

      }


   }
?>
