<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 23.6.2018
 * Time: 17:52
 */

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class ImageUpload extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('name', 'string')
            ->addField('email', ['type' => 'email'])
            ->addField('phone', ['type' => 'tel'])
            ->addField('message', ['type' => 'text']);
    }

    protected function _buildValidator(Validator $validator)
    {
        $validator->requirePresence('name',[
        'message' => __('Please provide your name'),
    ])
        ->notBlank('name',__('Name cannot be blank'))
        ->add('name', [
            'minlength' => [
                'rule' => ['minLength', 2],
                'message' => __('Name must be at least 2 characters long')
            ],
            'maxlength' => [
                'rule' => ['maxLength', 50],
                'message' => __('Name cannot be more than 50 characters long')
            ]
        ]);
// 'email' field
        $validator
            ->requirePresence('email',[
                'message' => __('Please provide your email address'),
            ])
            ->notBlank('email',__('Email address cannot be blank'))
            ->add('email', 'format', [
                'rule' => 'email',
                'message' => __('Please provide a valid email address')
            ]);
// 'phone' field
        $validator
            ->allowEmpty('phone')
            ->add('phone', [
                'minlength' => [
                    'rule' => ['minLength', 7],
                    'message' => __('Your phone# must be at least 7 characters long'),
                    'on' => function ($context) {return !empty($context['data']['phone']);} // conditional on presence
                ],
                'maxlength' => [
                    'rule' => ['maxLength', 30],
                    'message' => __('Your phone# cannot be more than 30 characters long')
                ]
            ]);
// 'message' field
        $validator
            ->requirePresence('message',[
                'message' => __('Please include your message'),
            ])
            ->notBlank('name',__('Your message cannot be blank'))
            ->add('message', [
                'minlength' => [
                    'rule' => ['minLength', 4],
                    'message' => __('Your message must be at least 4 characters long')
                ],
                'maxlength' => [
                    'rule' => ['maxLength', 2048],
                    'message' => __('Your message cannot be more than 2048 characters long')
                ]
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        return true;
    }

}