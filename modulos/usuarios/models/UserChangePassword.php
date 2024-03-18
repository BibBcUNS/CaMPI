<?php

namespace app\models;

use Yii;
//use mdm\admin\models\User;
use yii\base\Model;

/**
 * Description of ChangePassword
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UserChangePassword extends Model
{
    public $id;
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'newPassword' => Yii::t('app', 'Nueva contraseña'),
            'retypePassword' => Yii::t('app', 'Repetir contraseña'),
        ];
    }

    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change($user)
    {
        if ($this->validate()) {
            /* @var $user User */
            //$user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
