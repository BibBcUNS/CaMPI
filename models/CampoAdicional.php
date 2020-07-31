<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "campo_adicional".
 *
 * @property integer $id
 * @property string $campo
 *
 * @property MasDatos[] $masDatos
 * @property Persona[] $personas
 */
class CampoAdicional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'campo_adicional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campo'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campo' => Yii::t('app', 'Campo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasDatos()
    {
        return $this->hasMany(MasDatos::className(), ['campo_adicional_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Persona::className(), ['id' => 'persona_id'])->viaTable('mas_datos', ['campo_adicional_id' => 'id']);
    }
}
