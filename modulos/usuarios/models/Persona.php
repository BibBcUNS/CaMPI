<?php

namespace app\models;


use Yii;
//use mdm\admin\models\User;
use app\rbac\LibraryDbManager;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use nhkey\arh\ActiveRecordHistoryBehavior;

/**
 * This is the model class for table "persona".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $telefono
 * @property string $domicilio
 * @property string $numero_documento
 * @property integer $tipo_documento_id
 *
 * @property MasDatos[] $masDatos
 * @property CampoAdicional[] $campoAdicionals
 * @property TipoDocumento $tipoDocumento
 * @property Usuario[] $usuarios
 * @property TipoUsuario[] $tipoUsuarios
 */
class Persona extends \yii\db\ActiveRecord
{
    public $masDatos;
    public $persona_config;
    public $lista_usuarios;

    const DEFUALT_TIPO_DOC = 1; //DNI
    const ESTADO_DEFAULT = 0; //DNI

    const BLOQUE_ESTADOS = [
        '0' => 'Habilitado',
        '1' => 'Bloqueo',
        '2' => 'Bloqueo completo',
    ];

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'apellido', 'numero_documento', 'tipo_documento_id'], 'required'],
            [['tipo_documento_id','bloqueo_actualizacion'], 'integer'],
            ['numero_documento', 'unique', 'targetAttribute' => ['tipo_documento_id','numero_documento'],'comboNotUnique'=>'Ya existe el tipo y número de documento ingresados.'],
            ['email', 'email'],
            [['nombre', 'apellido', 'email', 'numero_documento'], 'string', 'max' => 45],
            [['telefono'], 'string', 'max' => 30],
            [['domicilio'], 'string', 'max' => 100],
            
            //[['domicilio'], 'validateFalse'],

            [['tipo_documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['tipo_documento_id' => 'id']],
        ];
    }

    public function usuarios_post_get_por_biblioteca($biblioteca_id) {
        foreach ($this->lista_usuarios as $usuario) {
            if ($usuario->biblioteca_id == $biblioteca_id)
                return $usuario;
        }
        return false;
    }

    public static function bloqueoEstado($estado_id = null) {
        $lista_estados = self::BLOQUE_ESTADOS;
        if (!is_null($estado_id) && isset($lista_estados[$estado_id])) 
            return self::BLOQUE_ESTADOS[$estado_id];
        else
            return self::BLOQUE_ESTADOS;
    }

    public function behaviors()
    {
        return [
            [ 'class' => TimestampBehavior::className()],
            [ 'class' => BlameableBehavior::className(),'value'=>'AYE'],
            [
                'class' => ActiveRecordHistoryBehavior::className(),
                'ignoreFields' => ['updated_at', 'created_at'],
            ],/*'history' => [
                'class' => ActiveRecordHistoryBehavior::className(),
                'manager' => 'DBManagesr',
                'managerOptions' => [
                    'tableName' => 'modelhistdory',
                ],
            ],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'persona';
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->persona_config = PersonaConfig::findOne(['persona_id' => $this->id]);

        if (!$this->persona_config) {
            $this->persona_config = new PersonaConfig(['persona_id' => $this->id]);
        }

        if (count($this->masDatosDb)==0) 
            $this->masDatos = [new MasDatos()];
        else
            $this->masDatos = $this->masDatosDb;
    }

    public function afterSave ( $insert, $changedAttributes ){
        parent::afterSave($insert, $changedAttributes);
        $this->persona_config->persona_id = $this->id;
        $this->persona_config->save();
    }


    public function init()
    {
        $this->persona_config = new PersonaConfig();
        $this->masDatos = [new MasDatos()];
        $this->tipo_documento_id = self::DEFUALT_TIPO_DOC;
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }



   public function validateFalse($attribute, $params, $validator)
    {
            $this->addError($attribute, 'Error. Esto no funciona');
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'apellido' => Yii::t('app', 'Apellido'),
            'email' => Yii::t('app', 'Email'),
            'telefono' => Yii::t('app', 'Telefono'),
            'domicilio' => Yii::t('app', 'Domicilio'),
            'numero_documento' => Yii::t('app', 'Nro. Doc.'),
            'tipo_documento_id' => Yii::t('app', 'Tipo'),
            'tipo_usuario_id' => Yii::t('app', 'Tipo'),
            'bloqueo_actualizacion' => Yii::t('app', 'Bloquear actualización'),

            'usuario.tipo_usuario_id' => Yii::t('app', 'Tipo'),
            'createdBy.username' => Yii::t('app', 'Creado por'),
            'updatedBy.username' => Yii::t('app', 'Actualizado por'),
            'updatedBy.username' => Yii::t('app', 'Actualizado por'),
        ];
    }

    public function saveDatosAdicionales() {
        // Borro los campos guardados en la BD
        Yii::$app->db->createCommand()
            //->delete('mas_datos', "persona_id={$this->id}")
            ->update(
                'mas_datos',
                ['vigente' => 0], // SETs
                ['persona_id' => $this->id,'vigente' => 1]) // Condiciones
            ->execute();

        // Guardo los nuevos datos.
        foreach ($this->masDatos as $dato) {
            $dato->persona_id = $this->id;
            $dato->vigente = true;
            $dato->save();
        }
    }

    public function saveUsuarios() {
        foreach ($this->lista_usuarios as $usuario) {
                $usuario->save();
        }
    }
    
    public function usuario_en($biblioteca_id)
    {
        foreach ($this->usuarios as $usuario) {
            if ($usuario->biblioteca_id==$biblioteca_id)
                return $usuario;
        }
        // si no encuentro usuario para dicha biblioteca;
        return false;
    }

    public function getApellidoYNombre(){
        return $this->apellido.', '.$this->nombre;
    }

    public function getTipoYNro(){
        return ((isset($this->tipoDocumento))?$this->tipoDocumento->tipo.' ':'').$this->numero_documento;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasDatosDb()
    {
        return $this->hasMany(MasDatos::className(), ['persona_id' => 'id'])->andOnCondition(['vigente' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCamposAdicionales()
    {
        return $this->hasMany(CampoAdicional::className(), ['id' => 'campo_adicional_id'])->viaTable('mas_datos', ['persona_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id' => 'tipo_documento_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['persona_id' => 'id']);
    }

    public function getBibliotecasUsuarios()
    {
        $bibliotecas = Biblioteca::bibliotecasHabilitadas();
        foreach ($bibliotecas as $biblioteca) {
            $biblitoeca_usuario[$biblioteca->id] = new Usuario(['biblioteca_id'=>$biblioteca->id,'categoria'=>'','persona_id'=>$this->id]);
        }
        foreach ($this->usuarios as $usuario) {
            if ($usuario->biblioteca->habilitar_update)
                $biblitoeca_usuario [$usuario->biblioteca_id] = $usuario;
        }
        return $biblitoeca_usuario;
    }

    public function getUsuariosEditables() {
        $editables = [];
        $user_id = Yii::$app->user->identity->id; // ID del usuario logueado
        $bibliotecas_habilitadas_ids = LibraryDbManager::userLibraries($user_id); // Bibliotecas habilitadas para el usuario logueado.

        foreach ($this->bibliotecasUsuarios as $usuario) {
            if ($usuario->biblioteca->habilitar_update && in_array($usuario->biblioteca_id, $bibliotecas_habilitadas_ids)) {
                $editables[$usuario->biblioteca_id]=$usuario;
            }
        }
        return $editables;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoUsuarios()
    {
        return $this->hasMany(TipoUsuario::className(), ['id' => 'tipo_usuario_id'])->viaTable('usuario', ['persona_id' => 'id']);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);

    }
}
