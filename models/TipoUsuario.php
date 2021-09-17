<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_usuario}}".
 *
 * @property int $id
 * @property string $nombre Tipo de Usuario
 *
 * @property Politica[] $politicas
 * @property PoliticaSeriadas[] $politicaSeriadas
 * @property Usuario[] $usuarios
 * @property Usuario[] $usuarios0
 */
class TipoUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tipo_usuario}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function getPoliticas()
    {
        return $this->hasMany(Politica::className(), ['tipo_usuario_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoliticaSeriadas()
    {
        return $this->hasMany(PoliticaSeriadas::className(), ['tipo_usuario_id' => 'id']);
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
    public function getUsuarios0()
    {
        return $this->hasMany(Usuario::className(), ['tipo_usuario_id' => 'id']);
    }
}
