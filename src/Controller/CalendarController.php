<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Auth\DefaultPasswordHasher;
   use Cake\Event\Event;
   use Cake\Routing\Router;

   class CalendarController extends AppController{

      public function index(){

        $plusMonths = 0;
        if($this->request->is('ajax')){
          $plusMonths = $this->request->getData('plusMonths');
    		}

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
        $this->set('isAtCalendar', true);

        if($this->request->is('ajax')){
          $this->viewBuilder()->setTemplatePath('Element');
          $this->render('calendar', 'ajax');
        }

      }

      public function add(){

        if($this->request->is('post')){
           $title = $this->request->getData('title');
           $date = $this->request->getData('date');

           $title = ltrim($title);
           if(strlen($title) == 0)
            return $this->App->errorOut("The title is empyt", 400);

           date_default_timezone_set('Australia/Sydney');
           $stamp = strtotime($date); // get unix timestamp
           $time_in_ms = $stamp*1000;

           if(microtime(true)*1000 > $time_in_ms)
              return $this->App->errorOut("You cannot add events in the past", 400);

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
             return $this->App->errorOut();
           }
        }
      }

      public function delete(){
          $user_id = $this->Auth->user('id');
          $id_event = $this->request->getData('id');
          $this->loadModel('Events');
          $event = $this->Events->get($id_event);

          if($event->user_id != $user_id && !$this->Auth->user('adm'))
            return $this->App->errorUnauthorized();

          $this->Events->delete($event);

          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
      }

      public function acceptInvitation() {
        $id = $this->request->getData('id');

        $this->Calendar->acceptInvitat($id);


       return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
          'status' => 200
        ]));
      }

      public function declineInvitation() {
        $id = $this->request->getData('id');

        if($this->Calendar->declineInvitation($id)){
          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
        }else{
          return $this->App->errorUnauthorized();
        }
      }

      public function removeInvite(){
        $id_event = $this->request->getData('id_event');
        $id_user = $this->request->getData('id_user');

        if($this->Calendar->removeInvitation($id_event, $id_user)){
          return $this->response
           ->withType('application/json')
           ->withStringBody(json_encode([]));
        }else{
          return $this->App->errorUnauthorized();
        }
      }

      public function invite(){
          $request = json_decode($this->request->getData('request'));

          $this->loadModel('Events');
          $this->loadModel('Invitation');
          $event = $this->Events->get($request->id_event);
          $user_id = $this->Auth->user('id');

          if($event->user_id != $user_id && !$this->Auth->user('adm'))
            return $this->App->errorUnauthorized();

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

      public function exportCalendarData(){
        $startDate = $this->request->getData('startDate');
        $endDate = $this->request->getData('endDate');

        $firstDate = array();
        $dateValue = strtotime($startDate);
        $firstDate["year"] = date("Y", $dateValue);
        $firstDate["month"] = date("m", $dateValue);
        $firstDate["day"] = date("d", $dateValue);
        $firstDate["time"] = date("H:i:s", $dateValue);

        $secondDate = array();
        $dateValue = strtotime($endDate);
        $secondDate["year"] = date("Y", $dateValue);
        $secondDate["month"] = date("m", $dateValue);
        $secondDate["day"] = date("d", $dateValue);
        $secondDate["time"] = date("H:i:s", $dateValue);

        $events = $this->Calendar->getEvents($firstDate, $secondDate, $firstDate["time"], $secondDate["time"]);
        $invitedEvents = $this->Calendar->getInvitedEvents($firstDate, $secondDate, $firstDate["time"], $secondDate["time"]);

        $name= "ExportedData.csv";

        $file_path = ROOT . "/webroot/files/" . $name;

        $content = "Subject,Start Date,Start Time,End Date,End Time,All day event,Description,Location\n";
        foreach ($events as $key => $ev) {
          $dateValue = strtotime($ev->date);
          $date = date("Y-m-d,H:i", $dateValue);
          $content .= "\"$ev->title\",$date,,,FALSE,,\n";
        }

        foreach ($invitedEvents as $key => $ev) {
          if(!$ev->accepted) continue;
          $dateValue = strtotime($ev->date);
          $date = date("Y-m-d,H:i", $dateValue);
          $content .= "\"$ev->title\",$date,,,FALSE,\"Invited by $ev->invitedBy\",\n";
        }

        $fp = fopen($file_path, "wb");
        fwrite($fp,$content);
        fclose($fp);

        $file_path = Router::url('/') . "webroot/files/" . $name;

       return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
          'file_url' => $file_path
        ]));
      }

      public function beforeFilter(Event $event) {
          parent::beforeFilter($event);
          $this->loadComponent('Calendar');
      }


   }
?>
