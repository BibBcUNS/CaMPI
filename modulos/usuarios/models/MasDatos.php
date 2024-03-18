<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mas_datos".
 *
 * @property integer $persona_id
 * @property string $valor
 * @property integer $campo_adicional_id
 *
 * @property CampoAdicional $campoAdicional
 * @property Persona $persona
 */
class MasDatos extends \yii\db\ActiveRecord
{

    public $vigente_labels =[
        '0'=> 'No',
        '1'=> 'Si'
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                //'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mas_datos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campo_adicional_id','valor'], 'required'],
            [['persona_id', 'campo_adicional_id'], 'integer'],
            [['valor'], 'string', 'max' => 100],
            [['campo_adicional_id'], 'exist', 'skipOnError' => true, 'targetClass' => CampoAdicional::className(), 'targetAttribute' => ['campo_adicional_id' => 'id']],
            [['persona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['persona_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'persona_id' => Yii::t('app', 'Persona ID'),
            'valor' => Yii::t('app', 'Valor'),
            'campo_adicional_id' => Yii::t('app', 'Campo Adicional ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampoAdicional()
    {
        return $this->hasOne(CampoAdicional::className(), ['id' => 'campo_adicional_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['id' => 'persona_id']);
    }

    public function getVigenteLabel() {
        return self::vigente_labels[$this->vigente];
    }
}
