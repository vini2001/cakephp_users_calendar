<?php

  namespace App\Controller\Component;

  use Cake\Controller\Component;
  use Cake\Controller;
  use Cake\ORM\TableRegistry;
  use Cake\Datasource\ConnectionManager;
  use Cake\Event\EventInterface;

  class CalendarComponent extends Component {

    public function initialize(array $config): void {
      $this->controller = $this->_registry->getController();
    }

    public function getMonthStr($plusMonths = 0){
        $date = date_create();
        $dateCopy = date_create();
        date_add($date, date_interval_create_from_date_string("$plusMonths months"));
        if($date->format('m') - $dateCopy->format('m') > $plusMonths){
          date_add($date, date_interval_create_from_date_string("-5 days"));
          //To avoid skiping a month when the next you has less days
          //Ex, today is January 30th, if I add one month, it would be
          //February 30th, as there is no such thing, it would be March
        }
        return $date->format('M Y');
    }

    public function getFirstDayDate($plusMonths = 0){
        $date = date_create();
        $dateCopy = date_create();
        date_add($date, date_interval_create_from_date_string("$plusMonths months"));
        if($date->format('m') - $dateCopy->format('m') > $plusMonths){
          date_add($date, date_interval_create_from_date_string("-5 days"));
        }
        date_add($date, date_interval_create_from_date_string('-'.($date->format('d') - 1).' day'));
        return $date;
    }

    public function getFirstWeekDay($plusMonths = 0){
        $date = date_create();
        $dateCopy = date_create();
        date_add($date, date_interval_create_from_date_string("$plusMonths months"));
        if($date->format('m') - $dateCopy->format('m') > $plusMonths){
          date_add($date, date_interval_create_from_date_string("-5 days"));
        }
        date_add($date, date_interval_create_from_date_string('-'.($date->format('d') - 1).' day'));
        return $date->format('N');
    }

      public function getDaysInTheMonth($plusMonths = 0){
        $month = date("m");
        $year = date("Y");

        $month += $plusMonths;
        if($month < 1) {
          $month = 12 + $month;
          $year--;
        }

        switch ($month) {
          case 4:
          case 6:
          case 9:
          case 11:
            $daysInTheMonth = 30;
            break;

          case 2:
            $daysInTheMonth = $year % 4 == 0 ? 29 : 28;
            break;

          default:
            $daysInTheMonth = 31;
            break;
        }

        return $daysInTheMonth;
      }

      public function getDaysArray($plusMonths = 0){

        $daysInTheMonth = $this->getDaysInTheMonth($plusMonths);
        $daysInPreviousMonth = $this->getDaysInTheMonth($plusMonths - 1);

        $month = date('m') + $plusMonths;
        $year = date('Y');

        if($month <= 0) {
          $year -= floor(($month*(-1)+12)/12);
          $month = 12 - ($month*(-1)%12);
        }else if($month >= 13){
          $year += floor($month/12);
          $month %= 12;
        }

        $weekDay = $this->getFirstWeekDay($plusMonths);
        if($weekDay != 7){
          $day = 7;
          while($day != $weekDay) {
            $days[] = array(
              'day' => $daysInPreviousMonth - ($weekDay - $day%7 - 1),
              'month' => ($month - 1) == 0 ? 12 : $month - 1,
              'year' => ($month - 1) == 0 ? $year-1 : $year,
              'weekDay' => $day,
              'otherMonth' => true,
            );
            $day ++;
            if($day == 8) $day = 1;
          }
        }

        $date = $this->getFirstDayDate($plusMonths);
        for($day = 1; $day <= $daysInTheMonth; $day++){
          $weekDay = $date->format('N');
          $days[] = array(
            'day' => $day,
            'month' => $month + 0,
            'year' => $year + 0,
            'weekDay' => $weekDay
          );
          date_add($date, date_interval_create_from_date_string('1 day'));
        }


        $day = 1;
        while($weekDay != 6) {
          $days[] = array(
            'day' => $day,
            'month' => ($month + 1) == 13 ? 1 : ($month + 1),
            'year' => ($month + 1) == 13 ? $year+1 : $year,
            'weekDay' => $weekDay,
            'otherMonth' => true
          );
          $weekDay++;
          if($weekDay == 8) $weekDay = 1;
          $day++;
        }

        return $days;
      }

      public function getEvents($firstDay, $lastDay){
        $firstDay = $firstDay['year'].'-'.$firstDay['month'].'-'.$firstDay['day'] . ' 00:00:00';
        $lastDay = $lastDay['year'].'-'.$lastDay['month'].'-'.$lastDay['day'] . ' 23:59:59';
        $events = array();

        $user_id = $this->controller->Auth->user('id');
        $adm = $this->controller->Auth->user('adm');

        $this->controller->loadModel('Events');

        $conditions = [
          'date >= '=>$firstDay,
          'date <= '=>$lastDay
        ];

        $fields = [
          'id' => 'Events.id',
          'date' => 'Events.date',
          'title' => 'Events.title',
          'user_id' => 'Events.user_id'
        ];

        if($adm != 1) {
          $conditions['user_id'] = $user_id;
        }else{
          $fields["user_name"] = "Users.name";
        }

        $events = $this->controller->Events->find('all',[
          'fields'=>$fields,
          'conditions'=>$conditions,
          'order'=>['date'=>'asc'],
          'contain' => ['Users']
        ]);

        $response = array();
        foreach ($events as $key => $event) {
          $dateValue = strtotime($event["date"]);
          $event["year"] = date("Y", $dateValue);
          $event["month"] = date("m", $dateValue);
          $event["day"] = date("d", $dateValue);
          $event["time"] = date("H:i:s", $dateValue);
          $response[] = $event;
        }

        return $response;
      }

      public function getInvitedEvents($firstDay, $lastDay){
        $firstDay = $firstDay['year'].'-'.$firstDay['month'].'-'.$firstDay['day'] . ' 00:00:00';
        $lastDay = $lastDay['year'].'-'.$lastDay['month'].'-'.$lastDay['day'] . ' 23:59:59';
        $events = array();

        $user_id = $this->controller->Auth->user('id');
        $adm = $this->controller->Auth->user('adm');

        $this->controller->loadModel('Events');
        $this->controller->loadModel('Invitation');
        $this->controller->loadModel('Users');

        $conditions = [
          'Events.date >= '=>$firstDay,
          'Events.date <= '=>$lastDay,
          'Invitation.id_user'=>$user_id
        ];

        $fields = [
          'id' => 'Events.id',
          'date' => 'Events.date',
          'title' => 'Events.title',
          'user_id' => 'Events.user_id',
          'invitedBy' => 'uCreator.name',
          'accepted' => 'Invitation.accepted'
        ];

        if($adm){
          $fields['user_name'] = 'Users.name';
        }

        $events = $this->controller->Invitation->find('all',[
          'fields'=>$fields,
          'conditions'=>$conditions,
          'order'=>['date'=>'asc'],
          'contain' => ['Users']
        ])->join([
            'table' => 'events',
            'alias' => 'Events',
            'type' => 'INNER',
            'conditions' => [
              'Events.id' => new \Cake\Database\Expression\IdentifierExpression('Invitation.id_event')
            ],
        ])->join([
            'table' => 'users',
            'alias' => 'uCreator',
            'type' => 'INNER',
            'conditions' => [
              'uCreator.id' => new \Cake\Database\Expression\IdentifierExpression('Events.user_id')
            ],
        ]);

        $response = array();
        foreach ($events as $key => $event) {
          $dateValue = strtotime($event["date"]);
          $event["year"] = date("Y", $dateValue);
          $event["month"] = date("m", $dateValue);
          $event["day"] = date("d", $dateValue);
          $event["time"] = date("H:i:s", $dateValue);
          $response[] = $event;
        }

        return $response;
      }

      public function getUsers(){
        $user_id = $this->controller->Auth->user('id');

        $this->controller->loadModel('Users');
        $this->controller->loadModel('Invitation');

        $conditions = [
          'Users.id <> '=> $user_id
        ];

        $users = $this->controller->Users->find('all',[
          'conditions'=>$conditions,
          'order'=>['name'=>'asc']
        ]);

        foreach ($users as $key => $user) {
          $events_ids = $this->controller->Invitation->find('all',[
            'fields' => ['id_event'],
            'conditions'=>[
              'id_user'=>$user["id"]
            ]
          ]);
          $user["events"] = [];
          foreach ($events_ids as $key => $ev) {
            $user["events"][] = $ev["id_event"];
          }
        }

        return $users;
      }

      public function acceptInvitat($eventId){

        $this->controller->loadModel('Invitation');
        $user_id = $this->controller->Auth->user('id');

        $conditions = [
          'id_event'=>$eventId,
          'id_user'=>$user_id
        ];

        $invitations = $this->controller->Invitation->find('all',[
          'conditions'=>$conditions
        ]);

        foreach ($invitations as $key => $item) {
          $invitation = $item;
        }

        $invitation->accepted = 1;
        $this->controller->Invitation->save($invitation);
      }

      public function removeInvitation($id_event, $id_user){
        $this->controller->loadModel('Invitation');
        $user_id_owner_event = $this->controller->Auth->user('id');
        $adm = $this->controller->Auth->user('adm');

        $conditions = [
          'id_event'=>$id_event,
          'id_user'=>$id_user
        ];

        $invitations = $this->controller->Invitation->find('all',[
          'fields' => [
            'Invitation.id', 'Invitation.id_user', 'Invitation.id_event',
            'Events.user_id'
          ],
          'conditions'=>$conditions,
          'contain'=>['Events']
        ]);

        foreach ($invitations as $key => $item) {
          $invitation = $item;
        }

        if($user_id_owner_event != $invitation["user_id"] && !$adm) return false;

        if(isset($invitation)) {
          $this->controller->Invitation->delete($invitation);
          return true;
        }
        return false;
      }

      public function declineInvitation($eventId){

        $this->controller->loadModel('Invitation');
        $user_id = $this->controller->Auth->user('id');

        $conditions = [
          'id_event'=>$eventId,
          'id_user'=>$user_id
        ];

        $invitations = $this->controller->Invitation->find('all',[
          'conditions'=>$conditions
        ]);

        foreach ($invitations as $key => $item) {
          $invitation = $item;
        }

        if(isset($invitation)) {
          $this->controller->Invitation->delete($invitation);
          return true;
        }
        return false;
      }

      public function errorOut($error = "Internal server error", $status = 500){
        return $this->controller->response
         ->withType('application/json')
         ->withStatus($status)
         ->withStringBody(json_encode([
           'error' => $error
         ]));
      }

      public function errorUnauthorized(){
        return $this->controller->response
         ->withType('application/json')
         ->withStatus(401)
         ->withStringBody(json_encode([
           'error' => 'Unauthorized request'
         ]));
      }

  }

?>
