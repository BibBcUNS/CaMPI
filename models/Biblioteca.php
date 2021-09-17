<?php

namespace app\models;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "biblioteca".
 *
 * @property int $id
 * @property string $nombre Biblioteca
 * @property string $prefijo
 * @property string $url_campi
 * @property int $habilitar_update
 */
class Biblioteca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biblioteca';
    }

    public static function bibliotecasHabilitadas () {
        return Self::find()->all();
        //->where(['habilitar_update'=>true]) Esto el sistema se comunicaba con los campi.
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefijo', 'habilitar_update'], 'required'],
            [['habilitar_update'], 'integer'],
            [['nombre'], 'string', 'max' => 45],
            [['prefijo'], 'string', 'max' => 20],
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
            'prefijo' => 'Prefijo',
            'habilitar_update' => 'Actualizar lector en OMP',
        ];
    }

    public function getTiposDeUsuario() {
        return $this->hasMany(TipoUsuario::className(), ['biblioteca_id' => 'id']);
    }

    public function getMapTiposDeUsuario() {
        return ArrayHelper::map($this->tiposDeUsuario, 'id', 'nombre');
    }

    /*public function categorias_usuarios() {
        $url_categorias = "http://{$this->url_campi}/omp/cgi-bin/wxis.exe/omp/webservices/?IsisScript=webservices/tipos-de-usuarios.xis";
        $categorias_json = utf8_encode(file_get_contents($url_categorias));
        $array_categorias = (array)json_decode($categorias_json);
        asort($array_categorias);
        if (count($array_categorias)>0) {
            return $array_categorias;
        }
        else
            return [];

    }*/
}
