<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $path
 * @property User $user
 */
class Image extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['file'], 'file', 'extensions' => 'gif, jpg'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'file' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Rotate the image 90 degrees to the left.
     * @param $degrees
     */
    public function rotateImg($degrees){

        $extension = explode(".", $this->path);
        $extension = $extension[1];
        if($extension == 'jpg') $this->rotateJpeg('../web'.$this->path, $degrees);
        else if ($extension == 'gif') $this->rotateGif('../web'.$this->path, $degrees);

    }

    /**
     * @return bool
     */
    public function deleteImage(){
        if (is_file('../web'.$this->path) and unlink('../web'.$this->path)) return true;
        return false;
    }

    /**
     * Apply watermark on images
     */
    public function addWatermark()
    {
        $extension = explode(".", $this->path);
        $extension = $extension[1];
        if($extension == 'jpg') $this->addWatermarkJpeg('../web'.$this->path, '../web/image/watermark.png');
        else if ($extension == 'gif') $this->addWatermarkGif('../web'.$this->path, '../web/image/watermark.png');
    }

    /**
     * @param $path
     * @param $degrees
     */
    private function rotateJpeg($path, $degrees)
    {
        $source = imagecreatefromjpeg($path);
        $rotate = imagerotate($source, $degrees, 0);
        imagejpeg($rotate, $path, 95 );
        imagedestroy($source);
        imagedestroy($rotate);

    }

    /**
     * @param $path
     * @param $degrees
     */
    private function rotateGif($path, $degrees)
    {
        $source = imagecreatefromgif($path);
        $rotate = imagerotate($source, $degrees, 0);
        imagegif($rotate, $path);
        imagedestroy($source);
        imagedestroy($rotate);
    }

    /**
     * @param $path
     * @param $path_to_stamp
     */
    private function addWatermarkJpeg($path, $path_to_stamp){
        $stamp = imagecreatefrompng($path_to_stamp);
        $im = imagecreatefromjpeg($path);
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        imagejpeg($im, $path, 95 );
        imagedestroy($im);
    }

    /**
     * @param $path
     * @param $path_to_stamp
     */
    private function addWatermarkGif($path, $path_to_stamp){
        $stamp = imagecreatefrompng($path_to_stamp);
        $im = imagecreatefromgif($path);
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        imagejpeg($im, $path, 95 );
        imagegif($im, $path);
        imagedestroy($im);
    }
}
