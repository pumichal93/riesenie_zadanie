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
use Cake\Log\LogTrait;
use Cake\ORM\TableRegistry;

class ImageForm extends Form
{
    use LogTrait;

    private $imageSize;

    private $imageConfig;

    private $imageType;

    /**
     * @return array
     */
    public function getImageConfig()
    {
        return $this->imageConfig;
    }

    /**
     * @param array $imageConfig
     */
    public function setImageConfig($imageConfig)
    {
        $this->imageConfig = $imageConfig;
    }

    /**
     * @return string
     */
    public function getImageType()
    {
        return $this->imageType;
    }

    /**
     * @param string $imageType
     */
    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
    }

    /**
     * @return array
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param array $imageSize
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
                        else {
                            return 'Upload file with correct file type';
                        }
                    }
                    else {
                        return 'Type of image is not defined';
                    }
                }
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        $imageName = $data['image']['name'];
        $imageTemp = $data['image']['tmp_name'];
        $imagePath = 'upload' . DS . $imageName;
        /*
         * Table to save data about uploaded image
         *
         * @var \Cake\ORM\Table $imageTable
         */
        $imagesTable = TableRegistry::getTableLocator()->get('Images');
        $image = $imagesTable->newEntity();
        $image->name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $imageName);
        $image->width = $data['width'];
        $image->height = $data['height'];
        $image->created = date("Y-m-d H:i:s");
        $image->path = $imagePath;
        if ($imagesTable->save($image)) {
            $createImage = 'imagecreatefrom' . $this->getImageType();
            $newImage = imagecrop($createImage($imageTemp), [
                'x' => $data['left'],
                'y' => $data['top'],
                'width' => $data['width'],
                'height' => $data['height']
            ]);
            if ($newImage !== false) {
                $createResource = 'image' . $this->getImageType();
                $createResource($newImage, ROOT . DS . 'webroot' . DS . $imagePath);
                //imagedestroy($newImage);
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