<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personahistory".
 *
 * @property string $id
 * @property string $date
 * @property string $table
 * @property string $field_name
 * @property string $field_id
 * @property string $old_value
 * @property string $new_value
 * @property int $type
 * @property string $user_id
 */
class PersonaHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modelhistory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'table', 'field_name', 'field_id', 'type', 'user_id'], 'required'],
            [['date'], 'safe'],
            [['old_value', 'new_value'], 'string'],
            [['type'], 'integer'],
            [['table', 'field_name', 'field_id', 'user_id'], 'string', 'max' => 255],
        ];
    }

    public function getPersona()
    {
        if ($this->table='persona') return $this->hasOne(Persona::className(), ['id' => 'field_id']);
    }

    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Fecha',
            'table' => 'Tabla',
            'field_name' => 'Campo',
            'field_id' => 'ID Persona',
            'old_value' => 'antes',
            'new_value' => 'después',
            'type' => 'Tipo',
            'user_id' => 'User ID',
        ];
    }
}
