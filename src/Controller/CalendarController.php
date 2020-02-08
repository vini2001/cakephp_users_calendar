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
        $invitedEvents = $this->Calendar->getInvitedEvents($days[0], $days[sizeof($days) - 1]);
        $today = $this->Calendar->getMonthStr($plusMonths);
        $users = $this->Calendar->getUsers();

        $this->set('plusMonths',$plusMonths);
        $this->set('days',$days);
        $this->set('today', $today);
        $this->set('events', $events);
        $this->set('invitedEvents', $invitedEvents);
        $this->set('users', $users);
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
          $user_id = $this->Auth->user('id');
          $id_event = $this->request->getData('id');
          $this->loadModel('Events');
          $event = $this->Events->get($id_event);

          if($event->user_id != $user_id && !$this->Auth->user('adm'))
            return $this->response
             ->withType('application/json')
             ->withStatus(401)
             ->withStringBody(json_encode([
               'error' => 'Unauthorized Request'
             ]));

          $this->Events->delete($event);

          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
      }

      public function acceptInvitation() {
        $this->loadComponent('Calendar');
        $id = $this->request->getData('id');

        $this->Calendar->acceptInvitat($id);


       return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
          'status' => 200
        ]));
      }

      public function declineInvitation() {
        $this->loadComponent('Calendar');
        $id = $this->request->getData('id');

        if($this->Calendar->declineInvitation($id)){
          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
        }else{
          return $this->response
           ->withType('application/json')
           ->withStatus(401)
           ->withStringBody(json_encode([
             'error' => 'Unauthorized Request'
           ]));
        }
      }

      public function removeInvite(){
        $this->loadComponent('Calendar');
        $id_event = $this->request->getData('id_event');
        $id_user = $this->request->getData('id_user');

        if($this->Calendar->removeInvitation($id_event, $id_user)){
          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
        }else{
          return $this->response
           ->withType('application/json')
           ->withStatus(401)
           ->withStringBody(json_encode([
             'error' => 'Unauthorized Request'
           ]));
        }
      }

      public function invite(){
          $request = json_decode($this->request->getData('request'));

          $this->loadModel('Events');
          $this->loadModel('Invitation');
          $event = $this->Events->get($request->id_event);
          $user_id = $this->Auth->user('id');

          if($event->user_id != $user_id && !$this->Auth->user('adm'))
            return $this->response
             ->withType('application/json')
             ->withStatus(401)
             ->withStringBody(json_encode([
               'error' => 'Unauthorized Request'
             ]));

          $data = [];
          foreach ($request->users as $key => $user_id) {
            $data[] = [
              'id_event' => $request->id_event,
              'id_user' => $user_id
            ];
          }

          $invitations = $this->Invitation->newEntities($data);
          $result = $this->Invitation->saveMany($invitations);

         return $this->response
          ->withType('application/json')
          ->withStringBody(json_encode([
            'event' => $result
          ]));
      }

      public function beforeFilter(Event $event) {
          parent::beforeFilter($event);
      }


   }
?>
