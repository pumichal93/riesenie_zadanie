<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 23.6.2018
 * Time: 18:17
 */

namespace App\Controller;

use App\Form\ImageForm;
use Cake\Database\Connection;
use Cake\Datasource\Paginator;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;
use Cake\View\View;

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

        $images = $this->paginate($this->Images);

        // model-less forms use src/Form/ImageForm.php
        $image = new ImageForm();
        $this->set(compact('image', 'images'));
    }

    public function add() {
        //
        $query = new Query(ConnectionManager::get('default'), TableRegistry::getTableLocator()->get('Images'));

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
    }

    public function filter()
    {
        $this->request->allowMethod('ajax');
        $this->autoRender = 'false';
        $this->viewBuilder()->setLayout('ajax');

        $this->log($this->request->getData());

        $imagesTable = TableRegistry::getTableLocator()->get('Images');
        $query = $imagesTable->find();
            /*->where([
                'id' > 3
            ]);*/

        $paginador = new Paginator();
        $paginador->setConfig('limit', 2);

        $images = $paginador->paginate($query);

        $this->log($paginador->getPagingParams());
        //$this->set(compact('image', 'images'));
        //$this->set('_serialize', ['content']);

        $view = new View();
        $view->set(compact('images'));
        $htmlGallery = stripcslashes( stripslashes( $view->renderLayout(null, 'gallery') ) );

        $pagingParams = $paginador->getPagingParams();
        $view->set(compact( 'pagingParams'));

        $htmlPaginator = stripcslashes( stripslashes( $view->renderLayout(null, 'pagination') ) );
        //$this->log((array) $html);
        $content = $htmlGallery;
        $pag = $htmlPaginator;


        $this->set(compact('pag'));
        $this->set(compact('content'));
        $this->set('_serialize', ['content', 'pag']);
    }
}