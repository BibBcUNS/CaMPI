<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_usuario}}".
 *
 * @property integer $id
 * @property string $nombre
 *
 * @property Usuario[] $usuarios
 * @property Persona[] $personas
 */
class TipoUsuario extends \yii\db\ActiveRecord
{
        const USUARIO_INDIVIDUAL = 1; //DNI
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_usuario}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 45],
            [['biblioteca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Biblioteca::className(), 'targetAttribute' => ['biblioteca_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiblioteca()
    {
        return $this->hasOne(Biblioteca::className(), ['id' => 'biblioteca_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['tipo_usuario_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Persona::className(), ['id' => 'persona_id'])->viaTable('{{%usuario}}', ['tipo_usuario_id' => 'id']);
    }
}
