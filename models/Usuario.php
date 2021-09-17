<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%usuario}}".
 *
 * @property integer $persona_id
 * @property integer $tipo_usuario_id
 *
 * @property Prestamo[] $prestamos
 * @property Persona $persona
 * @property TipoUsuario $tipoUsuario
 */
class Usuario extends \yii\db\ActiveRecord
{

    public $eliminar_sanciones = false;
    public $categoria, $sanciones, $prestamos;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%usuario}}';
    }

    public function init()
    {
        /*if (isset($this->persona->username)) {
            $url_usuario_campi = "http://{$this->biblioteca->url_campi}/omp/cgi-bin/wxis.exe/omp/webservices/?IsisScript=webservices/usuario.xis&usuario_id=".$this->persona->username;
            $usuario_json = utf8_encode(file_get_contents($url_usuario_campi));
            $usuario_campi = (Array)json_decode($usuario_json);

            // categoría:
            $this->categoria = null;
            $this->prestamos = [];
            $this->sanciones = [];
            // Categoría:
            if (isset($usuario_campi['categoria']))
                $this->categoria = $usuario_campi['categoria'];
            // prestamos:
            if (isset($usuario_campi['prestamos']))
                $this->prestamos = $usuario_campi['prestamos'];
            // sanciones:
            if (isset($usuario_campi['sanciones']))
                $this->sanciones = $usuario_campi['sanciones'];
        }*/
    }

    public function afterFind() {
	$this->init();
	parent::afterFind();

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['persona_id'/*,'categoria'*/], 'required'],
            [['notas','eliminar_sanciones','categoria'], 'safe'],
            [['persona_id', 'biblioteca_id'], 'integer'],
            [['persona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['persona_id' => 'id']],
            [['biblioteca_id'], 'exist', 'skipOnError' => true, 'targetClass' =>  Biblioteca::className(), 'targetAttribute' => ['biblioteca_id' => 'id']],
            [['tipo_usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoUsuario::className(), 'targetAttribute' => ['tipo_usuario_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'persona_id'    => 'Persona ID',
            'categoria'     => 'Categoría',
            'notas'         => 'Notas',
            'eliminar_sanciones' => 'Eliminar sanciones y habilitar',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getPrestamos()
    {
        return $this->hasMany(Prestamo::className(), ['usuario_persona_id' => 'persona_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['id' => 'persona_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiblioteca()
    {
        return Biblioteca::find()->where(['id' => $this->biblioteca_id])->one();
    }

    /*public function getCategoriaCampi() {
        if (isset($this->persona->username)) {
            $url_usuario_campi = "http://{$this->biblioteca->url_campi}/omp/cgi-bin/wxis.exe/omp/webservices/?IsisScript=webservices/usuario.xis&usuario_id=".$this->persona->username;
            $usuario_json = utf8_encode(file_get_contents($url_usuario_campi));
            $usuario_campi = (Array)json_decode($usuario_json);

            if (isset($usuario_campi['categoria']))
                return $usuario_campi['categoria'];
            else
                return false;
        }

        return false;

    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getTipoUsuario()
    {
        return $this->hasOne(TipoUsuario::className(), ['id' => 'tipo_usuario_id']);
    }*/
}
