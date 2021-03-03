<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\models\base;

// Esca clase fue reescrita para unificar la encriptación del Password con VuFind.

//use Yii;
use yii\base\Security as BaseSecurity;

class Security extends BaseSecurity
{
    public function generatePasswordHash($password, $cost = null)
    {
        return sha1($password);
    }

    public function validatePassword($password, $hash)
    {
        if (!is_string($password) || $password === '') {
            throw new InvalidArgumentException('Password must be a string and cannot be empty.');
        }

        $sha1 = sha1($password);
        return $this->compareString($sha1, $hash);
    }

    
}
