<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona_config".
 *
 * @property int $persona_id
 * @property int $notificacion_proximo_a_vencer
 * @property int $notificacion_espera
 * @property int $notificacion_prestamo
 *
 * @property Usuario $persona
 */
class PersonaConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['persona_id'], 'required'],
            [['persona_id', 'notificacion_proximo_a_vencer', 'notificacion_espera', 'notificacion_prestamo'], 'integer'],
            [['persona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['persona_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'persona_id' => 'Persona ID',
            'notificacion_proximo_a_vencer' => 'Notificacion: Proximo a vencer',
            'notificacion_espera' => 'Notificacion Espera',
            'notificacion_prestamo' => 'Notificacion Prestamo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Usuario::className(), ['persona_id' => 'persona_id']);
    }
}
