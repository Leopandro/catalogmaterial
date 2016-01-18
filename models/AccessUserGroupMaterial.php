<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "access_user_group_material".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_group_material
 * @property integer $is_allow
 *
 * @property Catalog $idGroupMaterial
 * @property User $idUser
 */
class AccessUserGroupMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_user_group_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_group_material', 'is_allow'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_group_material' => 'Id Group Material',
            'is_allow' => 'Is Allow',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroupMaterial()
    {
        return $this->hasOne(Catalog::className(), ['id' => 'id_group_material']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
