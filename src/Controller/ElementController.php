<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Event\EventInterface;
   use Cake\Auth\DefaultPasswordHasher;

   class ElementController extends AppController{

      public function calendar(){
        $this->loadComponent('Calendar');

        if($this->request->is('post')){
          $plusMonths = $this->request->getData('plusMonths');

          $days = $this->Calendar->getDaysArray($plusMonths);
          $events = $this->Calendar->getEvents($days[0], $days[sizeof($days) - 1]);
          $invitedEvents = $this->Calendar->getInvitedEvents($days[0], $days[sizeof($days) - 1]);
          $today = $this->Calendar->getMonthStr($plusMonths);
          $users = $this->Calendar->getUsers();

          $this->set('plusMonths',$plusMonths);
          $this->set('days',$days);
          $this->set('today', $today);
          $this->set('events', $events);
          $this->set('invitedEvents', $invitedEvents);
          $this->set('users', $users);
        }else{
          return $this->redirect(['controller' => 'Calendar', 'action' => 'index']);
        }
      }

   }
?>
