<?php
   namespace App\Controller;
   use App\Controller\AppController;
   use Cake\ORM\TableRegistry;
   use Cake\Datasource\ConnectionManager;
   use Cake\Auth\DefaultPasswordHasher;

   class UsersController extends AppController{

      public function index(){
         $this->loadModel('Users');
         $query = $this->Users->find('all',[
           'conditions' => [
            'adm'=>0
         ]]);
         $this->set('results',$query);
      }

      public function add(){
         if($this->request->is('post')){
            $username = $this->request->getData('username');
            $name = $this->request->getData('name');
            $hashPswdObj = new DefaultPasswordHasher;
            $password = $hashPswdObj->hash($this->request->getData('password'));
            $users_table = TableRegistry::get('users');
            $users = $users_table->newEmptyEntity();
            $users->username = $username;
            $users->password = $password;
            $users->name = $name;

            if($users_table->save($users))
            return $this->redirect(['action' => 'index']);
         }
      }

      public function edit($id){
         if($this->request->is('post')){
            $username = $this->request->getData('username');
            $name = $this->request->getData('name');
            $password = $this->request->getData('password');
            $users_table = TableRegistry::get('users');
            $users = $users_table->get($id);
            $users->username = $username;
            if($password != "") {
              $hashPswdObj = new DefaultPasswordHasher;
              $password = $hashPswdObj->hash($password);
              $users->password = $password;
            }
            $users->name = $name;
            $users_table->save($users);
            return $this->redirect(['action' => 'index']);
         } else {
            $users_table = TableRegistry::get('users')->find();
            $users = $users_table->where(['id'=>$id])->first();
            $this->set('username',$users->username);
            $this->set('name',$users->name);
            $this->set('id',$id);
         }
      }

      public function delete($id){
         $users_table = TableRegistry::get('users');
         $users = $users_table->get($id);
         $users_table->delete($users);
         return $this->redirect(['action' => 'index']);
      }


   }
?>
