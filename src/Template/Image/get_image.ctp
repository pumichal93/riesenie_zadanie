<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use App\Form\ImageUpload;
use App\Model\Entity\Image;
$this->layout = false;
if (!Configure::read('debug')) :
throw new NotFoundException(
'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
);
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        images sub-page
    </title>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('home.css') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">

<div class="row">
    <div class="column large-6">
        <div id="image-update"></div>
        <?= $this->Flash->render() ?>
        <?= $this->Form->create($image, [
            'type' => 'file',
            'url'=> [
                'controller' => 'Image',
                'action' => 'add'
            ],
            'name' => 'ImageForm',
            'id' => 'imageUploadForm',
            'data-update' => 'image-update',
            ]
            );
        ?>
        <?= $this->Form->input('image',['type' => 'file']); ?>
        <?= $this->Form->control('left',['id'=>'imageLeft','type' => 'number']); ?>
        <?= $this->Form->control('top',['id'=>'imageTop','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->control('width',['id'=>'imageWidth','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->control('height',['id'=>'imageHeight','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->submit('Upload image'); ?>
        <div id="responseMessage"></div>
        <?= $this->Form->end(); ?>
        <div id="image-update"></div>
    </div>

</div>
<div id="preview"></div>

<?= $this->Html->script(['jquery/jquery.js', 'global.js']); ?>
</body>
</html>