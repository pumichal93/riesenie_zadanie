<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 23.6.2018
 * Time: 17:52
 */

namespace App\Form;

use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Log\LogTrait;
use Cake\ORM\TableRegistry;

class ImageForm extends Form
{
    use LogTrait;

    private $imageSize;

    private $imageConfig;

    /**
     * @return mixed
     */
    public function getImageConfig()
    {
        return $this->imageConfig;
    }

    /**
     * @param mixed $imageConfig
     */
    public function setImageConfig($imageConfig)
    {
        $this->imageConfig = $imageConfig;
    }

    private $imageType;

    /**
     * @return null
     */
    public function getImageType()
    {
        return $this->imageType;
    }

    /**
     * @param null $imageType
     */
    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
    }

    /**
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param mixed $imageSize
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;
    }

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('top', 'number')
            ->addField('width', 'number')
            ->addField('height', 'number')
            ->addField('left', 'number');
            //->addField('image', 'string');
    }

    protected function _buildValidator(Validator $validator)
    {
        //$imagesConfig = Configure::consumeOrFail('ImageStorage');

        $validator
            ->add('top', 'inHeight', [
                'rule' => function ($data, $provider) {
                    if(($data >= 0) && ($data < $this->getImageSize()[1])) {
                        return true;
                    }

                    return 'value must be between 0 and max height';
                }
            ])
            ->add('left', 'inWidth', [
                'rule' => function ($data, $provider) {
                    if(($data >= 0) && ($data < $this->getImageSize()[0])) {
                        return true;
                    }

                    return 'value must be between 0 and max width';
                }])
            ->add('width', 'length',
                ['rule' => function ($data, $provider) {
                    if(($data > 0) && ($data < $this->getImageSize()[0])) {
                        return true;
                    }

                    return 'value is out of range';
                }])
            ->add('height', 'inHeight', [
                'rule' => function ($data, $provider) {
                    if(($data > 0) && ($data < $this->getImageSize()[1])) {
                        return true;
                    }

                    return 'value is out of range';
                    }
                ])
            ->add('image', 'fileType', [
                'rule' => function() {
                    if (isset($this->imageSize['mime'])) {
                        $mime = $this->imageSize['mime'];
                        $types = preg_split('/\//', $mime);
                        if (
                            (strcmp($types[0], 'image') == 0) &
                            (in_array($types[1], $this->getImageConfig()['types'], true))
                        ) {
                            $this->setImageType($types[1]);
                            return true;
                        }

                        return true;
                    }

                    return 'Upload file with correct file type';
                }
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        $imageName = $data['image']['name'];
        $imageTemp = $data['image']['tmp_name'];
        $imagePath = DS . 'upload' . DS . $imageName;
        /*
         * @var \Cake\ORM\Table $imageTable
         *
         * Table to save data about uploaded image
         */
        $imagesTable = TableRegistry::getTableLocator()->get('Images');
        $image = $imagesTable->newEntity();
        $image->name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $imageName);
        $image->width = $data['width'];
        $image->height = $data['height'];
        $image->created = date("Y-m-d H:i:s");
        $image->path = $imagePath;
        if ($imagesTable->save($image)) {
            $newImage = imagecrop(call_user_func('imagecreatefrom' . $this->getImageType(), $imageTemp), [
                'x' => $data['left'],
                'y' => $data['top'],
                'width' => $data['width'],
                'height' => $data['height']
            ]);
            if ($newImage !== false) {

                call_user_func('image' . $this->getImageType(), $newImage, ROOT . DS . $imagePath);
                imagedestroy($newImage);
            }
            else {
                return 'Unknown error in resize execution';
            }
        }
        else {
            return 'Unexpected error in save process';
        }

        return true;
    }

}