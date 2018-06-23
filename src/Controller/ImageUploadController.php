<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 23.6.2018
 * Time: 18:17
 */

namespace App\Controller;

use App\Controller\AppController;
use App\Form\ImageUpload;
class ImageUploadController extends AppController
{
    /*public function index()
    {
        $image = new ImageUpload();
        //$this->Flash->success('We will get back to you soon.');
        if ($this->request->is('get')) {
            // Values from the User Model e.g.
            $this->request = $this->request
                ->withData('left', 0)
                ->withData('top','john.doe@example.com');
        }
        $this->set('image', $image);
    }*/

    public function upload() {

        $this->log('You are here', 'debug');
        $this->autoRender = 'false';
        $this->viewBuilder()->setLayout('ajax');
        $content = '<div class="alert alert-warning" role="alert">Something unexpected occured</div>';

        $this->request->allowMethod('ajax');
        //var_dump(['error' => 4]);
        //$form = new ImageUpload();
        /*if ($this->request->is('post')) {
            if ($form->validate($this->request->data)) {
                $save = 'go to save image';
                var_dump($save);
            }
            else {
                $errors = $form->errors();
                var_dump($errors);
            }
        }*/
        //set current date as content to show in view
        $this->set(compact('content'));

        //render spacial view for ajax
        $this->render('ajax_response', 'ajax');
    }

}