<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Controller\Component\AuthComponent;
use Cake\Event\Event;




/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authenticate' => [
               'Form' => [
                  'fields' => ['username' => 'username', 'password' => 'password']
               ]
            ],
            'authError' => false,
            'loginAction' => ['controller' => 'Authexs', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'Calendar', 'action' => 'index'],
            'logoutRedirect' => ['controller' => 'Authexs', 'action' => 'login']
         ]);

         $this->Auth->setConfig('authenticate', [AuthComponent::ALL => ['userModel' => 'users'], 'Form']);


         if($this->Auth->user()) {
             $this->set('loggedIn',true);
             $this->set('name',$this->Auth->user('name'));
             $this->set('is_adm',$this->Auth->user('adm'));
         }else $this->set('loggedIn',false);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->loadComponent('App');
    }
}
