<?php
namespace app\models;

use yii\base\Model;

class UserForm extends Model
{
    public $username;
    public $password;
    public $is_admin;

    public function rules()
    {
        return [
            [['username', 'password', 'is_admin'], 'required'],
            ['username', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['is_admin', 'boolean'],
        ];
    }
}