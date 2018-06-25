<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 23.6.2018
 * Time: 18:17
 */

namespace App\Controller;

use App\Form\ImageForm;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Core\Configure;

class ImageController extends AppController
{

    public function initialize(){
        parent::initialize();

        // Load Files model
        $this->loadModel('Images');

    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        // Change layout for Ajax requests
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    private function setErrorFlash($errors) {
        if (count($errors)) {
            $flat_errors = Hash::flatten($errors);
            $messages = [];
            if (count($flat_errors) > 0) {
                foreach ($flat_errors as $key => $error) {
                    $messages[preg_split('/\./', $key)[0]] = $error;
                }
            }
        }

        return $messages;
    }

    public function getImage() {

        // model-less forms use src/Form/ImageForm.php
        $image = new ImageForm();
        $this->set(compact('image'));
    }

    public function add() {
        //$this->request->allowMethod('ajax');
        //$this->autoRender = 'false';
        //$this->viewBuilder()->setLayout('ajax');
        $this->redirect(['action' => 'get_image']);
        if ($this->request->is('post')) {
            if (!empty($this->request->getData()['image']['name'])) {
                $form = new ImageForm();
                $form->setImageSize(getimagesize($this->request->getData('image.tmp_name')));
                $form->setImageConfig(Configure::consumeOrFail('ImageStorage'));
                if ($form->validate($this->request->getData())) {
                    $resolve = $form->execute($this->request->getData());
                    if (!($resolve)) {
                        $this->Flash->error($resolve);
                    }
                    $this->Flash->success(__('File has been uploaded and inserted successfully.'));
                }
                else {
                    $messages = $this->setErrorFlash($form->errors());
                    foreach ($messages as $key => $message) {
                        $this->Flash->error(sprintf('<b>%s</b> %s',h($key), h($message)), ['escape' => false]);
                    }
                }
            }
        }

        //$this->set('_serialize', ['content']);
        //$this->set(compact('content'));
    }
}