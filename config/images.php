<?php
use Cake\Core\Configure;

Configure::write('ImageStorage', [
    'path' => 'upload',
    'imageSizes' => [
        'ProductImage' => [
            'large' => [
                'thumbnail' => [
                    'width' => 800,
                    'height' => 800
                ]
            ],
            'medium' => [
                'thumbnail' => [
                    'width' => 200,
                    'height' => 200
                ]
            ],
            'small' => [
                'thumbnail' => [
                    'width' => 80,
                    'height' => 80
                ]
            ]
        ]
    ],
    'types' => [
        'png',
        'jpeg'
    ],
    'separation' => true
]);