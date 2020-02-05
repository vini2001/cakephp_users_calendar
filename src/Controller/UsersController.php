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

        $this->loadModel('Users');

         if($this->request->is('post')){
            $hashPswdObj = new DefaultPasswordHasher;
            $password = $hashPswdObj->hash($this->request->getData('password'));
            $user = $this->Users->newEntity($this->request->getData());
            $user->password = $password;

            if($this->Users->save($user))
              return $this->redirect(['action' => 'index']);

            if ($user->errors()) {
              $error_msg = [];
                foreach($user->errors() as $key => $errors){
                    if(is_array($errors)){
                        foreach($errors as $key2 => $error){
                            $error_msg[$key]    =   $error;
                        }
                    }else{
                        $error_msg[$key]    =   $errors;
                    }
                }
                /*if(!empty($error_msg)){
                    foreach($error_msg as $error){
                        $this->Flash->error($error);
                    }
                }*/                
                $this->set('errors', $error_msg);
            }
         }

         $user = $this->Users->newEntity();
         $this->set('user', $user);
      }

      public function edit($id){

        $user = $this->Users->newEntity($this->request->getData());
        if ($user->errors()) {
          $error_msg = [];
            foreach($user->errors() as $key => $errors){
                if(is_array($errors)){
                    foreach($errors as $key2 => $error){
                        $error_msg[$key]    =   $error;
                    }
                }else{
                    $error_msg[$key]    =   $errors;
                }
            }
            if(!empty($error_msg)){
                foreach($error_msg as $error){
                    $this->Flash->error($error);
                }
            }
            $this->set('errors', $error_msg);
        }

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

         $this->loadModel('Users');
         $user = $this->Users->newEntity();
         $this->set('user', $user);
      }

      public function delete($id){
         $users_table = TableRegistry::get('users');
         $users = $users_table->get($id);
         $users_table->delete($users);
         return $this->redirect(['action' => 'index']);
      }


   }
?>
