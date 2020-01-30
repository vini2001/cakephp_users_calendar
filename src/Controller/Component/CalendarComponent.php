<?php

  namespace App\Controller\Component;

  use Cake\Controller\Component;

  class CalendarComponent extends Component
  {
    public function getMonthStr($plusMonths = 0){
        $date = date_create();
        $dateCopy = date_create();
        date_add($date, date_interval_create_from_date_string("$plusMonths months"));
        if($date->format('m') - $dateCopy->format('m') > 1){
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
        if($date->format('m') - $dateCopy->format('m') > 1){
          date_add($date, date_interval_create_from_date_string("-5 days"));
        }
        date_add($date, date_interval_create_from_date_string('-'.($date->format('d') - 1).' day'));
        return $date;
    }

    public function getFirstWeekDay($plusMonths = 0){
        $date = date_create();
        $dateCopy = date_create();
        date_add($date, date_interval_create_from_date_string("$plusMonths months"));
        if($date->format('m') - $dateCopy->format('m') > 1){
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

        $weekDay = $this->getFirstWeekDay($plusMonths);
        if($weekDay != 7){
          $day = 7;
          while($day != $weekDay) {
            $days[] = array(
              'day' => $daysInPreviousMonth - ($weekDay - $day%7 - 1),
              'weekDay' => $day,
              'otherMonth' => true
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
            'weekDay' => $weekDay
          );
          date_add($date, date_interval_create_from_date_string('1 day'));
        }


        $day = 1;
        while($weekDay != 6) {
          $days[] = array(
            'day' => $day,
            'weekDay' => $weekDay,
            'otherMonth' => true
          );
          $weekDay++;
          if($weekDay == 8) $weekDay = 1;
          $day++;
        }
        
        return $days;
      }

  }

?>
