<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

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
    <div class="columns large-6">
        <form role="form" id = "image-upload">
            <div class="form-group" style="margin-top: 20px">
                <h2>Upload image</h2>
            </div>
            <div class="form-group">
            <span class="input-group-btn">
                <span class="btn btn-default btn-file">
                    Browse… <input type="file" id="imgInp">
                </span>
            </span>
            </div>
            <div class="form-group">
                <label class="control-label" for="imageLeft">* Left (px)</label>
                <input name="imageLeft" id="imageLeft" type="number" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label" for="imageTop">* Top (px)</label>
                <input name="imageTop" id="imageTop" type="number" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label" for="imageWidth">* Width (px)</label>
                <input name="imageWidth" id="imageWidth" type="number" class="form-control">
            </div>
            <div class="form-group">
                <label class="control-label" for="imageHeight">* Height (px)</label>
                <input name="imageHeight" id="imageHeight" type="number">
            </div>
            <div class="form-group">
                <button type="submit" id="image-upload" class="btn btn-info btn-block">Upload</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>