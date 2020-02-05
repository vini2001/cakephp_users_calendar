<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Auth\DefaultPasswordHasher;
   use Cake\Event\Event;

   class CalendarController extends AppController{

      public function index($plusMonths = 0){
        $this->loadComponent('Calendar');

        $days = $this->Calendar->getDaysArray($plusMonths);
        $events = $this->Calendar->getEvents($days[0], $days[sizeof($days) - 1]);

        $today = $this->Calendar->getMonthStr($plusMonths);
        $this->set('plusMonths',$plusMonths);
        $this->set('days',$days);
        $this->set('today', $today);
        $this->set('events', $events);
      }

      public function add(){
        if($this->request->is('post')){
           $title = $this->request->getData('title');
           $date = $this->request->getData('date');
           $user_id = $this->Auth->user('id');

           $this->loadModel('Events');
           $ev = $this->Events->newEntity();
           $ev->title = $title;
           $ev->date = $date;
           $ev->user_id = $user_id;
           if($this->Events->save($ev)){
             return $this->response
              ->withType('application/json')
              ->withStringBody(json_encode([
                'status' => 200,
                'id' => $ev->id
              ]));
           }else{
             return $this->response
              ->withType('application/json')
              ->withStringBody(json_encode([
                'status' => 400
              ]));
           }
        }
      }

      public function delete(){
          $id = $this->request->getData('id');
          $this->loadModel('Events');
          $event = $this->Events->get($id);
          $this->Events->delete($event);

         return $this->response
          ->withType('application/json')
          ->withStringBody(json_encode([
            'status' => 200
          ]));
      }

      public function beforeFilter(Event $event) {
          parent::beforeFilter($event);
      }


   }
?>
