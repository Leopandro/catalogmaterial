<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx, csv'],
        ];
    }

    public function upload()
    {
            $path = Yii::getAlias('@app/runtime/uploads/').Yii::$app->user->identity->id.'/';
            if (!is_dir($path)) mkdir($path);
            $filename = Yii::$app->security->generateRandomString(9);
            $this->file->saveAs($path . $filename . '.' . $this->file->extension);
            return $path . $filename . '.' . $this->file->extension;
    }
}