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

    public function initialize(array $config) {
        $this->setTable('images');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }
}