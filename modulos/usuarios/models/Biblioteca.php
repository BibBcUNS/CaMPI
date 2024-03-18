<?php

namespace app\models;

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
        return Self::find()->where(['habilitar_update'=>true])->all();
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
            [['url_campi'], 'string', 'max' => 200],
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
            'url_campi' => 'Url Campi',
            'habilitar_update' => 'Actualizar lector en OMP',
        ];
    }

    public function categorias_usuarios() {
        $url_categorias = "http://{$this->url_campi}/omp/cgi-bin/wxis.exe/omp/webservices/?IsisScript=webservices/tipos-de-usuarios.xis";
        $categorias_json = utf8_encode(file_get_contents($url_categorias));
        $array_categorias = (array)json_decode($categorias_json);
        asort($array_categorias);
        if (count($array_categorias)>0) {
            return $array_categorias;
        }
        else
            return [];

    }
}
