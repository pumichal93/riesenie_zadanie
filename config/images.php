<?php
use Cake\Core\Configure;

Configure::write('ImageStorage', [
    'path' => 'upload',
    'imageSizes' => [
        'width' => 200,
        'height' => 200
    ],
    'types' => [
        'png',
        'jpeg'
    ]
]);