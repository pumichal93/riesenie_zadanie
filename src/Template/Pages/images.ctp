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
        <?= $this->Form->create(null, [
        'url'=>['controller'=>'ImageUpload', 'action' => 'upload'],'id'=>'imageUploadForm']
        ); ?>
        <?= $this->Form->control('* Left (px)',['id'=>'imageLeft','type' => 'number', 'value' => 0]); ?>
        <?= $this->Form->control('* Top (px)',['id'=>'imageTop','type' => 'number', 'value' => 0]); ?>
        <?= $this->Form->control('* Width (px)',['id'=>'imageWidth','type' => 'number']); ?>
        <?= $this->Form->control('* Height (px)',['id'=>'imageHeight','type' => 'number']); ?>
        <?= $this->Form->button('Submit'); ?>
        <div id="responseMessage"></div>
        <?= $this->Form->end(); ?>
    </div>

</div>
<div id="preview"></div>

<?= $this->Html->script(['jquery/jquery.js', 'images/upload.js']); ?>
</body>
</html>