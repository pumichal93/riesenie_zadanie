<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 24.6.2018
 * Time: 23:29
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class Images extends Table
{
    private $image_size;

    public function initialize(array $config) {
        $this->setTable('images');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('title', 'create')
            ->add('left', 'myRule', [
                'rule' => function ($data, $provider) {
                    if ($data > 200) {
                        return true;
                    }
                    return 'Not a good value.';
                }]);


        return $validator;
    }
}