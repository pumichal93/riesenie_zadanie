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

$this->log($this->Paginator->templates('prevActive'));

$this->Paginator->setTemplates([
    'number' => '<li><a href="#" id="{{text}}">{{text}}</a></li>'
]);
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
    <style>
        .gallery-img {
            float: left;
            width: 25%;
            padding: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">

<div class="row">
    <div class="column large-6">
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
        <?= $this->Form->control('left',['id'=>'imageLeft','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->control('top',['id'=>'imageTop','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->control('width',['id'=>'imageWidth','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->control('height',['id'=>'imageHeight','type' => 'number', 'value' => '0']); ?>
        <?= $this->Form->submit('Upload image'); ?>
        <div id="responseMessage"></div>
        <?= $this->Form->end(); ?>
        <div id="image-update"></div>
    </div>
    <div class="column large-6">
        <?= $this->Form->create($image, [
        'url'=> [
        'controller' => 'Image',
        'action' => 'filter'
        ],
        'name' => 'ImageForm',
        'id' => 'filterForm',
        'data-update' => 'image-update',
        ]
        );
        ?>
        <?= $this->Form->control('filter',['id'=>'imageHeight','type' => 'textarea']); ?>
        <?= $this->Form->submit('Filter by query'); ?>
        <button type="reset" onclick="window.location.reload();">Reset</button>
        <?= $this->Form->end(); ?>
    </div>

</div>

<div class="row">
    <div class="large-12">
        <div id="gallery">
            <?php foreach ($images as $image): ?>
                <div class="column gallery-img">
                    <p><?php echo $image->name ?></p>
                    <img src=<?php echo $image->path ?>>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="paginator">
            <nav>
                <ul class="pagination">
                    <?= $this->Paginator->numbers([
                    'after' => '</li>',
                    'before' => '<li class="pagination">'
                    ]) ?>
                </ul>

                <p><?= $this->Paginator->counter() ?></p>
            </nav>
        </div>
    </div>
</div>
</div>
<div id="preview"></div>

<?= $this->Html->script(['jquery/jquery.js', 'global.js']); ?>
</body>
</html>