<?php

  namespace App\Controller\Component;

  use Cake\Controller\Component;
  use Cake\Controller;
  use Cake\ORM\TableRegistry;
  use Cake\Datasource\ConnectionManager;
  use Cake\Event\EventInterface;

  class AppComponent extends Component {

    public function initialize(array $config): void {
      $this->controller = $this->_registry->getController();
    }

    public function isInt($value) {
        return is_numeric($value) && floatval(intval($value)) === floatval($value);
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
