<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Event\EventInterface;
   use Cake\Auth\DefaultPasswordHasher;

   class AuthexsController extends AppController{

      public function index(){
          if($this->Auth->user())
            $this->redirect(['controller' => 'Users', 'action' => 'index']);
      }

      public function login(){
         if($this->request->is('post')){
            $user = $this->Auth->identify();

            if($user){
               $this->Auth->setUser($user);
               return $this->redirect($this->Auth->redirectUrl());
            } else
            $this->Flash->error('Your username or password is incorrect.');
         }
      }
      public function logout(){
         return $this->redirect($this->Auth->logout());
      }

   }
?>
