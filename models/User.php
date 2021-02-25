<?php

namespace app\models;
use yii;
use yii\db\Query;
use mdm\admin\models\User as MdmUser;

class User extends MdmUser
{
    const FULL_ADMIN='FullAdmin';

    public $permisos,$permiso; //permisos: una lista de permisos (solo fulladmin). permiso: Solo permiso para una biblioteca


    public static function tableName()
    {
        return '{{%persona}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nombre', 'apellido', 'email','status'], 'required'],
            [['permisos','permiso'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de usuario',
            'biblioteca.nombre'=>'Biblioteca'
        ];
    }

    /*parece que esto se puede borrar 
    
    public function permisosBibliotecas() {
    	$rows = (new Query())
		    ->from('auth_assignemnt')
		    ->where(['user_id' => $this->id])
		    ->all();
    }*/

    static public function rolesDisponibles () {
        $roles_db = (new Query())
            ->from('auth_item')
            ->select(['name'])
            ->orderBy('level')
            ->where(['>','level', 1])
            //->andWhere(['>','level', 1])
            ->all();

        $roles = [];

        foreach ($roles_db as $rol) {
            $rol_name = $rol['name'];
            $roles[$rol_name]=$rol_name;
        }

        return $roles;
    }

    public function getPermisosEfectivos () {
        $permisos_db = (new Query())
            ->from('auth_assignment')
            ->select(['library_id','item_name'])
            ->where(['user_id'=>$this->id])
            ->andWhere(['not',['library_id'=>$this->id]])
            ->all();

        $permisos = [];
        foreach ($permisos_db as $permiso) {
            $library_id = $permiso['library_id'];
            $item_name = $permiso['item_name'];
            $permisos[$library_id]=$item_name;
        }

        //var_dump($permisos);exit;
        return $permisos;
    }

    public function setPermisosEfectivos () {
        // permissos para todas las biblitoecas
        foreach ($this->permisos as $library_id=>$item_name) {

            // Borro
            (new Query())->createCommand()->delete(
                'auth_assignment',
                [
                    'user_id'    => $this->id,
                    'library_id' =>$library_id,
                    //'item_name'  =>$item_name
                ])
                ->execute();

            if ($item_name) {
                // Si está vacío no asigno permiso.
                // Asigno permiso
                Yii::$app->db->createCommand()->insert(
                    'auth_assignment',
                    [
                        'item_name'  =>$item_name,
                        'user_id'    =>$this->id,
                        'library_id' =>$library_id
                    ])
                    ->execute();
            }
        }
    }

    public function setPermiso ($library_id,$item_name) {
        (new Query())
            ->createCommand()
            ->delete('auth_assignment', [
                'user_id'    => $this->id,
                'library_id' => $library_id,
            ])
            ->execute();
        
       
        if ($item_name) {
            Yii::$app->db->createCommand()->insert(
                'auth_assignment',
                [
                 'item_name'=>$item_name,
                 'user_id'=>$this->id,
                 'library_id'=>$library_id,
                ]
            )->execute();
        }

    }

    public function afterFind() {
        if ($library=Yii::$app->session->get('library')) {
            if (isset($this->permisosEfectivos[$library->id])) {
                $this->permiso = $this->permisosEfectivos[$library->id];
            }
        }
        $this->status = ($this->status==10)?1:0;
    }

    public function beforeSave($insert) {
        $this->status = ($this->status==1)?10:9;
        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        (new Query())
            ->createCommand()
            ->delete('auth_assignment', [
                'user_id'    => $this->id,
            ])
            ->execute();
        return true;
    }

   /*public function getEstado() {
        return ($this->status)?'Activo':'Inactivo';
    }*/

    public function getIsFullAdmin() {
        if (Yii::$app->user->isGuest)
            return false;
        else {
            $assignment = Yii::$app->authManager->getAssignment(
                self::FULL_ADMIN,Yii::$app->user->identity->id
            );
            return !is_null($assignment);
        }
    }

}
